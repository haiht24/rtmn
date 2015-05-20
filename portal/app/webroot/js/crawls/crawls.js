
$(document).ready(function(){
        // Clear data from div "rs"
        $('#btnClearData').click(function(){
            $('#output').empty();
            $('#lblMess').empty();
        })
        // Delete row
        $(document).on('click', '.del', function(){
            var id = $(this).attr('id');
            var ipId = id.split('-');
            $('#' + ipId[1]).fadeOut(500, function(){$('#' + ipId[1]).remove()})
            $(this).fadeOut(500, function(){$(this).remove()});
        })
        // Delete wrap
        $(document).on('click', '.delWrap', function(){
            var id = $(this).attr('id');
            var ipId = id.split('-');
            $('#' + ipId[1]).remove();
            $(this).remove();
        })
        // Add to database
        $(document).on('click', '.addDb', function(){
            var struct =  $(this).attr('struct');
            var arrStruct = struct.split('|');
            var controller = arrStruct[0];
            var columnName = arrStruct[1];
            $.post('Category/addByJq', {name : 'demo category'}, function(dt){
                console.log(dt);
            }, 'json');

        })
        // Select Type
        $('#slType').change(function(){
           if(this.value == 'category'){
                $('.tmpCategory').show();
                $('.tmpStore').hide();
           }else if(this.value == 'store'){
                $('.tmpStore').show();
                $('.tmpCategory').hide();
           }
        })
        // Crawl
        $('#btnCrawl').click(function(){
            $('#btnCrawl').text('Crawling ...');
            if($('#slType').val() == 'category'){
                 $.ajax({
                    type : 'POST',
                    dataType : 'json',
                    //url : 'Crawls/crawlCategory',
                    url : crawlCategory,
                    data : {
                        url : $('#ipUrl').val(),
                        parentClass : $('#ipParentClass').val(),
                        homePage : $('#homepage').val()
                    },
                    success : function(data){
                        if(data['cat'].length > 0){
                            $('#lblMess').text(data['cat'].length + ' rows');
                            $('#lblMess').show();

                            var divName = "<div class='rsName' style='height: 200px; overflow-y: scroll;'><br><button id='insertCatName' class='btn btn-danger'>Add to Database</button><br></div>";
                            $('#output').append(divName);
                            for(i = 0; i < data['cat'].length; i++){
                                singleResult = "<input class='cat_name input-xs' id='name_" + i + "' value='" + data['cat'][i] + "'"
                                + ">" + "<button class='del' id='del-name_" + i + "'>x</button>";
                                $('.rsName').append(singleResult);
                            }
                        }
                        $('#btnCrawl').text('Crawl');
                    }
                })
            }
            if($('#slType').val() == 'store'){
                $.ajax({
                    type : 'POST',
                    dataType : 'json',
                    //url : 'Crawls/crawlStore',
                    url : crawlStore,
                    data : {
                        url : $('#ipUrl').val(),
                        parentClass : $('#ipParentClass').val(),
                        classStoreName : $('#cl_storeName').val(),
                        classStoreUrl : $('#cl_storeUrl').val(),
                        homePage : $('#homepage').val()
                    },
                    success : function(data){
//                        if(data['stName'].length > 0){
//                            $('#lblMess').text(data['stName'].length + ' rows');
//                            $('#lblMess').show();
//
//                            var divName = "<div class='rsStore' style='height: 200px; overflow-y: scroll;'><br><button id='insertStore' class='btn btn-danger'>Add to Database</button><br></div>";
//                            $('#output').append(divName);
//
//                            for(i = 0; i < data['stName'].length; i++){
//                                singleResult = "<input class='store_name input-xs' id='store_" + i + "' value='" + data['stName'][i] + "'"
//                                + ">" + "<button class='del' id='del-store_" + i + "'>x</button>";
//                                $('.rsStore').append(singleResult);
//                            }
//                        }
//                        $('#btnCrawl').text('Crawl');
                        if(data.length > 0){
                            $('#lblMess').text(data.length + ' rows');
                            $('#lblMess').show();
                            var divName = "<div class='rsStore' style='height: 200px; overflow-y: scroll;'><br><button id='insertStore' class='btn btn-danger'>Add to Database</button><br></div>";
                            $('#output').append(divName);

                            for(i = 0; i < data.length; i++){
                                singleResult = "<input class='store_name input-xs' id='store_" + i + "' value='" + data[i] + "'"
                                + ">" + "<button class='del' id='del-store_" + i + "'>x</button>";
                                $('.rsStore').append(singleResult);
                            }
                        }
                        $('#btnCrawl').text('Crawl');
                    },
                    timeout : 999999999
                })
            }
        })

        // Button Add Category
        $(document).on('click', '#insertCatName', function(){
            workerAddCategory();
        })
        // Button Add Store
        $(document).on('click', '#insertStore', function(){
            workerAddStore();
        })
        // Homepage
        $('#ipUrl').blur(function(){
            var strInputUrl = $('#ipUrl').val();
            if(!strInputUrl){
                return false;
            }
            strInputUrl = strInputUrl.split('/');
            var homepage = strInputUrl[0] + '//' + strInputUrl[2];
            $('#homepage').val(homepage);
        })
        // Load stores
        $('#btnLoadStore').click(function(){
            $.ajax({
                type : 'POST',
                dataType : 'json',
                //url : 'Wpposts/loadStores',
                url : loadStores,
                data : {database : $('#slDb').val()},
                success : function(data){
                    $('#output').append(data);
                    if(data.length > 0){
                        for (i=0; i < data.length; i++) {
                            var ipPendingStore = "<input type='text' class='store' id='" + data[i]['ID']
                            + "' value='" + data[i]['post_title'] + "'"
                            + "class_store_desc_metadata = '" + data[i]['class_store_desc_metadata'] + "'"
                            + "class_store_homepage_metadata = '" + data[i]['class_store_homepage_metadata'] + "'"
                            + "class_store_logo_metadata = '" + data[i]['class_store_logo_metadata'] + "'"
                            + "store_url = '" + data[i]['store_url_metadata'] + "'"
                            + "class_coupon_parent_metadata = '" + data[i]['class_coupon_parent_metadata'] + "'"
                            + "class_coupon_title_metadata = '" + data[i]['class_coupon_title_metadata'] + "'"
                            + "class_coupon_code_metadata = '" + data[i]['class_coupon_code_metadata'] + "'"
                            + "class_coupon_desc_metadata = '" + data[i]['class_coupon_desc_metadata'] + "'"
                            + "class_coupon_expire_metadata = '" + data[i]['class_coupon_expire_metadata'] + "'"
                            + "class_store_breadcrumb_metadata = '" + data[i]['class_store_breadcrumb_metadata'] + "'"
                            +">";
                            $('#output').append(ipPendingStore);
                            $('#btnGetStoreInfo').prop('disabled', false);
                        }
                    }
                }
            })
        })
        // Get store information
        $('#btnGetStoreInfo').click(function(){
            workerGetCoupon();
        })
        // worker get store info and get coupons
        function workerGetCoupon(){
            var storeId = $('.store').attr('id');
            var storeUrl = $('.store').attr('store_url');
            var classStoreLogo = $('.store').attr('class_store_logo_metadata');
            var classStoreHomepage = $('.store').attr('class_store_homepage_metadata');
            var classStoreDescription = $('.store').attr('class_store_desc_metadata');

            var classCouponParent = $('.store').attr('class_coupon_parent_metadata');
            var classCouponTitle = $('.store').attr('class_coupon_title_metadata');
            var classCouponCode = $('.store').attr('class_coupon_code_metadata');
            var classCouponDescription = $('.store').attr('class_coupon_desc_metadata');
            var classCouponExpire = $('.store').attr('class_coupon_expire_metadata');
            var classStoreBreadcrumb = $('.store').attr('class_store_breadcrumb_metadata');
            if(storeId){
                $.ajax({
                    type : 'POST',
                    //dataType : 'json',
                    //url : 'Wpposts/getStoreInfo',
                    url : getStoreInfo,
                    data : {
                        database : $('#slDb').val(),
                        storeId : storeId,
                        storeUrl : storeUrl,
                        classStoreLogo : classStoreLogo,
                        classStoreHomepage : classStoreHomepage,
                        classStoreDescription : classStoreDescription,

                        classCouponParent : classCouponParent,
                        classCouponTitle : classCouponTitle,
                        classCouponCode : classCouponCode,
                        classCouponDescription : classCouponDescription,
                        classCouponExpire : classCouponExpire,
                        classStoreBreadcrumb : classStoreBreadcrumb
                    },
                    success : function(data){
                        console.log(data);
                    },
                    complete : function(){
                        $('#' + storeId).remove();
                        setTimeout(workerGetCoupon, 0);
                    }
                })
            }
        }
        // worker add category
        function workerAddCategory(){
            var strCatNameAndUrl = $('.cat_name').val();
            var strCatNameAndUrl = strCatNameAndUrl.split('|');
            var catName = strCatNameAndUrl[0];
            var catUrl = strCatNameAndUrl[1];

            var inputCatId = $('.cat_name').attr('id');
            if(inputCatId){
                $.ajax({
                    type: 'POST',
                    //url: 'WpTerms/add',
                    url : addTerm,
                    data: {catName : catName, catUrl : catUrl, database : $('#slDb').val()},
                    dataType : 'json',
                    success: function(rs) {
                        console.log(rs);
                    },
                    complete: function() {
                        $('#' + inputCatId).remove();
                        $('#del-' + inputCatId).remove();
                        setTimeout(workerAddCategory, 0);
                    }
                });
            }
        }
        function workerAddStore(){
            var strStoreNameAndUrl = $('.store_name').val();
            strStoreNameAndUrl = strStoreNameAndUrl.split('|');
            var storeName = strStoreNameAndUrl[0];
            var url = strStoreNameAndUrl[1];
            // Add classes to get store info later
            var classForDesc = $('#cl_storeDesc').val();
            var classForHomepage = $('#cl_storeHomepage').val();
            var classForLogo = $('#cl_storeLogo').val();
            // Add classes to get coupon info later
            var classForCouponParent = $('#clCouponParentClass').val();
            var classForCouponTitle = $('#clCouponTitle').val();
            var classForCouponCode = $('#clCouponCode').val();
            var classForCouponDesc = $('#clCouponDesc').val();
            var classForCouponExpire = $('#clCouponExpire').val();
            var classForBreadcrumb = $('#cl_storeBreadcrumb').val();

            var input = $('.store_name').attr('id');
            var btnDel = $('.del').attr('id');
            if(input){
                $.ajax({
                    type: 'POST',
                    //url: 'WpPosts/addByJq',
                    url : addPostByJq,
                    data: {
                        database : $('#slDb').val(),
                        post_title : storeName, url : url,
                        classForDesc : classForDesc,
                        classForHomepage : classForHomepage,
                        classForLogo : classForLogo,
                        classForCouponParent : classForCouponParent,
                        classForCouponTitle : classForCouponTitle,
                        classForCouponCode : classForCouponCode,
                        classForCouponDesc : classForCouponDesc,
                        classForCouponExpire : classForCouponExpire,
                        classForBreadcrumb : classForBreadcrumb
                    },
                    dataType : 'json',
                    success: function(rs) {
                        console.log(rs);
                        $('#divMessage').append(rs + "<br/>");
                    },
                    complete: function() {
                        $('#' + input).remove();
                        $('#' + btnDel + '.del').remove();
                        setTimeout(workerAddStore, 0);
                    }
                });
            }
        }
        // Clear all input text
        $('#btnClearInput').click(function(){
            $('input').val('');
        })
        // Save Config
        $('#btnSaveConfig').click(function(){
            $.ajax({
                type: 'POST',
                url : ctrlCrawl + '/saveCrawlConfig',
                data: {
                    dbName : $('#slDb').val(),
                    URL : $('#ipUrl').val(),
                    homePage : $('#homepage').val(),
                    parentClass : $('#ipParentClass').val(),
                    type : $('#slType').val(),
                    clStoreName : $('#cl_storeName').val(),
                    clStoreURL : $('#cl_storeUrl').val(),
                    clStoreDesc : $('#cl_storeDesc').val(),
                    clStoreHome : $('#cl_storeHomepage').val(),
                    clStoreLogo : $('#cl_storeLogo').val(),
                    clBreadcrumb : $('#cl_storeBreadcrumb').val(),
                    clCpParent : $('#clCouponParentClass').val(),
                    clCpTitle : $('#clCouponTitle').val(),
                    clCpCode : $('#clCouponCode').val(),
                    clCpDesc : $('#clCouponDesc').val(),
                    clCpExpire : $('#clCouponExpire').val()
                },
                dataType : 'json',
                success: function(rs) {
                    console.log(rs);
                },
                complete: function() {
                }
            });
        })
        // Load config
        $('#btnLoadConfig').click(function(){
            $.ajax({
                type: 'POST',
                url : ctrlCrawl + '/loadCrawlConfig',
                data: {
                    dbName : $('#slDb').val(),
                    type : $('#slType').val()
                },
                dataType : 'json',
                success: function(rs) {
                    console.log(rs);

                    $('#ipUrl').val(rs['crawlConfig']['database']['url']);
                    $('#homepage').val(rs['crawlConfig']['database']['homePage']);
                    $('#ipParentClass').val(rs['crawlConfig']['database']['parentClass']);
                    $('#cl_storeName').val(rs['crawlConfig']['database']['clStoreName']);
                    $('#cl_storeUrl').val(rs['crawlConfig']['database']['clStoreURL']);
                    $('#cl_storeDesc').val(rs['crawlConfig']['database']['clStoreDesc']);
                    $('#cl_storeHomepage').val(rs['crawlConfig']['database']['clStoreHome']);
                    $('#cl_storeLogo').val(rs['crawlConfig']['database']['clStoreLogo']);
                    $('#cl_storeBreadcrumb').val(rs['crawlConfig']['database']['clBreadcrumb']);
                    $('#clCouponParentClass').val(rs['crawlConfig']['database']['clCpParent']);
                    $('#clCouponTitle').val(rs['crawlConfig']['database']['clCpTitle']);
                    $('#clCouponCode').val(rs['crawlConfig']['database']['clCpCode']);
                    $('#clCouponDesc').val(rs['crawlConfig']['database']['clCpDesc']);
                    $('#clCouponExpire').val(rs['crawlConfig']['database']['clCpExpire']);
                },
                complete: function() {
                }
            });
        })
    })