<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('dashboard'); ?></a></li>
        <li class="breadcrumb-item active"><?php echo $this->lang->line('account'); ?></li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">
    <?php echo $this->lang->line('account_header'); ?> <?php echo $year; ?>
    </h1>
    <!-- end page-header -->

    <!-- begin row -->
    <div class="row">
        <!-- begin col-12 -->
        <div class="col-lg-9">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <!-- begin panel-body -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th width="1%">#</th>
                                    <th><?php echo $this->lang->line('month'); ?></th>
                                    <th><?php echo $this->lang->line('total_rents'); ?></th>
                                    <th><?php echo $this->lang->line('late_fee'); ?></th>
                                    <th><?php echo $this->lang->line('service'); ?></th>
                                    <th><?php echo $this->lang->line('custom_service'); ?></th>
                                    <th><?php echo '+ ' . $this->lang->line('paid'); ?></th>
                                    <th><?php echo $this->lang->line('due'); ?></th>
                                    <th><?php echo '- ' . $this->lang->line('staff_salary'); ?></th>
                                    <th><?php echo '- ' . $this->lang->line('utility_bills'); ?></th>
                                    <th><?php echo '- ' . $this->lang->line('expenses'); ?></th>
                                    <th><?php echo $this->lang->line('balance'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $count = 1;
                                    $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                    foreach ($months as $month) :
                                ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td><?php echo html_escape($this->lang->line(lcfirst($month))); ?></td>
                                    <td>
                                        <?php
                                        $this->db->select_sum('amount');
                                        $this->db->from('tenant_rent');
                                        $this->db->where('month', $month);
                                        $this->db->where('year', $year);

                                        $rent_amount = $this->db->get()->row()->amount;
                                        echo $rent_amount > 0 ? number_format($rent_amount) : '-';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $late_fee = 0;
                                        $start_date = strtotime($month . ' ' . '01' . ', ' . $year);
                                        $end_date = strtotime($month . ' ' . date('t', strtotime($year . '-' . $month)) . ', ' . $year . '11:59:59 pm');

                                        $this->db->select_sum('late_fee');
                                        $this->db->from('invoice');
                                        $this->db->where('due_date >', $start_date);
                                        $this->db->where('due_date <', $end_date);

                                        $late_fee = $this->db->get()->row()->late_fee;
                                        echo $late_fee > 0 ? number_format($late_fee) : '-';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $services_amount = 0;
                                        $this->db->select_sum('amount');
                                        $this->db->from('invoice_service');
                                        $this->db->where('month', $month);
                                        $this->db->where('year', $year);

                                        $services_amount = $this->db->get()->row()->amount;
                                        echo $services_amount > 0 ? number_format($services_amount) : '-';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $this->db->select_sum('amount');
                                        $this->db->from('invoice_custom_service');
                                        $this->db->where('month', $month);
                                        $this->db->where('year', $year);

                                        $custom_services_amount = $this->db->get()->row()->amount;
                                        echo $custom_services_amount > 0 ? number_format($custom_services_amount) : '-';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $this->db->select_sum('amount');
                                        $this->db->from('tenant_paid');
                                        $this->db->where('month', $month);
                                        $this->db->where('year', $year);

                                        $paid_amount = $this->db->get()->row()->amount;

                                        echo $paid_amount > 0 ? number_format($paid_amount) : '-';
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo ($rent_amount + $late_fee + $services_amount + $custom_services_amount - $paid_amount) > 0 ? number_format($rent_amount + $late_fee + $services_amount + $custom_services_amount - $paid_amount) : '-'; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $this->db->select_sum('amount');
                                        $this->db->from('staff_salary');
                                        $this->db->where('status', 1);
                                        $this->db->where('month', $month);
                                        $this->db->where('year', $year);

                                        $staff_salary = $this->db->get()->row()->amount;
                                        echo $staff_salary > 0 ? number_format($staff_salary) : '-';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $this->db->select_sum('amount');
                                        $this->db->from('utility_bill');
                                        $this->db->where('status', 1);
                                        $this->db->where('month', $month);
                                        $this->db->where('year', $year);

                                        $utility_bills = $this->db->get()->row()->amount;
                                        echo $utility_bills > 0 ? number_format($utility_bills) : '-';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $this->db->select_sum('amount');
                                        $this->db->from('expense');
                                        $this->db->where('month', $month);
                                        $this->db->where('year', $year);

                                        $expenses = $this->db->get()->row()->amount;
                                        echo $expenses > 0 ? number_format($expenses) : '-';
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo ($paid_amount - $staff_salary - $utility_bills - $expenses) ? number_format($paid_amount - $staff_salary - $utility_bills - $expenses) : '-'; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- end panel-body -->
            </div>
            <!-- end panel -->
        </div>
        <!-- end col-12 -->
        <!-- begin col-3 -->
        <div class="col-lg-3">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <!-- begin panel-body -->
                <div class="panel-body">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('year'); ?> *</label>
                        <div>
                            <select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="year" id="year">
                                <option value=""><?php echo $this->lang->line('select_year'); ?></option>
                                <option <?php if ($year  == (date('Y') - 4)) echo 'selected'; ?> value="<?php echo date('Y') - 4; ?>"><?php echo date('Y') - 4; ?></option>
                                <option <?php if ($year  == (date('Y') - 3)) echo 'selected'; ?> value="<?php echo date('Y') - 3; ?>"><?php echo date('Y') - 3; ?></option>
                                <option <?php if ($year  == (date('Y') - 2)) echo 'selected'; ?> value="<?php echo date('Y') - 2; ?>"><?php echo date('Y') - 2; ?></option>
                                <option <?php if ($year  == (date('Y') - 1)) echo 'selected'; ?> value="<?php echo date('Y') - 1; ?>"><?php echo date('Y') - 1; ?></option>
                                <option <?php if ($year  == (date('Y'))) echo 'selected'; ?> value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></option>
                                <option <?php if ($year  == (date('Y') + 1)) echo 'selected'; ?> value="<?php echo date('Y') + 1; ?>"><?php echo date('Y') + 1; ?></option>
                                <option <?php if ($year  == (date('Y') + 2)) echo 'selected'; ?> value="<?php echo date('Y') + 2; ?>"><?php echo date('Y') + 2; ?></option>
                                <option <?php if ($year  == (date('Y') + 3)) echo 'selected'; ?> value="<?php echo date('Y') + 3; ?>"><?php echo date('Y') + 3; ?></option>
                                <option <?php if ($year  == (date('Y') + 4)) echo 'selected'; ?> value="<?php echo date('Y') + 4; ?>"><?php echo date('Y') + 4; ?></option>
                            </select>
                        </div>
                    </div>

                    <button onclick="showSingleYearAccount()" type="button" class="mb-sm btn btn-block btn-primary"><?php echo $this->lang->line('show'); ?></button>
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
    function showSingleYearAccount() {
        var year = $("#year").val();

        url = "<?php echo base_url(); ?>single_year_account/" + year;

        window.location = url;
    }
</script>