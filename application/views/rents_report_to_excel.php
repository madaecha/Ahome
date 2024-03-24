<?PHP
    // header("Content-Type: text/plain");
    
    function cleanData(&$str) 
    {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
    }
    $filename = "rents_report_" . $year . ".xls";
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Content-Type: application/vnd.ms-excel");

    $flag = false;
    $query = "SELECT `month` as Month, `year` as Year, `amount` as Amount, `timestamp` as Date, `invoice_id` as Invoice, `tenant_id` as Tenant FROM tenant_rent where year = '$year'";
    $data = $this->db->query($query)->result_array();
    foreach ($data as $row) {
        // Services
        $service_names = '';
        $service_costs = 0;
        $services = $this->db->get_where('invoice_service', array('invoice_id' => $row['Invoice'], 'year' => $year, 'month' => $row['Month']))->result_array();
        if (sizeof($services) > 0) {
            foreach ($services as $key => $value) {
                $service_query = $this->db->get_where('service', array('service_id' => $value['service_id']));
                if ($service_query->num_rows() > 0) {
                    if ($key + 1 != sizeof($services))
                        $service_names .= $service_query->row()->name . ' & ';
                    else 
                        $service_names .= $service_query->row()->name;

                    $service_costs += $value['amount'];
                }
            }
        }

        // Custom Services
        $custom_service_names = '';
        $custom_service_costs = 0;
        $custom_services = $this->db->get_where('invoice_custom_service', array('invoice_id' => $row['Invoice'], 'year' => $year, 'month' => $row['Month']))->result_array();
        if (sizeof($custom_services) > 0) {
            foreach ($custom_services as $key => $value) {
                if ($key + 1 != sizeof($custom_services))
                    $custom_service_names .= $value['name'] . ' & ';
                else 
                    $custom_service_names .= $value['name'];

                $custom_service_costs += $value['amount'];
            }
        }

        $tenant_name = '';
        if ($this->db->get_where('tenant', array('tenant_id' => $row['Tenant']))->num_rows() > 0)
            $tenant_name = $this->db->get_where('tenant', array('tenant_id' => $row['Tenant']))->row()->name; 
        else
            $tenant_name = 'Tenant not found';

        $start_date = strtotime($row['Month'] . ' ' . '01' . ', ' . $year);
        $end_date = strtotime($row['Month'] . ' ' . date('t', strtotime($year . '-' . $row['Month'])) . ', ' . $year . '11:59:59 pm');

        $late_fee_query = $this->db->get_where('invoice', array('invoice_id' => $row['Invoice'], 'due_date >' => $start_date, 'due_date <' => $end_date));
        
        $row['Date']                    =   date('d-M-Y', $row['Date']);
        $row['Late Fee']                =   $late_fee_query->num_rows() > 0 ? $late_fee_query->row()->late_fee : 0;
        $row['Invoice']                 =   $this->db->get_where('invoice', array('invoice_id' => $row['Invoice']))->row()->invoice_number;
        $row['Tenant']                  =   $tenant_name;
        $row['Services']                =   $service_names;
        $row['Services Cost']           =   $service_costs;
        $row['Custom Services']         =   $custom_service_names;
        $row['Custom Services Cost']    =   $custom_service_costs;

        if(!$flag) {
            echo implode("\t", array_keys($row)) . "\r\n";
            $flag = true;
        }

        array_walk($row, __NAMESPACE__ . '\cleanData');
        
        echo implode("\t", array_values($row)) . "\r\n";
    }
    exit;
?>