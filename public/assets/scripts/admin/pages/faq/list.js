/**
 * list.js - Admin / FAQ/List
 */

define(['plugin', 'bootbox', 'nestable', 'growl'], function (plugin, bootbox) {

	var fn = {	
		$tabContent: null,
  	$addBtn: null,
  	$saveBtn: null,
    $noItems: null,
    $catItems: null,
  	
    catId: 0,
    faqId: 0,
    newFaqId: 0,
    defaultTitle: 'UNTITLED',
    defaultFAQ: 'Please update this FAQ.',
    saveUrl: null,
    loadUrl: null,

    htmls: {
      detailBtn: '<button class="btn-detail btn btn-info btn-xs" type="button">Detail <i class="fa fa-eye"></i></button>',
      editBtn: '<button class="btn-edit btn btn-success btn-xs" type="button">Edit <i class="fa fa-edit"></i></button>',
      removeBtn: '<button class="btn-remove btn btn-danger btn-xs" type="button">Remove <i class="fa fa-trash"></i></button>',
      updateBtn: '<button class="btn-update btn btn-success btn-xs" type="button">Update <i class="fa fa-magic"></i></button>',
      cancelBtn: '<button class="btn-cancel btn btn-default btn-xs" type="button">Cancel <i class="fa fa-sign-out"></i></button>',
      noFAQ:'<li class="no-items hidden"><div class="dd3-content">No Faq found.</div></li>',
    },

    toggleDetail: function () {
      var $li = $(this).parents('.dd-item');

      $li.find('.dd-item-content').collapse('toggle');
      fn.$tabContent.find('.dd-item').not($li).find('.dd-item-content.in').collapse('hide');
    },

    sorted: function () {
      var cat_id = fn.catId;
      var sort_ind = 0;
      var sorts = {};
      fn.$tabContent.find(".dd-item:visible").each(function(){
        sorts[$(this).attr("data-id")] = sort_ind;
        sort_ind++;
      });

      fn.monitor.set("order", cat_id, sorts);
    },

    checkNoItems: function () {
      if (fn.$tabContent.find('.dd-item:visible').length > 0) {
        fn.$noItems.addClass('hidden');
      } else {
        fn.$noItems.removeClass('hidden');
      }
    },

  	/**
     * Add new faq.
     */
    add: function (event) {
      event.preventDefault();
      var id = 'temp-' + fn.newFaqId;
      var $activeList = fn.$tabContent.find(' ol.dd-list');
      var html = '<li class="dd-item" data-type="1" data-id="temp-' + fn.newFaqId + '">';
      html += '<i class="dd-handle fa fa-arrows"></i>';
      html += '<div class="dd3-content">';
      html += '<div class="dd-item-header">';
      html += '<div class="row">';
      html += '<div class="col-sm-8 col-xs-12">';
      html += '<h5 class="title">' + fn.defaultTitle + '</h5>';
      html += '</div>'; // .col-sm-8
      html += '<div class="col-sm-4 col-xs-12">';
      html += '<div class="toolbar pull-right">';
      html += fn.htmls.detailBtn + ' ' + fn.htmls.editBtn + ' ' + fn.htmls.removeBtn;
      html += '</div>'; // .toolbar
      html += '</div>'; // .col-sm-4
      html += '<div class="type all"><i class="fa fa-question"></i></div>';
      html += '</div>'; // .row
      html += '</div>'; // .dd-item-header
      html += '<div class="dd-item-body">';
      html += '<div class="dd-item-content collapse">' + fn.defaultFAQ + '</div>';
      html += '</div>'; // .dd-item-body
      html += '</div>'; // .dd3-content
      html += '</li>'; // li
      fn.newFaqId++;
      $activeList.prepend(html);
      
      fn.monitor.set(id, 'id', id, true);
      fn.monitor.set(id, 'title', fn.defaultTitle, true);
      fn.monitor.set(id, 'content', fn.defaultFAQ, true);
      fn.monitor.set(id, 'cat_id', fn.catId, true);
      fn.monitor.set(id, 'type', 1);
      fn.sorted();
      fn.checkNoItems();
    },
    remove: function () {
      var $li = $(this).parents('.dd-item'),
        id = $li.attr('data-id');

      bootbox.dialog({
        title: '',
        message: trans.remove_faq,
        buttons: {
          yes: {
            label: trans.btn_yes,
            className: 'btn-danger btn-sm',
            callback: function () {
              if (isNaN(id)) {
                delete fn.monitor.changes[id];
                fn.monitor.run();
              } else {
                fn.monitor.set(id, 'id', id, true);
                fn.monitor.set(id, 'remove', true);
              }
              $li.slideUp(function() {
                fn.checkNoItems();
                fn.sorted();  
              });
            },
          },
          no: {
            label: trans.btn_no,
            className: 'btn-default btn-sm',
          },
        },
      });
    },
    edit: {
      editing: function () {
        fn.edit.lock();
        var $li = $(this).parents('.dd-item');
        var html = '<div class="edit-form">';

        html += '<div class="title"><input type="text" max-length="128" value="' + $li.find(".dd-item-header .title").html() + '"></div>';
        
        html += '<div class="actions">';
        html += '<select class="faq-type" name="faq-type">';
        html += '<option value="1" ' + ($li.attr("data-type") == 1 ?'selected':'') +'>All</option>';
        html += '<option value="0" ' + ($li.attr("data-type") == 0 ?'selected':'') +'>Buyer</option>';
        html += '<option value="2" ' + ($li.attr("data-type") == 2 ?'selected':'') +'>Freelancer</option>';
        html += '</select>';
        html += ' ' + fn.htmls.updateBtn + ' ' + fn.htmls.cancelBtn;
        html += '</div>';

        html += '<div class="msgs">';
        html += '<textarea>' + br2nl($li.find(".dd-item-content").html()) + '</textarea>';
        html += '</div>';
        html += '</div>'; // .edit-form
        $li.addClass('editing');
        $li.append(html);

        $li.find('.edit-form input').focus();
      },
      update: function () {
        fn.edit.unlock();
        var $li = $(this).parents('.dd-item'),
          id = $li.attr('data-id'),
          $editForm = $li.find('.edit-form'),
          $editTitle = $editForm.find('.title input'),
          $editMsgs = $editForm.find('.msgs textarea'),
          title = $editTitle.val();
        var msgs = nl2br($editMsgs.val());
        var type = $li.find(".faq-type").val();

        if (!$editTitle.val()) {
          $editTitle.addClass('error');
          $editTitle.focus();
          return;
        }

        $editTitle.removeClass('error');

        $li.find('.dd-item-header .title').html(title);
        
        $li.find('.dd-item-content').html(msgs);

        $li.attr("data-type", type);
        $li.find(".dd-item-header .type").removeClass("all").removeClass("buyer").removeClass("freelancer");
        $li.find(".dd-item-header .type").addClass($li.find(".faq-type option:selected").text().toLowerCase());

        fn.monitor.set(id, 'id', id, true);
        fn.monitor.set(id, 'title', title, true);
        fn.monitor.set(id, 'content', br2nl($editMsgs.val()), true);
        fn.monitor.set(id, 'cat_id', fn.catId);
        fn.monitor.set(id, 'type', type);
        $li.removeClass('editing');
        $editForm.remove();
        
        $li.find(".dd-item-header .btn-detail").trigger("click");
        fn.sorted();
      },
      cancel: function () {
        fn.edit.unlock();
        var $li = $(this).parents('.dd-item');
        $li.removeClass('editing');
        $li.find('.edit-form').remove();
      },
      lock: function () {
        fn.$tabContent.find(".btn-detail").addClass("disabled");
        fn.$tabContent.find(".btn-edit").addClass("disabled");
        fn.$tabContent.find(".btn-remove").addClass("disabled");
      },
      unlock: function () {
        fn.$tabContent.find(".btn-detail").removeClass("disabled");
        fn.$tabContent.find(".btn-edit").removeClass("disabled");
        fn.$tabContent.find(".btn-remove").removeClass("disabled");
      },
    },
    selectCategory: function () {
      $li = $(this);
      $(".faq-sidebar-menu ul li").removeClass("active");
      $li.addClass("active");
      fn.catId = $li.find("a").attr("cat-id");
      fn.load();
    },
    load: function () {
      // make ajax post call to save the changes.
      plugin.blockUI();
      $.post(fn.loadUrl, {cat_id: fn.catId}, function (json) {
        plugin.unblockUI();
        var $activeList = fn.$tabContent.find(' ol.dd-list');
        if (json.status == 'success') {
          fn.newFaqId = 0;
          var html = '';
          
          var type_arr = ["buyer", "all", "freelancer"];
          $.each(json.faqs, function(id, faq){
            var id = faq.id;
            
            html += '<li class="dd-item" data-type="' + faq.type + '" data-id="' + faq.id + '">';
            html += '<i class="dd-handle fa fa-arrows"></i>';
            html += '<div class="dd3-content">';
            html += '<div class="dd-item-header">';
            html += '<div class="row">';
            html += '<div class="col-sm-8 col-xs-12">';
            html += '<h5 class="title">' + faq.title + '</h5>';
            html += '</div>'; // .col-sm-8
            html += '<div class="col-sm-4 col-xs-12">';
            html += '<div class="toolbar pull-right">';
            html += fn.htmls.detailBtn + ' ' + fn.htmls.editBtn + ' ' + fn.htmls.removeBtn;
            html += '</div>'; // .toolbar
            html += '</div>'; // .col-sm-4
            html += '<div class="type ' + type_arr[faq.type] + '"><i class="fa fa-question"></i></div>';
            html += '</div>'; // .row
            html += '</div>'; // .dd-item-header
            html += '<div class="dd-item-body">';
            html += '<div class="dd-item-content collapse">' + faq.content + '</div>';
            html += '</div>'; // .dd-item-body
            html += '</div>'; // .dd3-content
            html += '</li>'; // li
            /*fn.monitor.set(id, 'id', faq.id, true);
            fn.monitor.set(id, 'title', faq.title, true);
            fn.monitor.set(id, 'content', faq.content, true);
            fn.monitor.set(id, 'cat_id', faq.cat_id, true);
            fn.monitor.set(id, 'type', faq.type);*/
          });
          html += fn.htmls.noFAQ;
          $activeList.html(html);
          fn.checkNoItems();
        } else {
          $activeList.html(fn.htmls.noFAQ);
          fn.checkNoItems();
        }
      });
    },
    save: function () {
      // make ajax post call to save the changes.
      plugin.blockUI();
      $.post(fn.saveUrl, {changes: fn.monitor.changes}, function (json) {
        plugin.unblockUI();
        if (json.status == 'success') {
          $.each(json.reflects, function (id, val) {
            // Added
            fn.$tabContent.find('.dd-item[data-id="' + id + '"]').attr('data-id', val);
          });

          $.bootstrapGrowl("The changed FAQ have saved successfully.", {
            type: 'default',
            align: 'center',
            width: 'auto',
            delay: 3000,
          });
        }
        fn.monitor.reset();
      });
    },
    monitor: {
      changes: {},
      reset: function () {
        fn.monitor.changes = {};
      },
      set: function (id, key, val, onlySet) {
        if (!fn.monitor.changes[id]) {
          fn.monitor.changes[id] = {};
        }

        if (isNaN(id)) {
          fn.monitor.changes[id][key] = val;
        } else {
          fn.monitor.changes[id][key] = val;
          if (!getObjectSize(fn.monitor.changes[id])) {
            delete fn.monitor.changes[id];
          }
        }

        if (!onlySet) {
          fn.monitor.run();
        }
      },
      run: function () {
        var changed = getObjectSize(fn.monitor.changes);

        if (changed) {
          fn.$saveBtn.removeAttr('disabled');
        } else {
          fn.$saveBtn.attr('disabled', true);
        }

        return changed;
      },
    },
		init: function () {

			fn.$tabContent = $('.tab-content');
    	fn.$addBtn = $('.page-actions .btn-add');
    	fn.$saveBtn = $('.page-actions .btn-save');
      fn.$noItems = fn.$tabContent.find('.no-items');
      fn.$catItems = $('.faq-sidebar-menu .menu-item');
      fn.saveUrl = data.saveUrl;
      fn.loadUrl = data.loadUrl;
      fn.checkNoItems();
    	// Bind nestable plugin.
      fn.$tabContent.find('> .dd').nestable({
        maxDepth: 1,
      }).on('change', fn.sorted);

      // Add
      fn.$addBtn.on('click', fn.add);
      // Save
      fn.$saveBtn.on('click', fn.save);

      fn.$tabContent.on('click', '.btn-remove', fn.remove);
      fn.$tabContent.on('click', '.btn-edit', fn.edit.editing);
      fn.$tabContent.on('click', '.btn-update', fn.edit.update);
      fn.$tabContent.on('click', '.btn-cancel', fn.edit.cancel);
      fn.$tabContent.on('click', '.btn-detail', fn.toggleDetail);

      fn.$catItems.on('click', fn.selectCategory);

      $(".faq-sidebar-menu ul li:first-child").addClass("active");
      fn.catId = $(".faq-sidebar-menu ul .active a").attr("cat-id");
      fn.load();

      $(window).on('beforeunload', function (event) {
        if (fn.monitor.run()) {
          return 'You have entered new data on this page. If you navigate away from this page without first saving your data, the changes will be lost.';
        }
      });

		}

	};

	return fn;
});

