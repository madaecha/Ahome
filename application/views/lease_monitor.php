<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('dashboard'); ?></a></li>
        <li class="breadcrumb-item active"><?php echo $this->lang->line('lease_monitor'); ?></li>
    </ol>
    <!-- end breadcrumb -->

    <!-- begin page-header -->
    <h1 class="page-header">
        <?php
            $expired_leases = [];
            $bg_color = 'E70001';

            switch($expired) {
                case 0:
                    echo $this->lang->line('expired_leases');
                    $days = 24 * 3600 + time();
                    $expired_leases = $this->db->get_where('tenant', array('lease_end <=' => $days))->result_array();
                    $bg_color = 'E70001';
                    break;
                case 30:
                    echo $this->lang->line('less_than_30_leases');
                    $day_start = 24 * 3600 + time();
                    $day_end = 30 * 24 * 3600 + time();
                    $expired_leases = $this->db->get_where('tenant', array('lease_end >' => $day_start, 'lease_end <=' => $day_end))->result_array();
                    $bg_color = 'E95420';
                    break;
                case 60:
                    echo $this->lang->line('less_than_60_leases');
                    $day_start = 30 * 24 * 3600 + time();
                    $day_end = 60 * 24 * 3600 + time();
                    $expired_leases = $this->db->get_where('tenant', array('lease_end >' => $day_start, 'lease_end <=' => $day_end))->result_array();
                    $bg_color = '1F70C1';
                    break;
                case 90:
                    echo $this->lang->line('less_than_90_leases');
                    $day_start = 60 * 24 * 3600 + time();
                    $day_end = 90 * 24 * 3600 + time();
                    $expired_leases = $this->db->get_where('tenant', array('lease_end >' => $day_start, 'lease_end <=' => $day_end))->result_array();
                    $bg_color = '4FCE5D';
                    break;
                default:
                    echo $this->lang->line('expired_leases');
            } 
        ?>
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
                            <tr style="background: #<?php echo $bg_color; ?>;">
                                <th width="1%">#</th>
                                <th><?php echo $this->lang->line('image'); ?></th>
                                <th><?php echo $this->lang->line('name'); ?></th>
                                <th><?php echo $this->lang->line('status'); ?></th>
                                <th><?php echo $this->lang->line('mobile'); ?></th>
                                <th><?php echo $this->lang->line('lease_period'); ?></th>
                                <th><?php echo $this->lang->line('id_type'); ?></th>
                                <th><?php echo $this->lang->line('id_number'); ?></th>
                                <th><?php echo $this->lang->line('room'); ?></th>
                                <th><?php echo $this->lang->line('emergency_person'); ?></th>
                                <th><?php echo $this->lang->line('emergency_contact'); ?></th>
                                <th><?php echo $this->lang->line('updated_on'); ?></th>
                                <th><?php echo $this->lang->line('updated_by'); ?></th>
                                <th><?php echo $this->lang->line('options'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $count = 1;
                                foreach ($expired_leases as $expired_lease) :
                            ?>
                            <tr>
                                <td width="1%"><?php echo $count++; ?></td>
                                <td class="with-img">
                                    <?php if ($expired_lease['image_link']) : ?>
                                        <img src="<?php echo base_url(); ?>uploads/tenants/<?php echo html_escape($expired_lease['image_link']); ?>" alt="<?php echo html_escape($expired_lease['name']); ?>" class="img-rounded height-30" />
                                    <?php else : ?>
                                        <img src="<?php echo base_url(); ?>assets/img/tenant.png" alt="Tenant image not found" class="img-rounded height-30" />
                                    <?php endif; ?>
                                </td>
                                <td><?php echo html_escape($expired_lease['name']); ?></td>
                                <td>
                                    <?php
                                    if ($expired_lease['status'])
                                        echo '<span class="badge badge-primary">' . $this->lang->line('active') . '</span>';
                                    else
                                        echo '<span class="badge badge-warning">' . $this->lang->line('inactive') . '</span>';
                                    ?>
                                </td>
                                <td><?php echo $expired_lease['mobile_number'] ? html_escape($expired_lease['mobile_number']) : 'N/A'; ?></td>
                                <td><?php echo ($expired_lease['lease_start'] ? date('d M, Y', $expired_lease['lease_start']) : 'N/A') . ' to ' . ($expired_lease['lease_end'] ? date('d M, Y', $expired_lease['lease_end']) : 'N/A'); ?></td>
                                <td>
                                    <?php
                                        if ($this->db->get_where('id_type', array('id_type_id' => $expired_lease['id_type_id']))->num_rows() > 0)
                                            echo $this->db->get_where('id_type', array('id_type_id' => $expired_lease['id_type_id']))->row()->name;
                                        else
                                            echo 'ID Type not found';
                                    ?>
                                </td>
                                <td><?php echo $expired_lease['id_number'] ? html_escape($expired_lease['id_number']) : 'N/A'; ?></td>
                                <td><?php echo $expired_lease['room_id'] ? html_escape($this->db->get_where('room', array('room_id' => $expired_lease['room_id']))->row()->room_number) : 'N/A'; ?></td>
                                <td><?php echo $expired_lease['emergency_person'] ? html_escape($expired_lease['emergency_person']) : 'N/A'; ?></td>
                                <td><?php echo $expired_lease['emergency_contact'] ? html_escape($expired_lease['emergency_contact']) : 'N/A'; ?></td>
                                <td><?php echo date('d M, Y', $expired_lease['timestamp']); ?></td>
                                <td>
                                    <?php
                                    $user_type =  $this->db->get_where('user', array('user_id' => $expired_lease['updated_by']))->row()->user_type;
                                    if ($user_type == 1) {
                                        echo 'Admin';
                                    } else {
                                        $person_id = $this->db->get_where('user', array('user_id' => $expired_lease['updated_by']))->row()->person_id;
                                        echo html_escape($this->db->get_where('staff', array('staff_id' => $person_id))->row()->name);
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a class="btn btn-white btn-xs" href="javascript:;" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_show_tenant_details/<?php echo $expired_lease['tenant_id']; ?>');">
                                        <i class="fa fa-info"></i> <?php echo $this->lang->line('details'); ?>
                                    </a>
                                    <a class="btn btn-primary btn-xs" href="javascript:;" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_change_tenant_image/<?php echo $expired_lease['tenant_id']; ?>');">
                                        <i class="fa fa-edit"></i> <?php echo $this->lang->line('change_tenant_image'); ?>
                                    </a>
                                    <a class="btn btn-info btn-xs" href="javascript:;" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_show_tenant_id_image/<?php echo $expired_lease['tenant_id']; ?>');">
                                        <i class="fa fa-image"></i> <?php echo $this->lang->line('show_id_image'); ?>
                                    </a>
                                    <a class="btn btn-primary btn-xs" href="javascript:;" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_change_tenant_id_image/<?php echo $expired_lease['tenant_id']; ?>');">
                                        <i class="fa fa-edit"></i> <?php echo $this->lang->line('change_id_image'); ?>
                                    </a>
                                    <a class="btn btn-info btn-xs" href="javascript:;" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_change_tenant_lease/<?php echo $expired_lease['tenant_id']; ?>');">
                                        <i class="fa fa-info"></i> <?php echo $this->lang->line('lease_period'); ?>
                                    </a>
                                    <a class="btn btn-primary btn-xs" href="javascript:;" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_edit_tenant/<?php echo $expired_lease['tenant_id']; ?>');">
                                        <i class="fa fa-edit"></i> <?php echo $this->lang->line('edit'); ?>
                                    </a>
                                    <?php if ($expired_lease['status']) : ?>
                                    <a class="btn btn-warning btn-xs" href="javascript:;" onclick="deactivate_modal('<?php echo base_url(); ?>tenants/deactivate/<?php echo $expired_lease['tenant_id']; ?>');">
                                        <i class="fa fa-edit"></i> <?php echo $this->lang->line('deactivate'); ?>
                                    </a>
                                    <?php endif; ?>
                                    <a class="btn btn-danger btn-xs" href="javascript:;" onclick="confirm_modal('<?php echo base_url(); ?>tenants/remove/<?php echo $expired_lease['tenant_id']; ?>');">
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
        <div class="col-lg-3">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <!-- begin panel-body -->
                <div class="panel-body">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('time_frame'); ?> *</label>
                        <div>
                            <select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="days" id="days">
                                <option value=""><?php echo $this->lang->line('select_time_frame'); ?></option>
                                <option <?php if ($expired  == 0) echo 'selected'; ?> value="<?php echo 0; ?>"><?php echo $this->lang->line('option_expired_leases'); ?></option>
                                <option <?php if ($expired  == 30) echo 'selected'; ?> value="<?php echo 30; ?>"><?php echo $this->lang->line('option_less_than_30_leases'); ?></option>
                                <option <?php if ($expired  == 60) echo 'selected'; ?> value="<?php echo 60; ?>"><?php echo $this->lang->line('option_less_than_60_leases'); ?></option>
                                <option <?php if ($expired  == 90) echo 'selected'; ?> value="<?php echo 90; ?>"><?php echo $this->lang->line('option_less_than_90_leases'); ?></option>
                            </select>
                        </div>
                    </div>

                    <button onclick="showSelectedTimeFrame()" type="button" class="mb-sm btn btn-block btn-primary"><?php echo $this->lang->line('show'); ?></button>
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

<script>
    function showSelectedTimeFrame() {
        var days = $("#days").val() ? $("#days").val() : 0;

        url = "<?php echo base_url(); ?>lease_monitor/" + days;

        window.location = url;
    }
</script>