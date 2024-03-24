<?php echo form_open_multipart('notices/update/' . $param2, array('id' => 'update_notice_details', 'class' => 'form-horizontal', 'method' => 'post', 'data-parsley-validate' => 'true')); ?>
<?php
$notice_details = $this->db->get_where('notice', array('notice_id' => $param2))->result_array();
foreach ($notice_details as $row) :
?>
<div class="form-group">
	<label><?php echo $this->lang->line('title'); ?> *</label>
	<input value="<?php echo $row['title']; ?>" name="title" type="text" data-parsley-required="true" class="form-control" placeholder="<?php echo $this->lang->line('add_notice_title_placeholder'); ?>" />
</div>
<div class="form-group">
	<label><?php echo $this->lang->line('existing_image'); ?></label>
	<br>
	<?php if ($row['image_link']) : ?>
	<img src="<?php echo base_url(); ?>uploads/notices/<?php echo $row['image_link']; ?>" alt="Image" class="img-thumbnail thumb128">
	<?php else : ?>
	<p><?php echo $this->lang->line('no_preview_available'); ?>.</p>
	<?php endif; ?>
</div>
<div class="form-group">
	<label><?php echo $this->lang->line('notice_image'); ?></label>
	<br>
	<img id="image-preview" width="90px" src="<?php echo base_url('assets/img/placeholder.jpg'); ?>" class="media-object" />
	<br>
	<br>
	<span class="btn btn-primary fileinput-button">
		<i class="fa fa-plus"></i>
		<span><?php echo $this->lang->line('add_file'); ?></span>
		<input onchange="readImageURL(this);" class="form-control" type="file" name="image_link">
	</span>
</div>
<div class="form-group">
	<label><?php echo $this->lang->line('notice'); ?> *</label>
	<textarea class="ckeditor" id="editor1" name="notice"><?php echo $row['notice']; ?></textarea>
</div>

<button type="submit" class="mb-sm btn btn-primary"><?php echo $this->lang->line('update'); ?></button>
<?php endforeach; ?>
<?php echo form_close(); ?>

<script>
	"use strict";

	$('#update_notice_details').parsley();
	CKEDITOR.replace('editor1');

    function readImageURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#image-preview')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>