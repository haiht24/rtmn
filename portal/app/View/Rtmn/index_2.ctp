<?php $this->Ng->ngController('Rtmn2Ctrl') ?>
<script type="text/javascript">
    $(document).ready(function($){
        var TARGET = 'http://www.toolsvoid.com/url-dump';
        var PROCESS_URL = 'http://www.retailmenot.com/view/target.com';
        var data =
        {
            urladdr : PROCESS_URL
        };

        $.ajax({
            type : 'POST',
            url : TARGET,
            data: data,
            success:function (returnData) {
                $("#result").append(returnData);
                var e = $('.myarea').eq(1).html();
                var sendData = {'send' : e, 'rtmn_url' : PROCESS_URL, 'target' : TARGET};

                $.post("<?php echo $this->Html->url(['controller' => 'Rtmn', 'action' => 'start']) ?>"
                ,sendData, function(rs) {
                    $("#result").html(rs);
                    // Add to db
                    if(rs != '"error"'){
                        var postData = {rs : rs};
                        $.post("<?php echo $this->Html->url(['controller' => 'RtmnStores', 'action' => 'add']) ?>"
                        ,postData, function(res) {
                            $("#result").html(res);
                        });

                    }else{
                        $("#result").html('Bot detected');
                    }

                });
            }
        });
    })
</script>

<div id="result"></div>

<div id="result2"></div>

