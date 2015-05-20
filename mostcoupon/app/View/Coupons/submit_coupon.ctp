<?php $this->Ng->ngController('submitCouponCtrl') ?>
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-sm-4 links">
                <ul>
                    <li>
                        <a href="/">Home</a>
                    </li>
                    <li>
                        <a href="#">Submit coupon</a>
                    </li>
                </ul>
            </div>
            <form class="col-sm-8 search hidden-xs">
                <div class="input">
                    <input type="text" class="form-control" placeholder="Search by store name, deal, coupon"/>
                    <i class="icon mc mc-search"></i>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container main-content submit-coupon-content">
    <h1 class="page-title font-quark">
        <strong class="text-success">Submit New Coupon</strong> to Us </h1>

    <div class="box rooow">
        <form class="submit-form gray-form col-sm-8" id="submit-box-form" method="post"
              action="<?php echo $this->Html->url(array('controller' => 'Coupons', 'action' => 'submitCoupon')) ?>">
            <div class="form-group">
                <input type="text" id="storeName" name="storeName" class="form-control required select-store"/>
            </div>
            <div class="form-group">
                <select class="form-control choice-category" name="category_id">
                    <option value="" selected>Choose a Category</option>
                    <?php foreach ($allCategories as $cate) : ?>
                        <option
                            value="<?php echo $cate['Category']['id'] ?>"><?php echo $cate['Category']['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="bubbles">
                <div id="btnCouponForm" class="bubble primary" onclick="addCouponForm()"> COUPON</div>
                <div id="btnDealForm" class="bubble" onclick="addDealForm()"> DEAL</div>
            </div>
            <div class="form-group">
                <input type="text" name="titleName" class="form-control submit-box-title required"
                       placeholder="Enter title coupon *"/>
            </div>
            <div class="form-group">
                            <textarea class="form-control required" name="description"
                                      placeholder="Description *"></textarea>
            </div>
            <div class="form-group coupon-field">
                <select name="coupon_type" class="form-control required">
                    <option value="Coupon Code">Coupon Code</option>
                    <option value="Free Shipping">Free Shipping</option>
                    <option value="Great Offer">Great Offer</option>
                </select>
            </div>
            <div class="form-group coupon-field">
                <input type="text" class="form-control required" name="yourCode"
                       placeholder="Enter your code *"/>
            </div>
            <div class="form-group coupon-field">
                <div class="input-group">
                    <select name="currency_coupon" class="form-control">
                        <option value="%">%</option>
                        <option value="$">$</option>
                        <option value="£">£</option>
                        <option value="¥">¥</option>
                        <option value="€">€</option>
                    </select>
                    <input type="text" class="form-control required auto-numeric" name="discount"
                           placeholder="Discount *"/>
                </div>
            </div>
            <div class="form-group deal-field">
                <select name="currency_deal" class="form-control required">
                    <option>$</option>
                    <option>£</option>
                    <option>¥</option>
                    <option>€</option>
                </select>
            </div>
            <div class="form-group deal-field deal-price">
                <div class="input-group">
                    <div class="input-group-addon">$</div>
                    <input type="text" placeholder="Origin price *"
                           name="origin_price" id="originPriceDeal"
                           onchange="autoCalculatePriceDeal(1)" class="form-control auto-numeric required">
                </div>
            </div>
            <div class="form-group deal-field deal-price">
                <div class="input-group">
                    <div class="input-group-addon">$</div>
                    <input type="text" placeholder="Discount price *"
                           name="discount_price" id="discountPriceDeal"
                           onchange="autoCalculatePriceDeal(2)" class="form-control auto-numeric required">
                </div>

            </div>
            <div class="form-group deal-field">
                <div class="input-group">
                    <div class="input-group-addon">%</div>
                    <input type="text" placeholder="Discount percent *"
                           name="discount_percent" id="discountPercentDeal"
                           onchange="autoCalculatePriceDeal(3)" class="form-control auto-numeric required"
                           data-v-max="100">
                </div>
            </div>
            <div class="form-group">
                <input type="text" class="form-control url image_url" name="image_url" placeholder="Enter image url"/>
            </div>
            <div class="form-group">
                <input type="text" class="form-control required url" name="product_link"
                       placeholder="Enter product link *"/>
            </div>
            <div class="form-group">
                <select class="form-control" name="event_id">
                    <option>Choose event (optional)</option>
                    <?php foreach ($events as $event) : ?>
                        <option
                            value="<?php echo $event['Event']['id'] ?>"><?php echo $event['Event']['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <input type="text" class="form-control start-date deal-field required" name="startDate"
                       placeholder="Start Date *"/>
            </div>
            <div class="form-group">
                <input type="text" class="form-control end-date" name="expireDate"
                       placeholder="Expire Date"/>
            </div>
            <div class="form-group">
                <div class="row col-sm-6">
                    <div class="g-recaptcha" data-sitekey="<?php echo $public_key ?>"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="row col-sm-12" style="margin-top: 10px;">
                    <button id="btn-submit-box" class="btn btn-primary" type="submit"> SEND US</button>
                </div>
            </div>
            <div class="form-group col-sm-12">
                <p class="notice">* Please submit only available Coupons and Deals which are allowed by the merchants.
                    You
                    can read the Terms and Conditions to get more information about contributing content of users. Thank
                    you
                    very much! </p>
            </div>
        </form>
        <div class="col-sm-4 golden-community hidden-xs">
            <div class="title"></div>
            <p>When you submit a coupon on
                <a href="<?php echo Configure::read('ShortUrl') ?>">MostCoupon.com</a>, not only do you help others save more money, but you also feel good to
                do
                it.</p>

            <div class="social">
                <a href="#" class="item">
                    <i class="mc mc-facebook"></i>
                </a>
                <a href="#" class="item">
                    <i class="mc mc-twitter"></i>
                </a>
                <a href="#" class="item">
                    <i class="mc mc-google-plus"></i>
                </a>
            </div>
            <ul>
                <li>
                    <i class="icon mc mc-check-circle-o"></i>
                    <span class="heading">Become the first to get the best deals</span>
                    <span>By setting customized e-mail alerts for selected stores and keywords</span>
                </li>
                <li>
                    <i class="icon mc mc-check-circle-o"></i>
                    <span class="heading">Speak thinking of you</span>
                    <span>Post comments on deals and blog posts</span>
                </li>
                <li>
                    <i class="icon mc mc-check-circle-o"></i>
                    <span class="heading">Share deals with the community</span>
                    <span>Find and share deals and coupons with friends</span>
                </li>
                <li>
                    <i class="icon mc mc-check-circle-o"></i>
                    <span class="heading">Build MostCoupon better</span>
                    <span>Help us gather the best coupons so everyone can save money!</span>
                </li>
            </ul>
            <div class="now">
                <a href="#" class="btn btn-block">JOIN NOW</a>
            </div>
        </div>
    </div>
</div>
<div class="container hidden-xs">
    <div class="store-list slider-box">
        <div class="caption"> OUR
            <strong>STORE</strong>
        </div>
        <div class="slider flexslider" data-flexslider-animation="slide" data-flexslider-animation-speed="2000"
             data-flexslider-control-nav="false" data-flexslider-direction-nav="true"
             data-flexslider-selector=".slides .slide" data-flexslider-item-width="95">
            <div class="slides">
                <?php foreach ($stores['stores'] as $store) : ?>
                    <a href="<?php echo $this->Html->url('/') . $store['Store']['alias'] ?>-coupons"
                       class="slide"
                       style="background-image: url('<?php echo $store['Store']['logo'] ?>')"></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        function repoFormatResult(repo) {
            var markup = "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__title'>" + repo.name + "</div>";

            markup += "<div class='select2-result-repository__description'>" + repo.store_url + "</div>";
            markup += "</div>";
            return markup;
        }

        function repoFormatSelection(repo) {
            return repo.store_url;
        }

        $(".select-store").select2({
            placeholder: "http://www.store.com *",
            minimumInputLength: 2,
            // instead of writing the function to execute the request we use Select2's convenient helper
            ajax: {
                url: "<?php echo $this->Html->url(array('controller' => 'Stores', 'action' => 'listStoreToSubmit')) ?>",
                dataType: "json",
                quietMillis: 1000,
                delay: 1000,
                data: function (term, page) {
                    return {
                        // search term
                        q: term
                    };
                },
                results: function (data, page) {
                    // parse the results into the format expected by Select2.
                    // since we are using custom formatting functions we do not need to alter the remote JSON data
                    return {results: data.items};
                },
                cache: true
            },
            initSelection: function (element, callback) {
                // the input tag has a value attribute preloaded that points to a preselected repository's id
                // this function resolves that id attribute to an object that select2 can render
                // using its formatResult renderer - that way the repository name is shown preselected
                var value = $(element).val();
                if (value !== '' && (/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test(value))) {
                    callback({"id": value, "name": value, "store_url": value});
                }
            },
            formatResult: repoFormatResult,
            formatSelection: repoFormatSelection,
            formatNoMatches: function (term) {
                return "<div class='input-group'>" + "<input type='text' class='form-control' id='newTerm' value='" + term + "'>" + "<span class='input-group-btn'>" + "<a id='addNew' class='btn btn-primary'><i class='fa fa-plus'></i></a>" + "</span>" + "</div>";
            }
        });
        $('.select2-with-searchbox').on('click', '#addNew', function () {
            /* add the new term */
            var newTerm = $('#newTerm').val();
            //alert('adding:'+newTerm);
            $('.select-store').select2('val', newTerm); // select the new term
            $(".select-store").select2('close');		// close the dropdown
        });
        var $form = $('#submit-box-form');
        var addCouponDealValidator = $form.validate({
            errorElement: "span", // contain the error msg in a small tag
            errorClass: 'help-block myErrorClass',
            focusInvalid: false,
            invalidHandler: function (form, validator) {
                if (!validator.numberOfInvalids())
                    return;
                $('html, body').animate({
                    scrollTop: $('#submit-box-form').offset().top - 100//$(validator.errorList[0].element).offset().top
                }, 1000);

            },
            errorPlacement: function (error, element) { // render error placement for each input type
                if (element.attr("type") == "radio" || element.attr("type") == "checkbox" || element.attr("type") == "file") { // for chosen elements, need to insert the error after the chosen container
                    error.insertAfter($(element).closest('.form-group').children('div').children().last());
                } else if (element.hasClass("ckeditor")) {
                    error.appendTo($(element).closest('.form-group'));
                } else if (element.parent().hasClass("input-group")) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                    // for other inputs, just perform default behavior
                }
            },
            highlight: function (element, errorClass, validClass) {
                var elem = $(element);
                if (elem.hasClass("select2-offscreen")) {
                    $("#s2id_" + elem.attr("id") + " ul").addClass(errorClass);
                } else {
                    $(element).closest('.help-block').removeClass('valid');
                    // display OK icon
                    $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
                    // add the Bootstrap error class to the control group
                }
            },
            unhighlight: function (element, errorClass, validClass) {
                // revert the change done by hightlight
                var elem = $(element);
                if (elem.hasClass("select2-offscreen")) {
                    $("#s2id_" + elem.attr("id") + " ul").removeClass(errorClass);
                } else {
                    $(element).closest('.form-group').removeClass('has-error');
                    // set error class to the control group
                }
            },
            success: function (label, element) {
                label.addClass('help-block valid');
                // mark the current input as valid and display OK icon
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
            }
        });

        $form.on('submit', function (e) {
            e.preventDefault();
            if ($form.valid()) {
                $('#btn-submit-box').empty().append("<i class='fa fa-spinner fa-pulse'></i>").addClass('disabled');
                $.ajax({
                    type: 'post',
                    url: $form.attr('action'),
                    data: $form.serialize(),
                    success: function (data) {
                        if (data.status == 'success') {
                            $("<div class='alert alert-success' role='alert'>" + data.msg + "</div>").insertBefore($('#btn-submit-box'));
                            addCouponDealValidator.resetForm();
                            $form[0].reset();
                        } else if (data.status == 'error') {
                            $("<div class='alert alert-danger' role='alert'>" + data.msg + "</div>").insertBefore($('#btn-submit-box'));
                        }
                        $('#btn-submit-box').empty().text("SEND US").removeClass('disabled');
                    }
                });
            }
        });
        $('.end-date').datepicker('setStartDate', new Date());
        $('.start-date').datepicker().on('changeDate', function (selected) {
            var startDate = $(selected.currentTarget).val();
            var endDate = $(selected.currentTarget).parent().next('div.form-group').find('.end-date')[0];
            if (startDate > new Date()) {
                $(endDate).datepicker('setStartDate', startDate);
            }
            if (startDate > $(endDate).val()) {
                $(endDate).datepicker('update', startDate);
            }
        });
        $('.end-date').datepicker().on('changeDate', function (selected) {
            var endDate = $(selected.currentTarget).val();
            var startDate = $(selected.currentTarget).parent().prev('div.form-group').find('.start-date')[0];
            $(startDate).datepicker('setEndDate', endDate);
            if ($(startDate).val() > endDate) {
                $(startDate).datepicker('update', endDate);
            }
        });

        $('.deal-field select').on('change', function () {
            var cur = $(this).val();
            $('.deal-price .input-group-addon').text(cur);
        });
        $('#currency-coupon').on('change', function () {
            var cur = $(this).val();
            if (cur == '%') {
                var cur_val = $(this).next('input.auto-numeric').autoNumeric('get');
                if (cur_val > 100) {
                    $(this).next('input.auto-numeric').autoNumeric('set', 100);
                }
                $(this).next('input.auto-numeric').autoNumeric('update', {vMax: 100});
            } else $(this).next('input.auto-numeric').autoNumeric('update', {vMax: 999999999.99});
        });
    });
    function autoCalculatePriceDeal(onChangeIndex) {
        var x = $('#originPriceDeal').autoNumeric('get');
        var y = $('#discountPriceDeal').autoNumeric('get');
        var z = $('#discountPercentDeal').autoNumeric('get');
        if (x.length > 0) {
            if (y.length > 0 && (onChangeIndex == 1 || onChangeIndex == 2)) {
                $('#discountPercentDeal').autoNumeric('set', ((x - y) / x) * 100);
            } else if (z.length > 0 && (onChangeIndex == 1 || onChangeIndex == 3)) {
                $('#discountPriceDeal').autoNumeric('set', ((100 - z ) * x) / 100);
            }
        }
    }
    function addCouponForm() {
        $('.submit-box .header .text').text('SUBMIT COUPON');
        $('.submit-box-title').attr('placeholder', 'Enter title coupon');
        $('#btnCouponForm').addClass('primary');
        $('#btnDealForm').removeClass('primary');
        $('#submit-box-form .coupon-field').show();
        $('#submit-box-form .deal-field').hide();
        $('#submit-box-form .choice-category').rules('remove');
        $('#submit-box-form .choice-category option:first-child').removeAttr('disabled');
        $('#submit-box-form .image_url').attr('placeholder', 'Enter image url');
        $('#submit-box-form .image_url').rules('remove');
        $('#submit-box-form .end-date').attr('placeholder', 'Expire Date');
        $('#submit-box-form .end-date').rules('remove');
        $('#submit-box-form').attr('action', "<?php echo $this->Html->url(array('controller' => 'Coupons', 'action' => 'submitCoupon')) ?>");
    }

    function addDealForm() {
        $('.submit-box .header .text').text('SUBMIT DEAL');
        $('.submit-box-title').attr('placeholder', 'Enter title deal');
        $('#btnCouponForm').removeClass('primary');
        $('#btnDealForm').addClass('primary');
        $('#submit-box-form .deal-field').show();
        $('#submit-box-form .coupon-field').hide();
        $('#submit-box-form .choice-category').rules('add', {required: true});
        $('#submit-box-form .choice-category option:first-child').attr('disabled', 'disabled');
        $('#submit-box-form .image_url').attr('placeholder', 'Enter image url *');
        $('#submit-box-form .image_url').rules('add', {required: true});
        $('#submit-box-form .end-date').attr('placeholder', 'Expire Date *');
        $('#submit-box-form .end-date').rules('add', {required: true});
        $('#submit-box-form').attr('action', "<?php echo $this->Html->url(array('controller' => 'Deals', 'action' => 'submitDeal')) ?>");
    }
</script>