<?php $this->Ng->ngController('RtmnCtrl') ?>
<script type="text/javascript">
    $(document).ready(function($){
        $('#start').click(function(){
        });
        $.post("<?php echo $this->Html->url(['controller' => 'Rtmn', 'action' => 'start']) ?>",
        function(rs) {
            $("#result").html(rs);
        });
    })
</script>

<button id="start" class="btn btn-primary">Start</button>
<div id="result"></div>
