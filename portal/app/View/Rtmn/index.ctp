<?php $this->Ng->ngController('RtmnCtrl') ?>
<script type="text/javascript">
    $(document).ready(function($){
        var TARGET = 'http://cousinisaac.com/mobitol/index.php';
        var PROCESS_URL = 'http://www.retailmenot.com/view/target.com';
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
                $("#result").append(rs);
            },
            complete:function (rs) {
                var e = $('#textfield').html();
                var sendData = {'send' : e, 'rtmn_url' : PROCESS_URL, 'target' : TARGET};

                $.post("<?php echo $this->Html->url(['controller' => 'Rtmn', 'action' => 'start']) ?>"
                ,sendData, function(rs) {
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