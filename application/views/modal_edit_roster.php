<?php echo form_open('rosters/' . $param2 . '/' . $param3 . '/update/' . $param4, array('id' => 'edit_roster', 'method' => 'post', 'data-parsley-validate' => 'true')); ?>
<?php
$schedule_details = $this->db->get_where('cleaning_schedule', array('cleaning_schedule_id' => $param4))->result_array();
foreach ($schedule_details as $row) :
?>
	<div class="form-group">
		<label><?php echo $this->lang->line('room'); ?></label>
		<select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="room_id">
			<option value=""><?php echo $this->lang->line('room'); ?></option>
			<?php
				$rooms = $this->db->get('room')->result_array();
				foreach ($rooms as $room):
			?>
			<option <?php if ($room['room_id'] == $row['room_id']) echo 'selected'; ?> value="<?php echo $room['room_id']; ?>"><?php echo $room['room_number']; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="form-group">
		<label><?php echo $this->lang->line('cleaner'); ?></label>
		<select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="cleaner_id">
			<option value=""><?php echo $this->lang->line('cleaner'); ?></option>
			<?php
				$cleaners = $this->db->get('cleaner')->result_array();
				foreach ($cleaners as $cleaner):
			?>
			<option <?php if ($cleaner['cleaner_id'] == $row['cleaner_id']) echo 'selected'; ?> value="<?php echo $cleaner['cleaner_id']; ?>"><?php echo $cleaner['name']; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="form-group">
		<label><?php echo $this->lang->line('start_date'); ?> *</label>
		<input value="<?php if ($row['start_date']) echo date('m/d/Y', $row['start_date']); ?>" name="start_date" type="text" class="form-control" id="datepicker-inline" placeholder="<?php echo $this->lang->line('start_date'); ?>" data-parsley-required="true" />
	</div>
	<div class="form-group">
		<label><?php echo $this->lang->line('end_date'); ?> *</label>
		<input value="<?php if ($row['end_date']) echo date('m/d/Y', $row['end_date']); ?>" name="end_date" type="text" class="form-control" id="datepicker-autoClose" placeholder="<?php echo $this->lang->line('end_date'); ?>" data-parsley-required="true" />
	</div>

	<button type="submit" class="mb-sm btn btn-primary"><?php echo $this->lang->line('update'); ?></button>
<?php endforeach; ?>
<?php echo form_close(); ?>

<script>
	$('#edit_croster').parsley();
	FormPlugins.init();

	$('select:not(.normal)').each(function() {
		$(this).select2({
			dropdownParent: $(this).parent()
		});
	});
</script>