define([
    'ko',
], function (
    ko,
) {
    'use strict';

    var mixin = {
        canUseShippingAddress: ko.computed(function () {
            return false;
        })
    };
    return function (target) {
        return target.extend(mixin);
    };
});
