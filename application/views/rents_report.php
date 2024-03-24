<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('dashboard'); ?></a></li>
        <li class="breadcrumb-item active"><?php echo $this->lang->line('reports'); ?></li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">
    <?php echo $this->lang->line('rents_report_header'); ?> <?php echo $year = date('Y'); ?>
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
                                    <th><?php echo $this->lang->line('invoice'); ?></th>
                                    <th><?php echo $this->lang->line('month'); ?></th>
                                    <th><?php echo $this->lang->line('amount'); ?></th>
                                    <th><?php echo $this->lang->line('due_date'); ?></th>
                                    <th><?php echo $this->lang->line('late_fee'); ?></th>
                                    <th><?php echo $this->lang->line('services'); ?></th>
                                    <th><?php echo $this->lang->line('service_costs'); ?></th>
                                    <th><?php echo $this->lang->line('custom_services'); ?></th>
                                    <th><?php echo $this->lang->line('custom_service_costs'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count = 1;
                                $tenant_ids = [];
                                $tenant_rents = $this->db->get_where('tenant_rent', array('year' => $year))->result_array();
                                foreach ($tenant_rents as $tenant_rent) {
                                    if (!in_array($tenant_rent['tenant_id'], $tenant_ids))
                                        array_push($tenant_ids, $tenant_rent['tenant_id']);
                                }
                                for ($i = 0; $i < count($tenant_ids); $i++) :
                                ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td colspan="9">
                                        <?php 
                                            if ($this->db->get_where('tenant', array('tenant_id' => $tenant_ids[$i]))->num_rows() > 0)
                                                echo $this->db->get_where('tenant', array('tenant_id' => $tenant_ids[$i]))->row()->name;
                                            else
                                                echo 'Tenant name not found';
                                        ?>
                                    </td>
                                </tr>
                                <?php
                                    $invoice_ids = [];
                                    foreach ($tenant_rents as $tenant_rent) {
                                        if (!in_array($tenant_rent['invoice_id'], $invoice_ids) && $tenant_rent['tenant_id'] == $tenant_ids[$i])
                                            array_push($invoice_ids, $tenant_rent['invoice_id']);
                                    }

                                    $invoice_numbers = '';
                                    for ($j = 0; $j < count($invoice_ids); $j++):
                                ?>
                                <tr>
                                    <td></td>
                                    <td><?php echo $this->db->get_where('invoice', array('invoice_id' => $invoice_ids[$j]))->row()->invoice_number; ?></td>
                                    <td>
                                        <?php
                                            $invoice_months = '';
                                            $invoice_amounts = 0;
                                            foreach ($this->db->get_where('tenant_rent', array('invoice_id' => $invoice_ids[$j], 'year' => $year))->result_array() as $invoice_month) {
                                                $invoice_months .= $invoice_month['month'] . ', ';
                                                $invoice_amounts += $invoice_month['amount'];
                                            }
                                            echo substr(trim($invoice_months), 0, -1);
                                        ?>
                                    </td>
                                    <td><?php echo number_format($invoice_amounts); ?></td>
                                    <td><?php echo date('d M, Y', $this->db->get_where('invoice', array('invoice_id' => $invoice_ids[$j]))->row()->due_date); ?></td>
                                    <td><?php echo $this->db->get_where('invoice', array('invoice_id' => $invoice_ids[$j]))->row()->late_fee > 0 ? number_format($this->db->get_where('invoice', array('invoice_id' => $invoice_ids[$j]))->row()->late_fee) : 0; ?></td>
                                    <td>
                                        <?php
                                            $service_names = '';
                                            $service_costs = 0;
                                            $services = $this->db->get_where('invoice_service', array('invoice_id' => $invoice_ids[$j], 'year' => $year))->result_array();
                                            if (sizeof($services) > 0) {
                                                foreach ($services as $key => $value) {
                                                    $service_query = $this->db->get_where('service', array('service_id' => $value['service_id']));
                                                    if ($service_query->num_rows() > 0) {
                                                        $service_names .= $service_query->row()->name . ', ';
                                                        $service_costs += $value['amount'];
                                                    }
                                                }
                                                echo substr(trim($service_names), 0, -1);
                                            } else 
                                                echo '-';
                                        ?>
                                    </td>
                                    <td><?php echo $service_costs > 0 ? number_format($service_costs) : 0; ?></td>
                                    <td>
                                        <?php
                                            $custom_service_names = '';
                                            $custom_service_costs = 0;
                                            $custom_services = $this->db->get_where('invoice_custom_service', array('invoice_id' => $invoice_ids[$j], 'year' => $year))->result_array();
                                            if (sizeof($custom_services) > 0) {
                                                foreach ($custom_services as $key => $value) {
                                                    $custom_service_names .= $value['name'] . ', ';
                                                    $custom_service_costs += $value['amount'];
                                                }
                                                echo substr(trim($custom_service_names), 0, -1);
                                            } else 
                                                echo '-';
                                        ?>
                                    </td>
                                    <td><?php echo $custom_service_costs > 0 ? number_format($custom_service_costs) : '-'; ?></td>
                                </tr>
                                <?php endfor; ?>
                                <?php endfor; ?>
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

                    <button onclick="showSingleYearRentsReport()" type="button" class="mb-sm btn btn-block btn-primary"><?php echo $this->lang->line('show'); ?></button>
                    <hr>
                    <button onclick="DownloadReport()" type="button" class="mb-sm btn btn-block btn-green"><i class="fa fa-download"></i> <?php echo $this->lang->line('download_report'); ?></button>
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
    function showSingleYearRentsReport() {
        var year = $("#year").val();

        url = "<?php echo base_url(); ?>single_year_rents_report/" + year;

        window.location = url;
    }

    function DownloadReport() {
        var year = $("#year").val();

        url = "<?php echo base_url(); ?>download_rents_report/" + year;

        window.location = url;
    }
</script>