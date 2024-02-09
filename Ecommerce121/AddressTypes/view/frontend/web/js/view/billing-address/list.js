define([
    'uiComponent',
    'Magento_Customer/js/model/address-list',
    'mage/translate',
    'Magento_Customer/js/model/customer'
], function (Component, addressList, $t, customer) {
    'use strict';

    var newAddressOption = {
            /**
             * Get new address label
             * @returns {String}
             */
            getAddressInline: function () {
                return $t('New Address');
            },
            customerAddressId: null
        },

        addressOptions = addressList().filter(address => address.getType() === 'customer-address'
            && address.customAttributes.some(attr =>
                attr.attribute_code === 'address_type'
                && attr.value === 'billing')),

        addressDefaultIndex = addressOptions.findIndex(function (address) {
            return address.isDefaultBilling();
        });

    return Component.extend({
        defaults: {
            template: 'Magento_Checkout/billing-address',
            selectedAddress: null,
            isNewAddressSelected: false,
            addressOptions: addressOptions,
            exports: {
                selectedAddress: '${ $.parentName }:selectedAddress'
            }
        },

        /**
         * @returns {Object} Chainable.
         */
        initConfig: function () {
            this._super();
            // disable the "New Address" option
            // this.addressOptions.push(newAddressOption);

            return this;
        },

        /**
         * @return {exports.initObservable}
         */
        initObservable: function () {
            this._super()
                .observe('selectedAddress isNewAddressSelected')
                .observe({
                    isNewAddressSelected: !customer.isLoggedIn() || !addressOptions.length,
                    selectedAddress: this.addressOptions[addressDefaultIndex]
                });

            return this;
        },

        /**
         * @param {Object} address
         * @return {*}
         */
        addressOptionsText: function (address) {
            return address.getAddressInline();
        },

        /**
         * @param {Object} address
         */
        onAddressChange: function (address) {
            this.isNewAddressSelected(address === newAddressOption);
        }
    });
});
