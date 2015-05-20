<?php $class = empty($class) ? 'info' : $class; ?>
<div class="message-new <?php echo $class ?>">
    <i class="ui-icon-checkmark"></i>
    <div class="message-content">
        <h5>
            <?php if ($class == 'info') { echo __('Information'); } ?>
            <?php if ($class == 'error') { echo __('Error'); } ?>
            <?php if ($class == 'success') { echo __('Success'); } ?>
        </h5>
        <p><?php echo $message ?></p>
    </div>
    <a class="right ui-icon-cancel" onclick="$(this).parents('.message-new').hide(300, 'swing', function () {$(this).remove()});"></a>
</div>