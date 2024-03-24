<?php echo form_open('payments/update/' . $param2, array('id' => 'edit_invoice_payment', 'method' => 'post', 'data-parsley-validate' => 'true')); ?>
<div class="form-group">
    <label><?php echo $this->lang->line('amount'); ?> *</label>
    <input value="<?php echo $this->db->get_where('tenant_paid', array('tenant_paid_id' => $param2))->row()->amount; ?>" type="text" name="amount" placeholder="<?php echo $this->lang->line('enter_paid_amount'); ?>" class="form-control" data-parsley-type="number">
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
            <option <?php if ($payment_method['payment_method_id'] == $this->db->get_where('tenant_paid', array('tenant_paid_id' => $param2))->row()->payment_method_id) echo 'selected'; ?> value="<?php echo $payment_method['payment_method_id']; ?>"><?php echo $payment_method['name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="form-group">
    <label><?php echo $this->lang->line('paid_on'); ?> (mm/dd/yyyy) *</label>
    <input value="<?php echo date('m/d/Y', $this->db->get_where('tenant_paid', array('tenant_paid_id' => $param2))->row()->paid_on); ?>" name="paid_on" type="text" class="form-control" id="datepicker-default" placeholder="<?php echo $this->lang->line('paid_on_date'); ?>" data-parsley-required="true" />
</div>
<div class="form-group">
    <label><?php echo $this->lang->line('notes'); ?></label>
    <textarea name="notes" class="form-control" cols="30" rows="5" placeholder="<?php echo $this->lang->line('notes_on_the_payment'); ?>"><?php echo $this->db->get_where('tenant_paid', array('tenant_paid_id' => $param2))->row()->notes; ?></textarea>
</div>

<button type="submit" class="mb-sm btn btn-primary"><?php echo $this->lang->line('update'); ?></button>
<?php echo form_close(); ?>

<script>
    $('#edit_invoice_payment').parsley();
    FormPlugins.init();

    $('select:not(.normal)').each(function() {
        $(this).select2({
            dropdownParent: $(this).parent()
        });
    });
</script>