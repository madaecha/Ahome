<?php echo form_open('currency_settings/update/' . $param2, array('id' => 'edit_currency', 'method' => 'post', 'data-parsley-validate' => 'true')); ?>
<div class="form-group">
	<label><?php echo $this->lang->line('name'); ?> *</label>
	<input value="<?php echo html_escape($this->db->get_where('currency', array('currency_id' => $param2))->row()->name); ?>" type="text" name="name" placeholder="<?php echo $this->lang->line('currency_name_placeholder'); ?>" class="form-control" data-parsley-required="true">
</div>
<div class="form-group">
	<label><?php echo $this->lang->line('symbol'); ?> *</label>
	<input value="<?php echo html_escape($this->db->get_where('currency', array('currency_id' => $param2))->row()->code); ?>" type="text" name="code" placeholder="<?php echo $this->lang->line('currency_symbol_placeholder'); ?>" class="form-control" data-parsley-required="true">
</div>

<button type="submit" class="mb-sm btn btn-primary"><?php echo $this->lang->line('update'); ?></button>
<?php echo form_close(); ?>

<script>
	$('#edit_currency').parsley();
</script>