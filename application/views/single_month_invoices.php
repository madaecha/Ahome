<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('dashboard'); ?></a></li>
        <li class="breadcrumb-item active"><?php echo $this->lang->line('single_month_invoices'); ?></li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <?php $currency = $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
    <h1 class="page-header">
    <?php echo $this->lang->line('monthly_invoices_header'); ?> <?php echo $this->lang->line(strtolower($month)) . ', ' .  $year; ?>
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
        <!-- end col-9 -->
        <!-- begin col-3 -->
        <div class="col-lg-3">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <!-- begin panel-body -->
                <div class="panel-body">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('year'); ?> *</label>
                        <div>
                            <select style="width: 100%" class="form-control default-select2" data-parsley-required="true" id="year">
                                <option value=""><?php echo $this->lang->line('select_year'); ?></option>
                                <option <?php if ($year == date('Y') - 4) echo 'selected'; ?> value="<?php echo date('Y') - 4; ?>"><?php echo date('Y') - 4; ?></option>
                                <option <?php if ($year == date('Y') - 3) echo 'selected'; ?> value="<?php echo date('Y') - 3; ?>"><?php echo date('Y') - 3; ?></option>
                                <option <?php if ($year == date('Y') - 2) echo 'selected'; ?> value="<?php echo date('Y') - 2; ?>"><?php echo date('Y') - 2; ?></option>
                                <option <?php if ($year == date('Y') - 1) echo 'selected'; ?> value="<?php echo date('Y') - 1; ?>"><?php echo date('Y') - 1; ?></option>
                                <option <?php if ($year == date('Y')) echo 'selected'; ?> value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></option>
                                <option <?php if ($year == date('Y') + 1) echo 'selected'; ?> value="<?php echo date('Y') + 1; ?>"><?php echo date('Y') + 1; ?></option>
                                <option <?php if ($year == date('Y') + 2) echo 'selected'; ?> value="<?php echo date('Y') + 2; ?>"><?php echo date('Y') + 2; ?></option>
                                <option <?php if ($year == date('Y') + 3) echo 'selected'; ?> value="<?php echo date('Y') + 3; ?>"><?php echo date('Y') + 3; ?></option>
                                <option <?php if ($year == date('Y') + 4) echo 'selected'; ?> value="<?php echo date('Y') + 4; ?>"><?php echo date('Y') + 4; ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?php echo $this->lang->line('month'); ?> *</label>
                        <div>
                            <select style="width: 100%" class="form-control default-select2" data-parsley-required="true" id="month">
                                <option value=""><?php echo $this->lang->line('select_month'); ?></option>
                                <option <?php if ($month == 'January') echo 'selected'; ?> value="January"><?php echo $this->lang->line('january'); ?></option>
                                <option <?php if ($month == 'February') echo 'selected'; ?> value="February"><?php echo $this->lang->line('february'); ?></option>
                                <option <?php if ($month == 'March') echo 'selected'; ?> value="March"><?php echo $this->lang->line('march'); ?></option>
                                <option <?php if ($month == 'April') echo 'selected'; ?> value="April"><?php echo $this->lang->line('april'); ?></option>
                                <option <?php if ($month == 'May') echo 'selected'; ?> value="May"><?php echo $this->lang->line('may'); ?></option>
                                <option <?php if ($month == 'June') echo 'selected'; ?> value="June"><?php echo $this->lang->line('june'); ?></option>
                                <option <?php if ($month == 'July') echo 'selected'; ?> value="July"><?php echo $this->lang->line('july'); ?></option>
                                <option <?php if ($month == 'August') echo 'selected'; ?> value="August"><?php echo $this->lang->line('august'); ?></option>
                                <option <?php if ($month == 'September') echo 'selected'; ?> value="September"><?php echo $this->lang->line('september'); ?></option>
                                <option <?php if ($month == 'October') echo 'selected'; ?> value="October"><?php echo $this->lang->line('october'); ?></option>
                                <option <?php if ($month == 'November') echo 'selected'; ?> value="November"><?php echo $this->lang->line('november'); ?></option>
                                <option <?php if ($month == 'December') echo 'selected'; ?> value="December"><?php echo $this->lang->line('december'); ?></option>
                            </select>
                        </div>
                    </div>

                    <button type="button" onclick="showSingleMonthInvoices()" class="mb-sm btn btn-block btn-primary"><?php echo $this->lang->line('show'); ?></button>
                </div>
                <!-- end panel-body -->
            </div>
            <!-- end panel -->
            <?php if ($this->session->userdata('user_type') != 3) : ?>
            <div class="widget widget-stats bg-blue">
                <div class="stats-icon"><i class="fa fa-credit-card"></i></div>
                <div class="stats-info">
                    <h4><b><?php echo $this->lang->line('total_rents_of'); ?> <?php echo $this->lang->line(strtolower($month)) . ', ' . $year; ?></b></h4>
                    <p>
                        <?php echo $currency; ?>
                        <?php echo $total_rents; ?>
                    </p>
                </div>
            </div>
            <div class="widget widget-stats bg-orange">
                <div class="stats-icon"><i class="fa fa-money-bill-alt"></i></div>
                <div class="stats-info">
                    <h4><b><?php echo $this->lang->line('due_rents_of'); ?> <?php echo $this->lang->line(strtolower($month)) . ', ' . $year; ?></b></h4>
                    <p>
                        <?php echo $currency; ?>
                        <?php echo $total_rents - $paid_rents; ?>
                    </p>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <!-- end col-3 -->
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

    function showSingleMonthInvoices() {
        var year = $("#year").val();
        var month = $("#month").val();

        url = "<?php echo base_url(); ?>single_month_invoices/" + year + "/" + month;

        window.location = url;
    }
</script>