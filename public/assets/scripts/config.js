/**
 * config.js
 * This script will support the config for app.js.
 */

var require = {
  baseUrl: siteUrl + '/assets',
  urlArgs: 'v=1.0.0.0',
  paths: {
    // Components.
    'bootbox': 'plugins/bootbox/bootbox',
    'bootstrap': 'plugins/bootstrap/dist/js/bootstrap.min',
    'bootstraphoverdropdown' : 'plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min', 
    'bootstrapswitch' : 'plugins/bootstrap-switch/js/bootstrap-switch.min',
    'bootstrapselect' : 'plugins/bootstrap-select/bootstrap-select.min',  
    'cookie': 'plugins/jquery.cookie/jquery.cookie',
    'datepicker': 'plugins/bootstrap-datepicker/js/bootstrap-datepicker', //used
    'datetimepicker': 'plugins/bootstrap3-datetimepicker/js/bootstrap-datetimepicker.min',
    'daterangepicker': 'plugins/bootstrap-daterangepicker/daterangepicker', //used
    'defines': 'scripts/helper/defines',
    'footable': 'plugins/footable/dist/footable.all.min',
    'form':  'plugins/jquery.form/jquery.form',
    'growl': 'plugins/bootstrap-growl/jquery.bootstrap-growl',
    'inputmask': 'plugins/jquery.inputmask.bundle.min',
    'jquery': 'plugins/jquery/dist/jquery.min',
    'jquerymigrate' : 'plugins/jquery-migrate-1.2.1.min', 
    'jqueryui' : 'plugins/jquery-ui/jquery-ui-1.10.3.custom.min',
    'jqueryvalidation': 'plugins/jquery.validation/dist/jquery.validate.min',
    'jqueryslimscroll' : 'plugins/jquery-slimscroll/jquery.slimscroll.min', 
    'jqueryblockui' : 'plugins/jquery.blockui.min', 
    'jquerycokie' : 'plugins/jquery.cokie.min', 
    'jquerypulsate': 'plugins/jquery.pulsate.min', 
    'moment': 'plugins/moment.min',
    'select2': 'plugins/select2/dist/js/select2.full',
    'uniform': 'plugins/jquery.uniform/jquery.uniform.min',
    'jqueryrating': 'plugins/star-rating/jquery.rating',
    'jqueryratingpack': 'plugins/star-rating/jquery.rating.pack',    
    'jquerymetadata': 'plugins/star-rating/jquery.MetaData',
    'jcrop': 'plugins/jcrop/js/jquery.Jcrop.min',
    'common': 'scripts/helper/common',
    'ajax': 'scripts/helper/ajax',
    'plugin': 'scripts/helper/plugin',
    'notify': 'scripts/helper/notify',
    'tooltip': 'plugins/bootstrap/js/tooltip',

    'wjbuyer': 'scripts/helper/buyer/buyer',
  },
  
  shim: {
    'bootstrap': {'deps': ['jquery']},
    'defines': {'deps': ['jquery']},
    'select2': {'deps': ['jquery']},
    'uniform': {'deps': ['jquery']},
    'footable': {'deps': ['jquery']},
    'bootbox' : {'deps': ['jquery', 'bootstrap']}, 
    'wjbuyer': {'deps': ['bootbox']},
    'form': {'deps': ['jquery']},
    'timepicker': {'deps': ['bootstrap', 'moment']},
  },
};

var config = {
  noScriptPages: [
    'auth/signup',
    'freelancer/job/job_detail',
    'freelancer/contract/my_all_jobs'
],
  ajaxServiceUrl: 'client/v2/ajax.php',
};