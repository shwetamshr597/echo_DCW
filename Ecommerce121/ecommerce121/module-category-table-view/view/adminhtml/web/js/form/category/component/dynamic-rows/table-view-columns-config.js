define([
    'Magento_Ui/js/dynamic-rows/dynamic-rows'
], function (DynamicRows) {
    'use strict';

    return DynamicRows.extend({
        defaults: {
            listens: {
                tableViewModeEnabled: 'handleTableViewModeChanges',
                configInheritanceValue: 'handleConfigInheritanceChanges',
                configInheritanceDisabled: 'handleConfigInheritanceDisabledChanges',
            },
            tableViewModeEnabled: null,
            configInheritanceValue: null,
            configInheritanceDisabled: null
        },

        handleTableViewModeChanges: function (newValue) {
            this.visible(this.configInheritanceValue && !this.configInheritanceDisabled && newValue);
        },

        handleConfigInheritanceChanges: function (newValue) {
            this.visible(this.tableViewModeEnabled && !this.configInheritanceDisabled && parseInt(newValue));
        },

        handleConfigInheritanceDisabledChanges: function (newValue) {
            this.visible(this.tableViewModeEnabled && parseInt(this.configInheritanceValue) && !newValue);
        }
    });
});
