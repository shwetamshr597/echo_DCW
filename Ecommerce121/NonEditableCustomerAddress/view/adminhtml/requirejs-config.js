var config = {
    map: {
        '*': {
            'Magento_InventoryInStorePickupSalesAdminUi/order/create/scripts-mixin':
                'Ecommerce121_NonEditableCustomerAddress/js/order/create/scripts-mixin'
        }
    },
    config: {
        mixins: {
            'Magento_Sales/order/edit/address/form': {
                'Ecommerce121_NonEditableCustomerAddress/js/order/edit/address/form-mixin': true
            }
        }
    }
};
