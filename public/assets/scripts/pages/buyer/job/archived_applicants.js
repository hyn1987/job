/**
 * job/create.js
 */

define(['wjbuyer'], function (buyer) {

  var fn = {
    $form: null,
    $remember: null,

    init: function () {
      buyer.initTopLinkHandler();
    }
  };

  return fn;
});