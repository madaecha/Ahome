<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('dashboard'); ?></a></li>
        <li class="breadcrumb-item active"><?php echo $this->lang->line('id_type_settings'); ?></li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">
    <?php echo $this->lang->line('id_type_settings_header'); ?>
    </h1>
    <!-- end page-header -->

    <!-- begin row -->
    <div class="row">
        <!-- begin col-9 -->
        <div class="col-lg-9">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <!-- begin panel-body -->
                <div class="panel-body">
                    <table id="data-table-buttons" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="1%">#</th>
                                <th class="text-nowrap"><?php echo $this->lang->line('name'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('created_on'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('created_by'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('updated_on'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('updated_by'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('options'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
						<?php
							$count = 1;
							$this->db->order_by('timestamp', 'desc');
							$id_types = $this->db->get('id_type')->result_array();
							foreach ($id_types as $row):
						?>
                            <tr>
								<td><?php echo $count++; ?></td>
								<td><?php echo html_escape($row['name']); ?></td>
								<td><?php echo date('d M, Y', $row['created_on']); ?></td>
								<td>
									<?php
										$user_type =  $this->db->get_where('user', array('user_id' => $row['created_by']))->row()->user_type;
										if ($user_type == 1) {
											echo 'Admin';
										} else {
                                            $person_id = $this->db->get_where('user', array('user_id' => $row['created_by']))->row()->person_id;
                                            echo html_escape($this->db->get_where('staff', array('staff_id' => $person_id))->row()->name);
										}
									?>
								</td>
								<td><?php echo date('d M, Y', $row['timestamp']); ?></td>
								<td>
									<?php
										$user_type =  $this->db->get_where('user', array('user_id' => $row['updated_by']))->row()->user_type;
										if ($user_type == 1) {
											echo 'Admin';
										} else {
                                            $person_id = $this->db->get_where('user', array('user_id' => $row['updated_by']))->row()->person_id;
                                            echo html_escape($this->db->get_where('staff', array('staff_id' => $person_id))->row()->name);
										}
									?>
								</td>
                                <td>
                                    <a class="btn btn-primary btn-xs" href="javascript:;" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_edit_id_type/<?php echo $row['id_type_id']; ?>');">
                                        <i class="fa fa-edit"></i> <?php echo $this->lang->line('edit'); ?>
                                    </a>
                                    <a class="btn btn-danger btn-xs" href="javascript:;" onclick="confirm_modal('<?php echo base_url('id_type_settings/remove/' . $row['id_type_id']); ?>');">
                                        <i class="fa fa-trash"></i> <?php echo $this->lang->line('remove'); ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- end panel-body -->
            </div>
            <!-- end panel -->
        </div>
        <!-- end col-9 -->
		<!-- begin col-3 -->
		<div class="col-md-3">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <!-- begin panel-body -->
                <div class="panel-body">
					<?php echo form_open('id_type_settings/add', array('method' => 'post', 'data-parsley-validate' => 'true')); ?>
						<div class="form-group">
							<label><?php echo $this->lang->line('id_type_name'); ?> *</label>
							<input type="text" name="name" placeholder="<?php echo $this->lang->line('id_type_name_placeholder'); ?>" class="form-control" data-parsley-required="true">
						</div>

						<button type="submit" class="mb-sm btn btn-primary"><?php echo $this->lang->line('submit'); ?></button>
					<?php echo form_close(); ?>
                </div>
                <!-- end panel-body -->
            </div>
            <!-- end panel -->
		</div>
		<!-- end col-3 -->
    </div>
    <!-- end row -->
</div>
<!-- end #content -->
