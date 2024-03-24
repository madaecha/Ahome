<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('dashboard'); ?></a></li>
        <li class="breadcrumb-item active"><?php echo $this->lang->line('rooms'); ?></li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">
        <a href="<?php echo base_url(); ?>add_room">
            <button type="button" class="btn btn-inverse"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_room'); ?></button>
        </a>
    </h1>
    <!-- end page-header -->

    <!-- begin row -->
    <div class="row">
        <!-- begin col-12 -->
        <div class="col-lg-12">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <!-- begin panel-body -->
                <div class="panel-body">
                    <table id="data-table-buttons" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="1%">#</th>
                                <th class="text-nowrap"><?php echo $this->lang->line('number_or_name'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('status'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('daily_rent'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('monthly_rent'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('floor'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('property'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('remarks'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('updated_on'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('updated_by'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('options'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 1;
                            $this->db->order_by('timestamp', 'desc');
                            $rooms = $this->db->get_where('room', array('status' => 1))->result_array();
                            foreach ($rooms as $room) :
                            ?>
                            <tr>
                                <td width="1%"><?php echo $count++; ?></td>
                                <td><?php echo html_escape($room['room_number']); ?></td>
                                <td>
                                    <?php
                                    if ($room['status'])
                                        echo '<span class="badge badge-primary">' . $this->lang->line('occupied') . '</span>';
                                    else
                                        echo '<span class="badge badge-warning">' . $this->lang->line('unoccupied') . '</span>';
                                    ?>
                                </td>
                                <td>
                                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                                    <?php echo html_escape(number_format($room['daily_rent'])); ?>
                                </td>
                                <td>
                                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                                    <?php echo html_escape(number_format($room['monthly_rent'])); ?>
                                </td>
                                <td><?php echo $room['floor'] ? html_escape($room['floor']) : 'N/A'; ?></td>
                                <td><?php echo $room['property_id'] > 0 ? $this->db->get_where('property', array('property_id' => $room['property_id']))->row()->name : 'N/A'; ?></td>
                                <td><?php echo $room['remarks'] ? html_escape($room['remarks']) : 'N/A'; ?></td>
                                <td><?php echo date('d M, Y', $room['timestamp']); ?></td>
                                <td>
                                    <?php
                                    $user_type =  $this->db->get_where('user', array('user_id' => $room['updated_by']))->row()->user_type;
                                    if ($user_type == 1) {
                                        echo 'Admin';
                                    } else {
                                        $person_id = $this->db->get_where('user', array('user_id' => $room['updated_by']))->row()->person_id;
                                        echo html_escape($this->db->get_where('staff', array('staff_id' => $person_id))->row()->name);
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a class="btn btn-primary btn-xs" href="javascript:;" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_edit_room/<?php echo $room['room_id']; ?>');">
                                        <i class="fa fa-edit"></i> <?php echo $this->lang->line('edit'); ?>
                                    </a>
                                    <a class="btn btn-warning btn-xs" href="javascript:;" onclick="vacant_modal('<?php echo base_url(); ?>rooms/vacant/<?php echo $room['room_id']; ?>');">
                                        <i class="fa fa-user-times"></i> <?php echo $this->lang->line('vacant_room'); ?>
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
        <!-- end col-12 -->
    </div>
    <!-- end row -->
</div>
<!-- end #content -->