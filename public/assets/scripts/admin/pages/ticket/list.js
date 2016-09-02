/**
 * list.js - Admin / Search Tickets
 *
 * This page supports modification of Ticket
 */

define(['notify', 'moment', 'bootbox', 'form', 'daterangepicker'], function (nt, moment, Bootbox) {

	var fn = {

    TICKET_STATUS: {
      OPEN: 0,
      ASSIGNED: 1,
      SOLVED: 2,
      CLOSED: 3
    },

    search: {

      initDateRanger: function() {
        $('#date_range').daterangepicker({
            opens: 'left',
            format: 'MM/DD/YYYY',
            ranges: {
              'Today': [moment(), moment()],
              'Last 7 days': [moment().subtract(6, 'days'), moment()],
              'Month to date': [moment().startOf('month'), moment()],
              'Year to date': [moment().startOf('year'), moment()],
              'The previous month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },            
            separator: ' to ',
            startDate: moment().subtract(29, 'days'),
            endDate: moment(),
            minDate: config.minDate,
            maxDate: moment()
          },

          function (start, end) {
            var s = start.format('MMM D, YYYY'); 
            var e = end.format('MMM D, YYYY'); 
            var str;

            if (s == e) {
              str = s;
            } else {
              str = s + " - " + e;
            }

            $('#date_range input').val(str);

            $("#frm_search").find("input[name=date_range]").val(str).end()
              .find('input[name=page]').val(1).end()
              .submit();
          }
        );   
      },

      onPagination: function() {
        $("ul.pagination").on("click", "a", function() {
          var $li = $(this).parent();
          if ($li.hasClass("disabled")) {
            return;
          }

          var page = $(this).text();

          $("#frm_search").find('input[name=page]').val(page).end().submit();

          return false;
        });
      },

      init: function() {
        this.initDateRanger();
        this.onPagination();
      }
    },

    list: {
      /* Given JSON data, update jQuery data of each Ticket row */
      renderItem: function(json) {  
        if (typeof json == "undefined") {
          console.error("fn.list.renderItem(): Invalid JSON data.");
          return false;
        }

        var data = json.data;

        var $row = $(".ticket-list li.item-" + data.id);
        if ($row.length == 0) {
          return false;
        }

        $row.data({
          "status": data.status,
          "adminId": data.admin_id,
          "type": data.type,
          "priority": data.priority
        });

        // Fix ticket class to new strType
        var c = $row.attr("class");
        c = c.replace(/\stype-[a-z]+/g, '');
        c += " type-" + data.strType.toLowerCase();
        $row.attr("class", c);

        $row.find("span.t-type").html(data.strType);

        return true;
      },

      // Adds a new comment to message thread of a ticket
      addComment: function(tc_info) {
        if ( !tc_info ) {
          return false;
        }

        var t_id = tc_info.t_id;
        var $collapse_toggler = $("#collapse_toggler" + t_id);
        if ( !$collapse_toggler.is(":visible") ) {
          $collapse_toggler.show();
        }

        var $thread = $("#thread_" + t_id);
        var $ul = $thread.find("ul.t-comments");
        $ul.append(tc_info.html);

        $ul.parent().animate({
          "scrollTop": $ul.height()
        }, 600);
      },

      // Update a comment
      updateComment: function(tc_info) {
        if ( !tc_info ) {
          return false;
        }

        var tc_id = tc_info.id;
        if ( !tc_id ) {
          return false;
        }

        var comment = nl2br(tc_info.comment);

        $(".t-comment-" + tc_id).find(".message").html(comment);
      },

      // Remove a comment and collapses the ticket thread if no comment is left
      removeComment: function(tc_id) {
        var $tc = $(".t-comment-" + tc_id);
        if ($tc.length == 0) {
          return false;
        }

        var leftComments = $tc.siblings().length;
        $tc.slideUp(function() {
          $tc.remove();
        });

        if (leftComments == 0) {
          var $t = $tc.closest("li.item");
          $t.removeClass("expanded")
            .find(".collapse-toggler").hide().end()
            .find(".thread").slideUp();
        }
      },

      expandTicket: function(t_id) {
        var $ticket = $(".ticket-list .item-" + t_id);
        var $thread = $ticket.find(".thread");

        $ticket.addClass("expanded");

        if ( !$thread.is(":visible") ) {
          $thread.slideDown();
        }
      },

      onListEvents: function() {
        // Show / Hide Detail
        $(".page-body").on("click", ".collapse-toggler", function() {
          var $row = $(this).closest(".item");
          $row.toggleClass("expanded");
          $row.find(".thread").slideToggle();
        });

        // Remove a comment
        $("ul.t-comments").on("click", "span.close", function() {
          nt.clear();
          
          var tc_id = $(this).data("id");
          var t_id = $(this).closest("li.item").data("id");

          var question = trans.remove_comment;

          Bootbox.dialog({
            title: '',
            message: question,
            buttons: {
              no: {
                label: trans.btn_cancel,
                className: 'btn-default btn-sm'
              },
              
              yes: {
                label: trans.btn_delete,
                className: "btn-danger btn-sm",
                callback: function(result) {
                  if ( !result ) {
                    return false;
                  }

                  $.post("/admin/ticket/ajax", {
                    cmd: "removeComment",
                    t_id: t_id,
                    tc_id: tc_id
                  }, function(json) {
                    if (!json.success) {
                      nt.error(json.msg);
                      return false;
                    }

                    nt.success(json.msg);

                    fn.list.removeComment(tc_id);
                  });
                }
              }
            }
          });

        });

      },

      init: function() {
        this.onListEvents();

        // At page loading, expand first comment if exists
        setTimeout(function() {
          $("ul.ticket-list .collapse-toggler:visible").first().trigger("click");
        }, 300);
      }
    },

    modal: {

      $modalEdit: null,
      $modalReply: null,
      $modalEditComment: null,

      toggleAssignee: function(v) {
        if (v == fn.TICKET_STATUS.OPEN) {
          $(".row-assignee", fn.modal.$modalEdit).hide();
        } else {
          $(".row-assignee", fn.modal.$modalEdit).show();
        }
      },

      onEdit: function() {

        // Edit Ticket modal / Submit
        this.$modalEdit.on("show.bs.modal", function(e) {
          nt.clear();
          
          // Set form data
          var $btn = $(e.relatedTarget);
          var $row = $btn.closest("li.item");
          var type = $row.data("type");
          var priority = $row.data("priority");
          var status = $row.data("status");

          fn.modal.$modalEdit.data({
            id: $row.data("id")
          });

          fn.modal.$modalEdit
            .find("#sel_type").val(type).end()
            .find("#sel_priority").val(priority).end()
            .find("#sel_status").val(status);

          // Show or Hide "Assignee" field
          fn.modal.toggleAssignee(status);

        }).on("click", ".btn-save", function() {
          fn.modal.$modalEdit.modal("hide");

          var id = fn.modal.$modalEdit.data("id");
          var type = fn.modal.$modalEdit.find("#sel_type").val();
          var priority = fn.modal.$modalEdit.find("#sel_priority").val();
          var status = fn.modal.$modalEdit.find("#sel_status").val();

          $.post('/admin/ticket/ajax', {
            cmd: "updateTicket",
            t_id: id,
            t_type: type,
            t_priority: priority,
            t_status: status
          }, function(json) {
            if (!json.success) {
              nt.error(json.msg);
              return false;
            }

            nt.success(json.msg);

            /* Update jQuery data of the current item */
            fn.list.renderItem(json);
          });

        }).on("change", "#sel_status", function() {
          var v = $(this).val();
          fn.modal.toggleAssignee(v);

        });
      },

      onReply: function() {
        this.$modalReply.on("show.bs.modal", function(e) {

          // Clear Form
          $("#frm_reply_ticket")[0].reset();

          var $btn = $(e.relatedTarget);
          var $row = $btn.closest("li.item");
          var t_id = $row.data("id");
          var t_subject = $row.find(".t-subject").html();
          
          fn.modal.$modalReply.data({
            id: t_id
          }).find("input[name=t_id]").val(t_id).end()
          .find(".t-subject").html(t_subject);

        }).on("submit", "#frm_reply_ticket", function() {
          // Ticket ID
          var id = fn.modal.$modalReply.data("id");

          // Reply message
          var msg = $("textarea.t-msg", fn.modal.$modalReply).val();

          if (msg.length == 0) {
            $("textarea.t-msg").focus();
            return false;
          }

          fn.modal.$modalReply.modal("hide");

          $(this).ajaxSubmit({
            success: function(json) {
              if (!json.success) {
                nt.error(json.msg);
                return false;
              }

              nt.success(json.msg);

              /* Auto-expand the ticket comments thread */
              fn.list.expandTicket(json.tc_info.t_id);


              /* Add replied message to message thread in the main list. */
              fn.list.addComment(json.tc_info);
            },

            error: function(xhr) {
              console.log(xhr);
              if (xhr.status == 500) {
                nt.error("500 - Internal Server error");
              }
            },

            dataType: 'json'
          });

          return false;
        });
      },

      onEditComment: function() {
        this.$modalEditComment.on("show.bs.modal", function(e) {
          // Load by ajax
          nt.clear();

          $("#frm_edit_comment")[0].reset();
          
          // Set form data
          var $btn = $(e.relatedTarget);
          var tc_id = $btn.data("id");

          fn.modal.$modalEditComment.data({
            tc_id: tc_id
          });

          $.post('/admin/ticket/ajax', {
            cmd: "getComment",
            tc_id: tc_id
          }, function(json) {
            if ( !json.success ) {
              nt.error(json.msg);
              return false;
            }

            if ( !json.hasOwnProperty("tc_info") ) {
              nt.error("Invalid data structure returned.");
              return false;
            }

            $("#txt_ticket_comment", fn.modal.$modalEditComment).val(json.tc_info.comment);
          }).fail(function(xhr) {
            console.log(xhr);
          });

        }).on("submit", "#frm_edit_comment", function() {
          // Ajax submit
          var tc_id = fn.modal.$modalEditComment.data("tc_id");

          $(this).ajaxSubmit({
            data: {
              tc_id: tc_id
            },
            dataType: 'json',            
            success: function(json) {
              if (!json.success) {
                nt.error(json.msg);
                return false;
              }

              nt.success(json.msg);

              /* Update comment */
              fn.list.updateComment(json.tc_info);

              fn.modal.$modalEditComment.modal("hide");
            },

            error: function(xhr) {
              console.log(xhr);
            }
          });

          return false;
        });
      },

      init: function() {
        this.$modalEdit = $("#modalEditTicket");
        this.$modalReply = $("#modalReplyTicket");
        this.$modalEditComment = $("#modalEditComment");

        this.onEdit();
        this.onReply();
        this.onEditComment();
      } /* fn.modal.init() */

    },

		init: function () {
      this.search.init();
      this.list.init();
      this.modal.init();
		}
	};

	return fn;
});