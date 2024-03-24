<?php echo form_open('properties/update/' . $param2, array('id' => 'edit_property', 'method' => 'post', 'data-parsley-validate' => 'true')); ?>
<?php $query = $this->db->get_where('property', array('property_id' => $param2)); ?>

<div class="form-group">
	<label><?php echo $this->lang->line('name'); ?> *</label>
	<input value="<?php echo $query->row()->name; ?>" type="text" name="name" placeholder="<?php echo $this->lang->line('property_name_placeholder'); ?>" data-parsley-required="true" class="form-control">
</div>
<div class="form-group">
	<label><?php echo $this->lang->line('address'); ?></label>
	<input value="<?php echo $query->row()->address; ?>" type="text" name="address" placeholder="<?php echo $this->lang->line('address_placeholder'); ?>" class="form-control">
</div>
<div class="form-group">
	<label><?php echo $this->lang->line('remarks'); ?></label>
	<textarea style="resize: none" type="text" name="remarks" placeholder="<?php echo $this->lang->line('enter_remarks'); ?>" class="form-control"><?php echo $query->row()->remarks; ?></textarea>
</div>

<button type="submit" class="mb-sm btn btn-primary"><?php echo $this->lang->line('update'); ?></button>
<?php echo form_close(); ?>

<script>
	$('#edit_property').parsley();
</script>