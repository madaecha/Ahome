<div class="form-group">
    <label><?php echo $this->lang->line('open_balance'); ?></label>
    <?php
        $tenant_rent = 0;
        $invoice_service_amount = 0;
        $invoice_custom_service_amount = 0;
        $late_fee = $this->db->get_where('invoice', array('invoice_id' => $param2))->row()->late_fee;
        $paid_amount = 0;

        $this->db->select_sum('amount');
        $this->db->from('tenant_rent');
        $this->db->where('invoice_id', $param2);
        $tenant_rent = $this->db->get()->row()->amount;

        $this->db->select_sum('amount');
        $this->db->from('invoice_service');
        $this->db->where('invoice_id', $param2);
        $invoice_service_amount = $this->db->get()->row()->amount;

        $this->db->select_sum('amount');
        $this->db->from('invoice_custom_service');
        $this->db->where('invoice_id', $param2);
        $invoice_custom_service_amount = $this->db->get()->row()->amount;

        $this->db->select_sum('amount');
        $this->db->from('tenant_paid');
        $this->db->where('invoice_id', $param2);
        $paid_amount = $this->db->get()->row()->amount;

        echo '<p>' . number_format($tenant_rent + $invoice_service_amount + $invoice_custom_service_amount + $late_fee - $paid_amount) . '</p>';
    ?>
</div>
<hr>
<?php echo form_open('payments/add/' . $param2, array('id' => 'add_invoice_payment', 'method' => 'post', 'data-parsley-validate' => 'true')); ?>
<div class="form-group">
    <label><?php echo $this->lang->line('amount'); ?> *</label>
    <input type="text" name="amount" placeholder="<?php echo $this->lang->line('enter_paid_amount'); ?>" class="form-control" data-parsley-type="number">
</div>
<div class="form-group">
    <label><?php echo $this->lang->line('payment_method'); ?></label>
    <div>
        <select style="width: 100%" class="form-control default-select2" name="payment_method_id">
            <option value=""><?php echo $this->lang->line('select_payment_method'); ?></option>
            <?php
                $payment_methods = $this->db->get('payment_method')->result_array();
                foreach ($payment_methods as $payment_method):
            ?>
            <option value="<?php echo $payment_method['payment_method_id']; ?>"><?php echo $payment_method['name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="form-group">
    <label><?php echo $this->lang->line('paid_on'); ?> (mm/dd/yyyy) *</label>
    <input name="paid_on" type="text" class="form-control" id="datepicker-default" placeholder="<?php echo $this->lang->line('paid_on_date'); ?>" data-parsley-required="true" />
</div>
<div class="form-group">
    <label><?php echo $this->lang->line('notes'); ?></label>
    <textarea name="notes" class="form-control" cols="30" rows="5" placeholder="<?php echo $this->lang->line('notes_on_the_payment'); ?>"></textarea>
</div>

<button type="submit" class="mb-sm btn btn-primary"><?php echo $this->lang->line('submit'); ?></button>
<?php echo form_close(); ?>

<script>
    $('#add_invoice_payment').parsley();
    FormPlugins.init();

    $('select:not(.normal)').each(function() {
        $(this).select2({
            dropdownParent: $(this).parent()
        });
    });
</script>