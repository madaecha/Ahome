<?php echo form_open('forgot_password', array('method' => 'post', 'data-parsley-validate' => 'true', 'id' => 'forgot-password')); ?>
<div class="from-group">
    <label><?php echo $this->lang->line('email'); ?></label>
    <input data-parsley-required="true" name="email" placeholder="<?php echo $this->lang->line('email_placeholder'); ?>" type="email">
</div>
<hr>
<button type="submit" class="btn pull-right"><?php echo $this->lang->line('submit'); ?></button>
<?php echo form_close(); ?>

<script>
    $(document).ready(function() {
        $('#forgot-password').parsley();
    });
</script>