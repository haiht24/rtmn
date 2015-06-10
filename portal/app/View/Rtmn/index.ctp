<?php $this->Ng->ngController('RtmnCtrl') ?>
<script type="text/javascript">
    $(document).ready(function($){
        $('#start').click(function(){
            var textfield = $('#site-title').html();
            console.log(textfield);
        });

        // $.post("<?php echo $this->Html->url(['controller' => 'Rtmn', 'action' => 'start']) ?>",
        // function(rs) {
        //     $("#result").html(rs);
        // });

        $.ajax({
            type : 'POST',
            url : 'http://cousinisaac.com/mobitol/index.php',
            data: {
                url   : 'www.retailmenot.com/view/target.com',
                TextType : 'html'
            },
            // dataType : 'jsonp',
            success:function (data) {
                $("#result").append(data);
            },
            complete:function (data) {
                // console.log(data.responseText);
                // $("#result").append(data.responseText);
                var e = $('#textfield').html();
                // alert(e);
                // $("#result").html(e);

                $.post("<?php echo $this->Html->url(['controller' => 'Rtmn', 'action' => 'start']) ?>",
                {'send' : e}, function(rs) {
                    $("#result").html(rs);
                    // alert(rs);
                });
            }
        });


    })
</script>
<button id="start" class="btn btn-primary">Start</button>

<div id="result"></div>

<div id="result2"></div>