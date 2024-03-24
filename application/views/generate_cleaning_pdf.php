<!doctype html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        .cleaning-box {
            width: 100%;
            margin: auto;
            padding-top: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            font-size: 11px;
            line-height: 22px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        table, th, td {
            border: 1px solid #eee;
            padding: 5px;
        }
    </style>
</head>

<body>
    <div class="cleaning-box">
        <h1><?php echo html_escape($this->db->get_where('setting', array('name' => 'system_name'))->row()->content); ?></h1>
        <h2><?php echo $this->lang->line('year') . ': ' . $year . ', ' . $this->lang->line('week') . ': ' . $week;?></h2>
        <h3><?php echo $this->lang->line('cleaning_schedule'); ?></h3>
        <table cellpadding="0" cellspacing="0">
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
                    <td align="center" style="background: <?php echo $query->row()->color_code; ?>"></td>
                    <?php
                        elseif ($this->db->get_where('cleaning_schedule', array('room_id' => $room['room_id'], 'start_date <' => $j, 'end_date >=' => $j))->num_rows() > 0):
                            $query2 = $this->db->get_where('cleaning_schedule', array('room_id' => $room['room_id'], 'start_date <' => $j, 'end_date >=' => $j));
                    ?>
                    <td align="center" style="background: <?php echo $query2->row()->color_code; ?>"></td>
                    <?php else: ?>
                    <td>&nbsp;</td>
                    <?php endif; ?>
                    <?php endfor; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <table cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                <?php
                    $cleaners = $this->db->get('cleaner')->result_array();
                    for ($k = 0; $k < count($cleaners); $k++):
                ?>
                    <td><?php echo $cleaners[$k]['name'] ? html_escape($cleaners[$k]['name']) : 'N/A'; ?></td>
                    <td>
                        <?php
                            if ($cleaners[$k]['color_code'])
                                echo '<div style="background: ' . $cleaners[$k]['color_code'] . '; padding: 5px; width: 10px; height: 10px"></div>';
                            else
                                echo 'N/A';
                        ?>
                    </td>
                    <br>
                    <?php endfor; ?>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>