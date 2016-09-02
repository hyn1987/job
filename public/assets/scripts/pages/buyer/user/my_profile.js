/**
 * my_pofile.js
 */


define(['jquery', 'jqueryui', 'jqueryvalidation'], function () {

  var fn = {
    $form: null,
    $remember: null,

    init: function () {

      var $form = $('#MyProfileForm');
      var $_rateInbox = $('#Profile_Rate');

      FormValidation.init();

      // edit / cancel button action
      $(".right-action-link a").click(function() {
        if ($(this).hasClass('cancel')) {
          $(this).removeClass('cancel');
          $(this).html('Edit');
          $form.removeClass('editable');
        } else {
          $(this).addClass('cancel');   
          $(this).html('Cancel');
          $form.addClass('editable');
        }
        return false;
      });

      // number only in rate input field
      $_rateInbox.bind('keydown', function(e){
        if ( ( e.keyCode >= 48 && e.keyCode <= 57 ) || ( e.keyCode >= 96 && e.keyCode <= 105 ) || e.keyCode == 110 || e.keyCode == 190 || e.keyCode == 46 || e.keyCode == 8 ){
          return true;
        }else{
           e.preventDefault();
        }
      });

      // select language action
      $('#LanguageSelect #addLanguage').click(function(){
        var $_lang_array = $('#LanguageSelect select#SelectLanguage').val();
        $('#Profile_Language').parent().find('label').remove();
        for ( i = 0; i < $_lang_array.length; i++){
          $('#Profile_Language').parent().append('<label class="divided" for="Language">' + $_lang_array[i] + '</label>');
        }
        $('#LanguageSelect').modal('hide');
      });

      // format fields when appear add/edit education history popup
      $('#EducationSelect').on('show.bs.modal', function (e) {
        $('#EducationSelect').find('.form-group').each(function(){
          $(this).closest('.form-group').removeClass('has-error');
          $(this).closest('.form-group').removeClass('has-success');
        });
      });

      // format fields when appear add/edit employee  history popup
      $('#EmployeeHistorySelect').on('show.bs.modal', function (e) {
        $('#EmployeeHistorySelect').find('.form-group').each(function(){
          $(this).closest('.form-group').removeClass('has-error');
          $(this).closest('.form-group').removeClass('has-success');
        });
      });      
      
    }
  };

  return fn;
});


var FormValidation = function () {
  // validation using icons
  var handleValidation = function() {

    var form = $('#MyProfileForm');
    var error = $('.alert-danger', form);
    var success = $('.alert-success', form);

    form.validate({
      errorElement: 'span', //default input error message container
      errorClass: 'help-block help-block-error', // default input error message class
      focusInvalid: false, // do not focus the last invalid input
      ignore: "",  // validate all fields including form hidden input
      rules: {
        pro_title: {
          required: true
        },
        pro_rate: {
          required: true,
          min: 0.01,
          max: 999.99
        },
        pro_description: {
          required: true
        },
        pro_education: {
          required: true
        }
      },

      invalidHandler: function (event, validator) { //display error alert on form submit
        error.show();                
      },

      errorPlacement: function (error, element) { // render error placement for each input type 
        var icon = $(element).parent('.input-icon').children('i');
        icon.removeClass('fa-check').addClass("fa-warning");
        icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
      },

      highlight: function (element) { // hightlight error inputs
        $(element)
          .closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
      },

      unhighlight: function (element) { // revert the change done by hightlight
        
      },

      success: function (label, element) {
        var icon = $(element).parent('.input-icon').children('i');
        $(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
        icon.removeClass("fa-warning").addClass("fa-check");
        
        var element_id = $(element).attr('id');
      },

      submitHandler: function (form) {
        if ( applyAllValues() ){
          form.submit();
        }        
      }
    });
  }
  return {
    //main function to initiate the module
    init: function () {
      handleValidation();
    }
  };
}();

function keyCodeToString(code){
  switch( code ) {
    case 48:
      return '0';
      break;
    case 49:
      return '1';
      break;
    case 50:
      return '2';
      break;
    case 51:
      return '3';
      break;
    case 52:
      return '4';
      break;
    case 53:
      return '5';
      break;
    case 54:
      return '6';
      break;
    case 55:
      return '7';
      break;
    case 56:
      return '8';
      break;
    case 57:
      return '9';
      break;
    case 190:
      return '.';
      break;
    default:
      return '';
      break;
  }
}
// remove education history function
function removeEducation(obj){
  var $_remove_num = parseInt($(obj).closest('.education-block').attr('block-number'));
  var $_block_len = $('.education-block').length;
  for ( $_i = 1; $_i <= $_block_len; $_i++ ){
    if ( $_remove_num < $_i ){
      $_reset_index = $_i - 1;
      $('.education-block[block-number=' + $_i + ']').attr('block-number', $_reset_index);
    }
  }
  $(obj).closest('.education-block').remove();
}

// edit education history function
function editEducation(obj){  
  editModalContent(obj, 'education-block', 'EducationSelect');
  var $_num = $(obj).closest('.education-block').attr('block-number');
  $('#EducationSelect .modal-footer .btn-primary').attr('onclick', 'editEducationHistory(' + $_num + ');');
  $('#EducationSelect').modal('show');
}

function editEducationHistory(number){
  if ( customValidation('EducationSelect') ){
    editContentBlock(number, 'education-block', 'EducationSelect');
  }
}

function editModalContent(obj, parent_cls, target_parent_id){
  $('#' + target_parent_id + ' .form-control[has-valuable-data="yes"]').each(function(){
    $(this).val($(obj).closest('.' + parent_cls).find('label[for="' + $(this).attr('id') + '"]').html());
  });
}

function editContentBlock(number, parent_cls, target_parent_id){
  $('.' + parent_cls + '[block-number=' + number + '] label').each(function(){
    $(this).html($('#' + target_parent_id + ' .form-control#' + $(this).attr('for')).val());
  });  
  $('#' + target_parent_id).modal('hide');
}

// add education history function
function addEducationHistory(){
  if ( customValidation('EducationSelect') ){
    addNewEducationBlock();
  }
}

function addEducation(obj){  
  $('#EducationSelect').find('#EduFromYear').val('1950');
  $('#EducationSelect').find('#EduFromMonth').val('01');
  $('#EducationSelect').find('#EduToYear').val('1950');
  $('#EducationSelect').find('#EduToMonth').val('01');
  $('#EducationSelect').find('#EduSchool').val('');
  $('#EducationSelect').find('#EduDegree').val('');
  $('#EducationSelect').find('#EduMajor').val('');
  $('#EducationSelect').find('#EduMinor').val('');
  $('#EducationSelect').find('#EduDesc').val('');
  $('#EducationSelect .modal-footer .btn-primary').attr('onclick', 'addEducationHistory();');
}

function addNewEducationBlock(){
  var $_fromYear  = $('#EducationSelect').find('#EduFromYear').val();
  var $_fromMonth = $('#EducationSelect').find('#EduFromMonth').val();
  var $_toYear    = $('#EducationSelect').find('#EduToYear').val();
  var $_toMonth   = $('#EducationSelect').find('#EduToMonth').val();
  var $_degree    = $('#EducationSelect').find('#EduDegree').val();
  var $_major     = $('#EducationSelect').find('#EduMajor').val();
  var $_minor     = $('#EducationSelect').find('#EduMinor').val();
  var $_school    = $('#EducationSelect').find('#EduSchool').val();
  var $_desc      = $('#EducationSelect').find('#EduDesc').val();

  var $_number = $('.education-block').length + 1;

  var $_html = '<div class="education-block" block-number="' + $_number + '"><label for="EduFromYear">' + $_fromYear + '</label>/<label for="EduFromMonth">' + $_fromMonth + '</label>~<label for="EduToYear">' + $_toYear + '</label>/<label for="EduToMonth">' + $_toMonth + '</label><br><label for="EduSchool">' + $_school + '</label><br><label for="EduDegree">' + $_degree + '</label><br><label for="EduMajor">' + $_major + '</label><br><label for="EduMinor">' + $_minor + '</label><br><label for="EduDesc">' + $_desc + '</label><br><div class="action-buttons"><a onclick="removeEducation(this);" href="javascript:void(0);" class="remove-button btn btn-primary action-btn">Remove</a><a onclick="editEducation(this);" href="javascript:void(0);" class="edit-button btn btn-primary action-btn">Edit</a></div><div class="clear-div"></div></div>';
  
  $('a[data-target="#EducationSelect"]').closest('.form-group').find('.input-icon').append($_html);
  $('#EducationSelect').modal('hide');
}

function customValidation(parent_id){
  $('#' + parent_id + ' .form-control[has-valuable-data="yes"]').each(function(){
    if ( !$(this).val() ){
      $(this).closest('.form-group').addClass('has-error');
      $(this).closest('.form-group').removeClass('has-success');
      $('#EducationSelect .alert.alert-danger').removeClass('display-hide');
    }else{
      $(this).closest('.form-group').addClass('has-success');
      $(this).closest('.form-group').removeClass('has-error');
    }
  });
  if ( $('#' + parent_id + ' .form-group.has-error').length > 0 ){
    return false;
  }else{
    return true;
  }
}

// remove employment history function
function removeEmployment(obj){
  var $_remove_num = parseInt($(obj).closest('.employee-history-block').attr('block-number'));
  var $_block_len = $('.employee-history-block').length;
  for ( $_i = 1; $_i <= $_block_len; $_i++ ){
    if ( $_remove_num < $_i ){
      $_reset_index = $_i - 1;
      $('.employee-history-block[block-number=' + $_i + ']').attr('block-number', $_reset_index);
    }
  }
  $(obj).closest('.employee-history-block').remove();
}

// edit education history function
function editEmployment(obj){  
  editModalContent(obj, 'employee-history-block', 'EmployeeHistorySelect');
  var $_num = $(obj).closest('.employee-history-block').attr('block-number');
  $('#EmployeeHistorySelect .modal-footer .btn-primary').attr('onclick', 'editEmploymentHistory(' + $_num + ');');
  $('#EmployeeHistorySelect').modal('show');
}

function editEmploymentHistory(number){
  if ( customValidation('EmployeeHistorySelect') ){
    editContentBlock(number, 'employee-history-block', 'EmployeeHistorySelect');
  }
}

// add education history function
function addEmployeementHistory(){
  if ( customValidation('EmployeeHistorySelect') ){
    addNewEmployeeBlock();
  }
}

function addEmployeeHistory(){
  $('#EmployeeHistorySelect').find('#EmpFromYear').val('1950');
  $('#EmployeeHistorySelect').find('#EmpFromMonth').val('01');
  $('#EmployeeHistorySelect').find('#EmpToYear').val('1950');
  $('#EmployeeHistorySelect').find('#EmpToMonth').val('01');
  $('#EmployeeHistorySelect').find('#EmpCompany').val('');
  $('#EmployeeHistorySelect').find('#EmpPosition').val('');
  $('#EmployeeHistorySelect').find('#EmpDesc').val('');
  $('#EmployeeHistorySelect .modal-footer .btn-primary').attr('onclick', 'addEmployeementHistory();');
}

function addNewEmployeeBlock(){
  var $_fromYear  = $('#EmployeeHistorySelect').find('#EmpFromYear').val();
  var $_fromMonth = $('#EmployeeHistorySelect').find('#EmpFromMonth').val();
  var $_toYear    = $('#EmployeeHistorySelect').find('#EmpToYear').val();
  var $_toMonth   = $('#EmployeeHistorySelect').find('#EmpToMonth').val();
  var $_company   = $('#EmployeeHistorySelect').find('#EmpCompany').val();
  var $_position  = $('#EmployeeHistorySelect').find('#EmpPosition').val();
  var $_desc      = $('#EmployeeHistorySelect').find('#EmpDesc').val();

  var $_number = $('.employee-history-block').length + 1;

  var $_html = '<div class="employee-history-block" block-number="' + $_number + '"><label for="EmpFromYear">' + $_fromYear + '</label>/<label for="EmpFromMonth">' + $_fromMonth + '</label>~<label for="EmpToYear">' + $_toYear + '</label>/<label for="EmpToMonth">' + $_toMonth + '</label><br><label for="EmpCompany">' + $_company + '</label><br><label for="EmpPosition">' + $_position + '</label><br><label for="EmpDesc">' + $_desc + '</label><br><div class="action-buttons"><a onclick="removeEmployment(this);" href="javascript:void(0);" class="remove-button btn btn-primary action-btn">Remove</a><a onclick="editEmployment(this);" href="javascript:void(0);" class="edit-button btn btn-primary action-btn">Edit</a></div><div class="clear-div"></div></div>';
  
  $('a[data-target="#EmployeeHistorySelect"]').closest('.form-group').find('.input-icon').append($_html);
  $('#EmployeeHistorySelect').modal('hide');
}

function applyAllValues(){  
  // apply language values
  var $_language    = new Array();
  var $_index   = 0;
  $('label[for="Language"]').each(function(){
    $_language[$_index]   = $(this).html();
    $_index++;
  });
  $('#Profile_Language').val(JSON.stringify($_language));
  // apply education history values
  var $_from    = new Array();
  var $_to      = new Array();
  var $_school  = new Array();
  var $_degree  = new Array();
  var $_major   = new Array();
  var $_minor   = new Array();
  var $_desc    = new Array();
  var $_index   = 0;
  $('.education-block').each(function(){    
    $_from[$_index]    = $(this).find('label[for="EduFromYear"]').html() + '/' + $(this).find('label[for="EduFromMonth"]').html();
    $_to[$_index]      = $(this).find('label[for="EduToYear"]').html() + '/' + $(this).find('label[for="EduToMonth"]').html();
    $_school[$_index]  = $(this).find('label[for="EduSchool"]').html();
    $_degree[$_index]  = $(this).find('label[for="EduDegree"]').html();
    $_major[$_index]   = $(this).find('label[for="EduMajor"]').html();
    $_minor[$_index]   = $(this).find('label[for="EduMinor"]').html();
    $_desc[$_index]    = $(this).find('label[for="EduDesc"]').html();
    $_index++;
  });
  $('#Profile_Edu_From').val(JSON.stringify($_from));
  $('#Profile_Edu_To').val(JSON.stringify($_to));
  $('#Profile_Edu_School').val(JSON.stringify($_school));
  $('#Profile_Edu_Degree').val(JSON.stringify($_degree));
  $('#Profile_Edu_Major').val(JSON.stringify($_major));
  $('#Profile_Edu_Minor').val(JSON.stringify($_minor));
  $('#Profile_Edu_Desc').val(JSON.stringify($_desc));
  // apply employee history values
  var $_from      = new Array();
  var $_to        = new Array();
  var $_company   = new Array();
  var $_position  = new Array();
  var $_desc      = new Array();
  var $_index     = 0;
  $('.employee-history-block').each(function(){    
    $_from[$_index]       = $(this).find('label[for="EmpFromYear"]').html() + '/' + $(this).find('label[for="EmpFromMonth"]').html();
    $_to[$_index]         = $(this).find('label[for="EmpToYear"]').html() + '/' + $(this).find('label[for="EmpToMonth"]').html();
    $_company[$_index]    = $(this).find('label[for="EmpCompany"]').html();
    $_position[$_index]   = $(this).find('label[for="EmpPosition"]').html();
    $_desc[$_index]       = $(this).find('label[for="EmpDesc"]').html();
    $_index++;
  });
  $('#Profile_Emp_From').val(JSON.stringify($_from));
  $('#Profile_Emp_To').val(JSON.stringify($_to));
  $('#Profile_Emp_Company').val(JSON.stringify($_company));
  $('#Profile_Emp_Position').val(JSON.stringify($_position));
  $('#Profile_Emp_Desc').val(JSON.stringify($_desc));
  return true;
}