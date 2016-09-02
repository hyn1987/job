/**
 * config.js
 * This script will support the config for app.js.
 */

var require = {
  baseUrl: siteUrl + '/assets',
  urlArgs: 'v=1.0.0.0',
  paths: {
    // Components.
    'moment': 'plugins/moment.min', //used
    'jquery': 'plugins/jquery/dist/jquery', //used
    'jquery-ui': 'plugins/jquery-ui/jquery-ui-1.10.3.custom.min', //used
    'defines': 'scripts/helper/defines',  // global utility functions
    'blockui': 'plugins/jquery.blockui.min', //used
    'bootstrap': 'plugins/bootstrap/dist/js/bootstrap.min', //used
    'bootstrapselect' : 'plugins/bootstrap-select/bootstrap-select.min',  
    'cookie': 'plugins/jquery.cookie/jquery.cookie',
    'datetimepicker': 'plugins/bootstrap3-datetimepicker/js/bootstrap-datetimepicker.min', //used
    'datepicker': 'plugins/bootstrap-datepicker/js/bootstrap-datepicker', //used
    'daterangepicker': 'plugins/bootstrap-daterangepicker/daterangepicker', //used
    'timepicker': 'plugins/bootstrap-timepicker/js/bootstrap-timepicker.min', //used
    'footable': 'plugins/footable/dist/footable.all.min',
    'form':  'plugins/jquery.form/jquery.form',
    'inputmask': 'plugins/jquery-inputmask/jquery.inputmask.bundle.min',
    'select2': 'plugins/select2/dist/js/select2.full.min',
    'nestable': 'plugins/jquery-nestable/jquery.nestable', //used
    'bootbox': 'plugins/bootbox/bootbox', //used
    'growl': 'plugins/bootstrap-growl/jquery.bootstrap-growl', //used
    'common': 'scripts/admin/helper/common', //used
    'plugin': 'scripts/admin/helper/plugin', //used
    'notify': 'scripts/admin/helper/notify',
    'uniform': 'plugins/jquery.uniform/jquery.uniform.min',
    'jqueryslimscroll': 'plugins/jquery-slimscroll/jquery.slimscroll.min',
    'bootstrap-hover-dropdown': 'plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min',
    'easypiechart': 'plugins/jquery-easypiechart/jquery.easypiechart.min',
    'sparkline': 'plugins/jquery.sparkline.min',
    'ion-rangeslider': 'plugins/ion.rangeslider/js/ion-rangeSlider/ion.rangeSlider.min', 

    'vmap': 'plugins/jqvmap/jqvmap/jquery.vmap',
    'vmap-world': 'plugins/jqvmap/jqvmap/maps/jquery.vmap.world',
    'vmap-europe': 'plugins/jqvmap/jqvmap/maps/jquery.vmap.europe',
    'vmap-russia': 'plugins/jqvmap/jqvmap/maps/jquery.vmap.russia',
    'vmap-germany': 'plugins/jqvmap/jqvmap/maps/jquery.vmap.germany',
    'vmap-usa': 'plugins/jqvmap/jqvmap/maps/jquery.vmap.usa',

    'wjbuyer': 'scripts/helper/buyer/buyer',
  },

  shim: {
    'bootstrap': {'deps': ['jquery']},
    'cookie': {'deps': ['jquery']},
    'defines': {'deps': ['jquery']},
    'footable': {'deps': ['jquery']},
    'form': {'deps': ['jquery']},
    'select2': {'deps': ['jquery']},
    'datepicker': {'deps': ['bootstrap', 'moment']},
    'datetimepicker': {'deps': ['jquery', 'moment']},
    'daterangepicker': {'deps': ['bootstrap', 'moment']},
    'inputmask': {'deps': ['jquery']},
    'jqueryslimscroll': {'deps': ['jquery']},
    'timepicker': {'deps': ['bootstrap', 'moment']},
    
    'vmap': {'deps': ['jquery']},
    'vmap-world': {'deps': ['vmap']},
    'vmap-europe': {'deps': ['vmap']},
    'vmap-russia': {'deps': ['vmap']},
    'vmap-germany': {'deps': ['vmap']},
    'vmap-usa': {'deps': ['vmap']},

    'wjbuyer': {'deps': ['bootbox']},
    'uniform': {'deps': ['jquery']},

  },
};

var config = {
  noScriptPages: ['admin/dashboard', 'admin/userlist'],
  minDate: '01/01/2012'
};