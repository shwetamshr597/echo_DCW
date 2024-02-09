define([
    'jquery',
    'list-js'
], function ($) {
    'use strict';

    $.widget('ecommerce121.sortableList', {
        options: {
            listContainerId: 'category-products-table',
            config: {"valueNames": ["sku", "name", {"name": "price-wrapper", "attr": "data-price-amount"}]},
            rowHeaderSelector: '.category-product-table.item .product-item-info-table',
            rowWrapperSelector: '.category-product-table.item',
            activeClass: 'active'
        },

        _create: function () {
            new List(this.options.listContainerId, this.options.config);

            $(this.options.rowHeaderSelector).click($.proxy(this._toggleRow, this));
        },

        _toggleRow: function (event) {
            var container = $(event.currentTarget).parent(this.options.rowWrapperSelector);
            $(this.options.rowWrapperSelector).not(container).removeClass(this.options.activeClass);
            container.toggleClass(this.options.activeClass);
        }
    });

    return $.ecommerce121.sortableList;
});
