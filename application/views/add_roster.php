<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('dashboard'); ?></a></li>
        <li class="breadcrumb-item active"><?php echo $this->lang->line('add_roster'); ?></li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">
        <?php echo $this->lang->line('add_roster_header'); ?>
    </h1>
    <!-- end page-header -->

    <!-- begin row -->
    <div class="row">
        <!-- begin col-12 -->
        <div class="col-lg-6 offset-lg-3">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <!-- begin panel-body -->
                <div class="panel-body">
                    <?php echo form_open('rosters/' . $year . '/' . $week . '/add', array('method' => 'post', 'data-parsley-validate' => 'ture')); ?>
                    <div class="form-group">
                        <label><?php echo $this->lang->line('room'); ?></label>
                        <select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="room_id">
                            <option value=""><?php echo $this->lang->line('room'); ?></option>
                            <?php
                                $rooms = $this->db->get('room')->result_array();
                                foreach ($rooms as $room):
                            ?>
                            <option value="<?php echo $room['room_id']; ?>"><?php echo $room['room_number']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><?php echo $this->lang->line('cleaner'); ?></label>
                        <select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="cleaner_id">
                            <option value=""><?php echo $this->lang->line('cleaner'); ?></option>
                            <?php
                                $cleaners = $this->db->get('cleaner')->result_array();
                                foreach ($cleaners as $cleaner):
                            ?>
                            <option value="<?php echo $cleaner['cleaner_id']; ?>"><?php echo $cleaner['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><?php echo $this->lang->line('start_date'); ?> *</label>
                        <input name="start_date" type="text" class="form-control" id="datepicker-inline" placeholder="<?php echo $this->lang->line('start_date'); ?>" data-parsley-required="true" />
                    </div>
                    <div class="form-group">
                        <label><?php echo $this->lang->line('end_date'); ?> *</label>
                        <input name="end_date" type="text" class="form-control" id="datepicker-autoClose" placeholder="<?php echo $this->lang->line('end_date'); ?>" data-parsley-required="true" />
                    </div>

                    <button type="submit" class="mb-sm btn btn-primary"><?php echo $this->lang->line('submit'); ?></button>
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