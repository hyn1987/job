/**
 * list.js - Admin / Settings / Categories
 */

define(['plugin', 'bootbox', 'growl'], function (plugin, bootbox) {

  var fn = {
    $skillList: null,
    $saveBtn: null,
    $addBtn: null,
    $noItems: null,
    skills: {},
    cntId: 0,
    deactivatableUrl: null,
    saveUrl: null,
    untitled: 'Untitled',
    htmls: {
      activateBtn: '<button class="btn-activate btn btn-success btn-xs" type="button">Activate <i class="fa fa-android"></i></button>',
      removeBtn: '<button class="btn-remove btn btn-danger btn-xs" type="button">Remove <i class="fa fa-trash"></i></button>',
      detailBtn: '<button class="btn-detail btn btn-info btn-xs" type="button">Detail <i class="fa fa-eye"></i></button>',
      editBtn: '<button class="btn-edit btn btn-success btn-xs" type="button">Edit <i class="fa fa-edit"></i></button>',
      deactivateBtn: '<button class="btn-deactivate btn btn-danger btn-xs" type="button">Deactivate <i class="fa fa-android"></i></button>',
      updateBtn: '<button class="btn-update btn btn-success btn-xs" type="button">Update <i class="fa fa-magic"></i></button>',
      cancelBtn: '<button class="btn-cancel btn btn-default btn-xs" type="button">Cancel <i class="fa fa-sign-out"></i></button>',
    },

    toggleDetail: function () {
      var $li = $(this).parents('.skill');

      $li.find('.desc').collapse('toggle');
      fn.$skillList.find('.skill').not($li).find('.desc.in').collapse('hide');
    },
    addRow: function (id, skill, prepend) {
      var html = '<li class="skill' + (skill.deleted_at ? ' deactive' : '') + '" data-id="' + id + '">';

      html += '<div class="title">' + skill.name + '</div>';
      html += '<div class="actions">';
      if (skill.deleted_at) {
        html += fn.htmls.activateBtn;
        html += ' ' + fn.htmls.removeBtn;
      } else {
        html += fn.htmls.detailBtn;
        html += ' ' + fn.htmls.editBtn;
        html += ' ' + fn.htmls.deactivateBtn;
      }
      html += '</div>';
      html += '<div class="desc collapse"><span>' + skill.desc + '</span></div>';
      html += '</li>';

      if (prepend) {
        fn.$skillList.prepend(html);
      } else {
        fn.$skillList.append(html);
      }
    },
    remove: function () {
      var $li = $(this).parents('.skill'),
        id = $li.attr('data-id');

      bootbox.dialog({
        title: '',
        message: trans.remove_skill,
        buttons: {
          yes: {
            label: trans.btn_yes,
            className: 'btn-danger btn-sm',
            callback: function () {
              if (isNaN(id)) {
                delete fn.skills[id];
                delete fn.monitor.changes[id];
                fn.monitor.run();
              } else {
                fn.monitor.set(id, 'remove', true);
              }
              $li.slideUp();

              fn.noItems();
            },
          },
          no: {
            label: trans.btn_no,
            className: 'btn-default btn-sm',
          },
        },
      });
    },
    activate: function () {
      var $li = $(this).parents('.skill');
      fn.monitor.set($li.attr('data-id'), 'active', true);
      $li.removeClass('deactive');
      $li.find('.actions').html(fn.htmls.detailBtn + ' ' + fn.htmls.editBtn + ' ' + fn.htmls.deactivateBtn);
    },
    deactivateAfter: function (id, $li) {
      fn.monitor.set(id, 'active', 0);
      $li.addClass('deactive');
      $li.find('.actions').html(fn.htmls.activateBtn + ' ' + fn.htmls.removeBtn);
    },
    deactivate: function () {
      var $li = $(this).parents('.skill'),
        id = $li.attr('data-id');

      bootbox.dialog({
        title: '',
        message: trans.deactivate_skill,
        buttons: {
          yes: {
            label: trans.btn_yes,
            className: 'btn-danger btn-sm',
            callback: function () {
              if (isNaN(id)) {
                fn.deactivateAfter(id, $li);
                return;
              }

              $.post(fn.deactivatableUrl, {id: parseInt(id)}, function (json) {
                if (!json.success) {
                  $.bootstrapGrowl(json.msg, {
                    type: 'danger',
                    align: 'right',
                    width: 'auto',
                    delay: 3000,
                  });
                  return;
                }

                fn.deactivateAfter(id, $li);
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
        var $li = $(this).parents('.skill'),
          html = '<div class="edit-form"><div class="title">',
          $title = $li.find('.title'),
          $desc = $li.find('.desc span');

        html += '<input type="text" max-length="64" value="' + $title.html() + '"></div>';
        html += '<div class="actions">' + fn.htmls.updateBtn + ' ' + fn.htmls.cancelBtn + '</div>';
        html += '<div class="desc"><textarea>' + br2nl($desc.html()) + '</textarea></div>';
        html += '</div>';
        $li.addClass('editing');
        $li.append(html);

        $li.find('.edit-form input').focus();
      },
      update: function () {
        var $li = $(this).parents('.skill'),
          id = $li.attr('data-id'),
          $editForm = $li.find('.edit-form'),
          $editTitle = $editForm.find('.title input'),
          $editDesc = $editForm.find('.desc textarea'),
          title = $editTitle.val(),
          desc = $editDesc.val();
          
        if (!$editTitle.val()) {
          $editTitle.addClass('error');
          $editTitle.focus();
          return;
        }

        $editTitle.removeClass('error');

        $li.find('> .title').html(title);
        $li.find('> .desc span').html(nl2br(desc));

        fn.monitor.set(id, 'name', title, true);
        fn.monitor.set(id, 'desc', desc);

        $li.removeClass('editing');
        $editForm.remove();
      },
      cancel: function () {
        var $li = $(this).parents('.skill');

        $li.removeClass('editing');
        $li.find('.edit-form').remove();
      },
    },
    choose: function () {
      var $this = $(this);
      $this.toggleClass('choose');
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
          switch (key) {
            case 'active':
              if ((val && fn.skills[id].deleted_at) || (!val && !fn.skills[id].deleted_at)) {
                fn.monitor.changes[id][key] = val;
              } else {
                delete fn.monitor.changes[id][key];
              }
              break;
            case 'name':
            case 'desc':
              if (fn.skills[id][key] != val) {
                fn.monitor.changes[id][key] = val;
              } else {
                delete fn.monitor.changes[id][key];
              }
              break;
            default:
              fn.monitor.changes[id][key] = val;
          }

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
    add: function () {
      var id = 'temp-' + fn.cntId;

      fn.skills[id] = {
        id: id,
        name: fn.untitled,
        desc: '',
        deleted_at: null,
      };

      fn.monitor.set(id, 'name', fn.untitled, true);
      fn.monitor.set(id, 'desc', '', true);
      fn.monitor.set(id, 'active', 1);

      fn.addRow(id, fn.skills[id], true);

      fn.cntId++;

      fn.noItems();
    },
    save: function () {
      // make ajax post call to save the changes.
      plugin.blockUI();
      $.post(fn.saveUrl, {changes: fn.monitor.changes}, function (json) {
        var msgs = '', temp;

        plugin.unblockUI();

        $.each(json.reflects, function (id, reflect) {
          // Added
          if (reflect.id) {
            fn.$skillList.find('.skill[data-id="' + id + '"]').attr('data-id', reflect.id);
            temp = fn.skills[id];
            fn.skills[reflect.id] = temp;
            delete fn.skills[id];
          }

          $.map(reflect.msgs, function (msg) {
            msgs += '<span class="msg msg-' + msg.type + '">' + msg.msg + '</span>';
          });
        });

        $.bootstrapGrowl(msgs, {
          type: 'default',
          align: 'right',
          width: 'auto',
          delay: 300440,
        });

        fn.monitor.reset();
      });
    },
    noItems: function () {
      if (fn.$skillList.find('.skill:visible').length) {
        fn.$noItems.addClass('hidden');
      } else {
        fn.$noItems.removeClass('hidden');
      }
    },
    search: {
      $searchBox: null,
      $searchName: null,

      run: function () {
        var name = fn.search.$searchBox.find('.search-name').val(),
          active = fn.search.$searchBox.find('.search-active').is('.active'),
          inactive = fn.search.$searchBox.find('.search-inactive').is('.active');

        $.each(fn.$skillList.find('.skill'), function (id, skill) {
          var $li = $(this);

          // By status
          if (!active && !inactive) {
            $li.addClass('hidden');
            return;
          }

          if (active && !inactive && !$li.find('.btn-deactivate').length) {
            $li.addClass('hidden');
            return;
          }

          if (!active && inactive && !$li.find('.btn-activate').length) {
            $li.addClass('hidden');
            return;
          }

          // By name
          if ($li.find('.title').html().indexOf(name) === -1) {
            $li.addClass('hidden');
            return;
          }

          $li.removeClass('hidden');
        });

        fn.noItems();
      },
      init: function () {
        fn.search.$searchBox = $('.search-box');

        fn.search.$searchBox.on('keyup click', '.search-item', fn.search.run);
      },
    },
    ticker: {
      className: '.btn-ticker',
      activeIcon: 'fa-check-square-o',
      inactiveIcon: 'fa-square-o',
      tick: function () {
        var $this = $(this),
          $icon = $this.find('i'),
          active = $(this).is('.active');

        if (active) {
          $this.removeClass('active');
          $icon.removeClass(fn.ticker.activeIcon).addClass(fn.ticker.inactiveIcon);
          return;
        }

        $this.addClass('active');
        $icon.addClass(fn.ticker.activeIcon).removeClass(fn.ticker.inactiveIcon);
      },
      init: function () {
        $(fn.ticker.className).on('click', fn.ticker.tick);
      },
    },

    init: function () {
      fn.$skillList = $('.skills');
      fn.$addBtn = $('.btn-add');
      fn.$saveBtn = $('.btn-save');
      fn.$noItems = fn.$skillList.find('.no-items');

      var skills = JSON.parse(fn.$skillList.attr('data-skills'));

      fn.saveUrl = fn.$skillList.attr('data-save-url');
      fn.deactivatableUrl = fn.$skillList.attr('data-deactivatable-url');

      fn.$skillList.removeAttr('data-skills').removeAttr('data-save-url').removeAttr('data-deactivatable-url');

      $.map(skills, function (skill) {
        fn.skills[skill.id] = skill;
        fn.addRow(skill.id, skill);
      });
      fn.noItems();

      //fn.$skillList.on('click', '> li', fn.choose);
      fn.$skillList.on('click', '.btn-detail', fn.toggleDetail);
      fn.$skillList.on('click', '.btn-activate', fn.activate);
      fn.$skillList.on('click', '.btn-deactivate', fn.deactivate);
      fn.$skillList.on('click', '.btn-remove', fn.remove);

      // Edit
      fn.$skillList.on('click', '.btn-edit', fn.edit.editing);
      fn.$skillList.on('click', '.btn-update', fn.edit.update);
      fn.$skillList.on('click', '.btn-cancel', fn.edit.cancel);

      // Add
      fn.$addBtn.on('click', fn.add);

      // Save
      fn.$saveBtn.on('click', fn.save);

      fn.search.init();
      fn.ticker.init();

      $(window).on('beforeunload', function (event) {
        if (fn.monitor.run()) {
          return 'You have entered new data on this page. If you navigate away from this page without first saving your data, the changes will be lost.';
        }
      });
    }
  };

  return fn;
});