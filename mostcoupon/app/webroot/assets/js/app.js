(function(window, $, undef){
    'use strict';

    (function inlineFlexslider() {
        var namespace = 'flexslider'
          , selector = '.flexslider, [data-' + namespace + ']';

        $(function() {
            $(document)
            .on('pretty-scroll.refresh', function() {
                  $('.pretty-scroll').perfectScrollbar();
            })
            .trigger('pretty-scroll.refresh');

            $(document)
            .on('flexslider.refresh', function() {
                if(!$.fn.flexslider) {
                    return null;
                }

                var sliders = $(selector).toArray();

                // Shift the being synced
                //   to the start of the queue
                // @see http://flexslider.woothemes.com/thumbnail-slider.html
                for(var idx in sliders) {
                    if(!$(sliders[idx]).data(namespace + '-as-nav-for')) {
                        sliders.push(sliders.splice(idx, 1)[0]);
                    }
                }

                $.each(sliders, function(idx, slider) {
                    var opts = $.extend({}, $.flexslider.defaults)
                      , $slider = $(slider)
                      , k, v;

                    // Config by a single
                    //   attribute as a JSON string
                    v = $slider.data(namespace);
                    if(undef != v) {
                        try {
                            v = $.parseJSON(v);
                            opts = $.extend(opts, v);
                        } catch(e) {}
                    }

                    // Config by single attributes
                    for(k in opts) {
                        v = $slider.data(
                            namespace
                            + '-'
                            + k.replace(
                                  /[A-Z]/g,
                                  function(c){ return '-' + c.toLowerCase() }
                              )
                        );
                        if(undef != v) {
                            opts[k] = v;
                        }
                    }

                    // Init the flex slider
                    $slider.flexslider(opts);
                });
            })
            .trigger('flexslider.refresh');
        });
    })();

    $(document).on('click', '.activator, [data-activate]', function(evt) {
        var target = $(this).data('activator-target')
            , cl = $(this).data('activator-class') || 'activated';

        if(/^\^/.test(target)) {
            target = $(this).parents(target.replace(/^\^/, ''));
        } else if(target) {
            target = $(target);
        } else {
            target = $(this).parent();
        }

        $(this).toggleClass(cl);
        target.toggleClass(cl).addClass('activator-target');
    });

    $(window).on('load', function() {
        $(document.body).addClass('loaded');
    });
})(window, window.jQuery);