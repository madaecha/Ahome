<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('dashboard'); ?></a></li>
        <li class="breadcrumb-item active"><?php echo $this->lang->line('rooms'); ?></li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">
        <?php echo $this->lang->line('add_service_to_tenants_header'); ?>
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
                    <div class="note note-primary">
                        <div class="note-content">
                            <h4><b>Instructions</b></h4>
                            - <?php echo $this->lang->line('add_services_to_tenants_note_1') . '<br>'; ?>
                            - <?php echo $this->lang->line('add_services_to_tenants_note_2') . '<br>'; ?>
                            - <?php echo $this->lang->line('add_services_to_tenants_note_3') . '<br>'; ?>
                            - <?php echo $this->lang->line('add_services_to_tenants_note_4') . '<br>'; ?>
                            - <?php echo $this->lang->line('add_services_to_tenants_note_5') . '<br>'; ?>
                            - <?php echo $this->lang->line('add_services_to_tenants_note_6') . '<br>'; ?>
                            - <?php echo $this->lang->line('add_services_to_tenants_note_7'); ?>
                        </div>
                    </div>
                    <?php echo form_open('add_services_to_tenants/update', array('method' => 'post', 'data-parsley-validate' => 'true')); ?>
                    <?php
                        $tenants = $this->db->get_where('tenant', array('status' => 1));
                        $services = $this->db->get('service')->result_array();
                        foreach ($services as $service):
                            $service_tenant_ids = [];
                            $service_tenant = $this->db->select('tenant_id')->get_where('service_tenant', array('service_id' => $service['service_id']));
                            if ($service_tenant->num_rows() > 0) {
                                if ($tenants->num_rows() == $service_tenant->num_rows()) {
                                    array_push($service_tenant_ids, 'All');
                                } else {
                                    foreach ($service_tenant->result_array() as $service_tenant_id) {
                                        array_push($service_tenant_ids, $service_tenant_id['tenant_id']);
                                    }
                                }
                            }
                    ?>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('service_name'); ?> *</label>
                                <p><?php echo html_escape($service['name']); ?></p>
                                <p><?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content . ' ' . number_format($service['cost']); ?></p>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('tenant'); ?></label>
                                <select class="multiple-select2 form-control" multiple="multiple" name="tenants<?php echo $service['service_id']; ?>[]" style="width: 100%">
                                    <option <?php if (in_array('All', $service_tenant_ids)) echo 'selected'; ?> value="All"><?php echo $this->lang->line('all_tenants'); ?></option>
                                    <?php foreach ($tenants->result_array() as $tenant) : ?>
                                    <option <?php if (in_array($tenant['tenant_id'], $service_tenant_ids)) echo 'selected'; ?> value="<?php echo html_escape($tenant['tenant_id']); ?>"><?php echo html_escape($tenant['name'] . ' - ' . $this->db->get_where('room', array('room_id' => $tenant['room_id']))->row()->room_number); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <?php endforeach; ?>
                    <button type="submit" class="mb-sm btn btn-block btn-primary"><?php echo $this->lang->line('add_services_to_tenants'); ?></button>
                    <?php echo form_close(); ?>
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