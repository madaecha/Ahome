<!-- begin #content -->
<div id="content" class="content">
	<!-- begin breadcrumb -->
	<ol class="breadcrumb pull-right">
		<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('dashboard'); ?></a></li>
		<li class="breadcrumb-item active"><?php echo $page_title; ?></li>
	</ol>
	<!-- end breadcrumb -->
	<!-- begin page-header -->
	<h1 class="page-header">
		<a href="<?php echo base_url(); ?>add_complaint">
			<button type="button" class="btn btn-inverse"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_complaint'); ?></button>
		</a>
	</h1>
	<!-- end page-header -->

	<!-- begin row -->
	<div class="row">
		<!-- begin col-12 -->
		<div class="col-md-12">
			<!-- begin panel -->
			<div class="panel panel-inverse">
				<div class="panel-body">
					<table id="data-table-buttons" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>#</th>
								<th><?php echo $this->lang->line('complaint_number'); ?></th>
								<th><?php echo $this->lang->line('title'); ?></th>
								<th><?php echo $this->lang->line('status'); ?></th>
								<th><?php echo $this->lang->line('total_messages'); ?></th>
								<?php if ($this->session->userdata('user_type') != 3) : ?>
								<th><?php echo $this->lang->line('tenant'); ?></th>
								<?php endif; ?>
								<th><?php echo $this->lang->line('created_on'); ?></th>
								<th><?php echo $this->lang->line('created_by'); ?></th>
								<th><?php echo $this->lang->line('updated_on'); ?></th>
								<th><?php echo $this->lang->line('updated_by'); ?></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count = 1;
							$this->db->order_by('timestamp', 'desc');
							if ($this->session->userdata('user_type') == 3) {
								$tenant_id = $this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->row()->person_id;
								$complaints = $this->db->get_where('complaint', array('tenant_id' => $tenant_id, 'status' => 1))->result_array();
							} else {
								$complaints = $this->db->get_where('complaint', array('status' => 1))->result_array();
							}
							foreach ($complaints as $row) :
							?>
							<tr>
								<td><?php echo $count++; ?></td>
								<td><?php echo $row['complaint_number']; ?></td>
								<td>
									<a onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_view_complaint_details/<?php echo $row['complaint_id']; ?>');" href="javascript:;">
										<?php echo $row['subject']; ?>
									</a>
								</td>
								<td>
									<?php if ($row['status'] == 0) : ?>
										<span class="badge badge-warning"><?php echo $this->lang->line('open'); ?></span>
									<?php endif; ?>
									<?php if ($row['status'] == 1) : ?>
										<span class="badge badge-primary"><?php echo $this->lang->line('closed'); ?></span>
									<?php endif; ?>
								</td>
								<td><?php echo $this->db->get_where('complaint_details', array('complaint_id' => $row['complaint_id']))->num_rows(); ?></td>
								<?php if ($this->session->userdata('user_type') != 3) : ?>
								<td>
									<?php 
										if ($this->db->get_where('tenant', array('tenant_id' => $row['tenant_id']))->num_rows() > 0)
											echo $this->db->get_where('tenant', array('tenant_id' => $row['tenant_id']))->row()->name; 
										else
											echo 'Tenant not found';
									?>
								</td>
								<?php endif; ?>
								<td><?php echo date('d M, Y', $row['created_on']); ?></td>
								<td>
									<?php
									$user_type =  $this->db->get_where('user', array('user_id' => $row['created_by']))->row()->user_type;
									if ($user_type == 1) {
										echo 'Admin';
									} else if ($user_type == 2) {
										$person_id = $this->db->get_where('user', array('user_id' => $row['created_by']))->row()->person_id;
										if ($this->db->get_where('staff', array('staff_id' => $person_id))->num_rows() > 0)
											echo $this->db->get_where('staff', array('staff_id' => $person_id))->row()->name; 
										else
											echo 'Staff not found';
									} else {
										$person_id = $this->db->get_where('user', array('user_id' => $row['created_by']))->row()->person_id;
										echo html_escape($this->db->get_where('tenant', array('tenant_id' => $person_id))->row()->name);
									}
									?>
								</td>
								<td><?php echo date('d M, Y', $row['timestamp']); ?></td>
								<td>
									<?php
									$user_type =  $this->db->get_where('user', array('user_id' => $row['updated_by']))->row()->user_type;
									if ($user_type == 1) {
										echo 'Admin';
									} else if ($user_type == 2) {
										$person_id = $this->db->get_where('user', array('user_id' => $row['updated_by']))->row()->person_id;
										if ($this->db->get_where('staff', array('staff_id' => $person_id))->num_rows() > 0)
											echo $this->db->get_where('staff', array('staff_id' => $person_id))->row()->name; 
										else
											echo 'Staff not found';
									} else {
										$person_id = $this->db->get_where('user', array('user_id' => $row['updated_by']))->row()->person_id;
										echo html_escape($this->db->get_where('tenant', array('tenant_id' => $person_id))->row()->name);
									}
									?>
								</td>
								<td>
									<?php if (!$row['status']): ?>
									<a href="javascript:;" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_show_complaint_media/<?php echo $row['complaint_id']; ?>')" class="btn btn-info btn-xs">
										<i class="fa fa-info"></i> <?php echo $this->lang->line('details'); ?>
									</a>
									<a href="javascript:;" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_reply_to_complaint/<?php echo $row['complaint_id']; ?>')" class="btn btn-primary btn-xs">
										<i class="fa fa-comment"></i> <?php echo $this->lang->line('reply'); ?>
									</a>
									<a href="javascript:;" onclick="confirm_close_modal('<?php echo base_url(); ?>complaints/close/<?php echo $row['complaint_id']; ?>')" class="btn btn-warning btn-xs">
										<i class="fa fa-times"></i> <?php echo $this->lang->line('close'); ?>
									</a>
									<?php else : ?>
									<a href="javascript:;" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_show_complaint_media/<?php echo $row['complaint_id']; ?>')" class="btn btn-info btn-xs">
										<i class="fa fa-info"></i> <?php echo $this->lang->line('details'); ?>
									</a>
									<a href="javascript:;" onclick="confirm_modal('<?php echo base_url(); ?>complaints/remove/<?php echo $row['complaint_id']; ?>')" class="btn btn-danger btn-xs">
										<i class="fa fa-trash"></i> <?php echo $this->lang->line('remove'); ?>
									</a>
									<?php endif; ?>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- end panel -->
		</div>
		<!-- end col-12 -->
	</div>
	<!-- end row -->
</div>
<!-- end #content -->