define(['jquery'], function ($) {
    'use strict';

    const extendUserEditWidget = {
        options: {
            popup: '[data-role="add-customer-dialog"]',
            statusSelect: '[data-role="status-select"]',
            telephoneDiv: '[class="field telephone required"]',
            emailInput: '[data-role="email"]',
            customerId: '[name="customer_id"]',
            roleSlect: '[data-role="role-select"]'
        },

        _setPopupFields: function (name, value) {
            this._super(name, value);
            
            this.options.popup.find(this.options.telephoneDiv).removeClass('required');

            this.options.popup.find('input').attr('disabled', true);

            this.options.popup.find(this.options.emailInput)
            .attr('disabled', true);

            this.options.popup.find(this.options.customerId).removeAttr('disabled');

            this.options.popup.find(this.options.statusSelect).attr('disabled', true);

            this.options.popup.find(this.options.roleSlect).removeAttr('disabled');
        }
    };

    return function (targetWidget) {
        $.widget('mage.userEdit', targetWidget, extendUserEditWidget);
        return $.mage.userEdit;
    };
});
