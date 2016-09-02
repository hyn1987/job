/**
 * my_info.js
 */

define(['jquery', 'jqueryui', 'jqueryvalidation', 'jcrop', 'form', 'bootstrapselect'], function () {

  var fn = {
    $form: null,
    $remember: null,
    $jcropCont: null,
    $imageInfo: null, 

    init: function () {
      this.$form = $('#form_user_my_info');
      this.$form.validate();

      $("#language").selectpicker();

      $(".right-action-link a.edit-action").css('display', 'block');

      $(".right-action-link a.cancel-action").bind('click', function() {
        $(this).removeClass('cancel');
        $(".right-action-link a.edit-action").css('display', 'block');
        $(".right-action-link a.cancel-action").css('display', 'none');
        $('#form_user_my_info').removeClass('editable');
        $('#temp-avatar').html('');
      });

      $(".right-action-link a.edit-action").bind('click', function() {
        $(".right-action-link a.edit-action").css('display', 'none');
        $(".right-action-link a.cancel-action").css('display', 'block');
        $('#form_user_my_info').addClass('editable');
        $('#temp-avatar').html('');
        
        return false;
      });

      //onchange event-handler
      $('#avatar').on('change', function () {
        
         $('#form_user_my_info').ajaxSubmit({
          success: function(json) {
            if (!json.success) {
              nt.error(json.msg);
              return false;
            }

            //show message detail result
            var src = '<img src="' + json.imgUrl + '" id="tempImage" width="100%" height="100%"/>';
            $('#temp-avatar').html(src);
            $imageInfo = json.imageInfo;

            $('#tempImage').Jcrop({
              bgFade:     true,
              bgOpacity: .2,
              setSelect: [ 210, 150, 420, 360 ],
              aspectRatio: 1,
              onchange:   fn.setCoords,
              onSelect:   fn.setCoords,
              onRelease:  fn.clearCoords,
            },function(){
              $jcropCont = this;
            });
          },

          error: function(xhr) {
            console.log(xhr);
          },

          dataType: 'json',
        });
      });
      //ajax file-upload      
    },

    setCoords: function (c) {
      var xRatio = $imageInfo['width']/$('#temp-avatar img').width();
      var yRatio = $imageInfo['height']/$('#temp-avatar img').height();

      $('#x1').val(Math.round(c.x * xRatio));
      $('#y1').val(Math.round(c.y * yRatio));
      //$('#x2').val(c.x2);
      //$('#y2').val(c.y2);
      $('#w').val( Math.round(c.w * xRatio));
      $('#h').val( Math.round(c.h * yRatio));
    },

    clearCoords: function (c) {
      $('#x1').val('');
      $('#y1').val('');
      //$('#x2').val(c.x2);
      //$('#y2').val(c.y2);
      $('#w').val('');
      $('#h').val('');
    },
  };

  return fn;
});