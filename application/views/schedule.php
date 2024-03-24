<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('dashboard'); ?></a></li>
        <li class="breadcrumb-item active"><?php echo $this->lang->line('schedule'); ?></li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">
        <?php echo $this->lang->line('schedule_header'); ?>
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
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('rooms'); ?></th>
                                    <?php for ($i = strtotime($year . 'W' . $week); $i < (strtotime($year . 'W' . $week) + 86400 * 7); $i = $i + 86400): ?>
                                    <th><?php echo $this->lang->line(date('l', $i)) . ' ' . date('d', $i); ?></th>
                                    <?php endfor; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $rooms = $this->db->get('room')->result_array();
                                foreach ($rooms as $room) :
                                ?>
                                    <tr>
                                        <td><?php echo $room['room_number']; ?></td>
                                        <?php for ($j = strtotime($year . 'W' . $week); $j < (strtotime($year . 'W' . $week) + 86400 * 7); $j = $j + 86400): ?>
                                        <?php
                                            if ($this->db->get_where('cleaning_schedule', array('room_id' => $room['room_id'], 'start_date' => $j))->num_rows() > 0):
                                                $query = $this->db->get_where('cleaning_schedule', array('room_id' => $room['room_id'], 'start_date' => $j));
                                        ?>
                                        <td align="center">
                                            <?php
                                                $color_code = $query->row()->color_code;
                                                echo '<div style="background: ' . $color_code . '; padding: 10px; color: white"></div>';
                                            ?>
                                        </td>
                                        <?php
                                            elseif ($this->db->get_where('cleaning_schedule', array('room_id' => $room['room_id'], 'start_date <' => $j, 'end_date >=' => $j))->num_rows() > 0):
                                                $query2 = $this->db->get_where('cleaning_schedule', array('room_id' => $room['room_id'], 'start_date <' => $j, 'end_date >=' => $j));
                                        ?>
                                        <td align="center">
                                            <?php
                                                $color_code = $query2->row()->color_code;
                                                echo '<div style="background: ' . $color_code . '; padding: 10px; color: white"></div>';
                                            ?>
                                        </td>
                                        <?php else: ?>
                                        <td>&nbsp;</td>
                                        <?php endif; ?>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
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

                    <button type="button" onclick="showWeeklySchedule()" class="mb-sm btn btn-block btn-primary"><?php echo $this->lang->line('show'); ?></button>
                </div>
                <!-- end panel-body -->
            </div>
            <!-- end panel -->

            <button type="button" onclick="showCleaningModal()" class="mb-sm btn btn-block btn-primary"><?php echo $this->lang->line('print'); ?></button>

            <!-- begin panel -->
            <div class="panel panel-inverse m-t-20">
                <!-- begin panel-body -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th width="1%">#</th>
                                    <th class="text-nowrap"><?php echo $this->lang->line('name'); ?></th>
                                    <th class="text-nowrap"><?php echo $this->lang->line('color_code'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count = 1;
                                $cleaners = $this->db->get('cleaner')->result_array();
                                foreach ($cleaners as $cleaner):
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
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- end panel-body -->
            </div>
            <!-- end panel -->
        </div>
        <!-- end col-3 -->
    </div>
    <!-- end row -->
</div>
<!-- end #content -->

<script>
    function showWeeklySchedule() {
        var year = $("#year").val();
        var week = $("#week").val();

        url = "<?php echo base_url(); ?>schedule/" + year + "/" + week;

        window.location = url;
    }

    function showCleaningModal() {
        var year = $("#year").val();
        var week = $("#week").val();

        $.ajax({
            url: "<?php echo base_url(); ?>generate_cleaning_pdf/" + year + "/" + week,
            success: function(result) {
                // console.log(result);
            }
        });

        showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_show_cleaning_pdf/' + year + "/" + week);
    }
</script>