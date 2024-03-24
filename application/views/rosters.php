<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('dashboard'); ?></a></li>
        <li class="breadcrumb-item active"><?php echo $this->lang->line('rosters'); ?></li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">
        <a href="<?php echo base_url('add_roster/' . $year . '/' . $week); ?>">
            <button type="button" class="btn btn-inverse"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_roster'); ?></button>
        </a>
    </h1>
    <!-- end page-header -->

    <!-- begin row -->
    <div class="row">
        <!-- begin col-9 -->
        <div class="col-lg-9">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <!-- begin panel-body -->
                <div class="panel-body">
                    <table id="data-table-buttons" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="1%">#</th>
                                <th class="text-nowrap"><?php echo $this->lang->line('room'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('cleaner'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('color_code'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('start_date'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('end_date'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('updated_on'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('options'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 1;
                            $this->db->order_by('timestamp', 'desc');
                            $week_start = strtotime($year . 'W' . $week);
                            $week_end = $week_start + (86400 * 6);
                            $this->db->group_start();
                            $this->db->where(array('start_date >=' => $week_start, 'start_date <=' => $week_end));
                            $this->db->group_end();
                            $this->db->or_group_start();
                            $this->db->where(array('end_date >=' => $week_start, 'end_date <=' => $week_end));
                            $this->db->group_end();
                            $this->db->or_group_start();
                            $this->db->where(array('start_date <' => $week_start, 'end_date >' => $week_end));
                            $this->db->group_end();
                            $schedule_details = $this->db->get('cleaning_schedule')->result_array();
                            foreach ($schedule_details as $roster) :
                            ?>
                            <tr>
                                <td width="1%"><?php echo $count++; ?></td>
                                <td>
                                    <?php
                                        if ($this->db->get_where('room', array('room_id' => $roster['room_id']))->num_rows() > 0)
                                            echo $this->db->get_where('room', array('room_id' => $roster['room_id']))->row()->room_number;
                                        else
                                            echo 'N/A';
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        if ($this->db->get_where('cleaner', array('cleaner_id' => $roster['cleaner_id']))->num_rows() > 0)
                                            echo $this->db->get_where('cleaner', array('cleaner_id' => $roster['cleaner_id']))->row()->name;
                                        else
                                            echo 'N/A';
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        if ($this->db->get_where('cleaner', array('cleaner_id' => $roster['cleaner_id']))->num_rows() > 0)
                                            echo '<div style="background: ' . $this->db->get_where('cleaner', array('cleaner_id' => $roster['cleaner_id']))->row()->color_code . '; padding: 10px; width: 10px"></div>';
                                        else
                                            echo 'N/A';
                                    ?>
                                </td>
                                <td><?php echo date('d M, Y', $roster['start_date']); ?></td>
                                <td><?php echo date('d M, Y', $roster['end_date']); ?></td>
                                <td><?php echo date('d M, Y', $roster['timestamp']); ?></td>
                                <td>
                                    <a class="btn btn-primary btn-xs" href="javascript:;" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_edit_roster/<?php echo $year . '/' . $week . '/' . $roster['cleaning_schedule_id']; ?>');">
                                        <i class="fa fa-edit"></i> <?php echo $this->lang->line('edit'); ?>
                                    </a>
                                    <a class="btn btn-danger btn-xs" href="javascript:;" onclick="confirm_modal('<?php echo base_url('rosters/' . $year . '/' . $week . '/remove/' . $roster['cleaning_schedule_id']); ?>');">
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
        <!-- end col-9 -->
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
                                <?php for ($year_counter = date('Y') - 4; $year_counter <= 2099; $year_counter++): ?>
                                <option <?php if ($year_counter == $year) echo 'selected'; ?> value="<?php echo $year_counter; ?>"><?php echo $year_counter; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?php echo $this->lang->line('week'); ?> *</label>
                        <div>
                            <select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="week" id="week">
                                <option value=""><?php echo $this->lang->line('select_week'); ?></option>
                                <?php for ($week_counter = 1; $week_counter <= 53; $week_counter++): ?>
                                <option <?php if ($week_counter == $week) echo 'selected'; ?> value="<?php echo $week_counter < 10 ? '0' . $week_counter : $week_counter; ?>"><?php echo $this->lang->line('week') . ' ' . $week_counter; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <button type="button" onclick="showWeeklyRoster()" class="mb-sm btn btn-block btn-primary"><?php echo $this->lang->line('show'); ?></button>
                </div>
                <!-- end panel-body -->
            </div>
        </div>
        <!-- end col-3 -->
    </div>
    <!-- end row -->
</div>
<!-- end #content -->

<script>
    function showWeeklyRoster() {
        var year = $("#year").val();
        var week = $("#week").val();

        url = "<?php echo base_url(); ?>rosters/" + year + "/" + week;

        window.location = url;

        // console.log(url);
        // console.log(year);
        // console.log(week);
    }
</script>