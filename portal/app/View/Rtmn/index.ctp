<?php $this->Ng->ngController('RtmnCtrl') ?>
<style>
div.scroll {width: auto;height: 100px;overflow: scroll;}
</style>
<script type="text/javascript">
    $(document).ready(function($){
        var RTMN_START = "<?php echo $this->Html->url(['controller' => 'Rtmn', 'action' => 'start']) ?>";
        var RTMNSTORES_LOADURL = "<?php echo $this->Html->url(['controller' => 'RtmnStores', 'action' => 'loadUrl']) ?>";
        var RTMNSTORES_ADD = "<?php echo $this->Html->url(['controller' => 'RtmnStores', 'action' => 'add']) ?>";
        var RTMN_GETCATS = "<?php echo $this->Html->url(['controller' => 'Rtmn', 'action' => 'processCategories']) ?>";
        /*Get Stores from cats*/
        $('#btnGetStoresFromCats').click(function(){
             $('#btnGetStoresFromCats').attr('disabled','disabled');
            $.post(RTMN_GETCATS, function(resp) {
                $('#btnGetStoresFromCats').removeAttr('disabled');
                console.log(resp);
            })
        })
        /*Load store urls*/
        $('#btnLoadUrls').click(function(){
            $('#btnLoadUrls').attr('disabled','disabled');
            $('#divUrls').empty();

            $.post(RTMNSTORES_LOADURL, function(resp) {
                $('#btnLoadUrls').removeAttr('disabled');
                resp = $.parseJSON(resp);
                if(resp.length > 0){
                    $.each(resp, function(k ,v){
                        // Append rtmn urls to div
                        $('#divUrls').append(
                            '<input class="url" type = "text" style="width:300px" readonly value="' + v.RtmnUrls.url + '" id="' + v.RtmnUrls.id + '" />'
                        );
                    })
                    console.log(resp);
                }
            })
        })
        /*Start server 1*/
        $('#btnStartServer1').click(function(){
            $('#btnStartServer1').attr('disabled','disabled');
            server_1($('.url'));
        })
        /*Start server 2*/
        $('#btnStartServer2').click(function(){
            $('#btnStartServer2').attr('disabled','disabled');
            server_2($('.url'));
        })

        // if(server_1('www.retailmenot.com/view/target.com') == 'Bot detected'){
        //     console.log('Server 1 blocked');
        //     if(server_2() == 'Bot detected'){
        //         console.log('Server 2 blocked');
        //         return;
        //     }
        // }

        function addToDb(rs, inputObj, server){
            var postData = {rs : rs};
            $.post(RTMNSTORES_ADD, postData, function(res) {
                $("#result").html(res);
                // remove input
                console.log(inputObj.attr('id'));
                $('#' + inputObj.attr('id')).remove();
                // Set interval
                if($('.url')){
                    if(server == 1){
                        setTimeout(function(){
                            server_1($('.url'));
                        }, 3000);
                    }else if(server == 2){
                        setTimeout(function(){
                            server_2($('.url'));
                        }, 3000);
                    }

                }

            });
        }

        /*Server 1*/
        function server_1(inputObj){
            $('#log').append('Server 1 : ' + inputObj.val() + '<br>');
            var TARGET = 'http://cousinisaac.com/mobitol/index.php';
            var PROCESS_URL = inputObj.val();
            var data =
            {
                url : PROCESS_URL,
                TextType : 'html'
            };

            $.ajax({
                type : 'POST',
                url : TARGET,
                data: data,
                success:function (rs) {
                    $("#result").html(rs);
                },
                complete:function (rs) {
                    var e = $('#textfield').html();
                    var sendData = {'send' : e, 'rtmn_url' : PROCESS_URL, 'target' : TARGET};

                    $.post(RTMN_START, sendData, function(rs) {
                        // Add to db
                        if(rs != '"error"'){
                            addToDb(rs, inputObj, 1);
                        }else{
                            $("#result").html('Bot detected');
                            return 'Bot detected';
                        }

                    });

                }
            });
        }
        /*End server 1*/

        /*Server 2*/
        function server_2(inputObj){
            $('#log').append('Server 2 : ' + inputObj.val() + '<br>');
            var TARGET = 'http://www.toolsvoid.com/url-dump';
            var PROCESS_URL = inputObj.val();
            var data =
            {
                urladdr : PROCESS_URL
            };

            $.ajax({
                type : 'POST',
                url : TARGET,
                data: data,
                success:function (returnData) {
                    $("#result").html(returnData);
                    var e = $('.myarea').eq(1).html();
                    if(!e){
                        $("#result").html('Server 2 blocked');
                        return;
                    }
                    var sendData = {'send' : e, 'rtmn_url' : PROCESS_URL, 'target' : TARGET};
                    $.post(RTMN_START, sendData, function(rs) {
                        // Add to db
                        if(rs != '"error"'){
                            addToDb(rs, inputObj, 2);
                        }else{
                            $("#result").html('Bot detected');
                            return 'Bot detected';
                        }

                    });
                }
            });
        }
        /*End server 2*/

        /*Server 3 : http://www.webtoolhub.com/tn561362-html-source-viewer.aspx*/

        /*End server 3*/

        /*Server 4 : http://www.serversfree.com/free-seo-tools/view-source-code/*/

        /*End server 4*/

    })
</script>
<input type="button" id = "btnGetStoresFromCats" value="Get From Cats" class="btn btn-primary" />
<input type="button" id = "btnLoadUrls" value="Load Urls" class="btn btn-primary" />

<input type="button" id = "btnStartServer1" value="Start server 1" class="btn btn-danger" />
<input type="button" id = "btnStartServer2" value="Start server 2" class="btn btn-danger" />
<p></p>
<div id="log" class="scroll"></div>
<p></p>
<div id="divUrls" class="scroll"></div>

<div id="result"></div>