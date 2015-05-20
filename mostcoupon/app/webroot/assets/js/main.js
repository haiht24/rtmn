/**
 * Created by Phuong on 2/26/2015.
 */
$(document).ready(function () {
    $('.tooltips').tooltip();
    $('body').on('hidden.bs.modal', '.modal', function () {
        $(this).data('bs.modal', null);
    });
    // browser window scroll (in pixels) after which the "back to top" link is shown
    var offset = 300,
    //browser window scroll (in pixels) after which the "back to top" link opacity is reduced
        offset_opacity = 1200,
    //duration of the top scrolling animation (in ms)
        scroll_top_duration = 700,
    //grab the "back to top" link
        $back_to_top = $('.cd-top');

    //hide or show the "back to top" link
    $(window).scroll(function () {
        ( $(this).scrollTop() > offset ) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible cd-fade-out');
        if ($(this).scrollTop() > offset_opacity) {
            $back_to_top.addClass('cd-fade-out');
        }
    });

    //smooth scroll to top
    $back_to_top.on('click', function (event) {
        event.preventDefault();
        $('body,html').animate({
                scrollTop: 0
            }, scroll_top_duration
        );
    });
    $('.search-mobile').click(function () {
        if ($('.search-btn').hasClass('fa-search')) {
            $('.search-open').slideDown(500);
            $('.search-btn').removeClass('fa-search');
            $('.search-btn').addClass('fa-times');
        } else {
            $('.search-open').slideUp(500);
            $('.search-btn').addClass('fa-search');
            $('.search-btn').removeClass('fa-times');
        }
    });
    $(".dotdotdot").dotdotdot({
        // configuration goes here
    });

    $('.desc-more').each(function () {
        var $dot = $(this);
        $dot.dotdotdot();
        var isTruncated = $dot.triggerHandler("isTruncated");
        if (isTruncated) {
            destroyDots();
            $dot.append(' <a class="toggle" href="#"><span class="open"><i>More</i></span><span class="close-more"><i>Less</i></span></a>');
            createDots();
        }
        function createDots() {
            $dot.dotdotdot({
                after: 'a.toggle'
            });
        }

        function destroyDots() {
            $dot.trigger('destroy');
        }

        $dot.on('click', 'a.toggle', function () {
            $dot.toggleClass('opened');
            if ($dot.hasClass('opened')) {
                destroyDots();
            } else {
                createDots();
            }
            return false;
        });
    });

    $.fn.datepicker.defaults.format = "dd M yyyy";
    $.fn.datepicker.defaults.autoclose = true;
    $.fn.datepicker.defaults.todayHighlight = true;
    $.fn.datepicker.defaults.clearBtn = true;
    setNavigation();
    $('.datepicker').datepicker();

    $('div.alert div.message').each(function () {
        if ($(this).text().length) {
            setTimeout(function () {
                $('div.alert').remove();
            }, 5000);
        }
    });

    $('.auto-numeric').autoNumeric('init', {mDec: '0'});
    $('a.popup-report').popover({
        "html": true,
        "placement": 'top',
        "content": function () {
            return $(this).next('.content-report').html();
        }
    });
    $('a.popup-send-mail').popover({
        "html": true,
        "placement": 'top',
        "content": function () {
            var val = "<form action-url='" + $(this).attr('action') + "' class='form-group send-coupon-form' id='" + $(this).attr('coupon-id') + "'><h4><b> Send this coupon to your inbox </b></h4>"
                + "<div class='input-group'>"
                + "<input type='email' name='yourEmail' class='form-control' placeholder='Enter your email'/>"
                + "<span class='input-group-btn'>"
                + "<button type='submit' class='btn btn-success btn-green send-coupon-submit' >Send</button>"
                + "</span></div></form>";
            return val;
        }
    }).on('shown.bs.popover', function (e) {
        var $form = $($(e.currentTarget).next('div.popover').find('form.send-coupon-form')[0]);
        if (!$form) return;
        var sendMailValidator = $form.validate({
            rules: {
                yourEmail: {
                    required: true,
                    email: true
                }
            },
            errorElement: "span", // contain the error msg in a small tag
            errorClass: 'help-block myErrorClass',
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.parent().hasClass("input-group")) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                    // for other inputs, just perform default behavior
                }
            },
            highlight: function (element, errorClass, validClass) {
                var elem = $(element);
                $(element).closest('.help-block').removeClass('valid');
                // display OK icon
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
                // add the Bootstrap error class to the control group
            },
            unhighlight: function (element, errorClass, validClass) {
                // revert the change done by hightlight
                var elem = $(element);
                $(element).closest('.form-group').removeClass('has-error');
                // set error class to the control group
            },
            success: function (label, element) {
                label.addClass('help-block valid');
                // mark the current input as valid and display OK icon
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
            }
        });
        var $that = $(this);
        $form.on('submit', function (e) {
            e.preventDefault();
            if ($form.valid()) {
                var id = $(this).attr('id');
                var email = $('#' + id + ' input.form-control').val();
                $that.attr('data-content', "<div style='width: 300px;height: 88px'><div style='margin: auto;width: 28px'><i class='fa fa-spinner fa-pulse fa-2x'></i></div></div>");
                var popover = $that.data('bs.popover');
                popover.setContent();
                popover.$tip.addClass(popover.options.placement);
                $.ajax({
                    type: 'post',
                    url: $(this).attr('action-url'),
                    data: {'email': email, 'id': id},
                    success: function (data) {
                        if (data.status == 'success') {
                            $that.attr('data-content', "<div style='color: #30b24b;width: 300px;height: 86px'><h4>Coupon Sent.</h4><p>Thanks for saving with MostCoupon!</p></div>");
                        } else {
                            $that.attr('data-content', "<div style='color: #d9534f;width: 300px;height: 86px'><h4>Can not send Email.</h4><p>Please try again!</p></div>");
                        }
                        var popover = $that.data('bs.popover');
                        popover.setContent();
                        popover.$tip.addClass(popover.options.placement);
                        setTimeout(function () {
                            $that.popover('hide')
                        }, 3000);
                    }
                });
            }
        });
    });

    jQuery.validator.addMethod("greaterThanZero", function (value, element) {
        return this.optional(element) || (parseFloat(value) > 0);
    }, "Discount must be greater than zero");

    $('.timeago').timeago();

    $('.main-box .vs .card .body strong').each(function () {
        switch ($(this).text().length) {
            case 6:
                $(this).addClass('font-size-29');
                break;
            case 7:
                $(this).addClass('font-size-24');
                break;
            case 8:
                $(this).addClass('font-size-21');
                break;
            case 10:
                $(this).addClass('font-size-18');
                break;
            case 11:
                $(this).addClass('font-size-14');
                break;
            case 12:
                $(this).addClass('font-size-14');
                break;
        }
    });
    var exist_cp = getUrlVars()['c'];
    if (exist_cp) {
        $("a.btn-get-code.hidden[coupon_id='" + exist_cp + "']").first().click();
    }
    var exist_d = getUrlVars()['d'];
    if (exist_d) {
        $("a.btn-get-deal.hidden[deal_id='" + exist_d + "']").first().click();
    }
    $('#get-coupon-code').on('show.bs.modal', function (e) {
        $('#get-coupon-code .desc-more').each(function () {
            var $dot = $(this);
            $dot.dotdotdot();
            var isTruncated = $dot.triggerHandler("isTruncated");
            if (isTruncated) {
                destroyDots();
                $dot.append(' <a class="toggle" href="#"><span class="open"><i>More</i></span><span class="close-more"><i>Less</i></span></a>');
                createDots();
            }
            function createDots() {
                $dot.dotdotdot({
                    after: 'a.toggle'
                });
            }

            function destroyDots() {
                $dot.trigger('destroy');
            }

            $dot.on('click', 'a.toggle', function () {
                $dot.toggleClass('opened');
                if ($dot.hasClass('opened')) {
                    destroyDots();
                } else {
                    createDots();
                }
                return false;
            });
        });
    });
});
function getUrlVars() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}
function open_popup(root) {
    var search = getUrlVars()['s'];
    var exist_cp = getUrlVars()['c'];
    var show = getUrlVars()['show'];
    var category = getUrlVars()['cat'];
    var _page = getUrlVars()['_page'];

    var parent = root.closest('.offers-module');
    var id = parent.attr('id');
    var current_url = parent.data('current_url').split('?');
    var outlink = parent.data('out');
    var url_popup = '';
    window.location = outlink;
    if (search) {
        url_popup = current_url[0] + '?s=' + search + '&c=' + id;
    } else if (show && _page) {
        url_popup = current_url[0] + '?_page=' + _page + '&show=' + show + '&c=' + id;
    } else if (show) {
        url_popup = current_url[0] + '?show=' + show + '&c=' + id;
    } else if (_page) {
        url_popup = current_url[0] + '?_page=' + _page + '&c=' + id;
    } else if (category) {
        url_popup = current_url[0] + '?cat=' + category + '&c=' + id;
    } else {
        url_popup = current_url[0] + '?c=' + id;
    }
    window.open(url_popup);
};
$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
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
function setNavigation() {
    var path = window.location.pathname;
    path = path.replace(/\/$/, "");
    path = decodeURIComponent(path);
    $(".nav > li > a").each(function () {
        var href = $(this).attr('href');
        href = href.replace(/\/$/, "");
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
