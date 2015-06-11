<?php $this->Ng->ngController('RtmnCtrl') ?>
<script type="text/javascript">
    $(document).ready(function($){
        // $('#start').click(function(){
        //     var textfield = $('#site-title').html();
        //     console.log(textfield);
        // });
        var TARGET = 'http://cousinisaac.com/mobitol/index.php';
        // var PROCESS_URL = 'www.retailmenot.com/view/target.com';
        var PROCESS_URL = 'http://www.retailmenot.com/view/bestbuy.com';


        $.ajax({
            type : 'POST',
            url : TARGET,
            data: {
                url   : PROCESS_URL,
                TextType : 'html'
            },
            success:function (data) {
                $("#result").append(data);
            },
            complete:function (data) {
                var e = $('#textfield').html();
                var sendData = {'send' : e, 'rtmnURL' : PROCESS_URL};

                $.post("<?php echo $this->Html->url(['controller' => 'Rtmn', 'action' => 'start']) ?>",
                sendData, function(rs) {
                    $("#result").html(rs);
                });
            }
        });
    })
</script>
<button id="start" class="btn btn-primary">Start</button>

<div id="result"></div>

<div id="result2"></div>