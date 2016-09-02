/**
 * list.js - Admin / Settings / Categories
 */

define(['plugin', 'bootbox', 'nestable', 'growl'], function (plugin, bootbox) {

  var fn = {
    categoryId: 0,
    newCategoryId: 0,
    $tabContent: null,
    $addBtn: null,

    /**
     * Ask confirm to user before remove category.
     */
    removeConfirm: function (event) {
      event.preventDefault();

      var $this = $(this),
        $li = $this.parents('.dd-item').eq(0);

      fn.categoryId = $li.attr('data-id');

      bootbox.dialog({
        title: '',
        message: trans.remove_category,
        buttons: {
          yes: {
            label: trans.btn_yes,
            className: 'btn-danger btn-sm',
            callback: fn.remove,
          },
          no: {
            label: trans.btn_no,
            className: 'btn-default btn-sm',
          },
        },
      });
    },

    /**
     * Remove category.
     */
    remove: function () {
      // There is no id selected.
      if (!fn.categoryId) {
        return;
      }

      var $activeList = fn.$tabContent.find('.dd.active'),
        $item = $activeList.find('.dd-item[data-id="' + fn.categoryId + '"]');


      $item.find('ol.dd-list > li').each(function () {
        $(this).attr('data-removed', true);
      });

      $item.attr('data-removed', true).hide();
      fn.categoryId = 0;

      // There is no items?
      if (!$activeList.find('.dd-item:visible').length) {
        $activeList.find('.no-items').show();
      }

      fn.track();
    },

    /**
     * Update sort info and toggle save button.
     */
    sorted: function () {
      fn.updateProjectCount();
      fn.track();
    },

    /**
     * Update the count of projects for every category.
     */
    updateProjectCount: function () {
      var $activeList = fn.$tabContent.find('.dd.active');

      $activeList.find('> ol.dd-list > li').each(function () {
        var $this = $(this),
          total = parseInt($this.attr('data-cnt-projects')),
          $removeBtn = $this.find('> .dd3-content .btn-remove'),
          $childrenWrap = $this.find('ol.dd-list');

        if ($childrenWrap.length) {
          $childrenWrap.find('> li').each(function () {
            var $child = $(this),
              count = parseInt($(this).attr('data-cnt-projects')),
              $subRemoveBtn = $child.find('> .dd3-content .btn-remove');

            if (count) {
              $subRemoveBtn.attr('disabled', true);
            } else {
              $subRemoveBtn.removeAttr('disabled');
            }

            total += count;
          });
        }

        if (total) {
          $removeBtn.attr('disabled', true);
        } else {
          $removeBtn.removeAttr('disabled');
        }

        $this.attr('data-cnt-projects-with-children', total);
        $this.find('> .dd3-content .dd-item-header h5 > span').html(' (' + total + ')');
      });
    },

    /**
     * Add new category.
     */
    addCategory: function (event) {
      event.preventDefault();

      fn.newCategoryId++;

      var $activeList = fn.$tabContent.find('.dd.active'),
        html = '<li class="dd-item" data-id="temp-' + fn.newCategoryId + '" data-order="-1" data-origin-name="" data-name="Untitled" data-cnt-projects-with-children="0" data-cnt-projects="0" data-parent-id="0">';

      html += '<i class="dd-handle fa fa-arrows"></i>';
      html += '<div class="dd3-content">';
      html += '<div class="dd-item-header">';
      html += '<div class="row">';
      html += '<div class="col-sm-8 col-xs-12">';
      html += '<h5>Untitled<span> (0)</span></h5>';
      html += '</div>'; // .col-sm-8
      html += '<div class="col-sm-4 col-xs-12">';
      html += '<div class="toolbar pull-right">';
      html += ' <button class="btn-edit btn btn-info btn-xs" type="button">Edit<i class="fa fa-edit"></i></button>';
      html += ' <button class="btn-remove btn btn-danger btn-xs" type="button">Remove<i class="fa fa-trash"></i></button>';
      html += '</div>'; // .toolbar
      html += '</div>'; // .col-sm-4
      html += '</div>'; // .row
      html += '</div>'; // .dd-item-header
      html += '</div>'; // .dd3-content
      html += '</li>'; // li

      $activeList.find('.no-items').hide();
      $activeList.find('> ol.dd-list').prepend(html);

      fn.track();
    },

    /**
     * Edit a category.
     */
    editCategory: function (event) {
      event.preventDefault();

      var $this = $(this),
        $item = $this.parents('.dd-item').eq(0),
        $content = $item.find('> .dd3-content');

      $content.append('<div class="edit-category-box"><div class="row"><div class="col-sm-8 col-xs-12"><input type="text" max-length="64" value="' + $item.attr('data-name') + '"></div><div class="col-sm-4 col-xs-12 text-right"><button class="btn-update btn btn-success btn-xs">Update</button> <button class="btn-cancel btn btn-default btn-xs">Cancel</button></div></div>');
      $content.find('.edit-category-box input').focus();
    },

    /**
     * Update category.
     */
    updateCategory: function (event) {
      event.preventDefault();

      var $this = $(this),
        $item = $this.parents('.dd-item').eq(0),
        $content = $item.find('> .dd3-content'),
        $editbox = $content.find('.edit-category-box'),
        $input = $editbox.find('input'),
        name = $input.val();

      if (!name) {
        $input.css('border-color', 'red').focus();
        return;
      }

      $content.find('.dd-item-header h5').html(name + '<span> (' + $item.attr('data-cnt-projects-with-children') + ')</span>');
      $item.attr('data-name', name);
      $editbox.remove();

      fn.track();
    },

    /**
     * Cancel to edit category.
     */
    cancelEditing: function (event) {
      event.preventDefault();

      var $this = $(this),
        $item = $this.parents('.dd-item').eq(0);

      $item.find('> .dd3-content .edit-category-box').remove();
    },

    /**
     * track changes.
     */
    track: function () {
      var $activeList = fn.$tabContent.find('.dd.active'),
        cntX = 0,
        cntY = 0,
        data = {
          type: $activeList.attr('data-type'),
          categories: {}
        },
        hasUpdates = false;

      $activeList.find('> ol.dd-list > li.dd-item').each(function (idx, category) {
        var $this = $(this),
          categoryId = $this.attr('data-id'),
          order = parseInt($this.attr('data-order')),
          $childrenWrap = $this.find('ol.dd-list'),
          temp = {};

        if ($this.is('[data-removed]')) {
          temp['removed'] = true;
          hasUpdates = true;
        } else {
          if (!isNaN($this.attr('data-parent-id')) && parseInt($this.attr('data-parent-id')) != 0) {
            temp['parent'] = 0;
            hasUpdates = true;
          }
          if (order != cntX) {
            temp['order'] = cntX;
            hasUpdates = true;
          }
          if ($this.attr('data-name') != $this.attr('data-origin-name')) {
            temp['name'] = $this.attr('data-name');
            hasUpdates = true;
          }
          cntX++;
        }

        data.categories[categoryId] = temp;
        if ($childrenWrap.length) {
          cntY = 0;
          $childrenWrap.find('> li.dd-item').each(function (idy, child) {
            var $child = $(this),
              childId = $child.attr('data-id'),
              childOrder = parseInt($child.attr('data-order')),
              childTemp = {};

            if ($this.is('[data-removed]') || $child.is('[data-removed]')) {
              childTemp['removed'] = true;
              hasUpdates = true;
            } else {
              if ($child.attr('data-parent-id') != categoryId) {
                childTemp['parent'] = categoryId;
                hasUpdates = true;
              }
              if (childOrder != cntY) {
                childTemp['order'] = cntY;
                hasUpdates = true;
              }
              if ($child.attr('data-name') != $child.attr('data-origin-name')) {
                childTemp['name'] = $child.attr('data-name');
                hasUpdates = true;
              }
              cntY++;
            }

            if (!data.categories[categoryId]['children']) {
              data.categories[categoryId]['children'] = {};
            }
            data.categories[categoryId]['children'][childId] = childTemp;
          });
        }
      });


      if (hasUpdates) {
        fn.$saveBtn.removeAttr('disabled');
      } else {
        fn.$saveBtn.attr('disabled', true);
      }

      return hasUpdates ? data : false;
    },

    /**
     * Save the updates.
     */
    save: function (event) {
      event.preventDefault();

      var $activeList = fn.$tabContent.find('.dd.active'),
        data = fn.track();

      if (!data) {
        return;
      }

      plugin.blockUI();
      // make ajax post call to save the changes.
      $.post(url.saveCategory, {data: data}, function (json) {
        plugin.unblockUI();

        $.bootstrapGrowl(json.msg, {
          type: json.success ? 'success' : 'danger',
          align: 'right',
          width: 'auto',
          delay: 3000,
        });

        if (!json.success) {
          return;
        }

        if (json.map) {
          $.each(json.map, function (oldId, newId) {
            $activeList.find('ol.dd-list > li[data-id="' + oldId + '"]').attr('data-id', newId);
          });
        }
      });
    },

    /**
     * Init
     */
    init: function () {
      fn.$tabContent = $('.tab-content');
      fn.$addBtn = $('.page-actions .btn-add');
      fn.$saveBtn = $('.page-actions .btn-save');

      // Bind nestable plugin.
      fn.$tabContent.find('> .dd').nestable({
        maxDepth: 2,
      }).on('change', fn.sorted);

      fn.$tabContent.on('click', '.btn-edit', fn.editCategory);
      fn.$tabContent.on('click', '.btn-remove', fn.removeConfirm);
      fn.$tabContent.on('click', '.edit-category-box .btn-update', fn.updateCategory);
      fn.$tabContent.on('click', '.edit-category-box .btn-cancel', fn.cancelEditing);

      fn.$addBtn.on('click', fn.addCategory);
      fn.$saveBtn.on('click', fn.save);
    }
  };

  return fn;
});