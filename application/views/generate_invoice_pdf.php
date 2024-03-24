<!doctype html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding-top: 30px;
            /* border: 1px solid #eee; */
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(4) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 24px;
            line-height: 24px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td {
            border-top: 2px solid #eee;
            font-weight: bold;
            background: whitesmoke;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .rtl table {
            text-align: right;
        }

        .rtl table tr td:nth-child(4) {
            text-align: left;
        }
    </style>
</head>

<body>
    <?php
    $invoice_query = $this->db->get_where('invoice', array('invoice_id' => $invoice_id));
    $tenant_id = $invoice_query->row()->tenant_id;
    $invoice_type = $invoice_query->row()->invoice_type;
    $tenant_rents = $this->db->get_where('tenant_rent', array('invoice_id' => $invoice_id))->result_array();

    $invoice_total = 0;
    ?>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
                            <td class="title">
                                <?php
                                    if ($this->db->get_where('setting', array('name' => 'invoice_logo'))->row()->content):
                                    $path = FCPATH . 'uploads/website/' . $this->db->get_where('setting', array('name' => 'invoice_logo'))->row()->content;
                                    $type = pathinfo($path, PATHINFO_EXTENSION);
                                    $data = file_get_contents($path);
                                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                ?>
                                <img style="width: 80px" src="<?php echo $base64; ?>" alt="">
                                <br>
                                <br>
                                <?php endif; ?>
                                <?php echo $this->db->get_where('setting', array('name' => 'tagline'))->row()->content; ?>
                            </td>
                            <td></td>
                            <td></td>
                            <td>
                                <?php echo $this->lang->line('invoice'); ?> #: <?php echo $invoice_query->row()->invoice_number; ?><br>
                                <?php echo $this->lang->line('created_on'); ?>: <?php echo date('F d, Y', $invoice_query->row()->created_on); ?><br>
                                <?php echo $this->lang->line('due_date'); ?>: <?php echo date('F d, Y', $invoice_query->row()->due_date); ?><br>
                                <?php $late_fee = $invoice_query->row()->late_fee; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="4">
                    <table>
                        <tr>
                            <td>
                                <?php echo html_escape($this->db->get_where('setting', array('name' => 'system_name'))->row()->content); ?><br>
                                <?php echo $this->db->get_where('setting', array('name' => 'address'))->row()->content; ?>
                            </td>
                            <td></td>
                            <td></td>
                            <td>
                                <?php echo $invoice_query->row()->tenant_name; ?><br>
                                <?php echo $invoice_query->row()->room_number . '<br>' . $invoice_query->row()->property_name; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td><?php echo $this->lang->line('description'); ?></td>
                <?php if ($invoice_type == 1 || $invoice_type == 3): ?>
                <td><?php echo $this->lang->line('starting_date'); ?></td>
                <td><?php echo $this->lang->line('ending_date'); ?></td>
                <?php else : ?>
                <td><?php echo $this->lang->line('month'); ?></td>
                <td><?php echo $this->lang->line('year'); ?></td>
                <?php endif; ?>
                <td><?php echo $this->lang->line('row_total'); ?></td>
            </tr>
            <?php if ($invoice_type == 1 || $invoice_type == 3): ?>
            <!-- Starts if invoice type is Date range -->
            <tr class="item">
                <td><?php echo $invoice_type == 1 ? $this->lang->line('date_range_rent') : $this->lang->line('yearly_rent'); ?></td>
                <td><?php echo date('d M, Y', $invoice_query->row()->start_date); ?></td>
                <td><?php echo date('d M, Y', $invoice_query->row()->end_date); ?></td>
                <td>
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php
                    $this->db->select_sum('amount');
                    $this->db->from('tenant_rent');
                    $this->db->where('invoice_id', $invoice_id);
                    $query = $this->db->get();

                    $invoice_total += $query->row()->amount;

                    echo number_format($query->row()->amount);
                    ?>
                </td>
            </tr>
            <?php
                $invoice_services_total = 0;
                $invoice_services = $this->db->get_where('invoice_service', array('invoice_id' => $invoice_id))->result_array();
                foreach ($invoice_services as $invoice_service):
            ?>
            <tr class="item">
                <td>
                    <span class="text-inverse">
                        <?php
                            $invoice_service_query = $this->db->get_where('service', array('service_id' => $invoice_service['service_id']));
                            if ($invoice_service_query->num_rows() > 0):
                                echo $invoice_service_query->row()->name;
                            else:
                                echo 'N/A';
                            endif;
                        ?>
                    </span>
                </td>
                <td class="text-center"><?php echo $invoice_service['month'] . ', ' . $invoice_service['year']; ?></td>
                <td class="text-center"><?php echo $invoice_service['month'] . ', ' . $invoice_service['year']; ?></td>
                <td class="text-right">
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php echo number_format($invoice_service['amount']); ?>
                    <?php $invoice_services_total += $invoice_service['amount']; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <!-- Starts invoice custom items -->
            <?php
                $invoice_custom_services_total = 0;
                $invoice_custom_services = $this->db->get_where('invoice_custom_service', array('invoice_id' => $invoice_id))->result_array();
                foreach ($invoice_custom_services as $invoice_custom_service):
            ?>
            <tr class="item">
                <td><span class="text-inverse"><?php echo $invoice_custom_service['name']; ?></span></td>
                <td class="text-center"><?php echo $invoice_custom_service['month'] . ', ' . $invoice_custom_service['year']; ?></td>
                <td class="text-center"><?php echo $invoice_custom_service['month'] . ', ' . $invoice_custom_service['year']; ?></td>
                <td class="text-right">
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php echo number_format($invoice_custom_service['amount']); ?>
                    <?php $invoice_custom_services_total += $invoice_custom_service['amount']; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <!-- Ends invoice custom items -->
            <!-- Ends if invoice type is Date range -->
            <?php else : ?>
            <!-- Starts if invoice type is Multiple months or Single Month -->
            <?php foreach ($tenant_rents as $tenant_rent) : ?>
            <tr class="item">
                <td><?php echo $this->lang->line('monthly_rent'); ?></td>
                <td><?php echo $tenant_rent['month']; ?></td>
                <td><?php echo $tenant_rent['year']; ?></td>
                <td>
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php 
                        $invoice_total += $tenant_rent['amount'];
                        echo number_format($tenant_rent['amount']); 
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php
                $invoice_services_total = 0; 
                $invoice_services = $this->db->get_where('invoice_service', array('invoice_id' => $invoice_id))->result_array();
                foreach ($invoice_services as $invoice_service):
            ?>
            <tr class="item">
                <td>
                    <span class="text-inverse">
                        <?php
                            $invoice_service_query = $this->db->get_where('service', array('service_id' => $invoice_service['service_id']));
                            if ($invoice_service_query->num_rows() > 0):
                                echo $invoice_service_query->row()->name;
                            else:
                                echo 'N/A';
                            endif;
                        ?>
                    </span>
                </td>
                <td><?php echo $invoice_service['month']; ?></td>
                <td><?php echo $invoice_service['year']; ?></td>
                <td>
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php echo number_format($invoice_service['amount']); ?>
                    <?php $invoice_services_total += $invoice_service['amount']; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <!-- Starts invoice custom items -->
            <?php
                $invoice_custom_services_total = 0;
                $invoice_custom_services = $this->db->get_where('invoice_custom_service', array('invoice_id' => $invoice_id))->result_array();
                foreach ($invoice_custom_services as $invoice_custom_service):
            ?>
            <tr class="item">
                <td><span class="text-inverse"><?php echo $invoice_custom_service['name']; ?></span></td>
                <td class="text-center"><?php echo $invoice_custom_service['month']; ?></td>
                <td class="text-center"><?php echo $invoice_custom_service['year']; ?></td>
                <td class="text-right">
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php echo number_format($invoice_custom_service['amount']); ?>
                    <?php $invoice_custom_services_total += $invoice_custom_service['amount']; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <!-- Ends invoice custom items -->
            <!-- Ends if invoice type is Multiple months or Single Month -->
            <?php endif; ?>
            <?php if ($late_fee > 0) : ?>
            <tr class="item">
                <td><?php echo $this->lang->line('late_fee'); ?></td>
                <td></td>
                <td></td>
                <td><?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content . ' ' . number_format($late_fee); ?></td>
            </tr>
            <?php endif; ?>
            <!-- Starts paid amounts -->
            <tr class="heading">
                <td><?php echo $this->lang->line('paid_on'); ?></td>
                <td><?php echo $this->lang->line('payment_method'); ?></td>
                <td><?php echo $this->lang->line('amount'); ?></td>
                <td><?php echo $this->lang->line('notes'); ?></td>
            </tr>
            <?php $total_paid = 0; ?>
            <?php if ($this->db->get_where('tenant_paid', array('invoice_id' => $invoice_id))->num_rows() > 0): ?>
			<?php foreach ($this->db->get_where('tenant_paid', array('invoice_id' => $invoice_id))->result_array() as $tenant_paid): ?>
            <tr class="item">
                <td><?php echo date('d M, Y', $tenant_paid['paid_on']); ?></td>
                <td>
                    <?php
                        if (($tenant_paid['payment_method_id'] > 0) && $this->db->get_where('payment_method', array('payment_method_id' => $tenant_paid['payment_method_id']))->num_rows() > 0) 
                            echo $this->db->get_where('payment_method', array('payment_method_id' => $tenant_paid['payment_method_id']))->row()->name;
                        else
                            echo 'N/A';
                    ?>
                </td>
                <td>
                    <?php
                        $total_paid += $tenant_paid['amount']; 
                        echo $tenant_paid['amount']; 
                    ?>
                </td>
                <td><?php echo $tenant_paid['notes'] ? $tenant_paid['notes'] : '-'; ?></td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="4" style="text-align: center;">N/A</td>
            </tr>	
            <?php endif; ?>
            <!-- Ends paid amounts -->

            <tr class="total">
                <td><?php echo $this->lang->line('total'); ?>: <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content . ' ' . number_format($invoice_total + $invoice_services_total + $invoice_custom_services_total + $late_fee); ?></td>
                <td><?php echo $this->lang->line('paid'); ?>: <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content . ' ' . number_format($total_paid); ?></td>
                <td></td>
                <td><?php echo $this->lang->line('due'); ?>: <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content . ' ' . number_format($invoice_total + $invoice_services_total + $invoice_custom_services_total + $late_fee - $total_paid); ?></td>
            </tr>
        </table>
    </div>
</body>

</html>