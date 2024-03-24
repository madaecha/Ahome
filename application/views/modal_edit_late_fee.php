<?php echo form_open('invoices/update_late_fee/' . $param2, array('id' => 'edit_late_fee', 'method' => 'post', 'data-parsley-validate' => 'true')); ?>
<div class="form-group">
    <label><?php echo $this->lang->line('due_date'); ?></label>
    <input value="<?php echo date('m/d/Y', $this->db->get_where('invoice', array('invoice_id' => $param2))->row()->due_date); ?>" type="text" id="datepicker-default" name="due_date" placeholder="<?php echo $this->lang->line('due_date'); ?>" class="form-control">
</div>
<div class="form-group">
    <label><?php echo $this->lang->line('late_fee'); ?> (<?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>)</label>
    <input value="<?php echo html_escape($this->db->get_where('invoice', array('invoice_id' => $param2))->row()->late_fee); ?>" type="text" name="late_fee" placeholder="<?php echo $this->lang->line('enter_late_fee'); ?>" class="form-control" data-parsley-type="number">
</div>

<button type="submit" class="mb-sm btn btn-primary"><?php echo $this->lang->line('update'); ?></button>
<?php echo form_close(); ?>

<script>
    $('#edit_late_fee').parsley();
    FormPlugins.init();
</script>