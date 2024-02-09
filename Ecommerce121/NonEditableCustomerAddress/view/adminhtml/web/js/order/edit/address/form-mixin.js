define([
    'jquery',
    'mage/utils/wrapper'
], function ($, wrapper) {
    'use strict';

    return function (setRegionAndDisableFields) {
        return wrapper.wrap(setRegionAndDisableFields, function (originalSetRegion, config, element) {
            originalSetRegion(config, element);

            let form = $(element);
            form.find('input:not([type="hidden"])').prop('disabled', true);
            form.find('select').prop('disabled', true);
        });
    };
});
