define([
    'Magento_Checkout/js/view/shipping',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/shipping-rates-validator'
], function (ShippingView, quote, shippingRatesValidator) {
    'use strict';

    var mixin = {
        defaults: {
            template: 'Ecommerce121_NonEditableCustomerAddress/shipping',
            isPermission: false,
            isQuoteAddressLocked: false,
            isQuoteAddressDeleted: false,
            hasQuoteShippingAddress: false
        }
    };
    return function (target) {
        return target.extend(mixin);
    };
});
