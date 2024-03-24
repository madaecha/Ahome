<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item active"><?php echo $this->lang->line('dashboard'); ?></li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header"><?php echo $this->lang->line('welcome_to'); ?> <?php echo $this->db->get_where('setting', array('name' => 'system_name'))->row()->content; ?> <small><?php echo date('d') . ' ' . $this->lang->line(strtolower(date('F'))) . ', ' . date('Y'); ?></small></h1>
    <!-- end page-header -->

    <!-- begin row -->
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-blue">
                <div class="stats-icon"><i class="fa fa-building"></i></div>
                <div class="stats-info">
                    <h4><b><?php echo $this->lang->line('total_rooms'); ?></b></h4>
                    <p><?php echo $data['total_rooms']; ?></p>
                </div>
                <div class="stats-link">
                    <a href="<?php echo base_url(); ?>rooms"><?php echo $this->lang->line('view_details'); ?> <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-blue">
                <div class="stats-icon"><i class="fa fa-building"></i></div>
                <div class="stats-info">
                    <h4><b><?php echo $this->lang->line('unoccupied_rooms'); ?></b></h4>
                    <p><?php echo $data['unoccupied_rooms']; ?></p>
                </div>
                <div class="stats-link">
                    <a href="<?php echo base_url(); ?>unoccupied_rooms"><?php echo $this->lang->line('view_details'); ?> <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-blue">
                <div class="stats-icon"><i class="fa fa-building"></i></div>
                <div class="stats-info">
                    <h4><b><?php echo $this->lang->line('occupied_rooms'); ?></b></h4>
                    <p><?php echo $data['occupied_rooms']; ?></p>
                </div>
                <div class="stats-link">
                    <a href="<?php echo base_url(); ?>occupied_rooms"><?php echo $this->lang->line('view_details'); ?> <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-blue">
                <div class="stats-icon"><i class="fa fa-building"></i></div>
                <div class="stats-info">
                    <h4><b><?php echo $this->lang->line('total_properties'); ?></b></h4>
                    <p><?php echo $data['total_properties']; ?></p>
                </div>
                <div class="stats-link">
                    <a href="<?php echo base_url(); ?>properties"><?php echo $this->lang->line('view_details'); ?> <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-blue">
                <div class="stats-icon"><i class="fa fa-users"></i></div>
                <div class="stats-info">
                    <h4><b><?php echo $this->lang->line('total_tenants'); ?></b></h4>
                    <p><?php echo $data['total_tenants']; ?></p>
                </div>
                <div class="stats-link">
                    <a href="<?php echo base_url(); ?>tenants"><?php echo $this->lang->line('view_details'); ?> <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-blue">
                <div class="stats-icon"><i class="fa fa-users"></i></div>
                <div class="stats-info">
                    <h4><b><?php echo $this->lang->line('inactive_tenants'); ?></b></h4>
                    <p><?php echo $data['inactive_tenants']; ?></p>
                </div>
                <div class="stats-link">
                    <a href="<?php echo base_url(); ?>inactive_tenants"><?php echo $this->lang->line('view_details'); ?> <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-blue">
                <div class="stats-icon"><i class="fa fa-users"></i></div>
                <div class="stats-info">
                    <h4><b><?php echo $this->lang->line('active_tenants'); ?></b></h4>
                    <p><?php echo $data['active_tenants']; ?></p>
                </div>
                <div class="stats-link">
                    <a href="<?php echo base_url(); ?>active_tenants"><?php echo $this->lang->line('view_details'); ?> <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-blue">
                <div class="stats-icon"><i class="fa fa-podcast"></i></div>
                <div class="stats-info">
                    <h4><b><?php echo $this->lang->line('total_notices'); ?></b></h4>
                    <p><?php echo $data['total_notices']; ?></p>
                </div>
                <div class="stats-link">
                    <a href="<?php echo base_url(); ?>notices"><?php echo $this->lang->line('view_details'); ?> <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-blue">
                <div class="stats-icon"><i class="far fa-credit-card"></i></div>
                <div class="stats-info">
                    <h4><b><?php echo $this->lang->line('total_invoices'); ?></b></h4>
                    <p><?php echo $data['total_invoices']; ?></p>
                </div>
                <div class="stats-link">
                    <a href="<?php echo base_url(); ?>invoices"><?php echo $this->lang->line('view_details'); ?> <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-blue">
                <div class="stats-icon"><i class="far fa-credit-card"></i></div>
                <div class="stats-info">
                    <h4><b><?php echo $this->lang->line('unpaid_invoices'); ?></b></h4>
                    <p><?php echo $data['unpaid_invoices']; ?></p>
                </div>
                <div class="stats-link">
                    <a href="<?php echo base_url('unpaid_invoices'); ?>"><?php echo $this->lang->line('view_details'); ?> <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-blue">
                <div class="stats-icon"><i class="far fa-credit-card"></i></div>
                <div class="stats-info">
                    <h4><b><?php echo $this->lang->line('paid_invoices'); ?></b></h4>
                    <p><?php echo $data['paid_invoices']; ?></p>
                </div>
                <div class="stats-link">
                    <a href="<?php echo base_url('paid_invoices'); ?>"><?php echo $this->lang->line('view_details'); ?> <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-blue">
                <div class="stats-icon"><i class="fa fa-money-bill-alt"></i></div>
                <div class="stats-info">
                    <h4><b><?php echo $this->lang->line('total_utility_bills'); ?></b></h4>
                    <p><?php echo $data['total_utility_bills']; ?></p>
                </div>
                <div class="stats-link">
                    <a href="<?php echo base_url(); ?>utility_bills"><?php echo $this->lang->line('view_details'); ?> <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-blue">
                <div class="stats-icon"><i class="fa fa-life-ring"></i></div>
                <div class="stats-info">
                    <h4><b><?php echo $this->lang->line('total_complaints'); ?></b></h4>
                    <p><?php echo $data['total_complaints']; ?></p>
                </div>
                <div class="stats-link">
                    <a href="<?php echo base_url(); ?>complaints"><?php echo $this->lang->line('view_details'); ?> <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-blue">
                <div class="stats-icon"><i class="fa fa-life-ring"></i></div>
                <div class="stats-info">
                    <h4><b><?php echo $this->lang->line('open_complaints'); ?></b></h4>
                    <p><?php echo $data['open_complaints']; ?></p>
                </div>
                <div class="stats-link">
                    <a href="<?php echo base_url(); ?>open_complaints"><?php echo $this->lang->line('view_details'); ?> <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-blue">
                <div class="stats-icon"><i class="fa fa-life-ring"></i></div>
                <div class="stats-info">
                    <h4><b><?php echo $this->lang->line('closed_complaints'); ?></b></h4>
                    <p><?php echo $data['closed_complaints']; ?></p>
                </div>
                <div class="stats-link">
                    <a href="<?php echo base_url(); ?>closed_complaints"><?php echo $this->lang->line('view_details'); ?> <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-blue">
                <div class="stats-icon"><i class="fas fa-credit-card"></i></div>
                <div class="stats-info">
                    <h4><b><?php echo $this->lang->line('total_expenses'); ?></b></h4>
                    <p><?php echo $data['total_expenses']; ?></p>
                </div>
                <div class="stats-link">
                    <a href="<?php echo base_url(); ?>expenses"><?php echo $this->lang->line('view_details'); ?> <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-blue">
                <div class="stats-icon"><i class="fa fa-user"></i></div>
                <div class="stats-info">
                    <h4><b><?php echo $this->lang->line('total_staff'); ?></b></h4>
                    <p><?php echo $data['total_staff']; ?></p>
                </div>
                <div class="stats-link">
                    <a href="<?php echo base_url(); ?>staff"><?php echo $this->lang->line('view_details'); ?> <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-blue">
                <div class="stats-icon"><i class="fas fa-recycle"></i></div>
                <div class="stats-info">
                    <h4><b><?php echo $this->lang->line('total_cleaners'); ?></b></h4>
                    <p><?php echo $data['total_cleaners']; ?></p>
                </div>
                <div class="stats-link">
                    <a href="<?php echo base_url(); ?>cleaners"><?php echo $this->lang->line('view_details'); ?> <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-blue">
                <div class="stats-icon"><i class="fas fa-shopping-cart"></i></div>
                <div class="stats-info">
                    <h4><b><?php echo $this->lang->line('total_services'); ?></b></h4>
                    <p><?php echo $data['total_services']; ?></p>
                </div>
                <div class="stats-link">
                    <a href="<?php echo base_url(); ?>service_settings"><?php echo $this->lang->line('view_details'); ?> <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="widget widget-stats bg-blue">
                <div class="stats-icon"><i class="fas fa-shower"></i></div>
                <div class="stats-info">
                    <h4><b><?php echo $this->lang->line('total_inventory'); ?></b></h4>
                    <p><?php echo $data['total_inventory']; ?></p>
                </div>
                <div class="stats-link">
                    <a href="<?php echo base_url(); ?>inventory"><?php echo $this->lang->line('view_details'); ?> <i class="fa fa-arrow-alt-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- begin total rents of current month -->
        <div class="col-lg-3 col-md-6">
            <div class="note note-light">
                <h5><b><?php echo $this->lang->line('total_rents_of'); ?> <?php echo $this->lang->line(strtolower(date('F'))) . ', ' . date('Y'); ?></b></h5>
                <p class="m-b-0">
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php echo number_format($data['current_month_total_rent']); ?>
                </p>
            </div>
        </div>
        <!-- end total rents of current month -->
        <!-- begin due rents of current month -->
        <div class="col-lg-3 col-md-6">
            <div class="note note-light">
                <h5><b><?php echo $this->lang->line('due_rents_of'); ?> <?php echo $this->lang->line(strtolower(date('F'))) . ', ' . date('Y'); ?></b></h5>
                <p class="m-b-0">
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php echo number_format($data['current_month_due_amount']); ?>
                </p>
            </div>
        </div>
        <!-- end due rents of current month -->
        <!-- begin total rents of last month -->
        <div class="col-lg-3 col-md-6">
            <div class="note note-light">
                <h5><b><?php echo $this->lang->line('total_rents_of'); ?> <?php echo $this->lang->line(strtolower(date('F', strtotime("-1 months")))) . ', ' . date('Y', strtotime("-1 months")); ?></b></h5>
                <p class="m-b-0">
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php echo number_format($data['last_month_total_rent']); ?>
                </p>
            </div>
        </div>
        <!-- end total rents of last month -->
        <!-- begin due rents of last month -->
        <div class="col-lg-3 col-md-6">
            <div class="note note-light">
                <h5><b><?php echo $this->lang->line('due_rents_of'); ?> <?php echo $this->lang->line(strtolower(date('F', strtotime("-1 months")))) . ', ' . date('Y', strtotime("-1 months")); ?></b></h5>
                <p class="m-b-0">
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php echo number_format($data['last_month_due_amount']); ?>
                </p>
            </div>
        </div>
        <!-- end due rents of last month -->

        <div class="col-lg-3 col-md-6">
            <div class="note note-light">
                <h5><b><?php echo $this->lang->line('total_utility_bills_overall'); ?></b></h5>
                <p class="m-b-0">
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php echo $data['total_utility_bills_overall'] > 1000000 ? number_format($data['total_utility_bills_overall']/1000000) . ' M' : number_format($data['total_utility_bills_overall'] ? $data['total_utility_bills_overall'] : 0); ?>
                </p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="note note-light">
                <h5><b><?php echo $this->lang->line('total_expenses_overall'); ?></b></h5>
                <p class="m-b-0">
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php echo $data['total_expenses_overall'] > 1000000 ? number_format($data['total_expenses_overall']/1000000) . ' M' : number_format($data['total_expenses_overall'] ? $data['total_expenses_overall'] : 0); ?>
                </p>
            </div>
        </div>
        <!-- begin total rents overall -->
        <div class="col-lg-3 col-md-6">
            <div class="note note-light">
                <h5><b><?php echo $this->lang->line('total_rents_overall'); ?></b></h5>
                <p class="m-b-0">
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php echo number_format($data['total_rents_overall']); ?>
                </p>
            </div>
        </div>
        <!-- end total rents overall -->
        <!-- begin due rents overall -->
        <div class="col-lg-3 col-md-6">
            <div class="note note-light">
                <h5><b><?php echo $this->lang->line('total_due_rents_overall'); ?></b></h5>
                <p class="m-b-0">
                    <?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
                    <?php echo number_format($data['total_due_rents_overall']); ?>
                </p>
            </div>
        </div>
        <!-- end due rents overall -->
    </div>
    <!-- end row -->
</div>
<!-- end #content -->