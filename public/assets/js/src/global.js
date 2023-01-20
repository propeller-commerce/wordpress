'use strict';

// Global object
window.Propeller || (window.Propeller = {});
// Detect Internet Explorer
window.Propeller.isIE = navigator.userAgent.indexOf("Trident") >= 0;
// Detect Edge
window.Propeller.isEdge = navigator.userAgent.indexOf("Edge") >= 0;
// Detect Mobile
window.Propeller.isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
// Tax codes
window.Propeller.TaxCodes = {
    H: 21,
    L: 9,
    N: 0
};
window.Propeller.product_container = '#propeller-product-list';

(function ($, window, document) {

    // Helper functions and extensions
    $.fn.serializeObject = function () {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };


    Propeller.Global = {

        maxSuggestions: 6,
        searchSuggestionTemplate: '<div class="beer-card">' +
            '<div class="beer-card__image">' +
            '<img src="/assets/jquerytypeahead/img/beer_v2/{{group}}/{{display|raw|slugify}}.jpg">' +
            '</div>' +
            '<div class="beer-card__name">{{display}}</div>' +
            '</div>',

        scrollTo: function (target) {
            $('html, body').stop().animate({
                'scrollTop': $(target).offset().top
            }, 500, 'swing');
        },
        changeAjaxPage: function (data, title, url) {
            if (window.history.pushState)
                window.history.pushState(data, title, url);
            else
                window.location.href = url;
        },

        formatPrice: function (price) {
            return Number(parseFloat(price).toFixed(2)).toLocaleString('nl', {
                minimumFractionDigits: 2
            });
        },
        getPercentage: function (percent, total) {
            return (percent / 100) * total;
        },
        parseQuery: function(queryString) {
            var query = {};

            var pairs = (queryString[0] === '?' ? queryString.substr(1) : queryString).split('&');

            if (pairs != '') {
                for (var i = 0; i < pairs.length; i++) {
                    var pair = pairs[i].split('=');
                    if(Array.isArray(pair) && pair.length > 1) {
                        query[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1].replace(/\+/g, ' ') || '');
                    }
                }
            }

            return query;
        },
        buildQuery: function(qryObj) {
            var aString = [];

            for (const key in qryObj) {
                if (qryObj.hasOwnProperty(key)) {

                    if (typeof qryObj[key] == 'object') {
                        var type = '';
                        var values = [];

                        for (var i = 0; i < qryObj[key].length; i++) {
                            var tmp = qryObj[key][i].split('~');

                            values.push(tmp[0]);
                            type = tmp[1];
                        }

                        var vals = values.join(',');
                        aString.push(`${key}=${vals}~${type}`);
                    }
                    else {
                        aString.push(`${key}=${qryObj[key]}`);
                    }
                }
            }

            return aString.join('&');
        },
    }

}(window.jQuery, window, document))