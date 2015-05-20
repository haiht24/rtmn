/**
 * Created by Phuong on 1/30/2015
 */
$.fn.datepicker.defaults.format = "dd M yyyy";
$.fn.datepicker.defaults.autoclose = true;
$.fn.datepicker.defaults.todayHighlight = true;
$.fn.datepicker.defaults.clearBtn = true;
$.fn.datetimepicker.defaults.format = "ll LT";
$.fn.datetimepicker.defaults.showClear = true;
$(function () {
    $('.auto-numeric').autoNumeric('init', {mDec: '0'});
    setNavigation();
    $('.datepicker').datepicker();
    $('.start-date').datepicker().on('changeDate', function (selected) {
        var startDate = $(selected.currentTarget).val();
        var endDate = $(selected.currentTarget).parent().parent().next('div').find('input.end-date')[0];
        if (new Date(startDate) > new Date()) {
            $(endDate).datepicker('setStartDate', startDate);
        }
        if (new Date(startDate) > new Date($(endDate).val())) {
            $(endDate).datepicker('update', startDate);
        }
    });
    $('.end-date').datepicker().on('changeDate', function (selected) {
        var endDate = $(selected.currentTarget).val();
        var startDate = $(selected.currentTarget).parent().parent().prev('div').find('input.start-date')[0];
        $(startDate).datepicker('setEndDate', endDate);
        if (new Date($(startDate).val()) > new Date(endDate)) {
            $(startDate).datepicker('update', endDate);
        }
    });

    $('.datetimepicker').datetimepicker({
        minDate: new Date(),
        format: "ll LT"
    });

    $('table th .check_all').click(function () {
        var check_element = $(this).closest('.table').find('.check_element');
        if ($(this).is(':checked')) {
            check_element.prop('checked', true);
        } else {
            check_element.prop('checked', false);
        }
    });
    jQuery.validator.addMethod("greaterThanZero", function (value, element) {
        return this.optional(element) || (parseFloat(replaceAll(',', '', value)) > 0);
    }, "Must be greater than zero");
    jQuery.validator.addMethod("greaterThan",
        function (value, element, param) {
            return this.optional(element) || parseFloat(replaceAll(',', '', value)) >= parseFloat(replaceAll(',', '', $(param).val()));
        }, 'Must be greater than {0}.');

    jQuery.validator.addMethod("lessThan",
        function (value, element, param) {
            return this.optional(element) || parseFloat(replaceAll(',', '', value)) <= parseFloat(replaceAll(',', '', $(param).val()));
        }, 'Must be less than {0}.');
});
function replaceAll(find, replace, str) {
    return str.replace(new RegExp(find, 'g'), replace);
}
function setNavigation() {
    var path = window.location.pathname;
    path = path.replace(/\/$/, "");
    path = decodeURIComponent(path);
    $(".nav > li > a").each(function () {
        var href = $(this).attr('href');
        if (href) {
            if (path.substr(path.length - href.length) === href && href.length > 1) {
                $(this).parent('li').addClass('active');
            }
        }
    });
    $(".nav > li > ul > li > a").each(function () {
        var href = $(this).attr('href');
        if (href) {
            if (path.substr(path.length - href.length) === href && href.length > 1) {
                $(this).parent('li').addClass('active');
                $(this).parent('li').parent('ul').parent('li').addClass('active open');
                var a_em = $(this).parent('li').parent('ul').parent('li').find('a em')[0];
                $(a_em).removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
            }
        }
    });
    $(".nav > li > ul > li > ul > li > a").each(function () {
        var href = $(this).attr('href');
        if (href) {
            if (path.substr(path.length - href.length) === href && href.length > 1) {
                $(this).parent('li').addClass('active');
                $(this).parent('li').parent('ul').parent('li').addClass('active open');
                var a_em = $(this).parent('li').parent('ul').parent('li').find('a em')[0];
                $(a_em).removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
                $(this).parent('li').parent('ul').parent('li').parent('ul').parent('li').addClass('active open');
                var a_em = $(this).parent('li').parent('ul').parent('li').parent('ul').parent('li').find('a em')[0];
                $(a_em).removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
            }
        }
    });
    $(".nav-second-level > li > a").each(function () {
        var href = $(this).attr('href');
        if (href) {
            if (path.substr(path.length - href.length) === href && href.length > 1) {
                $(this).parent('li').addClass('active');
                $(this).parent('li').parent('ul').parent('li').addClass('active');
            }
        }
    });
    $(".list-group a").each(function () {
        var href = $(this).attr('href');
        if (href) {
            if (path.substr(path.length - href.length) === href && href.length > 1) {
                $(this).closest('a').addClass('active');
            }
        }
    });
}
