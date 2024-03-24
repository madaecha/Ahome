<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('dashboard'); ?></a></li>
        <li class="breadcrumb-item active"><?php echo $this->lang->line('recurring_invoice_generation'); ?></li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">
        <?php echo $this->lang->line('recurring_invoice_generation_header'); ?>
    </h1>
    <!-- end page-header -->

    <!-- begin row -->
    <div class="row">
        <!-- begin col-6 -->
        <div class="col-lg-6">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <!-- begin panel-heading -->
				<div class="panel-heading">
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
					</div>
					<h4 class="panel-title"><?php echo $this->lang->line('recurring_invoice_generation_table'); ?></h4>
				</div>
				<!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                    <table id="data-table-buttons" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="1%">#</th>
                                <th class="text-nowrap"><?php echo $this->lang->line('date'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('number_of_invoices_generated'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('generated_by'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            <?php foreach ($recurring_invoices as $recurring_invoice): ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><?php echo date('d M, Y', $recurring_invoice['created_on']); ?></td>
                                <td><?php echo $recurring_invoice['number_of_invoices_generated']; ?></td>
                                <td>
                                    <?php
                                        $user_type =  $this->db->get_where('user', array('user_id' => $recurring_invoice['created_by']))->row()->user_type;
                                        if ($user_type == 1) {
                                            echo 'Admin';
                                        } else {
                                            $person_id = $this->db->get_where('user', array('user_id' => $recurring_invoice['created_by']))->row()->person_id;
                                            echo html_escape($this->db->get_where('staff', array('staff_id' => $person_id))->row()->name);
                                        }
                                    ?>
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
        <!-- end col-6 -->

        <!-- begin col-6 -->
        <div class="col-lg-6">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <!-- begin panel-heading -->
				<div class="panel-heading">
					<div class="panel-heading-btn">
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
						<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
					</div>
					<h4 class="panel-title"><?php echo $this->lang->line('generate_invoices'); ?></h4>
				</div>
				<!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                    <div class="note note-primary">
                        <div class="note-content">
                            <h4><b>Instructions</b></h4>
                            - <?php echo $this->lang->line('recurring_invoice_generation_note_1') . '<br>'; ?>
                            - <?php echo $this->lang->line('recurring_invoice_generation_note_2') . '<br>'; ?>
                            - <?php echo $this->lang->line('recurring_invoice_generation_note_3') . '<br>'; ?>
                            - <?php echo $this->lang->line('recurring_invoice_generation_note_4') . '<br>'; ?>
                            - <?php echo $this->lang->line('recurring_invoice_generation_note_5') . '<br>'; ?>
                            - <?php echo $this->lang->line('recurring_invoice_generation_note_6') . '<br>'; ?>
                            - <?php echo $this->lang->line('recurring_invoice_generation_note_7') . '<br>'; ?>
                            - <?php echo $this->lang->line('recurring_invoice_generation_note_8'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?php echo $this->lang->line('last_generated'); ?></label>
                        <p id="last-generated-date">
                            <?php
                                $this->db->order_by('recurring_invoice_id', 'desc');
                                $query = $this->db->get('recurring_invoice');
                                if ($query->num_rows() > 0) {
                                    echo date('d M, Y', $query->row()->created_on);
                                } else {
                                    echo 'Never generated.';
                                }                                    
                            ?>
                        </p>
                    </div>

                    <div class="form-group" id="recurring-invoice-actions"></div>

                    <button onclick="generateRecurringInvoices()" type="button" id="recurring-invoice-generation" class="mb-sm btn btn-block btn-primary"><?php echo $this->lang->line('generate_invoices_automatically'); ?></button>
                </div>
                <!-- end panel-body -->
            </div>
            <!-- end panel -->
        </div>
        <!-- end col-6 -->
    </div>
    <!-- end row -->
</div>
<!-- end #content -->

<script>
    function generateRecurringInvoices() {
        $('#recurring-invoice-generation').prop('disabled', true);
        $('#recurring-invoice-generation').html('<i class="fas fa-spinner fa-spin"></i>');

        $('#recurring-invoice-actions').append('<label>Actions</label>');
        $('#recurring-invoice-actions').append('<p>1. Checking for invoices to be generated</p>');

        $.ajax({
            url: "<?php echo base_url('find_recurring_invoices'); ?>",
            success: function(recurring_invoices) {
                number_of_recurring_invoices = jQuery.parseJSON(recurring_invoices).length;
                // console.log(jQuery.parseJSON(recurring_invoices).length);
                $('#recurring-invoice-actions').append('<p>2. Number of recurring invoices to be generated found: <b>' + number_of_recurring_invoices + '</b></p>');
                if (number_of_recurring_invoices > 0) {
                    start_generating_invoices(recurring_invoices);

                    var d = new Date();
                    $('#last-generated-date').html((d.getDate() < 10 ? '0' + d.getDate() : d.getDate()) + ' ' + getMonthShortName(d.getMonth()) + ', ' + d.getFullYear());
                } else {
                    $('#recurring-invoice-actions').append('<p>3. No recurring invoices found to generate this time');

                    $('#recurring-invoice-generation').prop('disabled', false);
                    $('#recurring-invoice-generation').html('<?php echo $this->lang->line('generate_invoices_automatically'); ?>');
                }
            }
        });
    }

    function start_generating_invoices(recurring_invoices) {
        $('#recurring-invoice-actions').append('<p>3. System has started generating invoices</p>');

        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('start_generating_recurring_invoices'); ?>",
            data: { 'recurring_invoices': recurring_invoices },
            success: function(number_of_invoices_generated) {
                if (number_of_invoices_generated > 0) {
                    $('#recurring-invoice-actions').append('<p>4. System has successfully generated <b>' + number_of_invoices_generated  + '</b> invoices.</p>');
                
                    $('#recurring-invoice-generation').prop('disabled', false);
                    $('#recurring-invoice-generation').html('<?php echo $this->lang->line('generate_invoices_automatically'); ?>');
                }
            }
        });
    }

    function getMonthShortName(month) {
        var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        
        return months[month];
    }
</script>