/**
 * list.js - Admin / Settings / Categories
 */

define(['plugin', 'bootbox', 'growl', 'form', 'datetimepicker'], function (plugin, bootbox) {

  var fn = {
    $searchUsers: null,
    $notificationUsers: null,
    $addUserBtn: null,
    $addAllBtn: null,
    $removeUserBtn: null,
    $removeAllBtn: null,
    $username: null,
    $email: null,
    $userType: null,
    $status: null,
    $notificationObj: null,
    $notification: null,
    $sendBtn: null,
    $cronBtn: null,

    /**
     * Ask confirm to user before send notification.
     */
    sendConfirm: function (event) {
      event.preventDefault();
      var users = "";
      $.each(fn.$notificationUsers.find(".searched"), function(){
        users += $(this).attr("uid") + ",";
      });
      if (users == "") {
        $.bootstrapGrowl('Please select the users for the notification', {
          type: 'danger',
          align: 'center',
          width: 'auto',
          delay: 2000,
        });
        return false;
      }
      users = users.substr(0, users.length - 1);
      $("#users").val(users);
      var noti_slug = $(".notifications .selected .noti-slug").text();
      if (noti_slug == "") {
        $.bootstrapGrowl('Please select a notification', {
          type: 'danger',
          align: 'center',
          width: 'auto',
          delay: 2000,
        }); 
        return false;
      }

      $("#slug").val($(".notifications .selected .noti-slug").text());

      if ($(this).hasClass("btn-send")) {
        $("#mode").val("0");
      } else {
        $("#mode").val("1");
      }

      bootbox.dialog({
        title: $("#mode").val() == "0" ? trans.title_send_notification : trans.title_add_cronjob,
        message: $("#mode").val() == "0" ? trans.send_notification : trans.add_cronjob,
        buttons: {
          yes: {
            label: trans.btn_yes,
            className: 'btn-danger btn-sm',
            callback: fn.send,
          },
          no: {
            label: trans.btn_no,
            className: 'btn-default btn-sm',
          },
        },
      });
      
    },

    send: function() {
      fn.$sendBtn.addClass("disabled");
      plugin.blockUI();
      $("#frm_notification_multicast").ajaxSubmit({
        success: function(json) {
          plugin.unblockUI();
          fn.$sendBtn.removeClass("disabled");
          $.bootstrapGrowl(json.msg, {
            type: json.status == 'success' ? 'success' : 'danger',
            align: 'center',
            width: 'auto',
            delay: 3000,
          });
          if (json.status == 'success') {

            return true;
          }

        },

        error: function(xhr) {
          console.log(xhr);
        },

        dataType: 'json'
      });
    },

    cron: function() {

    },
    search: function () {
      var username = fn.$username.val();
      var email = fn.$email.val();
      var userType = fn.$userType.val();
      var status = fn.$status.val();
      fn.$searchUsers.find(".dd-item").each(function(){
        var $li = $(this);
        if (username != "") {
          if ($li.find('.username').text().indexOf(username) === -1) {
            $li.removeClass('searched selected');
            return;
          }
        }
        if (email != "") {
          if ($li.find('.email').text().indexOf(email) === -1) {
            $li.removeClass('searched selected');
            return;
          }
        }

        if (userType != "") {
          if ($li.find('.user-type-' + userType).length == 0) {
            $li.removeClass('searched selected');
            return;
          }
        }

        if (status != "") {
          if ($li.find('.state-' + status).length == 0) {
            $li.removeClass('searched selected');
            return;
          }
        }
        if (!$li.hasClass("searched")) {
          $li.addClass('searched');  
        }
        
      });
    },

    searchNotification: function () {
      var notificationName = fn.$notificationObj.val().toLowerCase();
      fn.$notification.each(function(){
        var $li = $(this);
        if (notificationName != "") {
          if ($li.find(".noti-slug").text().toLowerCase().indexOf(notificationName) === -1) {
            $li.removeClass('searched');
            return;
          }
        } else{
          $li.addClass('searched');
        }
      });
    },

    getNotification: function () {
      var $this = $(this);
      fn.$notification.removeClass("selected");
      $this.addClass("selected");
      var notificationId = $this.attr("data-id");
      var _url = siteUrl + '/admin/notification/get/' + notificationId;
      $.ajax({
        url:   _url,
        type:   'POST',
        data:{},
        beforeSend: function(jqXHR, settings) {},
        error: function() {},
        success: function(json) {
          if (json.status == 'success') {
            var content = JSON.parse(json.result.content);
            var params = json.params;
            var html = '';
            $.each(content, function (locale, msg) {
              html += '<li data-locale="' + locale + '"><span class="label label-primary">' + locale + '</span><div class="msg">' + nl2br(msg) + '</div></li>';
            });
            $this.find(".msgs > ul").html(html);
            fn.$notification.find('.msgs.in').collapse('hide');
            $this.find('.msgs').collapse('toggle');
            var frm_html = "";
            $.each(params, function(locale, params) {
              if (params.length > 0) {
                frm_html += '<li><span class="label label-primary">' + locale + '</span></li>';
              } else {
                frm_html += '<li><span class="label label-primary">' + locale + '</span></li><li>No parameter required.</li>';
              }
              
              $.each(params, function(key, val) {
                frm_html += '<li><input type="text" name="param[' + locale + '][' + val + ']" class="form-control param-control" value="" placeholder="' + locale + ':' + val + '"></li>';
              });
            });
            $("#frm_notification_multicast .params").html(frm_html);
            
          } else {

          }        
        },   // END OF SUCESS FUNCTION
        complete: function (jqXHR, textStatus) {
          
        }
      });
    },

    selectUser: function () {
      var $this = $(this);
      if ($this.hasClass("selected")) {
        $this.removeClass("selected");
      } else{
        $this.addClass("selected");
      }
    },

    addUser: function () {
      var item_html = '';
      fn.$searchUsers.find(".selected").each(function(){
        var $sel = $(this);
        item_html += "<li class='dd-item searched user" + $sel.attr("uid") + "' uid='" + $sel.attr("uid") + "'>";
        item_html += $sel.html();
        item_html += "</li>";
        $sel.removeClass("selected").addClass("added");
      });
      fn.$notificationUsers.append(item_html);
      fn.$notificationUsers.off('click', '.dd-item', fn.selectUser);
      fn.$notificationUsers.on('click', '.dd-item', fn.selectUser);
    },

    addAllUser: function () {
      var item_html = '';
      fn.$searchUsers.find(".searched").each(function(){
        var $sel = $(this);
        item_html += "<li class='dd-item searched user" + $sel.attr("uid") + "' uid='" + $sel.attr("uid") + "'>";
        item_html += $sel.html();
        item_html += "</li>";
        $sel.removeClass("selected").addClass("added");
      });
      fn.$notificationUsers.append(item_html);
      fn.$notificationUsers.off('click', '.dd-item', fn.selectUser);
      fn.$notificationUsers.on('click', '.dd-item', fn.selectUser);
    },

    removeUser: function () {
      var item_html = '';
      fn.$notificationUsers.find(".selected").each(function(){
        var $sel = $(this);
        var search_class = ".user" + $sel.attr("uid");
        fn.$searchUsers.find(search_class).removeClass("added");
        $sel.remove();
      });
    },

    removeAllUser: function () {
      var item_html = '';
      fn.$notificationUsers.find(".searched").each(function(){
        var $sel = $(this);
        var search_class = ".user" + $sel.attr("uid");
        fn.$searchUsers.find(search_class).removeClass("added");
        $sel.remove();
      });
    },

    init: function () {
      fn.$searchUsers = $(".search-users");
      fn.$notificationUsers = $(".notification-users");
      fn.$addUserBtn = $(".btn-add");
      fn.$addAllBtn = $(".btn-add-all");
      fn.$removeUserBtn = $(".btn-remove");
      fn.$removeAllBtn = $(".btn-remove-all");
      fn.$username = $("#username");
      fn.$email = $("#email");
      fn.$userType = $("#user_type");
      fn.$status = $("#status");
      fn.$notification = $(".notifications .notification");
      fn.$notificationObj = $("#notification_name");
      fn.$sendBtn = $(".btn-send");
      fn.$cronBtn = $(".btn-cron");
      $(".noti-datetime").datetimepicker({
        format: 'YYYY-MM-DD HH:mm',
      });


      //Add event handlers
      fn.$searchUsers.on('click', '.dd-item', fn.selectUser);
      fn.$notificationUsers.on('click', '.dd-item', fn.selectUser);
      fn.$addUserBtn.on('click', fn.addUser);
      fn.$addAllBtn.on('click', fn.addAllUser);
      fn.$removeUserBtn.on('click', fn.removeUser);
      fn.$removeAllBtn.on('click', fn.removeAllUser);
      fn.$notificationObj.on('keyup', fn.searchNotification);
      fn.$notification.on('click', fn.getNotification);
      fn.$username.on('keyup', fn.search);
      fn.$email.on('keyup', fn.search);
      fn.$userType.on('click', fn.search);
      fn.$status.on('click', fn.search);
      fn.$sendBtn.on('click', fn.sendConfirm);
      fn.$cronBtn.on('click', fn.sendConfirm);

    }
  };

  return fn;
});