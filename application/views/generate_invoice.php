<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('dashboard'); ?></a></li>
        <li class="breadcrumb-item active"><?php echo $this->lang->line('generate_invoice'); ?></li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">
        <?php echo $this->lang->line('generate_invoice_header'); ?>
    </h1>
    <!-- end page-header -->

    <!-- begin row -->
    <div class="row">
        <!-- begin col-6 -->
        <div class="col-lg-6">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <!-- begin panel-body -->
                <ul class="nav nav-tabs nav-tabs-inverse nav-justified nav-justified-mobile">
                    <li class="nav-item">
                        <a href="#single-month" data-toggle="tab" class="nav-link active">
                            <span class="d-md-inline"><?php echo $this->lang->line('single_month'); ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#date-range" data-toggle="tab" class="nav-link">
                            <span class="d-md-inline"><?php echo $this->lang->line('date_range'); ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#multiple-months" data-toggle="tab" class="nav-link">
                            <span class="d-md-inline"><?php echo $this->lang->line('multiple_months'); ?></span>
                        </a>
                    </li>                    
                    <li class="nav-item">
                        <a href="#single-year" data-toggle="tab" class="nav-link">
                            <span class="d-md-inline"><?php echo $this->lang->line('single_year'); ?></span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <?php $tenants = $this->db->get_where('tenant', array('status' => 1))->result_array(); ?>
                    <div class="tab-pane fade active show" id="single-month">
                        <div class="note note-primary">
                            <div class="note-content">
                                <h4><b>Instructions</b></h4>
                                - <?php echo $this->lang->line('single_month_note_1') . '<br>'; ?>
                                - <?php echo $this->lang->line('single_month_note_2') . '<br>'; ?>
                                - <?php echo $this->lang->line('due_date_note'); ?>
                            </div>
                        </div>
                        <?php echo form_open('generate_invoice/single', array('method' => 'post', 'data-parsley-validate' => 'true')); ?>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('tenant'); ?> *</label>
                            <div>
                                <select class="multiple-select2 form-control" multiple="multiple" name="tenants[]" data-parsley-required="true" style="width: 100%">
                                    <option value="All"><?php echo $this->lang->line('all_tenants'); ?></option>
                                    <?php foreach ($tenants as $tenant) : ?>
                                    <option value="<?php echo html_escape($tenant['tenant_id']); ?>"><?php echo html_escape($tenant['name'] . ' - ' . $this->db->get_where('room', array('room_id' => $tenant['room_id']))->row()->room_number); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('year'); ?> *</label>
                            <div>
                                <select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="year">
                                    <option value=""><?php echo $this->lang->line('select_year'); ?></option>
                                    <option value="<?php echo date('Y') - 4; ?>"><?php echo date('Y') - 4; ?></option>
                                    <option value="<?php echo date('Y') - 3; ?>"><?php echo date('Y') - 3; ?></option>
                                    <option value="<?php echo date('Y') - 2; ?>"><?php echo date('Y') - 2; ?></option>
                                    <option value="<?php echo date('Y') - 1; ?>"><?php echo date('Y') - 1; ?></option>
                                    <option value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></option>
                                    <option value="<?php echo date('Y') + 1; ?>"><?php echo date('Y') + 1; ?></option>
                                    <option value="<?php echo date('Y') + 2; ?>"><?php echo date('Y') + 2; ?></option>
                                    <option value="<?php echo date('Y') + 3; ?>"><?php echo date('Y') + 3; ?></option>
                                    <option value="<?php echo date('Y') + 4; ?>"><?php echo date('Y') + 4; ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('month'); ?> *</label>
                            <div>
                                <select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="month">
                                    <option value=""><?php echo $this->lang->line('select_month'); ?></option>
                                    <option value="January"><?php echo $this->lang->line('january'); ?></option>
                                    <option value="February"><?php echo $this->lang->line('february'); ?></option>
                                    <option value="March"><?php echo $this->lang->line('march'); ?></option>
                                    <option value="April"><?php echo $this->lang->line('april'); ?></option>
                                    <option value="May"><?php echo $this->lang->line('may'); ?></option>
                                    <option value="June"><?php echo $this->lang->line('june'); ?></option>
                                    <option value="July"><?php echo $this->lang->line('july'); ?></option>
                                    <option value="August"><?php echo $this->lang->line('august'); ?></option>
                                    <option value="September"><?php echo $this->lang->line('september'); ?></option>
                                    <option value="October"><?php echo $this->lang->line('october'); ?></option>
                                    <option value="November"><?php echo $this->lang->line('november'); ?></option>
                                    <option value="December"><?php echo $this->lang->line('december'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('due_date'); ?> (mm/dd/yyyy) *</label>
                            <input name="due_date" type="text" class="form-control" id="datepicker-autoClose" placeholder="<?php echo $this->lang->line('due_date'); ?>" data-parsley-required="true" />
                        </div>

                        <button type="submit" class="mb-sm btn btn-block btn-primary"><?php echo $this->lang->line('generate_monthly_invoices'); ?></button>
                        <?php echo form_close(); ?>
                    </div>
                    <div class="tab-pane fade" id="date-range">
                        <div class="note note-primary">
                            <div class="note-content">
                                <h4><b>Instructions</b></h4>
                                - <?php echo $this->lang->line('date_range_note_1') . '<br>'; ?>
                                - <?php echo $this->lang->line('date_range_note_2') . '<br>'; ?>
                                - <?php echo $this->lang->line('due_date_note'); ?>
                            </div>
                        </div>
                        <?php echo form_open('generate_invoice/range', array('method' => 'post', 'data-parsley-validate' => 'true')); ?>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('tenant'); ?> *</label>
                            <div>
                                <select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="tenant_id">
                                    <option value=""><?php echo $this->lang->line('select_tenant'); ?></option>
                                    <?php foreach ($tenants as $tenant): ?>
                                    <option value="<?php echo html_escape($tenant['tenant_id']); ?>"><?php echo html_escape($tenant['name'] . ' - ' . $this->db->get_where('room', array('room_id' => $tenant['room_id']))->row()->room_number); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('range'); ?> *</label>
                            <div class="input-group input-daterange">
                                <input type="text" class="form-control" name="start" placeholder="<?php echo $this->lang->line('date_start'); ?>" data-parsley-required="true" />
                                <span class="input-group-addon"><?php echo $this->lang->line('to'); ?></span>
                                <input type="text" class="form-control" name="end" placeholder="<?php echo $this->lang->line('date_end'); ?>" data-parsley-required="true" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('due_date'); ?> (mm/dd/yyyy) *</label>
                            <input name="due_date" type="text" class="form-control" id="datepicker-default" placeholder="<?php echo $this->lang->line('due_date'); ?>" data-parsley-required="true" />
                        </div>

                        <button type="submit" class="mb-sm btn btn-block btn-primary"><?php echo $this->lang->line('generate_time_period_invoice'); ?></button>
                        <?php echo form_close(); ?>
                    </div>
                    <div class="tab-pane fade" id="multiple-months">
                        <div class="note note-primary">
                            <div class="note-content">
                                <h4><b>Instructions</b></h4>
                                - <?php echo $this->lang->line('multiple_month_note_1') . '<br>'; ?>
                                - <?php echo $this->lang->line('multiple_month_note_2') . '<br>'; ?>
                                - <?php echo $this->lang->line('due_date_note'); ?>
                            </div>
                        </div>
                        <?php echo form_open('generate_invoice/multiple', array('method' => 'post', 'data-parsley-validate' => 'true')); ?>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('tenant'); ?> *</label>
                            <div>
                                <select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="tenant_id">
                                    <option value=""><?php echo $this->lang->line('select_tenant'); ?></option>
                                    <?php foreach ($tenants as $tenant) : ?>
                                    <option value="<?php echo html_escape($tenant['tenant_id']); ?>"><?php echo html_escape($tenant['name'] . ' - ' . $this->db->get_where('room', array('room_id' => $tenant['room_id']))->row()->room_number); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('year'); ?> *</label>
                            <div>
                                <select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="year">
                                    <option value=""><?php echo $this->lang->line('select_year'); ?></option>
                                    <option value="<?php echo date('Y') - 4; ?>"><?php echo date('Y') - 4; ?></option>
                                    <option value="<?php echo date('Y') - 3; ?>"><?php echo date('Y') - 3; ?></option>
                                    <option value="<?php echo date('Y') - 2; ?>"><?php echo date('Y') - 2; ?></option>
                                    <option value="<?php echo date('Y') - 1; ?>"><?php echo date('Y') - 1; ?></option>
                                    <option value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></option>
                                    <option value="<?php echo date('Y') + 1; ?>"><?php echo date('Y') + 1; ?></option>
                                    <option value="<?php echo date('Y') + 2; ?>"><?php echo date('Y') + 2; ?></option>
                                    <option value="<?php echo date('Y') + 3; ?>"><?php echo date('Y') + 3; ?></option>
                                    <option value="<?php echo date('Y') + 4; ?>"><?php echo date('Y') + 4; ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('months'); ?> *</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="January" value="January" name="months[]" data-parsley-required="true" />
                                        <label for="January"><?php echo $this->lang->line('january'); ?></label>
                                    </div>
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="May" value="May" name="months[]" />
                                        <label for="May"><?php echo $this->lang->line('may'); ?></label>
                                    </div>
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="September" value="September" name="months[]" />
                                        <label for="September"><?php echo $this->lang->line('september'); ?></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="February" value="February" name="months[]" />
                                        <label for="February"><?php echo $this->lang->line('february'); ?></label>
                                    </div>
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="June" value="June" name="months[]" />
                                        <label for="June"><?php echo $this->lang->line('june'); ?></label>
                                    </div>
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="October" value="October" name="months[]" />
                                        <label for="October"><?php echo $this->lang->line('october'); ?></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="March" value="March" name="months[]" />
                                        <label for="March"><?php echo $this->lang->line('march'); ?></label>
                                    </div>
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="July" value="July" name="months[]" />
                                        <label for="July"><?php echo $this->lang->line('july'); ?></label>
                                    </div>
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="November" value="November" name="months[]" />
                                        <label for="November"><?php echo $this->lang->line('november'); ?></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="April" value="April" name="months[]" />
                                        <label for="April"><?php echo $this->lang->line('april'); ?></label>
                                    </div>
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="August" value="August" name="months[]" />
                                        <label for="August"><?php echo $this->lang->line('august'); ?></label>
                                    </div>
                                    <div class="checkbox checkbox-css">
                                        <input type="checkbox" id="December" value="December" name="months[]" />
                                        <label for="December"><?php echo $this->lang->line('december'); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('due_date'); ?> (mm/dd/yyyy) *</label>
                            <input name="due_date" type="text" class="form-control" id="datepicker-inline" placeholder="<?php echo $this->lang->line('due_date'); ?>" data-parsley-required="true" />
                        </div>

                        <button type="submit" class="mb-sm btn btn-block btn-primary"><?php echo $this->lang->line('generate_single_tenant_invoice'); ?></button>
                        <?php echo form_close(); ?>
                    </div>                    
                    <div class="tab-pane fade" id="single-year">
                        <div class="note note-primary">
                            <div class="note-content">
                                <h4><b>Instructions</b></h4>
                                - <?php echo $this->lang->line('single_year_note_1') . '<br>'; ?>
                                - <?php echo $this->lang->line('single_year_note_2') . '<br>'; ?>
                                - <?php echo $this->lang->line('due_date_note'); ?>
                            </div>
                        </div>
                        <?php echo form_open('generate_invoice/year', array('method' => 'post', 'data-parsley-validate' => 'true')); ?>                        
                        <div class="form-group">
                            <label><?php echo $this->lang->line('tenant'); ?> *</label>
                            <div>
                                <select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="tenant_id">
                                    <option value=""><?php echo $this->lang->line('select_tenant'); ?></option>
                                    <?php foreach ($tenants as $tenant): ?>
                                    <option value="<?php echo html_escape($tenant['tenant_id']); ?>"><?php echo html_escape($tenant['name'] . ' - ' . $this->db->get_where('room', array('room_id' => $tenant['room_id']))->row()->room_number); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('range'); ?> *</label>
                            <div class="input-group input-daterange">
                                <input type="text" value="<?php echo date('m/d/Y', time()); ?>" id="yearly-start" class="form-control" name="start" placeholder="<?php echo $this->lang->line('date_start'); ?>" data-parsley-required="true" />
                                <span class="input-group-addon"><?php echo $this->lang->line('to'); ?></span>
                                <input type="text" id="yearly-end" class="form-control" name="end" placeholder="<?php echo $this->lang->line('date_end'); ?>" data-parsley-required="true" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('due_date'); ?> (mm/dd/yyyy) *</label>
                            <input name="due_date" type="text" class="form-control" id="datepicker-disabled-past" placeholder="<?php echo $this->lang->line('due_date'); ?>" data-parsley-required="true" />
                        </div>

                        <button type="submit" class="mb-sm btn btn-block btn-primary"><?php echo $this->lang->line('generate_yearly_invoice'); ?></button>
                        <?php echo form_close(); ?>
                    </div>
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
					<h4 class="panel-title"><?php echo $this->lang->line('late_fee'); ?></h4>
				</div>
				<!-- end panel-heading -->
                <!-- begin panel-body -->
                <div class="panel-body">
                    <div class="note note-primary">
                        <div class="note-content">
                            <h4><b>Instructions</b></h4>
                            - <?php echo $this->lang->line('late_fee_note_1') . '<br>'; ?>
                            - <?php echo $this->lang->line('late_fee_note_2') . '<br>'; ?>
                            - <?php echo $this->lang->line('late_fee_note_3') . '<br>'; ?>
                            - <?php echo $this->lang->line('late_fee_note_4') . '<br>'; ?>
                            - <?php echo $this->lang->line('late_fee_note_5'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?php echo $this->lang->line('last_updated'); ?></label>
                        <p id="last-update-date">
                            <?php
                                $this->db->order_by('late_fee_log_id', 'desc');
                                $query = $this->db->get('late_fee_log');
                                if ($query->num_rows() > 0) {
                                    echo date('d M, Y', $query->row()->created_on);
                                } else {
                                    echo 'Never initiated.';
                                }                                    
                            ?>
                        </p>
                    </div>

                    <div class="form-group" id="late-fee-actions"></div>

                    <button onclick="addLateFees()" type="button" id="add-late-fees-to-due-invoices" class="mb-sm btn btn-block btn-primary"><?php echo $this->lang->line('add_late_fees_to_due_invoices'); ?></button>
                    <?php echo form_close(); ?>
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
    document.getElementById('yearly-start').onchange = function() {
        var start = new Date(document.getElementById('yearly-start').value);
        var end = new Date(document.getElementById('yearly-end'));

        end.value = (start.getMonth() + 1) + '/' + start.getDate() + '/' + (start.getFullYear() + 1);
        console.log((start.getMonth() + 1) + '/' + start.getDate() + '/' + (start.getFullYear() + 1))
    };

    function addLateFees() {
        $('#add-late-fees-to-due-invoices').prop('disabled', true);
        $('#add-late-fees-to-due-invoices').html('<i class="fas fa-spinner fa-spin"></i>');

        $('#late-fee-actions').append('<label>Actions</label>');
        $('#late-fee-actions').append('<p>1. Checking for due invoices</p>');

        $.ajax({
            url: "<?php echo base_url('find_due_invoices'); ?>",
            success: function(due_invoices) {
                number_of_due_invoices = jQuery.parseJSON(due_invoices).length;
                // console.log(jQuery.parseJSON(due_invoices).length);
                $('#late-fee-actions').append('<p>2. Number of due invoices found: <b>' + number_of_due_invoices + '</b></p>');
                if (number_of_due_invoices > 0) {
                    start_adding_late_fees(due_invoices);

                    var d = new Date();
                    $('#last-update-date').html((d.getDate() < 10 ? '0' + d.getDate() : d.getDate()) + ' ' + getMonthShortName(d.getMonth()) + ', ' + d.getFullYear());
                } else {
                    $('#late-fee-actions').append('<p>3. No due invoices found for adding late fees');

                    $('#add-late-fees-to-due-invoices').prop('disabled', false);
                    $('#add-late-fees-to-due-invoices').html('<?php echo $this->lang->line('add_late_fees_to_due_invoices'); ?>');
                }
            }
        });
    }

    function start_adding_late_fees(due_invoices) {
        $('#late-fee-actions').append('<p>3. System has started adding late fees</p>');

        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('start_adding_late_fees'); ?>",
            data: { 'due_invoices': due_invoices },
            success: function(number_of_invoices_added_late_fees_to) {
                if (number_of_invoices_added_late_fees_to > 0) {
                    $('#late-fee-actions').append('<p>4. System has successfully added late fees to <b>' + number_of_invoices_added_late_fees_to  + '</b> due invoices.</p>');
                
                    $('#add-late-fees-to-due-invoices').prop('disabled', false);
                    $('#add-late-fees-to-due-invoices').html('<?php echo $this->lang->line('add_late_fees_to_due_invoices'); ?>');
                }
            }
        });
    }

    function getMonthShortName(month) {
        var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        
        return months[month];
    }
</script>