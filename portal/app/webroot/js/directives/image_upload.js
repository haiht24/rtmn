angular.module('fdb.directives').directive('imageUpload', function($window, $parse) {
    return {
        compile: function compile(tElement, tAttrs, transclude) {
            if ($window.File && $window.FileReader && $window.FileList) {
                var crop = tAttrs.hasOwnProperty('crop');
                var showLoading = tAttrs.hasOwnProperty('imageLoading');
                var opacity = tAttrs.hasOwnProperty('opacity');
                var nopreview = tAttrs.hasOwnProperty('nopreview');
                var noremove = tAttrs.hasOwnProperty('noremove');
                var jasny = tAttrs.hasOwnProperty('jasnyFileinput');
                var maxImageSize = 15000000; //for testing. 
                if (tAttrs.hasOwnProperty('maxImageSize')) {
                    maxImageSize = tAttrs.maxImageSize;
                }
                var imageJpeg = tAttrs.hasOwnProperty('imageJpeg');
                var jpegType = false;
                var rawImage = (tAttrs.imageUpload + '_raw').replace(/[^a-zA-Z0-9]/g, '_');
                var showImage = (tAttrs.imageUpload + '_show').replace(/[^a-zA-Z0-9]/g, '_');
                var html = '<input type="file" accept="image/*"/>';
                if (jasny) {
                    html += '<span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span>';
                }
                if (!nopreview) {
                    html += '<div class="image-preview" >';
                    html += '<img ng-show="' + showImage + '" ng-src="{{' + showImage + ' || null }}" />';
                    html += '</div>';
                }

                if (opacity) {
                    html += '<div class="background-opacity"></div>';
                    html += '<div class="slider-adjust" ng-show="' + showImage + '"></div>';
                }
                if (crop) {
                    html += '<div class="progress"></div>';
                }
                if (crop) {
                    html += '<div class="slider-container"></div>';
                    html += '<div class="save-container"></div>';
                }
                //html += '<div class="image-message">{{' + tAttrs.defaultMessage + '}}</div>';
                if (!noremove) {
                    html += '<div class="image-remove">x</div>';
                    html += '<div class="image-loading hidden">\n\
                                <img src="' + Config.baseUrl + '/img/select2-spinner.gif">\n\
                            </div>';
                }

                tElement.html(html);
                return function(scope, iElement, iAttrs) {
                    var origin = {
                        width: 0,
                        height: 0,
                        snapToContainer: false,
                        image: {
                            imgW: 0,
                            imgH: 0,
                            w: 0,
                            h: 0,
                            posX: 0,
                            posY: 0,
                            scaleX: 0,
                            scaleY: 0
                        }
                    };
                    var fixed = 'w';
                    if (tAttrs.hasOwnProperty('fixed')) {
                        fixed = 'h';
                    }
                    var store = angular.copy(origin);
                    var ratio = iElement.width() / iElement.height();
                    if (tAttrs.hasOwnProperty('imageRatio')) {
                        ratio = tAttrs.imageRatio;
                    }
                    var imageWidth = 0;
                    if (tAttrs.hasOwnProperty('imageWidth')) {
                        imageWidth = tAttrs.imageWidth;
                    } else {
                        imageWidth = iElement.width();
                    }
                    var input = iElement.find('input[type=file]');
                    var preview = iElement.find('div.image-preview');
                    var previewImage = iElement.find('div.image-preview > img:first');
                    var sliderAdjust = iElement.find('div.slider-adjust');
                    var backgroundOpacity = iElement.find('div.background-opacity');
                    var slider = iElement.find('div.slider-container');
                    var progressBar = iElement.find('div.progress');
                    var saveButton = iElement.find('div.save-container');
                    var setHeight = function() {
                        if (!tAttrs.hasOwnProperty('noSetHeight')) {
                            store.width = imageWidth;
                            if (scope.$eval(rawImage) || scope.$eval(showImage)) {
                                store.height = imageWidth / ratio;
                                iElement.height(iElement.width() / ratio);
                            } else {
                                iElement.css('height', '');
                                store.height = imageWidth / ratio;
                            }
                        }
                    };
                    var imageLoading = iElement.find('div.image-loading');
                    var ApplyCssToImageBaseOnData = function(setSize, setPosition, forceStretch) {
                        if (setSize) {
                            if ((fixed == 'w') && (store.image.w >= store.width || forceStretch)) {
                                store.image.h = Math.round(store.image.h * (store.width / store.image.w));
                                store.image.w = store.width;
                            }
                            if ((fixed == 'h') && (store.image.h >= store.height || forceStretch)) {
                                store.image.w = Math.round(store.image.w * (store.height / store.image.h));
                                store.image.h = store.height;
                            }
                        }
                        if (setPosition) {
                            store.image.posY = (store.height - store.image.h) / 2;
                            store.image.posX = (store.width - store.image.w) / 2;
                        }
                        var divA = imageWidth / iElement.width();
                        previewImage.css({
                            'position': 'absolute',
                            'top': store.image.posY / divA,
                            'left': store.image.posX / divA,
                            'width': store.image.w / divA,
                            'height': store.image.h / divA
                        });
                    };
                    var calculateFactor = function() {
                        store.image.scaleX = (store.width / store.image.w);
                        store.image.scaleY = (store.height / store.image.h);
                    };
                    var getPercentOfZoom = function(image) {
                        var percent = 0;
                        if (image.w > image.h) {
                            percent = ((image.w * 100) / store.image.imgW);
                        } else {
                            percent = ((image.h * 100) / store.image.imgH);
                        }
                        return percent;
                    };
                    var createZoomSlider = function() {
                        var zoomContainerSlider = $("<div />").attr('class',
                                'zoomContainer').mouseover(function() {
                            $(this).css('opacity', 1);
                        }).mouseout(function() {
                            $(this).css('opacity', 0.6);
                        });
                        var zoomMin = $('<div />').attr('class', 'zoomMin').html(
                                "<b>+</b>");
                        var zoomMax = $('<div />').attr('class', 'zoomMax').html(
                                "<b>-</b>");
                        var $slider = $("<div />").attr('class', 'zoomSlider');
                        // Apply Slider
                        $slider
                                .slider({
                            orientation: 'vertical',
                            value: getPercentOfZoom(store.image),
                            min: 100,
                            max: 150,
                            step: 10,
                            slide: function(event, ui) {
                                var value = ui.value;
                                var zoomInPx_width = (store.image.imgW * Math.abs(value) / 100);
                                var zoomInPx_height = (store.image.imgH * Math.abs(value) / 100);

                                var difX = (store.image.w / 2) - (zoomInPx_width / 2);
                                var difY = (store.image.h / 2) - (zoomInPx_height / 2);

                                var newX = (difX > 0 ? store.image.posX
                                        + Math.abs(difX)
                                        : store.image.posX
                                        - Math.abs(difX));
                                var newY = (difY > 0 ? store.image.posY
                                        + Math.abs(difY)
                                        : store.image.posY
                                        - Math.abs(difY));
                                store.image.posX = newX;
                                store.image.posY = newY;
                                store.image.w = zoomInPx_width;
                                store.image.h = zoomInPx_height;
                                calculateFactor();
                                ApplyCssToImageBaseOnData(false, false, false);
                            }
                        });
                        zoomContainerSlider.append(zoomMin);
                        zoomContainerSlider.append($slider);
                        zoomContainerSlider.append(zoomMax);
                        zoomMin.addClass('vertical');
                        zoomMax.addClass('vertical');
                        $slider.addClass('vertical');
                        zoomContainerSlider.addClass('vertical');
                        zoomContainerSlider.css({
                            'position': 'absolute',
                            'top': 5,
                            'right': 5,
                            'opacity': 0.6
                        });
                        slider.html(zoomContainerSlider).show().on('click', function(event) {
                            event.stopPropagation();
                        });
                    };
                    var applyAdjust = function() {
                        var value = scope.$eval(iAttrs.opacity) || (scope.$eval(iAttrs.opacity) === 0 ? 0 : 10);
                        if (value >= 10) {
                            backgroundOpacity.css({
                                'background': 'rgba(255, 255, 255, ' + (value - 10) / 10 + ')'
                            });
                        } else {
                            backgroundOpacity.css({
                                'background': 'rgba(0, 0, 0, ' + (1 - (value / 10)) + ')'
                            });
                        }
                    };
                    var createZoomSliderAdjust = function() {
                        var zoomContainerAdjustSlider = $("<div />").attr('class',
                                'zoomContainerAdjust').mouseover(function() {
                            $(this).css('opacity', 1);
                        }).mouseout(function() {
                            $(this).css('opacity', 0.6);
                        });
                        var zoomMin = $('<div />').attr('class', 'zoomMin').html(
                                "<b>+</b>");
                        var zoomMax = $('<div />').attr('class', 'zoomMax').html(
                                "<b>-</b>");
                        var $slider = $("<div />").attr('class', 'zoomSliderAdjust');
                        // Apply Slider
                        $slider
                                .slider({
                            orientation: 'vertical',
                            min: 0,
                            max: 20,
                            value: scope.$eval(iAttrs.opacity) || 10,
                            step: 1,
                            slide: function(event, ui) {
                                scope.$apply(function() {
                                    $parse(iAttrs.opacity).assign(scope, ui.value);
                                });
                                applyAdjust();
                            }
                        });
                        zoomContainerAdjustSlider.append(zoomMin);
                        zoomContainerAdjustSlider.append($slider);
                        zoomContainerAdjustSlider.append(zoomMax);
                        zoomMin.addClass('vertical');
                        zoomMax.addClass('vertical');
                        $slider.addClass('vertical');
                        zoomContainerAdjustSlider.addClass('vertical');
                        zoomContainerAdjustSlider.css({
                            'position': 'absolute',
                            'top': 90,
                            'left': 5,
                            'opacity': 0.6
                        });
                        sliderAdjust.html(zoomContainerAdjustSlider).show().on('click', function(event) {
                            event.stopPropagation();
                        });
                    };
                    var zoomInInit = function() {
                        if ((fixed == 'w') && (store.image.w >= store.width)) {
                            store.image.h = Math.round(store.image.h * (store.width / store.image.w));
                            store.image.w = store.width;
                        }
                        if ((fixed == 'h') && (store.image.h >= store.height)) {
                            store.image.w = Math.round(store.image.w * (store.height / store.image.h));
                            store.image.h = store.height;
                        }
                        store.image.posY = (store.height - store.image.h) / 2;
                        store.image.posX = (store.width - store.image.w) / 2;
                        var diffW = store.width / store.image.imgW;
                        var diffH = store.height / store.image.imgH;
                        var diff = diffW > diffH ? diffW : diffH;
                        var checkStore = angular.copy(store);
                        var zoomInPx_width = checkStore.image.imgW * diff;
                        var zoomInPx_height = checkStore.image.imgH * diff;
                        var difX = (checkStore.image.w / 2) - (zoomInPx_width / 2);
                        var difY = (checkStore.image.h / 2) - (zoomInPx_height / 2);
                        var newX = (difX > 0 ? checkStore.image.posX
                                + Math.abs(difX)
                                : checkStore.image.posX
                                - Math.abs(difX));
                        var newY = (difY > 0 ? checkStore.image.posY
                                + Math.abs(difY)
                                : checkStore.image.posY
                                - Math.abs(difY));
                        checkStore.image.posX = newX;
                        checkStore.image.posY = newY;
                        checkStore.image.w = zoomInPx_width;
                        checkStore.image.h = zoomInPx_height;
                        checkStore.image.scaleX = (checkStore.width / checkStore.image.w);
                        checkStore.image.scaleY = (checkStore.height / checkStore.image.h);

                        store.image.imgW = checkStore.image.w;
                        store.image.imgH = checkStore.image.h;
                        store.image.posX = checkStore.image.posX;
                        store.image.posY = checkStore.image.posY;
                        store.image.w = checkStore.image.w;
                        store.image.h = checkStore.image.h;
                        store.image.scaleX = checkStore.image.scaleX;
                        store.image.scaleY = checkStore.image.scaleY;
                        ApplyCssToImageBaseOnData(false, false, false);
                    };
                    //function to render image at first time after upload.
                    var renderFirstTime = function() {
                        var image = new Image();
                        image.src = scope.$eval(rawImage);
                        image.onload = function() {
                            setHeight();
                            store.image.w = store.image.imgW = image.width;
                            store.image.h = store.image.imgH = image.height;
                            // Valid Ad dimension - by HaiHT
                            if($('#ad_upload').html()){
                                var adWidthAllow = $('#ad_upload').attr('ad_width_allow');
                                var adHeightAllow = $('#ad_upload').attr('ad_height_allow');
                                // Check ad width
                                if(adWidthAllow){
                                    if(store.image.w != adWidthAllow){
                                        alert('Image width too big. Width allow exactly ' + adWidthAllow + ' pixel');
                                        return;
                                    }
                                }else{
                                    alert('Missing attribute "ad_width_allow" white using element id = "ad_upload"');
                                }
                                // Check ad height
                                if(adHeightAllow){
                                    if(store.image.h != adHeightAllow){
                                        alert('Image width too big. Width allow exactly ' + adWidthAllow + ' pixel');
                                        return;
                                    }
                                }else{
                                    alert('Missing attribute "ad_height_allow" white using element id = "ad_upload"');
                                }
                            }
                            //# End Valid Ad dimension #
                            //resize if fixed is setted.
                            calculateFactor();
                            if (crop) {
                                zoomInInit();
                                image = null;
                                createZoomSlider();
                                saveButton.html('<button class="save save-crop">' + 'Confirm crop' + '</button>').show();
                                // adding draggable to the image
                                previewImage.draggable({
                                    refreshPositions: true,
                                    disabled: false,
                                    drag: function(event, ui) {
                                        var diff = imageWidth / iElement.width();
                                        store.image.posY = ui.position.top * diff;
                                        store.image.posX = ui.position.left * diff;
                                        ApplyCssToImageBaseOnData(false, false, false);
                                    },
                                    stop: function(event, ui) {
                                        var diff = imageWidth / iElement.width();
                                        store.image.posY = ui.position.top * diff;
                                        store.image.posX = ui.position.left * diff;
                                        ApplyCssToImageBaseOnData(false, false, false);
                                    }
                                });
                            } else {
                                if (showLoading) {
                                    imageLoading.removeClass('hidden');
                                    preview.css({
                                        'opacity': 0.3
                                    });
                                }
                                ApplyCssToImageBaseOnData(true, true, true);
                                //create canvans and save.
                                var canvas = document.createElement('canvas');
                                canvas.width = store.image.w;
                                canvas.height = store.image.h;
                                canvas.getContext("2d").drawImage(image, 0, 0, store.image.w, store.image.h);
                                var canvasUrl = null;
                                if (jpegType) {
                                    canvasUrl= canvas.toDataURL("image/jpeg");
                                } else {
                                    canvasUrl = canvas.toDataURL("image/png");
                                }
                                canvas = null;
                                image = null;
                                $.ajax({
                                    type: 'POST',
                                    url: Config.baseUrl + "/upload",
                                    //data: {rawImage: canvasUrl},
                                    data: {rawImage: scope.$eval(rawImage)},
                                    success: function(data) {
                                        scope.$apply(function() {
                                            if (showLoading) {
                                                imageLoading.addClass('hidden');
                                                preview.css({
                                                    'opacity': 1
                                                });
                                            }
                                            $parse(iAttrs.imageUpload).assign(scope, data);
                                            $parse(showImage).assign(scope, data);
                                            $parse(rawImage).assign(scope, undefined);
                                            ApplyCssToImageBaseOnData(true, true, true);
                                        });
                                    }
                                });
                            }
                        };
                    };
                    var saveImage = function() {
                        slider.empty().hide();
                        saveButton.empty().hide();
                        previewImage.draggable({disabled: true});
                        //overlay preview pane .
                        preview.css({
                            'opacity': 0.6
                        });
                        //show upload progress bar
                        progressBar.html('<div class="bar"></div><div class="percent">0%</div >').show();
                        var image = new Image();
                        image.src = scope.$eval(rawImage);
                        image.onload = function() {
                            var canvas = document.createElement('canvas');
                            canvas.width = store.width;
                            canvas.height = store.height;
                            canvas.getContext("2d").drawImage(image, store.image.posX, store.image.posY, store.image.w, store.image.h);
                            var canvasUrl = null;
                            if (jpegType) {
                                canvasUrl = canvas.toDataURL("image/jpeg");
                            } else {
                                canvasUrl = canvas.toDataURL("image/png");
                            }
                            canvas = null;
                            image = null;
                            $.ajax({
                                xhr: function()
                                {
                                    var xhr = new window.XMLHttpRequest();
                                    //Upload progress
                                    xhr.upload.addEventListener("progress", function(evt) {
                                        if (evt.lengthComputable) {

                                            var percentVal = Math.round(evt.loaded / evt.total * 100) + '%';
                                            progressBar.find('div.bar').width(percentVal)
                                            progressBar.find('div.percent').html(percentVal);
                                        }
                                    }, false);
                                    return xhr;
                                },
                                type: 'POST',
                                url: Config.baseUrl + "/upload",
                                data: {rawImage: canvasUrl},
                                success: function(data) {
                                    scope.$apply(function() {
                                        $parse(iAttrs.imageUpload).assign(scope, data);
                                        $parse(showImage).assign(scope, data);
                                        $parse(rawImage).assign(scope, undefined);
                                        preview.css({
                                            'opacity': 1
                                        });
                                        var percentVal = '100%';
                                        progressBar.find('div.bar').width(percentVal)
                                        progressBar.find('div.percent').html(percentVal);
                                        previewImage.removeClass('ui-draggable-disabled').removeClass('ui-state-disabled').css({
                                            'top': 0,
                                            'left': 0,
                                            'height': iElement.width() / ratio,
                                            'width': 'auto'
                                        });
                                        if (opacity) {
                                            applyAdjust();
                                        } else {
                                            previewImage.css({
                                                'opacity': 1
                                            });
                                        }
                                        store = angular.copy(origin);
                                        progressBar.empty().hide();
                                    });
                                }
                            });
                        };
                    };
                    var handleFileSelect = function(fileList) {
                        if (fileList.length < 1) {
                            input.val('');
                            return; // no image selected
                        }
                        var file = fileList[0];
                        if (file.size > maxImageSize) {
                            input.val('');
                            alert('Image size too big');
                            return;
                        }
                        if(imageJpeg) {
                            if (!file.type.match('image/jpeg')) {
                                alert("File is not an jpeg image");
                                return;
                            }
                        } else if (!(file.type.match('image/jpeg') || file.type.match('image/png'))) {
                            alert("File is not an jpeg/png image");
                            return;
                        }
                        if (file.type.match('image/jpeg')) {
                            jpegType = true;
                        } else {
                            jpegType = false;
                        }
                        var reader = new FileReader();
                        reader.onloadend = (function(theFile) {
                            return function(e) {
                                scope.$apply(function() {
                                    $parse(rawImage).assign(scope, e.target.result);
                                    $parse(showImage).assign(scope, e.target.result);
                                    renderFirstTime();
                                });
                            };
                        })(file);
                        reader.readAsDataURL(file);
                    };
                    var Init = function() {
                        // reset the raw && show image  
                        if (scope.$eval(tAttrs.imageUpload)) {
                            $parse(showImage).assign(scope, scope.$eval(tAttrs.imageUpload));
                        } else {
                            if (scope.$eval(tAttrs.defaultImage)) {
                                $parse(showImage).assign(scope, scope.$eval(tAttrs.defaultImage));
                            } else {
                                $parse(showImage).assign(scope, '');
                            }
                        }
                        setHeight();
                        if (scope.$eval(showImage)) {
                            var image = new Image();
                            image.src = scope.$eval(showImage);
                            image.onload = function() {
                                store.image.w = store.image.imageW = image.width;
                                store.image.h = store.image.imageH = image.height;
                                image = null;
                                ApplyCssToImageBaseOnData(true, true, true);
                            };
                        }
                        if (opacity) {
                            createZoomSliderAdjust();
                            if (opacity) {
                                applyAdjust();
                            }
                        }
                    };
                    scope.$watch('incentiveAdd', function(newValue) {
                        if (newValue == 1) {
                            Init();
                        }
                    }, true);
                    Init();
                    iElement.find('div.image-remove').click(function(event) {
                        event.stopPropagation();
                        scope.$apply(function() {
                            if (scope.$eval(tAttrs.defaultImage)) {
                                $parse(iAttrs.imageUpload).assign(scope, scope.$eval(tAttrs.defaultImage));
                                $parse(showImage).assign(scope, scope.$eval(tAttrs.defaultImage));
                            } else {
                                $parse(iAttrs.imageUpload).assign(scope, '');
                                $parse(showImage).assign(scope, '');
                                if (opacity) {
                                    $parse(iAttrs.opacity).assign(scope, 10);
                                    applyAdjust();
                                }
                            }
                            $parse(rawImage).assign(scope, undefined);
                            slider.empty().hide();
                            saveButton.empty().hide();
                            progressBar.empty().hide();
                            setHeight();
                            if (scope.$eval(tAttrs.defaultImage)) {
                                var image = new Image();
                                image.src = scope.$eval(tAttrs.defaultImage);
                                image.onload = function() {
                                    store.image.w = store.image.imageW = image.width;
                                    store.image.h = store.image.imageH = image.height;
                                    image = null;
                                    ApplyCssToImageBaseOnData(true, true, true);
                                };
                            }
                        });
                    });
                    iElement.on('click', '.save', function(event) {
                        event.stopPropagation();
                        saveImage();
                    });
                    input.change(function(event) {
                        handleFileSelect(event.target.files);
                    });
                    preview.on('dragover', function(event) {
                        event.stopPropagation();
                        event.preventDefault();
                        event.originalEvent.dataTransfer.dropEffect = 'copy'; // Explicitly show this is a copy.                            
                    });
                    preview.on('dragenter', function(event) {
                        event.stopPropagation();
                        event.preventDefault();
                        preview.addClass('dragover');
                    });
                    preview.on('dragleave', function(event) {
                        event.stopPropagation();
                        event.preventDefault();
                        preview.removeClass('dragover');
                    });
                    preview.on('drop', function(event) {
                        preview.removeClass('dragover');
                        event.stopPropagation();
                        event.preventDefault();
                        handleFileSelect(event.originalEvent.dataTransfer.files);
                    });
                    preview.click(function(event) {
                        input.click();
                    });
                    if (opacity) {
                        backgroundOpacity.click(function(event) {
                            input.click();
                        });
                    }
                };
            } else {
                tElement.html('Browser not supportet');
            }
        }
    };
});