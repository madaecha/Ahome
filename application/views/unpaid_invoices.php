<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('dashboard'); ?></a></li>
        <li class="breadcrumb-item active"><?php echo $this->lang->line('invoices'); ?></li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <?php $currency = $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
    <h1 class="page-header">
    <?php echo $this->lang->line('invoices_header'); ?>
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
                                <th class="text-nowrap"><?php echo $this->lang->line('invoice'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('tenant_name'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('tenant_mobile'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('room'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('property'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('amount'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('late_fee'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('paid'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('open_balance'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('status'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('due_date'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('sms'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('email'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('updated_on'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('updated_by'); ?></th>
                                <?php if ($this->session->userdata('user_type') != 3) : ?>
                                <th class="text-nowrap"><?php echo $this->lang->line('options'); ?></th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $count = 1;
                                foreach ($invoices as $row) :
                            ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td>
                                    <a href="<?php echo base_url(); ?>invoice/<?php echo $row['invoice_id']; ?>">
                                        #<?php echo html_escape($row['invoice_number']); ?>
                                    </a>
                                </td>
                                <td><?php echo html_escape($row['tenant_name']); ?></td>
                                <td><?php echo html_escape($row['tenant_mobile']); ?></td>
                                <td><?php echo html_escape($row['room_number']); ?></td>
                                <td><?php echo html_escape($row['property']); ?></td>
                                <td><?php echo $currency . ' ' . number_format($row['amount']); ?></td>
                                <td><?php echo $currency . ' ' . number_format($row['late_fee']); ?></td>
                                <td><?php echo $currency . ' ' . number_format($row['paid']); ?></td>
                                <td><?php echo $currency . ' ' . number_format($row['open_balance']); ?></td>
                                <td><?php echo $row['status']; ?></td>
                                <td><?php echo $row['due_date']; ?></td>
                                <td><?php echo $row['sms'] ? $this->lang->line('sent') : $this->lang->line('not_sent'); ?></td>
                                <td><?php echo $row['email'] ? $this->lang->line('sent') : $this->lang->line('not_sent'); ?></td>
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
                                <?php if ($this->session->userdata('user_type') != 3) : ?>
                                <td>
                                    <a href="javascript:;" onclick="showInvoiceModal(<?php echo $row['invoice_id']; ?>)" class="btn btn-white btn-xs">
                                        <i class="fa fa-envelope"></i> <?php echo $this->lang->line('show_invoice_pdf'); ?>
                                    </a>
                                    <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_show_invoice_sms/<?php echo $row['invoice_id']; ?>/<?php echo $row['amount'] + $row['late_fee'] - $row['paid']; ?>')" class="btn btn-info btn-xs">
                                        <i class="fa fa-comment"></i> <?php echo $this->lang->line('send_sms'); ?>
                                    </a>
                                    <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_edit_invoice_services/<?php echo $row['invoice_id']; ?>')" class="btn btn-primary btn-xs">
                                        <i class="fa fa-edit"></i> <?php echo $this->lang->line('update_services'); ?>
                                    </a>
                                    <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_edit_late_fee/<?php echo $row['invoice_id']; ?>')" class="btn btn-warning btn-xs">
                                        <i class="fa fa-edit"></i> <?php echo $this->lang->line('edit_late_fee'); ?>
                                    </a>
                                    <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_add_payment/<?php echo $row['invoice_id']; ?>/<?php echo $row['amount'] + $row['late_fee'] - $row['paid']; ?>')" class="btn btn-inverse btn-xs">
                                        <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_payment'); ?>
                                    </a>
                                    <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_show_payment_details/<?php echo $row['invoice_id']; ?>');" class="btn btn-info btn-xs">
                                        <i class="fa fa-edit"></i> <?php echo $this->lang->line('payment_details'); ?>
                                    </a>
                                    <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_edit_invoice_custom_services/<?php echo $row['invoice_id']; ?>')" class="btn btn-primary btn-xs">
                                        <i class="fa fa-edit"></i> <?php echo $this->lang->line('update_custom_services'); ?>
                                    </a>
                                    <a href="javascript:;" onclick="confirm_modal('<?php echo base_url(); ?>invoices/remove/<?php echo $row['invoice_id']; ?>');" class="btn btn-danger btn-xs">
                                        <i class="fa fa-trash"></i> <?php echo $this->lang->line('remove'); ?>
                                    </a>
                                </td>
                                <?php endif; ?>
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

<script>
    function showInvoiceModal(invoice_id) {
        $.ajax({
            url: "<?php echo base_url(); ?>generate_invoice_pdf/" + invoice_id,
            success: function(result) {
                // console.log(result);
            }
        });

        showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_show_invoice_pdf/' + invoice_id);
    }
</script>