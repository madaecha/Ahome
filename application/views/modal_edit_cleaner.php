<?php echo form_open('cleaners/update/' . $param2, array('id' => 'edit_cleaner', 'method' => 'post', 'data-parsley-validate' => 'true')); ?>
<?php
$cleaner_details = $this->db->get_where('cleaner', array('cleaner_id' => $param2))->result_array();
foreach ($cleaner_details as $row) :
?>
<div class="form-group">
	<label><?php echo $this->lang->line('name'); ?> *</label>
	<input value="<?php echo html_escape($row['name']); ?>" type="text" name="name" placeholder="<?php echo $this->lang->line('enter_name'); ?>" class="form-control" data-parsley-required="true">
</div>
<div class="form-group">
	<label><?php echo $this->lang->line('color_code'); ?> *</label>
	<input type="text" value="<?php echo $row['color_code']; ?>" class="form-control" name="color_code" data-parsley-required="true" id="colorpicker" />
</div>

<button type="submit" class="mb-sm btn btn-primary"><?php echo $this->lang->line('update'); ?></button>
<?php endforeach; ?>
<?php echo form_close(); ?>

<script>
	$('#edit_cleaner').parsley();
	FormPlugins.init();
</script>