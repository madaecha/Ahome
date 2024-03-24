<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<head>
	<meta charset="utf-8" />
	<title><?php echo $this->db->get_where('setting', array('name' => 'system_name'))->row()->content; ?> - <?php echo $this->db->get_where('setting', array('name' => 'tagline'))->row()->content; ?> | Book Room</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="<?php echo $this->db->get_where('setting', array('name' => 'tagline'))->row()->content; ?>" name="description" />
	<meta content="t1m9m.com" name="author" />

	<link rel="icon" type="image/*" href="<?php echo base_url(); ?>uploads/website/<?php echo $this->db->get_where('setting', array('name' => 'favicon'))->row()->content; ?>">

	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="<?php echo $this->db->get_where('setting', array('name' => 'font_src'))->row()->content; ?>" rel="stylesheet">
	<link href="<?php echo base_url(); ?>assets/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
	<link href="<?php echo base_url(); ?>assets/plugins/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" />
	<link href="<?php echo base_url(); ?>assets/plugins/font-awesome/5.0/css/fontawesome-all.min.css" rel="stylesheet" />
	<link href="<?php echo base_url(); ?>assets/plugins/animate/animate.min.css" rel="stylesheet" />
	<link href="<?php echo base_url(); ?>assets/css/material/style.min.css" rel="stylesheet" />
	<link href="<?php echo base_url(); ?>assets/css/material/style-responsive.min.css" rel="stylesheet" />
	<link href="<?php echo base_url(); ?>assets/css/material/theme/blue.css" rel="stylesheet" id="theme" />
	<!-- ================== END BASE CSS STYLE ================== -->

	<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
	<link href="<?php echo base_url(); ?>assets/plugins/parsley/src/parsley.css" rel="stylesheet" />
	<!-- ================== END PAGE LEVEL STYLE ================== -->

	<!-- ================== BEGIN BASE JS ================== -->
	<script src="<?php echo base_url(); ?>assets/plugins/pace/pace.min.js"></script>
	<!-- ================== END BASE JS ================== -->

	<style type="text/css">
		body {
			font-family: <?php echo $this->db->get_where('setting', array('name' => 'font_family'))->row()->content; ?>;
		}

		.ie8 body {
			font-family: <?php echo $this->db->get_where('setting', array('name' => 'font_family'))->row()->content; ?>;
		}
	</style>
</head>

<body class="pace-top bg-white">
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade show">
		<div class="material-loader">
			<svg class="circular" viewBox="25 25 50 50">
				<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>
			</svg>
			<div class="message">Loading...</div>
		</div>
	</div>
	<!-- end #page-loader -->

	<!-- begin #page-container -->
	<div id="page-container" class="fade">
		<!-- begin login -->
		<div class="login login-with-news-feed">
			<!-- begin news-feed -->
			<div class="news-feed">
				<div class="news-image" style="background-image: url(<?php echo base_url('uploads/website/' . $this->db->get_where('setting', array('name' => 'login_bg'))->row()->content); ?>)"></div>
			</div>
			<!-- end news-feed -->
			<!-- begin right-content -->
			<div class="right-content">
				<!-- begin login-header -->
				<div class="login-header">
					<div class="brand">
						<?php echo html_escape($this->db->get_where('setting', array('name' => 'system_name'))->row()->content); ?>
						<small><?php echo html_escape($this->db->get_where('setting', array('name' => 'tagline'))->row()->content); ?></small>
					</div>
					<div class="icon">
						<i class="fa fa-sign-in"></i>
					</div>
				</div>
				<!-- end login-header -->
				<!-- begin login-content -->
				<div class="login-content">
					<?php if ($this->session->tempdata('success')) : ?>
					<div class="alert alert-success fade show">
						<?php echo $this->session->tempdata('success'); ?>
					</div>
					<?php endif; ?>
					<?php if ($this->session->tempdata('warning')) : ?>
					<div class="alert alert-warning fade show">
						<?php echo $this->session->tempdata('warning'); ?>
					</div>
					<?php endif; ?>
					<?php if ($this->session->tempdata('error')) : ?>
					<div class="alert alert-error fade show">
						<?php echo $this->session->tempdata('error'); ?>
					</div>
					<?php endif; ?>
					<?php echo form_open('book_room/add', array('class' => 'margin-bottom-0', 'method' => 'post', 'data-parsley-validate' => 'true')); ?>
					<div class="form-group m-b-15">
						<input name="name" type="text" class="form-control form-control-lg" placeholder="<?php echo $this->lang->line('enter_name'); ?>" data-parsley-required="true" />
					</div>
					<div class="form-group m-b-15">
						<input name="mobile_number" type="text" class="form-control form-control-lg" placeholder="<?php echo $this->lang->line('enter_mobile_number'); ?>" data-parsley-required="true" />
					</div>
					<div class="form-group m-b-15">
						<input name="email" type="email" class="form-control form-control-lg" placeholder="<?php echo $this->lang->line('email_address'); ?>" />
					</div>
					<div class="form-group m-b-15">
						<select style="width: 100%" class="form-control form-control-lg" name="room_id" data-parsley-required="true">
							<option value=""><?php echo $this->lang->line('select_room'); ?></option>
							<?php
							$rooms = $this->db->get_where('room', array('status' => 0))->result_array();
							foreach ($rooms as $room) :
							?>
								<option value="<?php echo html_escape($room['room_id']); ?>"><?php echo html_escape($room['room_number']); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="login-buttons m-t-20 m-b-40 p-b-40">
						<button type="submit" title="<?php echo $this->lang->line('sign_me_in'); ?>" class="btn btn-primary btn-block btn-lg m-b-10"><?php echo $this->lang->line('book_for_me'); ?></button>
						
						<a class="pull-right" href="<?php echo base_url(); ?>">
							<?php echo $this->lang->line('login'); ?>
						</a>
					</div>
					<?php echo form_close(); ?>
					
					<hr />
					<p class="text-center text-grey-darker">
						<strong>v6.02</strong> &copy; 2018 - <?php echo date('Y'); ?> <a href="<?php echo $this->db->get_where('setting', array('name' => 'copyright_url'))->row()->content; ?>" target="_blank"><?php echo $this->db->get_where('setting', array('name' => 'copyright'))->row()->content; ?></a>, All rights reserved.
					</p>
				</div>
				<!-- end login-content -->
			</div>
			<!-- end right-container -->
		</div>
		<!-- end login -->
	</div>
	<!-- end page container -->

	<?php include 'modal.php'; ?>

	<!-- ================== BEGIN BASE JS ================== -->
	<script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery-3.2.1.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/jquery-ui/jquery-ui.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
	<!--[if lt IE 9]>
	<script src="<?php echo base_url(); ?>assets/crossbrowserjs/html5shiv.js"></script>
	<script src="<?php echo base_url(); ?>assets/crossbrowserjs/respond.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	<script src="<?php echo base_url(); ?>assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/js-cookie/js.cookie.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/theme/material.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/apps.min.js"></script>
	<!-- ================== END BASE JS ================== -->

	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="<?php echo base_url(); ?>assets/plugins/parsley/dist/parsley.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/highlight/highlight.common.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/demo/render.highlight.js"></script>

	<script src="<?php echo base_url(); ?>assets/js/demo/form-plugins.demo.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->

	<script>
		$(document).ready(function() {
			App.init();
			Highlight.init();

			window.setTimeout(function() {
				$(".alert").slideUp(500, function() {
					$(this).remove();
				});
			}, 5000);
		});
	</script>
</body>

</html>