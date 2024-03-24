<?php echo form_open('rooms/update/' . $param2, array('id' => 'edit_room', 'method' => 'post', 'data-parsley-validate' => 'true')); ?>
<?php $query = $this->db->get_where('room', array('room_id' => $param2)); ?>
<div class="form-group">
	<label><?php echo $this->lang->line('room_number_or_name'); ?> *</label>
	<input value="<?php echo html_escape($query->row()->room_number); ?>" type="text" name="room_number" placeholder="<?php echo $this->lang->line('room_number_or_name_placeholder'); ?>" data-parsley-required="true" class="form-control">
</div>
<div class="form-group">
	<label><?php echo $this->lang->line('daily_rent'); ?> (<?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>) *</label>
	<input value="<?php echo html_escape($query->row()->daily_rent); ?>" type="text" data-parsley-required="true" data-parsley-type="number" name="daily_rent" placeholder="<?php echo $this->lang->line('daily_rent_placeholder'); ?>" class="form-control" min="0">
</div>
<div class="form-group">
	<label><?php echo $this->lang->line('monthly_rent'); ?> (<?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>) *</label>
	<input value="<?php echo html_escape($query->row()->monthly_rent); ?>" type="text" data-parsley-required="true" data-parsley-type="number" name="monthly_rent" placeholder="<?php echo $this->lang->line('monthly_rent_placeholder'); ?>" class="form-control" min="0">
</div>
<div class="form-group">
	<label><?php echo $this->lang->line('yearly_rent'); ?> (<?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>) *</label>
	<input value="<?php echo html_escape($query->row()->yearly_rent); ?>" type="text" data-parsley-type="number" name="yearly_rent" placeholder="<?php echo $this->lang->line('yearly_rent_placeholder'); ?>" class="form-control" min="0">
</div>
<div class="form-group">
	<label><?php echo $this->lang->line('floor_number'); ?></label>
	<input value="<?php echo html_escape($query->row()->floor); ?>" type="text" name="floor" placeholder="<?php echo $this->lang->line('floor_number_placeholder'); ?>" class="form-control">
</div>
<div class="form-group">
	<label><?php echo $this->lang->line('property'); ?> *</label>
	<select style="width: 100%" class="form-control default-select2" name="property_id">
		<option value=""><?php echo $this->lang->line('select_property'); ?></option>
		<?php foreach ($this->db->get('property')->result_array() as $property): ?>
		<option <?php if ($query->row()->property_id == $property['property_id']) echo 'selected'; ?> value="<?php echo $property['property_id']; ?>"><?php echo $property['name']; ?></option>
		<?php endforeach; ?>
	</select>
</div>
<div class="form-group">
	<label><?php echo $this->lang->line('remarks'); ?></label>
	<textarea style="resize: none" type="text" name="remarks" placeholder="<?php echo $this->lang->line('enter_remarks'); ?>" class="form-control"><?php echo html_escape($query->row()->remarks); ?></textarea>
</div>

<button type="submit" class="mb-sm btn btn-primary"><?php echo $this->lang->line('update'); ?></button>
<?php echo form_close(); ?>

<script>
	$('#edit_room').parsley();

	$('select:not(.normal)').each(function() {
		$(this).select2({
			dropdownParent: $(this).parent()
		});
	});
</script>