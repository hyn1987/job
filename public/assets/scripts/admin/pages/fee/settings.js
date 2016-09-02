/**
 * settings.js - Admin / Settings / Categories
 */

define(['plugin', 'bootbox', 'growl', 'ion-rangeslider'], function (plugin, bootbox) {

  var fn = {
    $fees: null,
    $saveBtn: null,
    $addBtn: null,
    
    feeTemplate: '<div class="form-group fee-item"><div class="col-md-9"><input type="text" name="range[]" value=""/></div><div class="col-md-3"><input type="number" name="fee[]" /></div></div>', 
    maxMoney: 10000, 

    addFee: function(fee) {
      
    }, 

    init: function () {
      fn.$fees = $('.fees');
      fn.$addBtn = $('.btn-add');
      fn.$saveBtn = $('.btn-save');

      //var fees = JSON.parse(fn.$fees.attr('data-fees'));

      // var fees = fn.$fees.data('fees').split(":");
      // for (var i=0; i<fees.length; i++) {
      //   fn.addFee(fees[i]);
      // }

      // $(".fee-item").each(function() {
      //   $(this).ionRangeSlider({
      //       min: 0,
      //       max: fn.maxMoney,
      //       from: 1000,
      //       to: 4000,
      //       type: 'double',
      //       step: 100,
      //       prefix: "$",
      //       maxPostfix: "+", 
      //       prettify: false,
      //       hasGrid: true, 
      //       onChange: function(obj){
      //         $("#range_2").ionRangeSlider("update", {
      //           from: obj['toNumber'],
      //         });
      //         console.log($("#range_2"));
      //       }
      //   });
      // });
    }
  };

  return fn;
});