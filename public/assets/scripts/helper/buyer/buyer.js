define(['bootbox'], function (bootbox) {
  var fn = {
    initTopLinkHandler: function() {
      $('.job-top-links .action-change-public a').bind('click', fn.job_change_public);
      $('.job-top-links .action-change-status a').bind('click', fn.job_change_status);
    }, 

    initApplicationLinkHandler: function() {
      $('a.decline-link').bind('click', fn.application_change_status);
    }, 

    job_change_public: function(e, obj) {
      var $_this = $(this);
      if (obj) {
        $_this = obj;
      }

      var _url = $_this.data('url');
      var _status = $_this.data('status');
      console.log(trans);
      var _msg = "<div class='buyer-job-confirm-message'>" + t(trans.change_public, {'status':trans.status[_status]}) + "</div>";
      bootbox.dialog({
          title: '',
          message: _msg,
          buttons: {
              ok: {
                  label: trans.btn_ok,
                  className: 'btn-primary',
                  callback: function() {
                      $.ajax({
                        url:   _url,
                        type:   'POST',
                        data:{},
                        beforeSend: function(jqXHR, settings) {},
                        error: function() {},
                        success: function(json) {
                          if (json.status == 'success') {

                          } else {

                          }        
                        },   // END OF SUCESS FUNCTION
                        complete: function (jqXHR, textStatus) {
                          window.location.href = window.location.href;
                        }
                      });
                  }
              },
              cancel: {
                  label: trans.btn_cancel,
                  className: 'btn-default',
                  callback: function() {
                    if ($_this.hasClass('job-action-link')) {
                      var $_v = $_this.closest('.job-info').find('select.job-action-control').data('selectpicker');
                      $_v.val("");
                    }
                  }
              },
          },
      });

      return false;
    }, 

    job_change_status: function(e, obj) {
      var $_this = $(this);
      if (obj) {
        $_this = obj;
      }

      var _url = $_this.data('url');
      var _msg = "";

      if ($_this.hasClass('status-closed')) {
        _msg = "<div class='buyer-job-confirm-message'>"+trans.close_job+"</div>";
      }

      bootbox.dialog({
          title: '',
          message: _msg,
          buttons: {
              ok: {
                  label: trans.btn_ok,
                  className: 'btn-primary',
                  callback: function() {
                      $.ajax({
                        url:   _url,
                        type:   'POST',
                        data:{},
                        beforeSend: function(jqXHR, settings) {},
                        error: function() {},
                        success: function(json) {
                          if (json.status == 'success') {
                          } else {
                          }        
                        },   // END OF SUCESS FUNCTION
                        complete: function (jqXHR, textStatus) {
                          window.location.href = window.location.href;
                        }
                      });
                  }
              },
              cancel: {
                  label: trans.btn_cancel,
                  className: 'btn-default',
                  callback: function() {
                    if ($_this.hasClass('job-action-link')) {
                      var $_v = $_this.closest('.job-info').find('select.job-action-control').data('selectpicker');
                      $_v.val("");
                    }
                  }
              },
          },
      });

      return false;
    }, 

    application_change_status: function(e, obj) {
      var $_this = $(this);
      if (obj) {
        $_this = obj;
      }
      var _url = $_this.attr('href');
      var _msg = "";
      if ($_this.hasClass('status-client-declined')) {
        _msg = "<div class='buyer-job-confirm-message'>" + trans.app_declined +"</div>";
      }

      bootbox.dialog({
          title: '',
          message: _msg,
          buttons: {
              ok: {
                  label: trans.btn_ok,
                  className: 'btn-primary',
                  callback: function() {
                      $.ajax({
                        url:   _url,
                        type:   'POST',
                        data:{},
                        beforeSend: function(jqXHR, settings) {},
                        error: function() {},
                        success: function(json) {
                          if (json.status == 'success') {
                          } else {
                          }        
                        },   // END OF SUCESS FUNCTION
                        complete: function (jqXHR, textStatus) {
                          window.location.href = window.location.href;
                        }
                      });
                  }
              },
              cancel: {
                  label: trans.btn_cancel,
                  className: 'btn-default',
                  callback: function() {

                  }
              },
          },
      });

      return false;
    }

  };

  return fn;
}); 