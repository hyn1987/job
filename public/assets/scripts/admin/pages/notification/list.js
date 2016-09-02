/**
 * list.js - Admin / Notification / Notifications
 */

define(['plugin', 'bootbox', 'growl'], function (plugin, bootbox) {

  var fn = {
    $notificationList: null,
    $saveBtn: null,
    $addBtn: null,
    $noItems: null,
    $editingMessage: null,
    $languageCode: null,
    $addLocaleBtn: null,
    $selLocaleBtn: null,
    $removeLocaleBtn: null,
    $closeLocaleBtn: null,

    notifications: {},
    cntId: 0,
    saveUrl: null,
    defaultSlug: 'UNTITLED',
    defaultLocale: 'EN',
    defaultNotification: 'Please update your notification.',
    htmls: {
      detailBtn: '<button class="btn-detail btn btn-info btn-xs" type="button">Detail <i class="fa fa-eye"></i></button>',
      editBtn: '<button class="btn-edit btn btn-success btn-xs" type="button">Edit <i class="fa fa-edit"></i></button>',
      removeBtn: '<button class="btn-remove btn btn-danger btn-xs" type="button">Remove <i class="fa fa-trash"></i></button>',
      updateBtn: '<button class="btn-update btn btn-success btn-xs" type="button">Update <i class="fa fa-magic"></i></button>',
      cancelBtn: '<button class="btn-cancel btn btn-default btn-xs" type="button">Cancel <i class="fa fa-sign-out"></i></button>',
      addLocaleBtn: '<button class="btn-add-locale btn btn-info btn-xs" type="button">Add Locale <i class="fa fa-language"></i></button>',
      systemChkBtn: '<button class="btn-system btn btn-info btn-xs" type="button"><input type="checkbox" class="is-system" value="1"/>System Notification</button>',
    },

    toggleDetail: function () {
      var $li = $(this).parents('.notification');

      $li.find('.msgs').collapse('toggle');
      fn.$notificationList.find('.notification').not($li).find('.msgs.in').collapse('hide');
    },
    addRow: function (id, notification, prepend) {
      var html = '<li class="notification" data-id="' + id + '">';

      html += '<div class="header"><div class="title">' + notification.slug + '</div>';
      // Notification type
      html += '<div class="type ' + (notification.is_const ? 'const' : (notification.type ? 'system' : 'normal')) + '"><i class="fa fa-' + (notification.type ? 'cogs' : 'warning') + '"></i></div>';
      html += '<div class="actions">';

      html += fn.htmls.detailBtn;
      html += ' ' + fn.htmls.editBtn;
      // If the notification is not constant.
      if (!notification.is_const) {
        html += ' ' + fn.htmls.removeBtn;
      }

      html += '</div>'; // .actions
      html += '</div>'; // .notification-header
      html += '<div class="body">';
      // Add notifications by locale.
      html += '<div class="msgs collapse"><ul>';
      $.each(notification.content, function (locale, msg) {
        html += '<li data-locale="' + locale + '"><span class="label label-primary">' + locale + '</span><div class="msg">' + msg + '</div></li>';
      });
      html += '</ul></div>'; // .msgs
      html += '</div>'; // .body
      html += '</li>';

      if (prepend) {
        fn.$notificationList.prepend(html);
      } else {
        fn.$notificationList.append(html);
      }
    },
    remove: function () {
      var $li = $(this).parents('.notification'),
        id = $li.attr('data-id');

      bootbox.dialog({
        title: '',
        message: trans.remove_notification,
        buttons: {
          yes: {
            label: trans.btn_yes,
            className: 'btn-danger btn-sm',
            callback: function () {
              if (isNaN(id)) {
                delete fn.notifications[id];
                delete fn.monitor.changes[id];
                fn.monitor.run();
              } else {
                fn.monitor.set(id, 'id', id, true);
                fn.monitor.set(id, 'remove', true);
              }
              $li.slideUp();

              fn.checkNoItems();
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
      template: function (locale) {
        var html = '';
        html += '<li data-locale="' + locale + '">';
        html += '<span class="label label-primary">' + locale + ' <i class="fa fa-edit"></i></span>';
        html += '<span class="label label-primary right btn-remove-locale">Remove <i class="fa fa-close"></i></span>';  
        html += '<textarea></textarea>';
        html += '</li>';
        return html;
      },
      editing: function () {
        fn.edit.lock();
        var $li = $(this).parents('.notification'),
          html = '<div class="edit-form">',
          $slug = $li.find('.title'),
          $msgs = $li.find('.msgs > ul > li');

        html += '<div class="slug"><input type="text" max-length="128" value="' + $slug.html() + '" ' + ($li.find(".header .type").hasClass("const")?'readonly class="disabled" title="This is the const slug."':'') + '></div>';
        
        html += '<div class="actions">';
        html += '<button class="btn-system btn btn-info btn-xs" type="button"><input type="checkbox" class="is-system" value="1" ' + ($li.find(".header .type").hasClass("system")?'checked':'') + ' id="systemchk' + $li.attr("data-id") + '"/><label for="systemchk' + $li.attr("data-id") + '">System Notification</lable></button> ';  
        html += fn.htmls.addLocaleBtn + ' ' + fn.htmls.updateBtn + ' ' + fn.htmls.cancelBtn;
        html += '</div>';

        html += '<div class="msgs"><ul>';
        $.map($msgs, function (msg) {
          var $msg = $(msg);
          html += '<li data-locale="' + $msg.attr('data-locale') + '">';
          html += '<span class="label label-primary">' + $msg.attr('data-locale') + ' <i class="fa fa-edit"></i></span>';
          if ($msg.attr('data-locale') != "EN") {
            html += '<span class="label label-primary right btn-remove-locale">Remove <i class="fa fa-close"></i></span>';  
          }
          html += '<textarea>' + br2nl($msg.find('.msg').html()) + '</textarea>';
          html += '</li>';
        });
        html += '</ul></div>';
        //html += fn.languages.html();
        html += '</div>'; // .edit-form
        $li.addClass('editing');
        $li.append(html);

        $li.find('.edit-form input').focus();
      },
      update: function () {
        fn.edit.unlock();
        var $li = $(this).parents('.notification'),
          id = $li.attr('data-id'),
          $editForm = $li.find('.edit-form'),
          $editTitle = $editForm.find('.slug input'),
          $editmsgs = $editForm.find('.msgs textarea'),
          title = $editTitle.val();
        var msgs = {};
        var msgs_html = '';
        var is_const = 0;
        var type = 0;

        $editForm.find(".msgs li").each(function(){
          var $this = $(this);
          var msg = nl2br($this.find("textarea").val());
          msgs[$this.attr("data-locale")] = br2nl($this.find("textarea").val());
          msgs_html += '<li data-locale="' + $this.attr("data-locale") + '">';
          msgs_html += '<span class="label label-primary">' + $this.attr("data-locale") + '</span>';
          msgs_html += '<div class="msg">' + msg + '</div>';
          msgs_html += '</li>';
        });

        if (!$editTitle.val()) {
          $editTitle.addClass('error');
          $editTitle.focus();
          return;
        }

        $editTitle.removeClass('error');

        $li.find('.header .title').html(title);
        if ($editForm.find(".is-system").prop("checked")) {
          $li.find('.header .type').removeClass("normal").addClass("system");
          $li.find('.header .type .fa').removeClass("fa-warning").addClass("fa-cogs");
        } else{
          $li.find('.header .type').removeClass("system").addClass("normal");
          $li.find('.header .type .fa').removeClass("fa-cogs").addClass("fa-warning");
        }
        
        $li.find('.body .msgs ul').html(msgs_html);

        if ($li.find('.header .type').hasClass('system')) {
          type = 1;
        }
        if ($li.find('.header .type').hasClass('const')) {
          is_const = 1;
        }

        fn.monitor.set(id, 'id', id, true);
        fn.monitor.set(id, 'slug', title, true);
        fn.monitor.set(id, 'content', msgs, true);
        fn.monitor.set(id, 'is_const', is_const, true);
        fn.monitor.set(id, 'type', type);

        $li.removeClass('editing');
        $editForm.remove();
        $li.find(".header .btn-detail").trigger("click");
      },
      cancel: function () {
        fn.edit.unlock();
        var $li = $(this).parents('.notification');

        $li.removeClass('editing');
        $li.find('.edit-form').remove();
        fn.$languageCode.fadeOut();
      },
      addLocale: function() {
        fn.$languageCode.hide();
        fn.$languageCode.fadeIn();
        fn.$languageCode.find("ul li").removeClass("selected");
        fn.$editingMessage = $(this).parent().next().find("ul");
        $.each(fn.$editingMessage.find("li"), function(){
          var data_locale = $(this).attr("data-locale");
          fn.$languageCode.find("ul li[data-locale='" + data_locale + "']").addClass("selected");
        });
        var offset = $(this).offset();
        fn.$languageCode.css('top', -110 + offset.top + 'px');
        fn.$selLocaleBtn.off('click');
        fn.$selLocaleBtn.on('click', fn.edit.selectLocale);
      },
      selectLocale: function() {
        var sel_locale = $(this);
        if (sel_locale.hasClass("selected")) {
          return false;
        } else {
          sel_locale.addClass("selected");

          fn.$editingMessage.append(fn.edit.template(sel_locale.attr("data-locale")));
        }
        
      },
      removeLocale: function() {
        $(this).parent().remove();
      },
      closeLocale: function() {
        fn.$languageCode.fadeOut();
      },
      lock: function () {
        fn.$notificationList.find(".btn-detail").addClass("disabled");
        fn.$notificationList.find(".btn-edit").addClass("disabled");
        fn.$notificationList.find(".btn-remove").addClass("disabled");
      },
      unlock: function () {
        fn.$notificationList.find(".btn-detail").removeClass("disabled");
        fn.$notificationList.find(".btn-edit").removeClass("disabled");
        fn.$notificationList.find(".btn-remove").removeClass("disabled");
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
      setBulk: function (id, data) {
        fn.monitor.changes[id] = data;
        fn.monitor.run();
      },
      set: function (id, key, val, onlySet) {
        if (!fn.monitor.changes[id]) {
          fn.monitor.changes[id] = {};
        }

        if (isNaN(id)) {
          fn.monitor.changes[id][key] = val;
        } else {
          fn.monitor.changes[id][key] = val;
          /*switch (key) {
            case 'content':
              $.each(val, function (locale, msg) {
                if (fn.notifications[id][key][locale]) {

                }

              });
            case 'slug':
              if (fn.notifications[id][key] != val) {
                fn.monitor.changes[id][key] = val;
              } else {
                delete fn.monitor.changes[id][key];
              }
              break;
            default:
              fn.monitor.changes[id][key] = val;
          }*/

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
    languages: {
      data: null,

      html: function (selected) {
        var html = '<div class="languages"><ul>';
        /*
        $.map(fn.languages.data, function (language) {
          html += '<li data-locale="' + language.code + '-' + language.country + '"' + (language.code + '-' + language.country  == selected ? ' class="active"' : '') + '><span class="label label-primary">' + language.code + '-' + language.country + '</span></li>';
        });*/
        html += '<li data-locale="KP"' + '><span class="label label-primary">KP</span></li>';
        html += '<li data-locale="EN"' + '><span class="label label-primary">EN</span></li>';
        html += '<li data-locale="CH"' + '><span class="label label-primary">CH</span></li>';
        html += '<ul></div>';

        return html;
      },
      init: function () {
        fn.languages.data = data.languages;
        fn.$languageCode.append(fn.languages.html());
        fn.$selLocaleBtn = $(".language-code .languages li");
      },
    },
    add: function () {
      var id = 'temp-' + fn.cntId;

      fn.notifications[id] = {
        id: id,
        slug: fn.defaultSlug,
        content: {},
        is_const: 0,
        type: 0,
      };

      fn.notifications[id]['content'][fn.defaultLocale] = fn.defaultNotification;

      fn.monitor.setBulk(id, fn.notifications[id]);

      fn.addRow(id, fn.notifications[id], true);

      fn.cntId++;

      fn.checkNoItems();
    },
    save: function () {
      // make ajax post call to save the changes.
      plugin.blockUI();
      $.post(fn.saveUrl, {changes: fn.monitor.changes}, function (json) {
        plugin.unblockUI();
        if (json.status == 'success') {
          var temp;
          $.each(json.reflects, function (id, val) {
            // Added
            fn.$notificationList.find('.notification[data-id="' + id + '"]').attr('data-id', val);
            temp = fn.notifications[id];
            fn.notifications[val] = temp;
            delete fn.notifications[id];
          });

          $.bootstrapGrowl("The changed notifications have saved successfully.", {
            type: 'default',
            align: 'center',
            width: 'auto',
            delay: 300440,
          });
        }
        fn.monitor.reset();
      });
    },
    checkNoItems: function () {
      if (fn.$notificationList.find('.notification:visible').length) {
        fn.$noItems.addClass('hidden');
      } else {
        fn.$noItems.removeClass('hidden');
      }
    },

    init: function () {
      fn.$notificationList = $('.notifications');
      fn.$addBtn = $('.btn-add');
      fn.$saveBtn = $('.btn-save');
      fn.$noItems = fn.$notificationList.find('.no-items');
      fn.$languageCode = $(".language-code");
      fn.$selLocaleBtn = $(".language-code .languages li");
      fn.$addLocaleBtn = $(".btn-add-locale");
      fn.$closeLocaleBtn = $(".language-code .close");

      fn.saveUrl = data.saveUrl;

      $.map(data.notifications, function (notification) {
        fn.notifications[notification.id] = notification;
        fn.addRow(notification.id, notification);
      });
      fn.checkNoItems();

      fn.$notificationList.on('click', '.btn-detail', fn.toggleDetail);
      fn.$notificationList.on('click', '.btn-remove', fn.remove);

      // Edit
      fn.$notificationList.on('click', '.btn-edit', fn.edit.editing);
      fn.$notificationList.on('click', '.btn-update', fn.edit.update);
      fn.$notificationList.on('click', '.btn-cancel', fn.edit.cancel);
      fn.$notificationList.on('click', '.btn-add-locale', fn.edit.addLocale);
      fn.$notificationList.on('click', '.btn-remove-locale', fn.edit.removeLocale);
      fn.$selLocaleBtn.on('click', fn.edit.selectLocale);
      fn.$closeLocaleBtn.on('click', fn.edit.closeLocale);

      // Add
      fn.$addBtn.on('click', fn.add);

      // Save
      fn.$saveBtn.on('click', fn.save);

      fn.languages.init();

      $(window).on('beforeunload', function (event) {
        if (fn.monitor.run()) {
          return 'You have entered new data on this page. If you navigate away from this page without first saving your data, the changes will be lost.';
        }
      });
    }
  };

  return fn;
});