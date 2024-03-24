<?php echo form_open('inventory/update/' . $param2, array('id' => 'edit_inventory', 'method' => 'post', 'data-parsley-validate' => 'true')); ?>
<div class="form-group">
	<label><?php echo $this->lang->line('name'); ?> *</label>
	<input value="<?php echo html_escape($this->db->get_where('inventory', array('inventory_id' => $param2))->row()->name); ?>" type="text" name="name" placeholder="<?php echo $this->lang->line('inventory_name_placeholder'); ?>" class="form-control" data-parsley-required="true">
</div>
<div class="form-group">
	<label><?php echo $this->lang->line('price'); ?> *</label>
	<input value="<?php echo html_escape($this->db->get_where('inventory', array('inventory_id' => $param2))->row()->price); ?>" type="text" name="price" placeholder="<?php echo $this->lang->line('inventory_price_placeholder'); ?>" class="form-control" data-parsley-required="true">
</div>
<div class="form-group">
	<label><?php echo $this->lang->line('quantity'); ?> *</label>
	<input value="<?php echo html_escape($this->db->get_where('inventory', array('inventory_id' => $param2))->row()->quantity); ?>" type="text" name="quantity" placeholder="<?php echo $this->lang->line('inventory_quantity_placeholder'); ?>" class="form-control" data-parsley-required="true">
</div>

<button type="submit" class="mb-sm btn btn-primary"><?php echo $this->lang->line('update'); ?></button>
<?php echo form_close(); ?>

<script>
	$('#edit_inventory').parsley();
</script>