<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('dashboard'); ?></a></li>
        <li class="breadcrumb-item active"><?php echo $this->lang->line('utility_bills'); ?></li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">
        <a href="<?php echo base_url(); ?>add_utility_bill">
            <button type="button" class="btn btn-inverse"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_utility_bill'); ?></button>
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
                                <th class="text-nowrap"><?php echo $this->lang->line('utility_bill_category'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('month'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('year'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('amount'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('status'); ?></th>
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
                            $utility_bills = $this->db->get('utility_bill')->result_array();
                            foreach ($utility_bills as $utility_bill) :
                            ?>
                                <tr>
                                    <td width="1%"><?php echo $count++; ?></td>
                                    <td>
                                        <?php
                                        if ($this->db->get_where('utility_bill_category', array('utility_bill_category_id' => $utility_bill['utility_bill_category_id']))->num_rows() > 0)
                                            echo html_escape($this->db->get_where('utility_bill_category', array('utility_bill_category_id' => $utility_bill['utility_bill_category_id']))->row()->name);
                                        else
                                            echo 'N/A';
                                        ?>
                                    </td>
                                    <td><?php echo html_escape($this->lang->line(strtolower($utility_bill['month']))); ?></td>
                                    <td><?php echo html_escape($utility_bill['year']); ?></td>
                                    <td>
                                        <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                                        <?php echo html_escape(number_format($utility_bill['amount'])); ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($utility_bill['status'])
                                            echo '<span class="badge badge-primary">' . $this->lang->line('paid') . '</span>';
                                        else
                                            echo '<span class="badge badge-warning">' . $this->lang->line('due') . '</span>';
                                        ?>
                                    </td>
                                    <td><?php echo date('d M, Y', $utility_bill['created_on']); ?></td>
                                    <td>
                                        <?php
                                        $user_type =  $this->db->get_where('user', array('user_id' => $utility_bill['created_by']))->row()->user_type;
                                        if ($user_type == 1) {
                                            echo 'Admin';
                                        } else {
                                            $person_id = $this->db->get_where('user', array('user_id' => $utility_bill['created_by']))->row()->person_id;
                                            echo html_escape($this->db->get_where('staff', array('staff_id' => $person_id))->row()->name);
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo date('d M, Y', $utility_bill['timestamp']); ?></td>
                                    <td>
                                        <?php
                                        $user_type =  $this->db->get_where('user', array('user_id' => $utility_bill['updated_by']))->row()->user_type;
                                        if ($user_type == 1) {
                                            echo 'Admin';
                                        } else {
                                            $person_id = $this->db->get_where('user', array('user_id' => $utility_bill['updated_by']))->row()->person_id;
                                            echo html_escape($this->db->get_where('staff', array('staff_id' => $person_id))->row()->name);
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a class="btn btn-xs btn-primary" href="javascript:;" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_edit_utility_bill/<?php echo $utility_bill['utility_bill_id']; ?>');">
                                            <i class="fa fa-edit"></i> <?php echo $this->lang->line('edit'); ?>
                                        </a>
                                        <a class="btn btn-xs btn-info" href="javascript:;" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_change_utility_bill_image/<?php echo $utility_bill['utility_bill_id']; ?>');">
                                            <i class="fa fa-image"></i> <?php echo $this->lang->line('change_image'); ?>
                                        </a>
                                        <a class="btn btn-xs btn-danger" href="javascript:;" onclick="confirm_modal('<?php echo base_url(); ?>utility_bills/remove/<?php echo $utility_bill['utility_bill_id']; ?>');">
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
        <!-- end col-12 -->
    </div>
    <!-- end row -->
</div>
<!-- end #content -->