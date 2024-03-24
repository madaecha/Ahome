<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('dashboard'); ?></a></li>
        <li class="breadcrumb-item active"><?php echo $this->lang->line('cleaners'); ?></li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">
        <a href="<?php echo base_url(); ?>add_cleaner">
            <button type="button" class="btn btn-inverse"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_cleaner'); ?></button>
        </a>
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
                    <table id="data-table-buttons" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="1%">#</th>
                                <th class="text-nowrap"><?php echo $this->lang->line('name'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('color_code'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('updated_on'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('options'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 1;
                            $this->db->order_by('timestamp', 'desc');
                            $cleaners = $this->db->get('cleaner')->result_array();
                            foreach ($cleaners as $cleaner) :
                            ?>
                                <tr>
                                    <td width="1%"><?php echo $count++; ?></td>
                                    <td><?php echo $cleaner['name'] ? html_escape($cleaner['name']) : 'N/A'; ?></td>
                                    <td>
                                        <?php
                                            if ($cleaner['color_code'])
                                                echo '<div style="background: ' . $cleaner['color_code'] . '; padding: 10px; width: 10px"></div>';
                                            else
                                                echo 'N/A';
                                        ?>
                                    </td>
                                    <td><?php echo date('d M, Y', $cleaner['timestamp']); ?></td>
                                    <td>
                                        <a class="btn btn-primary btn-xs" href="javascript:;" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_edit_cleaner/<?php echo $cleaner['cleaner_id']; ?>');">
                                            <i class="fa fa-edit"></i> <?php echo $this->lang->line('edit'); ?>
                                        </a>
                                        <a class="btn btn-danger btn-xs" href="javascript:;" onclick="confirm_modal('<?php echo base_url(); ?>cleaners/remove/<?php echo $cleaner['cleaner_id']; ?>');">
                                            <i class="fa fa-trash"></i> <?php echo $this->lang->line('remove'); ?>
                                        </a>
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
        <!-- end col-12 -->
    </div>
    <!-- end row -->
</div>
<!-- end #content -->