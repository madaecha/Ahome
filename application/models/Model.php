<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model extends CI_Model
{
	function dashboard()
	{
		$data = [];

		$data['total_rooms'] 		= 	$this->db->get('room')->num_rows();
		$data['unoccupied_rooms'] 	= 	$this->db->get_where('room', array('status' => 0))->num_rows();
		$data['occupied_rooms'] 	= 	$data['total_rooms'] - $data['unoccupied_rooms'];
		$data['total_properties']	= 	$this->db->get('property')->num_rows();

		$data['total_tenants'] 		= 	$this->db->get('tenant')->num_rows();
		$data['inactive_tenants']	= 	$this->db->get_where('tenant', array('status' => 0))->num_rows();
		$data['active_tenants']		= 	$data['total_tenants'] - $data['inactive_tenants'];
		$data['total_notices'] 		=	$this->db->get('notice')->num_rows();

		$data['total_invoices'] 		= 	$this->db->get('invoice')->num_rows();
		$data['unpaid_invoices'] 		= 	0;
		foreach ($this->db->get('invoice')->result_array() as $invoice) {
			$tenant_rent = 0;
			$invoice_service_amount = 0;
			$invoice_custom_service_amount = 0;
			$late_fee = $this->db->get_where('invoice', array('invoice_id' => $invoice['invoice_id']))->row()->late_fee;
			$paid_amount = 0;

			$this->db->select_sum('amount');
			$this->db->from('tenant_rent');
			$this->db->where('invoice_id', $invoice['invoice_id']);
			$tenant_rent = $this->db->get()->row()->amount;

			$this->db->select_sum('amount');
			$this->db->from('invoice_service');
			$this->db->where('invoice_id', $invoice['invoice_id']);
			$invoice_service_amount = $this->db->get()->row()->amount;

			$this->db->select_sum('amount');
			$this->db->from('invoice_custom_service');
			$this->db->where('invoice_id', $invoice['invoice_id']);
			$invoice_custom_service_amount = $this->db->get()->row()->amount;

			$this->db->select_sum('amount');
			$this->db->from('tenant_paid');
			$this->db->where('invoice_id', $invoice['invoice_id']);
			$paid_amount = $this->db->get()->row()->amount;

			if (($tenant_rent + $invoice_service_amount + $invoice_custom_service_amount + $late_fee) > $paid_amount)
				$data['unpaid_invoices']++;
		}
		$data['paid_invoices'] 			= 	$data['total_invoices'] - $data['unpaid_invoices'];
		$data['total_utility_bills']	=	$this->db->get('utility_bill')->num_rows();

		$data['total_complaints'] 	= 	$this->db->get('complaint')->num_rows();
		$data['open_complaints'] 	= 	$this->db->get_where('complaint', array('status' => 0))->num_rows();
		$data['closed_complaints']	= 	$data['total_complaints'] - $data['open_complaints'];
		$data['total_expenses'] 	= 	$this->db->get('expense')->num_rows();

		$data['total_staff'] 	= 	$this->db->get('staff')->num_rows();
		$data['total_cleaners']	= 	$this->db->get('cleaner')->num_rows();
		$data['total_services'] = 	$this->db->get('service')->num_rows();
		$data['total_inventory'] = 	$this->db->get('inventory')->num_rows();

		// Current month
		$current_month_tenant_rent = 0;
		$current_month_invoice_service = 0;
		$current_month_invoice_custom_service = 0;
		$current_month_late_fee = 0;
		$current_month_paid_amount = 0;

		$this->db->select_sum('amount');
		$this->db->from('tenant_rent');
		$this->db->where('month', date('F'));
		$this->db->where('year', date('Y'));
		$current_month_tenant_rent = $this->db->get()->row()->amount;

		$this->db->select_sum('amount');
		$this->db->from('invoice_service');
		$this->db->where('month', date('F'));
		$this->db->where('year', date('Y'));
		$current_month_invoice_service = $this->db->get()->row()->amount;

		$this->db->select_sum('amount');
		$this->db->from('invoice_custom_service');
		$this->db->where('month', date('F'));
		$this->db->where('year', date('Y'));
		$current_month_invoice_custom_service = $this->db->get()->row()->amount;

		$current_month_start_date = strtotime(date('F') . ' ' . '01' . ', ' . date('Y'));
		$current_month_end_date = strtotime(date('F') . ' ' . date('t', strtotime(date('Y') . '-' . date('F'))) . ', ' . date('Y') . '11:59:59 pm');

		$this->db->select_sum('late_fee');
		$this->db->from('invoice');
		$this->db->where('due_date >', $current_month_start_date);
		$this->db->where('due_date <', $current_month_end_date);
		$current_month_late_fee = $this->db->get()->row()->late_fee;

		$this->db->select_sum('amount');
		$this->db->from('tenant_paid');
		$this->db->where('month', date('F'));
		$this->db->where('year', date('Y'));
		$current_month_paid_amount = $this->db->get()->row()->amount;

		$data['current_month_total_rent'] 	= 	$current_month_tenant_rent + $current_month_invoice_service + $current_month_invoice_custom_service + $current_month_late_fee;
		$data['current_month_due_amount']	= 	$data['current_month_total_rent'] - $current_month_paid_amount;

		// Last month
		$last_month_tenant_rent = 0;
		$last_month_invoice_service = 0;
		$last_month_invoice_custom_service = 0;
		$last_month_late_fee = 0;
		$last_month_paid_amount = 0;

		$this->db->select_sum('amount');
		$this->db->from('tenant_rent');
		$this->db->where('month', date('F', strtotime("-1 months")));
		$this->db->where('year', date('Y', strtotime("-1 months")));
		$last_month_tenant_rent = $this->db->get()->row()->amount;

		$this->db->select_sum('amount');
		$this->db->from('invoice_service');
		$this->db->where('month', date('F', strtotime("-1 months")));
		$this->db->where('year', date('Y', strtotime("-1 months")));
		$last_month_invoice_service = $this->db->get()->row()->amount;

		$this->db->select_sum('amount');
		$this->db->from('invoice_custom_service');
		$this->db->where('month', date('F', strtotime("-1 months")));
		$this->db->where('year', date('Y', strtotime("-1 months")));
		$last_month_invoice_custom_service = $this->db->get()->row()->amount;

		$last_month_start_date = strtotime(date('F', strtotime("-1 months")) . ' ' . '01' . ', ' . date('Y', strtotime("-1 months")));
		$last_month_end_date = strtotime(date('F', strtotime("-1 months")) . ' ' . date('t', strtotime(date('Y', strtotime("-1 months")) . '-' . date('F', strtotime("-1 months")))) . ', ' . date('Y', strtotime("-1 months")) . '11:59:59 pm');

		$this->db->select_sum('late_fee');
		$this->db->from('invoice');
		$this->db->where('due_date >', $last_month_start_date);
		$this->db->where('due_date <', $last_month_end_date);
		$last_month_late_fee = $this->db->get()->row()->late_fee;

		$this->db->select_sum('amount');
		$this->db->from('tenant_paid');
		$this->db->where('month', date('F', strtotime("-1 months")));
		$this->db->where('year', date('Y', strtotime("-1 months")));
		$last_month_paid_amount = $this->db->get()->row()->amount;

		$data['last_month_total_rent'] 	= 	$last_month_tenant_rent + $last_month_invoice_service + $last_month_invoice_custom_service + $last_month_late_fee;
		$data['last_month_due_amount']	= 	$data['last_month_total_rent'] - $last_month_paid_amount;

		$this->db->select_sum('amount');
		$this->db->from('utility_bill');
		$data['total_utility_bills_overall']	=	$this->db->get()->row()->amount;

		$this->db->select_sum('amount');
		$this->db->from('expense');
		$data['total_expenses_overall']			= 	$this->db->get()->row()->amount;
		
		$tenant_rent = 0;
		$invoice_service_amount = 0;
		$invoice_custom_service_amount = 0;
		$late_fee = 0;
		$paid_amount = 0;
		
		$this->db->select_sum('amount');
		$this->db->from('tenant_rent');
		$tenant_rent = $this->db->get()->row()->amount;

		$this->db->select_sum('amount');
		$this->db->from('invoice_service');
		$invoice_service_amount = $this->db->get()->row()->amount;

		$this->db->select_sum('amount');
		$this->db->from('invoice_custom_service');
		$invoice_custom_service_amount = $this->db->get()->row()->amount;

		$this->db->select_sum('late_fee');
		$this->db->from('invoice');
		$late_fee = $this->db->get()->row()->late_fee;

		$this->db->select_sum('amount');
		$this->db->from('tenant_paid');
		$paid_amount = $this->db->get()->row()->amount;

		$data['total_rents_overall'] 		= 	$tenant_rent + $invoice_service_amount + $invoice_custom_service_amount + $late_fee;
		$data['total_due_rents_overall']	= 	$data['total_rents_overall'] - $paid_amount;
		
		return $data;
	}

	function add_property()
	{
		$data['name']		=	$this->input->post('name');
		$data['address']	=	$this->input->post('address');
		$data['remarks']	=	$this->input->post('remarks');
		$data['created_on']	=	time();
		$data['created_by']	=	$this->session->userdata('user_id');
		$data['timestamp']	=	time();
		$data['updated_by']	=	$this->session->userdata('user_id');

		$this->db->insert('property', $data);

		$this->session->set_tempdata('success', $this->lang->line('property_added_successfully'), 3);

		redirect(base_url('properties'), 'refresh');
	}

	function update_property($property_id = '')
	{
		$data['name']		=	$this->input->post('name');
		$data['address']	=	$this->input->post('address');
		$data['remarks']	=	$this->input->post('remarks');
		$data['timestamp']	=	time();
		$data['updated_by']	=	$this->session->userdata('user_id');

		$this->db->where('property_id', $property_id);
		$this->db->update('property', $data);

		$this->session->set_tempdata('success', $this->lang->line('property_updated_successfully'), 3);

		redirect(base_url('properties'), 'refresh');
	}

	function remove_property($property_id = '')
	{
		$this->db->where('property_id', $property_id);
		$this->db->delete('property');

		$this->session->set_tempdata('success', $this->lang->line('property_deleted_successfully'), 3);

		redirect(base_url('properties'), 'refresh');
	}

	function add_room()
	{
		$rooms 							= 	$this->db->get('room')->result_array();
		foreach ($rooms as $room) {
			if ($room['room_number'] == $this->input->post('room_number') && $room['floor'] == $this->input->post('floor')) {
				$this->session->set_tempdata('warning', $this->lang->line('room_already_exists'), 3);

				redirect(base_url() . 'add_room', 'refresh');
			}
		}

		$data['room_number']			=	$this->input->post('room_number');
		$data['daily_rent']				=	$this->input->post('daily_rent');
		$data['monthly_rent']			=	$this->input->post('monthly_rent');
		$data['yearly_rent']			=	$this->input->post('yearly_rent');
		$data['status']					=	0;
		$data['floor']					=	$this->input->post('floor');
		$data['property_id']			=	$this->input->post('property_id') ? $this->input->post('property_id') : 0;
		$data['remarks']				=	$this->input->post('remarks');
		$data['created_on']				=	time();
		$data['created_by']				=	$this->session->userdata('user_id');
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->insert('room', $data);

		$this->session->set_tempdata('success', $this->lang->line('room_added_successfully'), 3);

		redirect(base_url() . 'rooms', 'refresh');
	}

	function update_room($room_id = '')
	{
		$existing_room_number 			=	$this->db->get_where('room', array('room_id' => $room_id))->row()->room_number;
		$existing_floor_number			=	$this->db->get_where('room', array('room_id' => $room_id))->row()->floor;

		if ($existing_room_number != $this->input->post('room_number') || $existing_floor_number != $this->input->post('floor')) {
			$rooms 							= 	$this->db->get('room')->result_array();
			foreach ($rooms as $room) {
				if ($room['room_number'] == $this->input->post('room_number') && $room['floor'] == $this->input->post('floor')) {
					$this->session->set_tempdata('warning', $this->lang->line('room_already_exists'), 3);

					redirect(base_url() . 'rooms', 'refresh');
				}
			}
		}

		$data['room_number']			=	$this->input->post('room_number');
		$data['daily_rent']				=	$this->input->post('daily_rent');
		$data['monthly_rent']			=	$this->input->post('monthly_rent');
		$data['yearly_rent']			=	$this->input->post('yearly_rent');
		$data['floor']					=	$this->input->post('floor');
		$data['property_id']			=	$this->input->post('property_id') ? $this->input->post('property_id') : 0;
		$data['remarks']				=	$this->input->post('remarks');
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->where('room_id', $room_id);
		$this->db->update('room', $data);

		$this->session->set_tempdata('success', $this->lang->line('room_updated_successfully'), 3);

		redirect(base_url('rooms'), 'refresh');
	}

	function remove_room($room_id = '')
	{
		$this->db->where('room_id', $room_id);
		$this->db->delete('room');

		$this->session->set_tempdata('success', $this->lang->line('room_deleted_successfully'), 3);

		redirect(base_url() . 'rooms', 'refresh');
	}

	function assign_tenant($room_id = '')
	{
		$data['status']			=	1;
		$data['timestamp']		=	time();
		$data['updated_by']		=	$this->session->userdata('user_id');

		$this->db->where('room_id', $room_id);
		$this->db->update('room', $data);

		$data2['room_id']		=	$room_id;
		$data2['status']		=	1;
		$data2['timestamp']		=	time();
		$data2['updated_by']	=	$this->session->userdata('user_id');

		$this->db->where('tenant_id', $this->input->post('tenant_id'));
		$this->db->update('tenant', $data2);

		$array = array('user_type' => 3, 'person_id' => $this->input->post('tenant_id'));
		$this->db->where($array);
		$this->db->update('user', $data);

		$this->session->set_tempdata('success', $this->lang->line('room_assigned_successfully'), 3);

		redirect(base_url() . 'rooms', 'refresh');
	}

	function vacant_room($room_id = '')
	{
		$data['status']			=	0;
		$data['timestamp']		=	time();
		$data['updated_by']		=	$this->session->userdata('user_id');

		$this->db->where('room_id', $room_id);
		$this->db->update('room', $data);

		$tenant_id 				=	$this->db->get_where('tenant', array('room_id' => $room_id))->row()->tenant_id;

		$data2['room_id']		=	0;
		$data2['status']		=	0;
		$data2['timestamp']		=	time();
		$data2['updated_by']	=	$this->session->userdata('user_id');

		$this->db->where('tenant_id', $tenant_id);
		$this->db->update('tenant', $data2);

		$this->session->set_tempdata('success', $this->lang->line('room_vacant_now'), 3);

		redirect(base_url() . 'rooms', 'refresh');
	}

	function book_room()
	{
		if ($this->db->get_where('setting', array('setting_id' => 23))->row()->content == 'yes') {
			if ($this->input->post('email')) {
				$users = $this->db->get('user')->result_array();
				foreach ($users as $user) {
					if ($user['email'] == $this->input->post('email')) {
						$this->session->set_tempdata('warning', $this->lang->line('tenant_email_already_registered'), 3);
	
						redirect(base_url('book_room'), 'refresh');
					}
				}
			}
	
			$data['name']			=	$this->input->post('name');
			$data['mobile_number']	=	$this->input->post('mobile_number');
			$data['email']			=	$this->input->post('email');
			$data['room_id']		=	$this->input->post('room_id') ? $this->input->post('room_id') : 0;
			$data['status']			=	0;
			$data['created_on']		=	time();
			$data['timestamp']		=	time();
	
			$this->db->insert('tenant', $data);
	
			$this->session->set_tempdata('success', $this->lang->line('booking_room_was_successful'), 3);
	
			redirect(base_url('book_room'), 'refresh');
		} else {
			redirect(base_url() . 'login', 'refresh');
		}		
	}

	function add_tenant()
	{
		$ext 							= 	pathinfo($_FILES['image_link']['name'], PATHINFO_EXTENSION);
		$ext_id_front 					= 	pathinfo($_FILES['id_front_image_link']['name'], PATHINFO_EXTENSION);
		$ext_id_back 					= 	pathinfo($_FILES['id_back_image_link']['name'], PATHINFO_EXTENSION);
		$ext_lease 						= 	pathinfo($_FILES['lease_link']['name'], PATHINFO_EXTENSION);

		$users = $this->db->get('user')->result_array();
		foreach ($users as $user) {
			if ($user['email'] == $this->input->post('email')) {
				$this->session->set_tempdata('warning', $this->lang->line('tenant_email_already_registered'), 3);

				redirect(base_url() . 'add_tenant', 'refresh');
			}
		}

		if ($this->input->post('status') && !($this->input->post('room_id'))) {
			$this->session->set_tempdata('warning', $this->lang->line('tenant_activate_assign_room'), 3);

			redirect(base_url() . 'add_tenant', 'refresh');
		} elseif (($this->input->post('opt_in_for_recurring_invoice') == 'yes') && !$this->input->post('lease_start') && !$this->input->post('lease_end')) {
			$this->session->set_tempdata('warning', $this->lang->line('opt_in_for_recurring_invoice_needs_lease_dates'), 3);

			redirect(base_url() . 'add_tenant', 'refresh');
		} elseif (!($this->input->post('status')) && $this->input->post('room_id')) {
			$this->session->set_tempdata('warning', $this->lang->line('tenant_assign_room_must_activate'), 3);

			redirect(base_url() . 'add_tenant', 'refresh');
		} else {
			if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'JPEG' || $ext == 'JPG' || $ext == 'PNG') {
				$data['image_link'] 			= 	strtolower(explode(" ", $this->input->post('name'))[0]) . '_' . time() . '.' . $ext;

				move_uploaded_file($_FILES['image_link']['tmp_name'], 'uploads/tenants/' . $data['image_link']);
			}

			if ($ext_id_front == 'jpeg' || $ext_id_front == 'jpg' || $ext_id_front == 'png' || $ext_id_front == 'JPEG' || $ext_id_front == 'JPG' || $ext_id_front == 'PNG') {
				$data['id_front_image_link'] 	= 	strtolower(explode(" ", $this->input->post('name'))[0]) . '_id_front_' . time() . '.' . $ext_id_front;

				move_uploaded_file($_FILES['id_front_image_link']['tmp_name'], 'uploads/tenants/' . $data['id_front_image_link']);
			}

			if ($ext_id_back == 'jpeg' || $ext_id_back == 'jpg' || $ext_id_back == 'png' || $ext_id_back == 'JPEG' || $ext_id_back == 'JPG' || $ext_id_back == 'PNG') {
				$data['id_back_image_link'] 	= 	strtolower(explode(" ", $this->input->post('name'))[0]) . '_id_back_' . time() . '.' . $ext_id_back;

				move_uploaded_file($_FILES['id_back_image_link']['tmp_name'], 'uploads/tenants/' . $data['id_back_image_link']);
			}

			if ($ext_lease == 'pdf' || $ext_lease == 'PDF') {
				$data['lease_link'] 			= 	strtolower(explode(" ", $this->input->post('name'))[0]) . '_lease_' . time() . '.' . $ext_lease;

				move_uploaded_file($_FILES['lease_link']['tmp_name'], 'uploads/tenants/' . $data['lease_link']);
			}

			$data['name']				=	$this->input->post('name');
			$data['mobile_number']		=	$this->input->post('mobile_number');
			$data['email']				=	$this->input->post('email');
			$data['id_type_id']			=	$this->input->post('id_type_id');
			$data['id_number']			=	$this->input->post('id_number');
			$data['home_address']		=	$this->input->post('home_address_line_1') . '<br>' . $this->input->post('home_address_line_2');
			$data['emergency_person']	=	$this->input->post('emergency_person');
			$data['emergency_contact']	=	$this->input->post('emergency_contact');
			$data['room_id']			=	$this->input->post('room_id') ? $this->input->post('room_id') : 0;

			if ($this->input->post('lease_start') && $this->input->post('lease_end')) {
				$data['lease_start']	=	strtotime($this->input->post('lease_start'));
				$data['lease_end']		=	strtotime($this->input->post('lease_end'));
			}

			$data['profession_id']					=	$this->input->post('profession_id');
			$data['work_address']					=	$this->input->post('work_address_line_1') . '<br>' . $this->input->post('work_address_line_2');
			$data['opt_in_for_recurring_invoice']	=	$this->input->post('opt_in_for_recurring_invoice');
			$data['status']							=	$this->input->post('status');
			$data['extra_note']						=	$this->input->post('extra_note');
			$data['created_on']						=	time();
			$data['created_by']						=	$this->session->userdata('user_id');
			$data['timestamp']						=	time();
			$data['updated_by']						=	$this->session->userdata('user_id');

			$this->db->insert('tenant', $data);

			if ($this->input->post('email')) {
				$data2['person_id']		=	$this->db->insert_id();
				$data2['email']			=	$this->input->post('email');
				$data2['password']		=	$this->input->post('password') ? password_hash($this->input->post('password'), PASSWORD_DEFAULT) : password_hash(123456, PASSWORD_DEFAULT);
				$data2['user_type']		=	3;
				$data2['status']		=	$this->input->post('status');
				$data2['created_on']	= 	time();
				$data2['created_by']	=	$this->session->userdata('user_id');
				$data2['timestamp']		=	time();
				$data2['updated_by']	=	$this->session->userdata('user_id');
				$data2['permissions']	=	'9,12,13';

				$this->db->insert('user', $data2);
			}

			if ($this->input->post('room_id')) {
				$data3['status']		=	1;
				$data3['timestamp']		=	time();
				$data3['updated_by']	=	$this->session->userdata('user_id');

				$this->db->where('room_id', $data['room_id']);
				$this->db->update('room', $data3);
			}

			$this->session->set_tempdata('success', $this->lang->line('tenant_added_successfully'), 3);

			redirect(base_url() . 'tenants', 'refresh');
		}
	}

	function change_tenant_image($tenant_id = '')
	{
		$ext 							= 	pathinfo($_FILES['image_link']['name'], PATHINFO_EXTENSION);

		if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'JPEG' || $ext == 'JPG' || $ext == 'PNG') {
			$image_link 				= 	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->image_link;

			if (isset($image_link)) unlink('uploads/tenants/' . $image_link);

			$tenant_name 				=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->name;

			$data['image_link'] 		= 	strtolower(explode(" ", $tenant_name)[0]) . '_' . time() . '.' . $ext;
			$data['timestamp']			=	time();
			$data['updated_by']			=	$this->session->userdata('user_id');

			move_uploaded_file($_FILES['image_link']['tmp_name'], 'uploads/tenants/' . $data['image_link']);

			$this->db->where('tenant_id', $tenant_id);
			$this->db->update('tenant', $data);

			$this->session->set_tempdata('success', $this->lang->line('tenant_image_updated_successfully'), 3);

			redirect(base_url() . 'tenants', 'refresh');
		} else {
			$this->session->set_tempdata('warning', $this->lang->line('wrong_image_supported_type'), 3);

			redirect(base_url() . 'tenants', 'refresh');
		}
	}

	function change_tenant_id_image($tenant_id = '')
	{
		$ext_id_front 					= 	pathinfo($_FILES['id_front_image_link']['name'], PATHINFO_EXTENSION);
		$ext_id_back 					= 	pathinfo($_FILES['id_back_image_link']['name'], PATHINFO_EXTENSION);

		if ($ext_id_front == 'jpeg' || $ext_id_front == 'jpg' || $ext_id_front == 'png' || $ext_id_front == 'JPEG' || $ext_id_front == 'JPG' || $ext_id_front == 'PNG') {
			$image_link 				= 	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->id_front_image_link;

			if (isset($image_link)) unlink('uploads/tenants/' . $image_link);

			$tenant_name 				=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->name;

			$data['id_front_image_link'] = 	strtolower(explode(" ", $tenant_name)[0]) . '_id_front_' . time() . '.' . $ext_id_front;
			$data['timestamp']			=	time();
			$data['updated_by']			=	$this->session->userdata('user_id');

			move_uploaded_file($_FILES['id_front_image_link']['tmp_name'], 'uploads/tenants/' . $data['id_front_image_link']);

			$this->db->where('tenant_id', $tenant_id);
			$this->db->update('tenant', $data);

			$this->session->set_tempdata('success', $this->lang->line('tenant_image_front_success'), 3);
		} else {
			$this->session->set_tempdata('warning', $this->lang->line('wrong_image_supported_type'), 3);
		}

		if ($ext_id_back == 'jpeg' || $ext_id_back == 'jpg' || $ext_id_back == 'png' || $ext_id_back == 'JPEG' || $ext_id_back == 'JPG' || $ext_id_back == 'PNG') {
			$image_link 				= 	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->id_back_image_link;

			if (isset($image_link)) unlink('uploads/tenants/' . $image_link);

			$tenant_name 				=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->name;

			$data['id_back_image_link'] = 	strtolower(explode(" ", $tenant_name)[0]) . '_id_back_' . time() . '.' . $ext_id_back;
			$data['timestamp']			=	time();
			$data['updated_by']			=	$this->session->userdata('user_id');

			move_uploaded_file($_FILES['id_back_image_link']['tmp_name'], 'uploads/tenants/' . $data['id_back_image_link']);

			$this->db->where('tenant_id', $tenant_id);
			$this->db->update('tenant', $data);

			$this->session->set_tempdata('success', $this->lang->line('tenant_image_back_success'), 3);
		} else {
			$this->session->set_tempdata('warning', $this->lang->line('wrong_image_supported_type'), 3);
		}

		redirect(base_url() . 'tenants', 'refresh');
	}

	function change_tenant_lease($tenant_id = '')
	{
		$ext 							= 	pathinfo($_FILES['lease_link']['name'], PATHINFO_EXTENSION);

		if ($ext == 'pdf' || $ext == 'PDF') {
			$lease_link 				= 	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->lease_link;

			if (isset($lease_link)) unlink('uploads/tenants/' . $lease_link);

			$tenant_name 				=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->name;

			$data['lease_link'] 		= 	strtolower(explode(" ", $tenant_name)[0]) . '_lease_' . time() . '.' . $ext;
			$data['timestamp']			=	time();
			$data['updated_by']			=	$this->session->userdata('user_id');

			move_uploaded_file($_FILES['lease_link']['tmp_name'], 'uploads/tenants/' . $data['lease_link']);

			$this->db->where('tenant_id', $tenant_id);
			$this->db->update('tenant', $data);

			$this->session->set_tempdata('success', $this->lang->line('tenant_lease_updated_successfully'), 3);

			redirect(base_url('tenants'), 'refresh');
		} else {
			$this->session->set_tempdata('warning', $this->lang->line('tenant_pdf_supported_type'), 3);

			redirect(base_url('tenants'), 'refresh');
		}
	}

	function update_tenant($tenant_id = '')
	{
		$existing_room_id 				=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->room_id;

		if ($this->input->post('status') && !($this->input->post('room_id'))) {
			$this->session->set_tempdata('warning', $this->lang->line('tenant_activate_assign_room'), 3);

			redirect(base_url() . 'tenants', 'refresh');
		} elseif (!($this->input->post('status')) && $this->input->post('room_id')) {
			$this->session->set_tempdata('warning', $this->lang->line('tenant_assign_room_must_activate'), 3);

			redirect(base_url() . 'tenants', 'refresh');
		} elseif (!($this->input->post('status')) && !($this->input->post('room_id'))) {
			$data4['status']			=	0;
			$data4['timestamp']			=	time();
			$data4['updated_by']		=	$this->session->userdata('user_id');

			$this->db->where('room_id', $existing_room_id);
			$this->db->update('room', $data4);

			$data['room_id ']			= 	0;
		} else {
			if ($existing_room_id != $this->input->post('room_id')) {
				if ($existing_room_id > 0) {
					$data2['status']		=	0;
					$data2['timestamp']		=	time();
					$data2['updated_by']	=	$this->session->userdata('user_id');

					$this->db->where('room_id', $existing_room_id);
					$this->db->update('room', $data2);
				}

				$data3['status']		=	1;
				$data3['timestamp']		=	time();
				$data3['updated_by']	=	$this->session->userdata('user_id');

				$this->db->where('room_id', $this->input->post('room_id'));
				$this->db->update('room', $data3);

				$data['room_id ']		= 	$this->input->post('room_id');
			}
		}

		$data['name']							=	$this->input->post('name');
		$data['mobile_number']					=	$this->input->post('mobile_number');
		$data['email']							=	$this->input->post('email');
		$data['id_type_id']						=	$this->input->post('id_type_id');
		$data['id_number']						=	$this->input->post('id_number');
		$data['home_address']					=	$this->input->post('home_address_line_1') . '<br>' . $this->input->post('home_address_line_2');
		$data['emergency_person']				=	$this->input->post('emergency_person');
		$data['emergency_contact']				=	$this->input->post('emergency_contact');
		$data['profession_id']					=	$this->input->post('profession_id');
		$data['work_address']					=	$this->input->post('work_address_line_1') . '<br>' . $this->input->post('work_address_line_2');
		$data['opt_in_for_recurring_invoice']	=	$this->input->post('opt_in_for_recurring_invoice');
		$data['status']							=	$this->input->post('status');
		$data['extra_note']						=	$this->input->post('extra_note');
		$data['timestamp']						=	time();
		$data['updated_by']						=	$this->session->userdata('user_id');

		if ($this->input->post('lease_start') && $this->input->post('lease_end')) {
			$data['lease_start']	=	strtotime($this->input->post('lease_start'));
			$data['lease_end']		=	strtotime($this->input->post('lease_end'));
		}

		$this->db->where('tenant_id', $tenant_id);
		$this->db->update('tenant', $data);

		if ($this->input->post('email')) {
			$user_found = $this->db->get_where('user', array('user_type' => 3, 'person_id' => $tenant_id));
			$new_password = $this->input->post('password') ? $this->input->post('password') : 123456;
			if ($user_found->num_rows() > 0) {
				$user['email']		=	$this->input->post('email');
				$user['password']	=	$user_found->row()->password ? $user_found->row()->password : password_hash($new_password, PASSWORD_DEFAULT);
				$user['status']		=	$this->input->post('status');
				$user['timestamp']	=	time();
				$user['updated_by']	=	$this->session->userdata('user_id');

				$array = array('user_type' => 3, 'person_id' => $tenant_id);
				$this->db->where($array);
				$this->db->update('user', $user);
			} else {
				$user['person_id']		=	$tenant_id;
				$user['email']			=	$this->input->post('email');
				$user['password']		=	$this->input->post('password') ? password_hash($this->input->post('password'), PASSWORD_DEFAULT) : password_hash(123456, PASSWORD_DEFAULT);
				$user['user_type']		=	3;
				$user['status']			=	$this->input->post('status');
				$user['created_on']		= 	time();
				$user['created_by']		=	$this->session->userdata('user_id');
				$user['timestamp']		=	time();
				$user['updated_by']		=	$this->session->userdata('user_id');
				$user['permissions']	=	'9,12,13';

				$this->db->insert('user', $user);
			}
		}

		$this->session->set_tempdata('success', $this->lang->line('tenant_updated_successfully'), 3);

		redirect(base_url() . 'tenants', 'refresh');
	}

	function deactivate_tenant($tenant_id = '')
	{
		$room_id 						=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->room_id;

		$data['status']					=	0;
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		if ($room_id) {
			$this->db->where('room_id', $room_id);
			$this->db->update('room', $data);
		}

		$data2['room_id']				=	0;
		$data2['status']				=	0;
		$data2['timestamp']				=	time();
		$data2['updated_by']			=	$this->session->userdata('user_id');

		$this->db->where('tenant_id', $tenant_id);
		$this->db->update('tenant', $data2);

		if ($this->db->get_where('user', array('user_type' => 3, 'person_id' => $tenant_id))->num_rows() > 0) {
			$array = array('user_type' => 3, 'person_id' => $tenant_id);
			$this->db->where($array);
			$this->db->update('user', $data);
		}

		$this->session->set_tempdata('success', $this->lang->line('tenant_deactivated_successfully'), 3);

		redirect(base_url() . 'tenants', 'refresh');
	}

	function remove_tenant($tenant_id = '')
	{
		$tenant = $this->db->get_where('tenant', array('tenant_id' => $tenant_id));
		$room_id = $tenant->row()->room_id;

		$room['status']		=	0;
		$room['timestamp']	=	time();
		$room['updated_by']	=	$this->session->userdata('user_id');

		// ROOM
		if ($room_id && $this->db->get_where('room', array('room_id' => $room_id))->num_rows() > 0) {
			$this->db->where('room_id', $room_id);
			$this->db->update('room', $room);
		}

		$image_link = $tenant->row()->image_link;
		$id_front_image_link = $tenant->row()->id_front_image_link;
		$id_back_image_link = $tenant->row()->id_back_image_link;
		$lease_link = $tenant->row()->lease_link;

		if (isset($image_link)) unlink('uploads/tenants/' . $image_link);
		if (isset($id_front_image_link)) unlink('uploads/tenants/' . $id_front_image_link);
		if (isset($id_back_image_link)) unlink('uploads/tenants/' . $id_back_image_link);
		if (isset($lease_link)) unlink('uploads/tenants/' . $lease_link);

		// TENANT
		$this->db->where('tenant_id', $tenant_id);
		$this->db->delete('tenant');

		// INVOICE + INVOICE_SERVICE + INVOICE_CUSTOM_SERVICE
		$invoices = $this->db->select('invoice_id')->get_where('invoice', array('tenant_id' => $tenant_id))->result_array();
		foreach ($invoices as $to_be_deleted_invoice) {
			$invoice_services = $this->db->select('invoice_service_id')->get_where('invoice_service', array('invoice_id' => $to_be_deleted_invoice['invoice_id']))->result_array();
			foreach ($invoice_services as $to_be_deleted_invoice_service) {
				$this->db->where('invoice_service_id', $to_be_deleted_invoice_service['invoice_service_id']);
				$this->db->delete('invoice_service');
			}

			$invoice_custom_services = $this->db->select('invoice_custom_service_id')->get_where('invoice_custom_service', array('invoice_id' => $to_be_deleted_invoice['invoice_id']))->result_array();
			foreach ($invoice_custom_services as $to_be_deleted_invoice_custom_service) {
				$this->db->where('invoice_custom_service_id', $to_be_deleted_invoice_custom_service['invoice_custom_service_id']);
				$this->db->delete('invoice_custom_service');
			}

			$invoice_number = $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->invoice_number;
			if (isset($invoice_number)) unlink('uploads/invoices/' . $invoice_number . '.pdf');

			$this->db->where('invoice_id', $to_be_deleted_invoice['invoice_id']);
			$this->db->delete('invoice');
		}

		// TENANT_RENT
		foreach ($this->db->select('tenant_rent_id')->get_where('tenant_rent', array('tenant_id' => $tenant_id))->result_array() as $to_be_deleted_tenant_rent) {
			$this->db->where('tenant_rent_id', $to_be_deleted_tenant_rent['tenant_rent_id']);
			$this->db->delete('tenant_rent');
		}

		// TENANT_PAID
		foreach ($this->db->select('tenant_paid_id')->get_where('tenant_paid', array('tenant_id' => $tenant_id))->result_array() as $to_be_deleted_tenant_paid) {
			$this->db->where('tenant_paid_id', $to_be_deleted_tenant_paid['tenant_paid_id']);
			$this->db->delete('tenant_paid');
		}

		// COMPLAINTS + COMPLAINT_DETAILS
		foreach ($this->db->select('complaint_id')->get_where('complaint', array('tenant_id' => $tenant_id))->result_array() as $to_be_deleted_complaint) {
			$complaint_details = $this->db->select('complaint_details_id')->get_where('complaint_details', array('complaint_id' => $to_be_deleted_complaint['complaint_id']))->result_array();
			foreach ($complaint_details as $to_be_deleted_complaint_detail) {
				$this->db->where('complaint_details_id', $to_be_deleted_complaint_detail['complaint_details_id']);
				$this->db->delete('complaint_details');
			}

			$complaint = $this->db->get_where('complaint', array('complaint_id' => $to_be_deleted_complaint['complaint_id']));

			$complaint_picture_1 = $complaint->row()->complaint_picture_1;
			$complaint_picture_2 = $complaint->row()->complaint_picture_2;
			$complaint_video = $complaint->row()->complaint_video;

			if (isset($complaint_picture_1)) unlink('uploads/complaints/' . $complaint_picture_1);
			if (isset($complaint_picture_2)) unlink('uploads/complaints/' . $complaint_picture_2);
			if (isset($complaint_video)) unlink('uploads/complaints/' . $complaint_video);
			
			$this->db->where('complaint_id', $to_be_deleted_complaint['complaint_id']);
			$this->db->delete('complaint');
		}

		// USER
		if ($this->db->get_where('user', array('user_type' => 3, 'person_id' => $tenant_id))->num_rows() > 0) {
			$array = array('user_type' => 3, 'person_id' => $tenant_id);
			$this->db->where($array);
			$this->db->delete('user');
		}

		$this->session->set_tempdata('success', $this->lang->line('tenant_deleted_successfully'), 3);

		redirect(base_url('tenants'), 'refresh');
	}

	function add_utility_bill()
	{
		$ext 								= 	pathinfo($_FILES['image_link']['name'], PATHINFO_EXTENSION);

		if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'JPEG' || $ext == 'JPG' || $ext == 'PNG') {
			$data['image_link'] 			= 	'utility_' . $this->input->post('year') . '_' . $this->input->post('month') . '_' . time() . '.' . $ext;

			move_uploaded_file($_FILES['image_link']['tmp_name'], 'uploads/bills/' . $data['image_link']);
		}

		$data['utility_bill_category_id']	=	$this->input->post('utility_bill_category_id');
		$data['year']						=	$this->input->post('year');
		$data['month']						=	$this->input->post('month');
		$data['amount']						=	$this->input->post('amount');
		$data['status']						=	$this->input->post('status');
		$data['created_on']					=	time();
		$data['created_by']					=	$this->session->userdata('user_id');
		$data['timestamp']					=	time();
		$data['updated_by']					=	$this->session->userdata('user_id');

		$this->db->insert('utility_bill', $data);

		$this->session->set_tempdata('success', $this->lang->line('utility_bill_added_successfully'), 3);

		redirect(base_url() . 'utility_bills', 'refresh');
	}

	function update_utility_bill($utility_bill_id = '')
	{
		$data['utility_bill_category_id']	=	$this->input->post('utility_bill_category_id');
		$data['year']						=	$this->input->post('year');
		$data['month']						=	$this->input->post('month');
		$data['amount']						=	$this->input->post('amount');
		$data['status']						=	$this->input->post('status');
		$data['timestamp']					=	time();
		$data['updated_by']					=	$this->session->userdata('user_id');

		$this->db->where('utility_bill_id', $utility_bill_id);
		$this->db->update('utility_bill', $data);

		$this->session->set_tempdata('success', $this->lang->line('utility_bill_updated_successfully'), 3);

		redirect(base_url() . 'utility_bills', 'refresh');
	}

	function change_utility_image($utility_bill_id = '')
	{
		$ext 							= 	pathinfo($_FILES['image_link']['name'], PATHINFO_EXTENSION);

		if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'JPEG' || $ext == 'JPG' || $ext == 'PNG') {
			$image_link 				= 	$this->db->get_where('utility_bill', array('utility_bill_id' => $utility_bill_id))->row()->image_link;

			if (isset($image_link)) unlink('uploads/bills/' . $image_link);

			$year 						=	$this->db->get_where('utility_bill', array('utility_bill_id' => $utility_bill_id))->row()->year;
			$month 						=	$this->db->get_where('utility_bill', array('utility_bill_id' => $utility_bill_id))->row()->month;

			$data['image_link'] 		= 	'utility_' . $year . '_' . $month . '_' . time() . '.' . $ext;
			$data['timestamp']			=	time();
			$data['updated_by']			=	$this->session->userdata('user_id');

			move_uploaded_file($_FILES['image_link']['tmp_name'], 'uploads/bills/' . $data['image_link']);

			$this->db->where('utility_bill_id', $utility_bill_id);
			$this->db->update('utility_bill', $data);

			$this->session->set_tempdata('success', $this->lang->line('utility_bill_image_updated_successfully'), 3);

			redirect(base_url() . 'utility_bills', 'refresh');
		} else {
			$this->session->set_tempdata('warning', $this->lang->line('wrong_image_supported_type'), 3);

			redirect(base_url() . 'utility_bills', 'refresh');
		}
	}

	function remove_utility_bill($utility_bill_id = '')
	{
		$this->db->where('utility_bill_id', $utility_bill_id);
		$this->db->delete('utility_bill');

		$this->session->set_tempdata('success', $this->lang->line('utility_bill_deleted_successfully'), 3);

		redirect(base_url() . 'utility_bills', 'refresh');
	}

	// Function related to adding utility bill category
	function add_utility_bill_category()
	{
		$data['name']					=	$this->input->post('name');
		$data['created_on']				=	time();
		$data['created_by']				=	$this->session->userdata('user_id');
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->insert('utility_bill_category', $data);

		$this->session->set_tempdata('success', $this->lang->line('utility_bill_cat_added_successfully'), 3);

		redirect(base_url() . 'utility_bill_categories', 'refresh');
	}

	// Function related to updating utility bill category
	function update_utility_bill_category($utility_bill_category_id = '')
	{
		$data['name']					=	$this->input->post('name');
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->where('utility_bill_category_id', $utility_bill_category_id);
		$this->db->update('utility_bill_category', $data);

		$this->session->set_tempdata('success', $this->lang->line('utility_bill_cat_updated_successfully'), 3);

		redirect(base_url() . 'utility_bill_categories', 'refresh');
	}

	// Function related to removing utility bill category
	function remove_utility_bill_category($utility_bill_category_id = '')
	{
		$this->db->where('utility_bill_category_id', $utility_bill_category_id);
		$this->db->delete('utility_bill_category');

		$this->session->set_tempdata('success', $this->lang->line('utility_bill_cat_deleted_successfully'), 3);

		redirect(base_url() . 'utility_bill_categories', 'refresh');
	}

	function add_expense()
	{
		$data['name']						=	$this->input->post('name');
		$data['amount']						=	$this->input->post('amount');
		$data['description']				=	$this->input->post('description');
		$data['year']						=	$this->input->post('year');
		$data['month']						=	$this->input->post('month');
		$data['created_on']					=	time();
		$data['created_by']					=	$this->session->userdata('user_id');
		$data['timestamp']					=	time();
		$data['updated_by']					=	$this->session->userdata('user_id');

		$this->db->insert('expense', $data);

		$this->session->set_tempdata('success', $this->lang->line('expense_added_successfully'), 3);

		redirect(base_url() . 'expenses', 'refresh');
	}

	function update_expense($expense_id = '')
	{
		$data['name']						=	$this->input->post('name');
		$data['amount']						=	$this->input->post('amount');
		$data['description']				=	$this->input->post('description');
		$data['year']						=	$this->input->post('year');
		$data['month']						=	$this->input->post('month');
		$data['timestamp']					=	time();
		$data['updated_by']					=	$this->session->userdata('user_id');

		$this->db->where('expense_id', $expense_id);
		$this->db->update('expense', $data);

		$this->session->set_tempdata('success', $this->lang->line('expense_updated_successfully'), 3);

		redirect(base_url() . 'expenses', 'refresh');
	}

	function remove_expense($expense_id = '')
	{
		$this->db->where('expense_id', $expense_id);
		$this->db->delete('expense');

		$this->session->set_tempdata('success', $this->lang->line('expense_deleted_successfully'), 3);

		redirect(base_url() . 'expenses', 'refresh');
	}

	function add_inventory()
	{
		$data['name']						=	$this->input->post('name');
		$data['price']						=	$this->input->post('price');
		$data['quantity']					=	$this->input->post('quantity');
		$data['created_on']					=	time();
		$data['created_by']					=	$this->session->userdata('user_id');
		$data['timestamp']					=	time();
		$data['updated_by']					=	$this->session->userdata('user_id');

		$this->db->insert('inventory', $data);

		$this->session->set_tempdata('success', $this->lang->line('inventory_added_successfully'), 3);

		redirect(base_url() . 'inventory', 'refresh');
	}

	function update_inventory($inventory_id = '')
	{
		$data['name']						=	$this->input->post('name');
		$data['price']						=	$this->input->post('price');
		$data['quantity']					=	$this->input->post('quantity');
		$data['timestamp']					=	time();
		$data['updated_by']					=	$this->session->userdata('user_id');

		$this->db->where('inventory_id', $inventory_id);
		$this->db->update('inventory', $data);

		$this->session->set_tempdata('success', $this->lang->line('inventory_updated_successfully'), 3);

		redirect(base_url() . 'inventory', 'refresh');
	}

	function remove_inventory($inventory_id = '')
	{
		$this->db->where('inventory_id', $inventory_id);
		$this->db->delete('inventory');

		$this->session->set_tempdata('success', $this->lang->line('inventory_deleted_successfully'), 3);

		redirect(base_url() . 'inventory', 'refresh');
	}

	function add_staff()
	{
		$users = $this->db->get('user')->result_array();
		foreach ($users as $user) {
			if ($user['email'] == $this->input->post('email')) {
				$this->session->set_tempdata('warning', $this->lang->line('tenant_email_already_registered'), 3);

				redirect(base_url() . 'add_staff', 'refresh');
			}
		}

		$data['name']					=	$this->input->post('name');
		$data['role']					=	$this->input->post('role');
		$data['mobile_number']			=	$this->input->post('mobile_number');
		$data['status']					=	$this->input->post('status');
		$data['remarks']				=	$this->input->post('remarks');
		$data['created_on']				= 	time();
		$data['created_by']				=	$this->session->userdata('user_id');
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->insert('staff', $data);

		if ($this->input->post('email')) {
			$data2['person_id']				=	$this->db->insert_id();
			$data2['email']					=	$this->input->post('email');
			$data2['password']				=	$this->input->post('password') ? password_hash($this->input->post('password'), PASSWORD_DEFAULT) : password_hash(123456, PASSWORD_DEFAULT);
			$data2['user_type']				=	2;
			$data2['status']				=	$this->input->post('status');
			$data2['created_on']			= 	time();
			$data2['created_by']			=	$this->session->userdata('user_id');
			$data2['timestamp']				=	time();
			$data2['updated_by']			=	$this->session->userdata('user_id');

			$this->db->insert('user', $data2);

			$permission 					= 	$this->input->post('permission');

			if (isset($permission)) {
				$this->update_staff_permission($data2['person_id'], $permission);
			}
		}

		$this->session->set_tempdata('success', $this->lang->line('staff_added_successfully'), 3);

		redirect(base_url() . 'staff', 'refresh');
	}

	function update_staff($staff_id = '')
	{
		$data['name']					=	$this->input->post('name');
		$data['role']					=	$this->input->post('role');
		$data['mobile_number']			=	$this->input->post('mobile_number');
		$data['status']					=	$this->input->post('status');
		$data['remarks']				=	$this->input->post('remarks');
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->where('staff_id', $staff_id);
		$this->db->update('staff', $data);

		if ($this->input->post('email')) {
			if ($this->db->get_where('user', array('user_type' => 2, 'person_id' => $staff_id))->num_rows() > 0) {
				$data2['email']					=	$this->input->post('email');
				$data2['status']				=	$this->input->post('status');
				$data2['timestamp']				=	time();
				$data2['updated_by']			=	$this->session->userdata('user_id');

				$array = array('user_type' => 2, 'person_id' => $staff_id);
				$this->db->where($array);
				$this->db->update('user', $data2);
			} else {
				$data2['person_id']				=	$staff_id;
				$data2['email']					=	$this->input->post('email');
				$data2['password']				=	$this->input->post('password') ? password_hash($this->input->post('password'), PASSWORD_DEFAULT) : password_hash(123456, PASSWORD_DEFAULT);
				$data2['user_type']				=	2;
				$data2['status']				=	$this->input->post('status');
				$data2['created_on']			= 	time();
				$data2['created_by']			=	$this->session->userdata('user_id');
				$data2['timestamp']				=	time();
				$data2['updated_by']			=	$this->session->userdata('user_id');

				$this->db->insert('user', $data2);
			}

			$permission 						= 	$this->input->post('permission');

			if (isset($permission)) {
				$this->update_staff_permission($staff_id, $permission);
			}
		}

		$this->session->set_tempdata('success', $this->lang->line('staff_updated_successfully'), 3);

		redirect(base_url() . 'staff', 'refresh');
	}

	function deactivate_staff($staff_id = '')
	{
		$data['status']					=	0;
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->where('staff_id', $staff_id);
		$this->db->update('staff', $data);

		if ($this->db->get_where('user', array('user_type' => 2, 'person_id' => $staff_id))->num_rows() > 0) {
			$array = array('user_type' => 2, 'person_id' => $staff_id);
			$this->db->where($array);
			$this->db->update('user', $data);
		}

		$this->session->set_tempdata('success', $this->lang->line('staff_deactivated_successfully'), 3);

		redirect(base_url() . 'staff', 'refresh');
	}

	function remove_staff($staff_id = '')
	{
		$this->db->where('staff_id', $staff_id);
		$this->db->delete('staff');

		foreach ($this->select('staff_salary_id')->db->get_where('staff_salary', array('staff_id' => $staff_id))->result_array() as $to_be_deleted_staff_salary) {
			$this->db->where('staff_salary_id', $to_be_deleted_staff_salary['staff_salary_id']);
			$this->db->delete('staff_salary');
		}

		if ($this->db->get_where('user', array('user_type' => 2, 'person_id' => $staff_id))->num_rows() > 0) {
			$array = array('user_type' => 2, 'person_id' => $staff_id);
			$this->db->where($array);
			$this->db->delete('user');
		}

		$this->session->set_tempdata('success', $this->lang->line('staff_deleted_successfully'), 3);

		redirect(base_url() . 'staff', 'refresh');
	}

	function update_staff_permission($staff_id = '', $permission = [])
	{
		$permissions 					=	'';

		foreach ($permission as $key => $value) {
			$permissions			.=	$value . ',';
		}

		$data['permissions']			=	substr(trim($permissions), 0, -1);
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$array = array('user_type' => 2, 'person_id' => $staff_id);
		$this->db->where($array);
		$this->db->update('user', $data);

		$this->session->set_tempdata('success', $this->lang->line('staff_permission_updated_successfully'), 3);

		redirect(base_url() . 'staff', 'refresh');
	}

	function add_staff_salary()
	{
		$data['staff_id']				=	$this->input->post('staff_id');
		$data['year']					=	$this->input->post('year');
		$data['month']					=	$this->input->post('month');
		$data['amount']					=	$this->input->post('amount');
		$data['status']					=	$this->input->post('status');
		$data['created_on']				= 	time();
		$data['created_by']				=	$this->session->userdata('user_id');
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->insert('staff_salary', $data);

		$this->session->set_tempdata('success', $this->lang->line('staff_salary_added_successfully'), 3);

		redirect(base_url('single_month_staff_payroll' . '/' . $data['year'] . '/' . $data['month']), 'refresh');
	}

	function update_staff_salary($staff_salary_id = '')
	{
		$data['staff_id']				=	$this->input->post('staff_id');
		$data['year']					=	$this->input->post('year');
		$data['month']					=	$this->input->post('month');
		$data['amount']					=	$this->input->post('amount');
		$data['status']					=	$this->input->post('status');
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->where('staff_salary_id', $staff_salary_id);
		$this->db->update('staff_salary', $data);

		$this->session->set_tempdata('success', $this->lang->line('staff_salary_updated_successfully'), 3);

		redirect(base_url() . 'staff_payroll', 'refresh');
	}

	function remove_staff_salary($staff_salary_id = '')
	{
		$this->db->where('staff_salary_id', $staff_salary_id);
		$this->db->delete('staff_salary');

		$this->session->set_tempdata('success', $this->lang->line('staff_salary_deleted_successfully'), 3);

		redirect(base_url() . 'staff_payroll', 'refresh');
	}

	function add_cleaner()
	{
		$data['name']					=	$this->input->post('name');
		$data['color_code']				=	$this->input->post('color_code');		
		$data['created_on']				=	time();
		$data['created_by']				=	$this->session->userdata('user_id');
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->insert('cleaner', $data);

		$this->session->set_tempdata('success', $this->lang->line('cleaner_added_successfully'));

		redirect(base_url() . 'cleaners', 'refresh');
	}

	function update_cleaner($cleaner_id = '')
	{
		$data['name']					=	$this->input->post('name');
		$data['color_code']				=	$this->input->post('color_code');
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->where('cleaner_id', $cleaner_id);
		$this->db->update('cleaner', $data);

		$this->session->set_tempdata('success', $this->lang->line('cleaner_updated_successfully'));

		redirect(base_url() . 'cleaners', 'refresh');
	}

	function remove_cleaner($cleaner_id = '')
	{
		$this->db->where('cleaner_id', $cleaner_id);
		$this->db->delete('cleaner');

		$this->session->set_tempdata('success', $this->lang->line('cleaner_deleted_successfully'));

		redirect(base_url() . 'cleaners', 'refresh');
	}
	
	function add_roster($year = '', $week = '')
	{
		$data['room_id']	=	$this->input->post('room_id');
		$data['cleaner_id']	=	$this->input->post('cleaner_id');
		$data['color_code']	=	$this->db->get_where('cleaner', array('cleaner_id' => $this->input->post('cleaner_id')))->row()->color_code;
		$data['start_date']	=	strtotime($this->input->post('start_date'));
		$data['end_date']	=	strtotime($this->input->post('end_date'));
		$data['created_on']	=	time();
		$data['created_by']	=	$this->session->userdata('user_id');
		$data['timestamp']	=	time();
		$data['updated_by']	=	$this->session->userdata('user_id');

		$this->db->insert('cleaning_schedule', $data);

		$this->session->set_tempdata('success', $this->lang->line('roster_added_successfully'));

		redirect(base_url('rosters/' . $year . '/' . $week), 'refresh');
	}

	function update_roster($year = '', $week = '', $cleaning_schedule_id = '')
	{
		$data['room_id']	=	$this->input->post('room_id');
		$data['cleaner_id']	=	$this->input->post('cleaner_id');
		$data['color_code']	=	$this->db->get_where('cleaner', array('cleaner_id' => $this->input->post('cleaner_id')))->row()->color_code;
		$data['start_date']	=	strtotime($this->input->post('start_date'));
		$data['end_date']	=	strtotime($this->input->post('end_date'));
		$data['timestamp']	=	time();
		$data['updated_by']	=	$this->session->userdata('user_id');

		$this->db->where('cleaning_schedule_id', $cleaning_schedule_id);
		$this->db->update('cleaning_schedule', $data);

		$this->session->set_tempdata('success', $this->lang->line('roster_updated_successfully'));

		redirect(base_url('rosters/' . $year . '/' . $week), 'refresh');
	}

	function remove_roster($year = '', $week = '', $cleaning_schedule_id = '')
	{
		if (file_exists('uploads/cleaning/' . $year . '_' . $week . '.pdf'))
			unlink('uploads/cleaning/' . $year . '_' . $week . '.pdf');

		$this->db->where('cleaning_schedule_id', $cleaning_schedule_id);
		$this->db->delete('cleaning_schedule');

		$this->session->set_tempdata('success', $this->lang->line('roster_deleted_successfully'));
		
		redirect(base_url('rosters/' . $year . '/' . $week), 'refresh');
	}

	function invoices()
	{
		$all_invoices = [];

		$this->db->order_by('timestamp', 'desc');
		if ($this->session->userdata('user_type') == 3) {
			$tenant_id = $this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->row()->person_id;
			$invoices = $this->db->get_where('invoice', array('tenant_id' => $tenant_id))->result_array();
		} else {
			$invoices = $this->db->get('invoice')->result_array();
		}

		foreach ($invoices as $row) {
			$data = [];

			$data['invoice_id'] = $row['invoice_id'];
			$data['invoice_number'] = $row['invoice_number'];
			$data['tenant_name'] = $row['tenant_name'];
			$data['tenant_mobile'] = $row['tenant_mobile'];
			$data['room_number'] = $row['room_number'];
			$data['property'] = $row['property_name'];
			$data['late_fee'] = $row['late_fee'];
			$data['due_date'] = date('d M, Y', $row['due_date']);
			$data['sms'] = $row['sms'];
			$data['email'] = $row['email'];
			$data['timestamp'] = $row['timestamp'];
			$data['updated_by'] = $row['updated_by'];

			$tenant_rent = 0;
			$invoice_service = 0;
			$invoice_custom_service = 0;
			$paid = 0;

			$this->db->select_sum('amount');
			$this->db->from('tenant_rent');
			$this->db->where('invoice_id', $row['invoice_id']);
			$tenant_rent = $this->db->get()->row()->amount;

			$this->db->select_sum('amount');
			$this->db->from('invoice_service');
			$this->db->where('invoice_id', $row['invoice_id']);
			$invoice_service = $this->db->get()->row()->amount;

			$this->db->select_sum('amount');
			$this->db->from('invoice_custom_service');
			$this->db->where('invoice_id', $row['invoice_id']);
			$invoice_custom_service = $this->db->get()->row()->amount;

			$data['amount'] = $tenant_rent + $invoice_service + $invoice_custom_service;

			$this->db->select_sum('amount');
			$this->db->from('tenant_paid');
			$this->db->where('invoice_id', $row['invoice_id']);
			$paid = $this->db->get()->row()->amount;

			$data['paid'] = $paid ? intval($paid) : 0;
			$data['open_balance'] = $data['amount'] + $row['late_fee'] - $data['paid'];

			if ($data['paid'] > 0) {
				if ($data['paid'] < ($data['amount'] + $row['late_fee']))
					$data['status'] = '<span class="badge badge-warning">' . $this->lang->line('partially_paid') . '</span>'; 
				else if ($data['paid'] >= ($data['amount'] + $row['late_fee']))
					$data['status'] = '<span class="badge badge-primary">' . $this->lang->line('paid') . '</span>';
			} else {
				$data['status'] = '<span class="badge badge-danger">' . $this->lang->line('due') . '</span>';
			}

			array_push($all_invoices, $data);
		}

		return $all_invoices;
	}

	function paid_invoices()
	{
		$paid_invoices = [];
		$invoices = [];

		$this->db->order_by('timestamp', 'desc');
		foreach ($this->db->get('invoice')->result_array() as $invoice) {
			$tenant_rent = 0;
			$invoice_service_amount = 0;
			$invoice_custom_service_amount = 0;
			$late_fee = $this->db->get_where('invoice', array('invoice_id' => $invoice['invoice_id']))->row()->late_fee;
			$paid_amount = 0;

			$this->db->select_sum('amount');
			$this->db->from('tenant_rent');
			$this->db->where('invoice_id', $invoice['invoice_id']);
			$tenant_rent = $this->db->get()->row()->amount;

			$this->db->select_sum('amount');
			$this->db->from('invoice_service');
			$this->db->where('invoice_id', $invoice['invoice_id']);
			$invoice_service_amount = $this->db->get()->row()->amount;

			$this->db->select_sum('amount');
			$this->db->from('invoice_custom_service');
			$this->db->where('invoice_id', $invoice['invoice_id']);
			$invoice_custom_service_amount = $this->db->get()->row()->amount;

			$this->db->select_sum('amount');
			$this->db->from('tenant_paid');
			$this->db->where('invoice_id', $invoice['invoice_id']);
			$paid_amount = $this->db->get()->row()->amount;

			if (($tenant_rent + $invoice_service_amount + $invoice_custom_service_amount + $late_fee) <= $paid_amount)
				array_push($invoices, $invoice);
		}

		foreach ($invoices as $row) {
			$data = [];

			$data['invoice_id'] = $row['invoice_id'];
			$data['invoice_number'] = $row['invoice_number'];
			$data['tenant_name'] = $row['tenant_name'];
			$data['tenant_mobile'] = $row['tenant_mobile'];
			$data['room_number'] = $row['room_number'];
			$data['property'] = $row['property_name'];
			$data['late_fee'] = $row['late_fee'];
			$data['due_date'] = date('d M, Y', $row['due_date']);
			$data['sms'] = $row['sms'];
			$data['email'] = $row['email'];
			$data['timestamp'] = $row['timestamp'];
			$data['updated_by'] = $row['updated_by'];

			$tenant_rent = 0;
			$invoice_service = 0;
			$invoice_custom_service = 0;
			$paid = 0;

			$this->db->select_sum('amount');
			$this->db->from('tenant_rent');
			$this->db->where('invoice_id', $row['invoice_id']);
			$tenant_rent = $this->db->get()->row()->amount;

			$this->db->select_sum('amount');
			$this->db->from('invoice_service');
			$this->db->where('invoice_id', $row['invoice_id']);
			$invoice_service = $this->db->get()->row()->amount;

			$this->db->select_sum('amount');
			$this->db->from('invoice_custom_service');
			$this->db->where('invoice_id', $row['invoice_id']);
			$invoice_custom_service = $this->db->get()->row()->amount;

			$data['amount'] = $tenant_rent + $invoice_service + $invoice_custom_service;

			$this->db->select_sum('amount');
			$this->db->from('tenant_paid');
			$this->db->where('invoice_id', $row['invoice_id']);
			$paid = $this->db->get()->row()->amount;

			$data['paid'] = $paid ? intval($paid) : 0;
			$data['open_balance'] = $data['amount'] + $row['late_fee'] - $data['paid'];

			if ($data['paid'] > 0) {
				if ($data['paid'] < ($data['amount'] + $row['late_fee']))
					$data['status'] = '<span class="badge badge-warning">' . $this->lang->line('partially_paid') . '</span>'; 
				else if ($data['paid'] >= ($data['amount'] + $row['late_fee']))
					$data['status'] = '<span class="badge badge-primary">' . $this->lang->line('paid') . '</span>';
			} else {
				$data['status'] = '<span class="badge badge-danger">' . $this->lang->line('due') . '</span>';
			}

			array_push($paid_invoices, $data);
		}

		return $paid_invoices;
	}

	function unpaid_invoices()
	{
		$unpaid_invoices = [];
		$invoices = [];

		$this->db->order_by('timestamp', 'desc');
		foreach ($this->db->get('invoice')->result_array() as $invoice) {
			$tenant_rent = 0;
			$invoice_service_amount = 0;
			$invoice_custom_service_amount = 0;
			$late_fee = $this->db->get_where('invoice', array('invoice_id' => $invoice['invoice_id']))->row()->late_fee;
			$paid_amount = 0;

			$this->db->select_sum('amount');
			$this->db->from('tenant_rent');
			$this->db->where('invoice_id', $invoice['invoice_id']);
			$tenant_rent = $this->db->get()->row()->amount;

			$this->db->select_sum('amount');
			$this->db->from('invoice_service');
			$this->db->where('invoice_id', $invoice['invoice_id']);
			$invoice_service_amount = $this->db->get()->row()->amount;

			$this->db->select_sum('amount');
			$this->db->from('invoice_custom_service');
			$this->db->where('invoice_id', $invoice['invoice_id']);
			$invoice_custom_service_amount = $this->db->get()->row()->amount;

			$this->db->select_sum('amount');
			$this->db->from('tenant_paid');
			$this->db->where('invoice_id', $invoice['invoice_id']);
			$paid_amount = $this->db->get()->row()->amount;

			if (($tenant_rent + $invoice_service_amount + $invoice_custom_service_amount + $late_fee) > $paid_amount)
				array_push($invoices, $invoice);
		}

		foreach ($invoices as $row) {
			$data = [];

			$data['invoice_id'] = $row['invoice_id'];
			$data['invoice_number'] = $row['invoice_number'];
			$data['tenant_name'] = $row['tenant_name'];
			$data['tenant_mobile'] = $row['tenant_mobile'];
			$data['room_number'] = $row['room_number'];
			$data['property'] = $row['property_name'];
			$data['late_fee'] = $row['late_fee'];
			$data['due_date'] = date('d M, Y', $row['due_date']);
			$data['sms'] = $row['sms'];
			$data['email'] = $row['email'];
			$data['timestamp'] = $row['timestamp'];
			$data['updated_by'] = $row['updated_by'];

			$tenant_rent = 0;
			$invoice_service = 0;
			$invoice_custom_service = 0;
			$paid = 0;

			$this->db->select_sum('amount');
			$this->db->from('tenant_rent');
			$this->db->where('invoice_id', $row['invoice_id']);
			$tenant_rent = $this->db->get()->row()->amount;

			$this->db->select_sum('amount');
			$this->db->from('invoice_service');
			$this->db->where('invoice_id', $row['invoice_id']);
			$invoice_service = $this->db->get()->row()->amount;

			$this->db->select_sum('amount');
			$this->db->from('invoice_custom_service');
			$this->db->where('invoice_id', $row['invoice_id']);
			$invoice_custom_service = $this->db->get()->row()->amount;

			$data['amount'] = $tenant_rent + $invoice_service + $invoice_custom_service;

			$this->db->select_sum('amount');
			$this->db->from('tenant_paid');
			$this->db->where('invoice_id', $row['invoice_id']);
			$paid = $this->db->get()->row()->amount;

			$data['paid'] = $paid ? intval($paid) : 0;
			$data['open_balance'] = $data['amount'] + $row['late_fee'] - $data['paid'];

			if ($data['paid'] > 0) {
				if ($data['paid'] < ($data['amount'] + $row['late_fee']))
					$data['status'] = '<span class="badge badge-warning">' . $this->lang->line('partially_paid') . '</span>'; 
				else if ($data['paid'] >= ($data['amount'] + $row['late_fee']))
					$data['status'] = '<span class="badge badge-primary">' . $this->lang->line('paid') . '</span>';
			} else {
				$data['status'] = '<span class="badge badge-danger">' . $this->lang->line('due') . '</span>';
			}

			array_push($unpaid_invoices, $data);
		}

		return $unpaid_invoices;
	}

	function generate_single_months_rent()
	{
		$tenants	=	[];
		$year		= 	$this->input->post('year');
		$month 		=	$this->input->post('month');

		if ($this->input->post('tenants')[0] == 'All') {
			$active_tenants = $this->db->get_where('tenant', array('status' => 1))->result_array();
			foreach ($active_tenants as $active_tenant) {
				array_push($tenants, $active_tenant['tenant_id']);
			}
		} else {
			$tenants = $this->input->post('tenants');
		}

		for ($i = 0; $i < sizeof($tenants); $i++) {
			$room_id 					=	$this->db->get_where('tenant', array('tenant_id' => $tenants[$i]))->row()->room_id;
			$room_number				= 	$this->db->get_where('room', array('room_id' => $room_id))->row()->room_number;
			$property_id 				=	$this->db->get_where('property', array('property_id' => $this->db->get_where('room', array('room_id' => $room_id))->row()->property_id));

			$invoice['tenant_name']		=	$this->db->get_where('tenant', array('tenant_id' => $tenants[$i]))->row()->name;
			$invoice['start_date']		=	strtotime($month . ' ' . '01' . ', ' . $year);
			$invoice['end_date']		=	strtotime($month . ' ' . date('t', strtotime($year . '-' . $month)) . ', ' . $year . '11:59:59 pm');
			$invoice['due_date']		=	strtotime($this->input->post('due_date') . '11:59:59 pm');
			$invoice['invoice_type']	=	2;
			$invoice['tenant_mobile']	=	$this->db->get_where('tenant', array('tenant_id' => $tenants[$i]))->row()->mobile_number;
			$invoice['room_number']		=	$room_number;
			$invoice['property_name']	=	$property_id->num_rows() > 0 ? $property_id->row()->name : '';
			$invoice['tenant_id']		=	$tenants[$i];
			$invoice['late_fee']		=	0;
			$invoice['invoice_number']	=	$year . date('m', strtotime($month)) . rand(1, 9999) . $tenants[$i];
			$invoice['created_on']		= 	time();
			$invoice['created_by']		=	$this->session->userdata('user_id');
			$invoice['timestamp']		=	time();
			$invoice['updated_by']		=	$this->session->userdata('user_id');

			$this->db->insert('invoice', $invoice);

			$invoice_id					=	$this->db->insert_id();

			$tenant_rent['month']		=	$month;
			$tenant_rent['year']		=	$year;
			$tenant_rent['amount']		=	$this->db->get_where('room', array('room_id' => $room_id))->row()->monthly_rent;
			$tenant_rent['invoice_id']	=	$invoice_id;
			$tenant_rent['tenant_id']	=	$tenants[$i];
			$tenant_rent['created_on']	= 	time();
			$tenant_rent['created_by']	=	$this->session->userdata('user_id');
			$tenant_rent['timestamp']	=	time();
			$tenant_rent['updated_by']	=	$this->session->userdata('user_id');

			$this->db->insert('tenant_rent', $tenant_rent);

			$tenant_services 					= 	$this->db->get_where('service_tenant', array('tenant_id' => $tenants[$i]))->result_array();
			foreach ($tenant_services as $tenant_service) {
				$invoice_service['service_id']	=	$tenant_service['service_id'];
				$invoice_service['year']     	=   $year;
				$invoice_service['month']      	=   $month;
				$invoice_service['invoice_id']	=   $invoice_id;
				$invoice_service['amount']		=	$this->db->get_where('service', array('service_id' => $tenant_service['service_id']))->row()->cost;
				$invoice_service['created_on']	= 	time();
				$invoice_service['created_by']	=	$this->session->userdata('user_id');
				$invoice_service['timestamp']	=	time();
				$invoice_service['updated_by']	=	$this->session->userdata('user_id');

				$this->db->insert('invoice_service', $invoice_service);
			}

			$invoice_log['invoice_id'] 		= 	$invoice_id;
			$invoice_log['invoice_type'] 	= 	2;
			$invoice_log['invoice_number'] 	= 	$invoice['invoice_number'];
			$invoice_log['generation_type']	= 	2;
			$invoice_log['created_on']		= 	time();
			$invoice_log['created_by']		=	$this->session->userdata('user_id');

			$this->db->insert('invoice_log', $invoice_log);
		}

		$this->session->set_tempdata('success', $this->lang->line('rent_monthly_generated_successfully'), 3);

		redirect(base_url() . 'invoices', 'refresh');
	}

	function generate_date_range_rents()
	{
		$tenant_id 					= 	$this->input->post('tenant_id');
		$start_date					=	strtotime($this->input->post('start'));
		$end_date					=	strtotime($this->input->post('end'));

		$room_id 					=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->room_id;
		$room_number				= 	$this->db->get_where('room', array('room_id' => $room_id))->row()->room_number;
		$property_id 				=	$this->db->get_where('property', array('property_id' => $this->db->get_where('room', array('room_id' => $room_id))->row()->property_id));

		$start_year  				= 	date('Y', $start_date);
		$end_year  					= 	date('Y', $end_date);
		$start_month  				= 	date('n', $start_date);
		$end_month  				= 	date('n', $end_date);
		$start_day 					= 	date('d', $start_date);
		$end_day 					= 	date('d', $end_date);

		$invoice['tenant_name']		=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->name;
		$invoice['start_date']		=	strtotime($this->input->post('start'));
		$invoice['end_date']		=	strtotime($this->input->post('end') . '11:59:59 pm');
		$invoice['due_date']		=	strtotime($this->input->post('due_date') . '11:59:59 pm');
		$invoice['invoice_type']	=	1;
		$invoice['tenant_mobile']	=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->mobile_number;
		$invoice['room_number']		=	$room_number;
		$invoice['property_name']	=	$property_id->num_rows() > 0 ? $property_id->row()->name : '';
		$invoice['tenant_id']		=	$tenant_id;
		$invoice['late_fee']		=	0;
		$invoice['invoice_number']	=	$start_year . $start_month . rand(1, 9999) . $tenant_id;
		$invoice['created_on']		= 	time();
		$invoice['created_by']		=	$this->session->userdata('user_id');
		$invoice['timestamp']		=	time();
		$invoice['updated_by']		=	$this->session->userdata('user_id');

		$this->db->insert('invoice', $invoice);

		$invoice_id					=	$this->db->insert_id();

		if ($start_year < $end_year) {
			for ($i = $start_month; $i <= ($end_month + 12); $i++) {
				if ($i > 12) {
					$year = $end_year;
					$month = date('F', strtotime($year . '-' . ($i - 12) . '-01'));
				} else {
					$year = $start_year;
					$month = date('F', strtotime($year . '-' . $i . '-01'));
				}
				
				$days = date('t', strtotime($year . '-' . $month));

				if ($i == $start_month) {
					$days = $days - $start_day + 1;
				} elseif ($i == ($end_month + 12)) {
					$days = $end_day;
				}

				$tenant_rent['month']		=	$month;
				$tenant_rent['year']		=	$year;
				$tenant_rent['amount']		=	$days * $this->db->get_where('room', array('room_id' => $room_id))->row()->daily_rent;
				$tenant_rent['invoice_id']	=	$invoice_id;
				$tenant_rent['tenant_id']	=	$tenant_id;
				$tenant_rent['created_on']	= 	time();
				$tenant_rent['created_by']	=	$this->session->userdata('user_id');
				$tenant_rent['timestamp']	=	time();
				$tenant_rent['updated_by']	=	$this->session->userdata('user_id');

				$this->db->insert('tenant_rent', $tenant_rent);
			}
		} else {
			for ($i = $start_month; $i <= $end_month; $i++) {
				$year = $start_year;
				$month = date('F', strtotime($year . '-' . $i . '-01'));
				$days = date('t', strtotime($year . '-' . $month));

				if ($start_month == $end_month) {
					$days = $end_day - $start_day + 1;
				} elseif ($i == $start_month) {
					$days = $days - $start_day + 1;
				} elseif ($i == $end_month) {
					$days = $end_day;
				}

				$tenant_rent['month']		=	$month;
				$tenant_rent['year']		=	$year;
				$tenant_rent['amount']		=	$days * $this->db->get_where('room', array('room_id' => $room_id))->row()->daily_rent;
				$tenant_rent['invoice_id']	=	$invoice_id;
				$tenant_rent['tenant_id']	=	$tenant_id;
				$tenant_rent['created_on']	= 	time();
				$tenant_rent['created_by']	=	$this->session->userdata('user_id');
				$tenant_rent['timestamp']	=	time();
				$tenant_rent['updated_by']	=	$this->session->userdata('user_id');

				$this->db->insert('tenant_rent', $tenant_rent);
			}
		}
		
		$tenant_services						= 	$this->db->get_where('service_tenant', array('tenant_id' => $tenant_id));
		if ($tenant_services->num_rows() > 0) {
			foreach ($tenant_services->result_array() as $tenant_service) {
				$invoice_service['service_id']	=	$tenant_service['service_id'];
				$invoice_service['year']     	=   date('Y');
				$invoice_service['month']      	=   date('F');
				$invoice_service['invoice_id'] 	=   $invoice_id;
				$invoice_service['amount']		=	$this->db->get_where('service', array('service_id' => $tenant_service['service_id']))->row()->cost;
				$invoice_service['created_on']	= 	time();
				$invoice_service['created_by']	=	$this->session->userdata('user_id');
				$invoice_service['timestamp']	=	time();
				$invoice_service['updated_by']	=	$this->session->userdata('user_id');
	
				$this->db->insert('invoice_service', $invoice_service);
			}
		}

		$invoice_log['invoice_id'] 		= 	$invoice_id;
		$invoice_log['invoice_type'] 	= 	1;
		$invoice_log['invoice_number'] 	= 	$invoice['invoice_number'];
		$invoice_log['generation_type']	= 	2;
		$invoice_log['created_on']		= 	time();
		$invoice_log['created_by']		=	$this->session->userdata('user_id');

		$this->db->insert('invoice_log', $invoice_log);

		$this->session->set_tempdata('success', $this->lang->line('rent_date_range_generated_successfully'), 3);

		redirect(base_url() . 'invoices', 'refresh');
	}

	function generate_multiple_months_rent()
	{
		$tenant_id 					= 	$this->input->post('tenant_id');
		$year 						=	$this->input->post('year');
		$months 					=	$this->input->post('months');

		$room_id 					=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->room_id;
		$room_number				= 	$this->db->get_where('room', array('room_id' => $room_id))->row()->room_number;
		$property_id 				=	$this->db->get_where('property', array('property_id' => $this->db->get_where('room', array('room_id' => $room_id))->row()->property_id));

		$invoice['tenant_name']		=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->name;
		$invoice['start_date']		=	strtotime($months[0] . ' ' . '01' . ', ' . $year);
		$invoice['end_date']		=	strtotime($months[count($months) - 1] . ' ' . date('t', strtotime($year . '-' . $months[count($months) - 1])) . ', ' . $year . '11:59:59 pm');
		$invoice['due_date']		=	strtotime($this->input->post('due_date') . '11:59:59 pm');
		$invoice['invoice_type']	=	2;
		$invoice['tenant_mobile']	=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->mobile_number;
		$invoice['room_number']		=	$room_number;
		$invoice['property_name']	=	$property_id->num_rows() > 0 ? $property_id->row()->name : '';
		$invoice['tenant_id']		=	$tenant_id;
		$invoice['late_fee']		=	0;
		$invoice['invoice_number']	=	$year . date('m', strtotime($months[0])) . rand(1, 9999) . $tenant_id;
		$invoice['created_on']		= 	time();
		$invoice['created_by']		=	$this->session->userdata('user_id');
		$invoice['timestamp']		=	time();
		$invoice['updated_by']		=	$this->session->userdata('user_id');

		$this->db->insert('invoice', $invoice);

		$invoice_id					=	$this->db->insert_id();

		for ($i = 0; $i < sizeof($months); $i++) {
			$tenant_rent['month']		=	$months[$i];
			$tenant_rent['year']		=	$year;
			$tenant_rent['amount']		=	$this->db->get_where('room', array('room_id' => $room_id))->row()->monthly_rent;
			$tenant_rent['invoice_id']	=	$invoice_id;
			$tenant_rent['tenant_id']	=	$tenant_id;
			$tenant_rent['created_on']	= 	time();
			$tenant_rent['created_by']	=	$this->session->userdata('user_id');
			$tenant_rent['timestamp']	=	time();
			$tenant_rent['updated_by']	=	$this->session->userdata('user_id');

			$this->db->insert('tenant_rent', $tenant_rent);
		}

		$tenant_services 						= 	$this->db->get_where('service_tenant', array('tenant_id' => $tenant_id));
		if ($tenant_services->num_rows() > 0) {
			foreach ($tenant_services->result_array() as $tenant_service) {
				$invoice_service['service_id']	=	$tenant_service['service_id'];
				$invoice_service['year']     	=   date('Y');
				$invoice_service['month']      	=   date('F');
				$invoice_service['invoice_id'] 	=   $invoice_id;
				$invoice_service['amount']		=	$this->db->get_where('service', array('service_id' => $tenant_service['service_id']))->row()->cost;
				$invoice_service['created_on']	= 	time();
				$invoice_service['created_by']	=	$this->session->userdata('user_id');
				$invoice_service['timestamp']	=	time();
				$invoice_service['updated_by']	=	$this->session->userdata('user_id');
	
				$this->db->insert('invoice_service', $invoice_service);
			}
		}

		$invoice_log['invoice_id'] 		= 	$invoice_id;
		$invoice_log['invoice_type'] 	= 	2;
		$invoice_log['invoice_number'] 	= 	$invoice['invoice_number'];
		$invoice_log['generation_type']	= 	2;
		$invoice_log['created_on']		= 	time();
		$invoice_log['created_by']		=	$this->session->userdata('user_id');

		$this->db->insert('invoice_log', $invoice_log);

		$this->session->set_tempdata('success', $this->lang->line('rent_single_tenant_generated_successfully'), 3);

		redirect(base_url() . 'invoices', 'refresh');
	}

	function generate_single_year_rent()
	{
		$tenant_id 					= 	$this->input->post('tenant_id');
		$start_date					=	strtotime($this->input->post('start'));
		$end_date					=	strtotime($this->input->post('end'));

		$room_id 					=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->room_id;
		$room_number				= 	$this->db->get_where('room', array('room_id' => $room_id))->row()->room_number;
		$property_id 				=	$this->db->get_where('property', array('property_id' => $this->db->get_where('room', array('room_id' => $room_id))->row()->property_id));

		$start_year  				= 	date('Y', $start_date);
		$end_year  					= 	date('Y', $end_date);
		$start_month  				= 	date('n', $start_date);
		$end_month  				= 	date('n', $end_date);
		$start_day 					= 	date('d', $start_date);
		$end_day 					= 	date('d', $end_date);

		$invoice['tenant_name']		=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->name;
		$invoice['start_date']		=	strtotime($this->input->post('start'));
		$invoice['end_date']		=	strtotime($this->input->post('end') . '11:59:59 pm');
		$invoice['due_date']		=	strtotime($this->input->post('due_date') . '11:59:59 pm');
		$invoice['invoice_type']	=	3;
		$invoice['tenant_mobile']	=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->mobile_number;
		$invoice['room_number']		=	$room_number;
		$invoice['property_name']	=	$property_id->num_rows() > 0 ? $property_id->row()->name : '';
		$invoice['tenant_id']		=	$tenant_id;
		$invoice['late_fee']		=	0;
		$invoice['invoice_number']	=	$start_year . $start_month . rand(1, 9999) . $tenant_id;
		$invoice['created_on']		= 	time();
		$invoice['created_by']		=	$this->session->userdata('user_id');
		$invoice['timestamp']		=	time();
		$invoice['updated_by']		=	$this->session->userdata('user_id');

		$this->db->insert('invoice', $invoice);

		$invoice_id					=	$this->db->insert_id();

		$total_amount	=	0;
		$yearly 		= 	$this->db->get_where('room', array('room_id' => $room_id))->row()->yearly_rent;

		if ($start_year < $end_year) {
			for ($i = $start_month; $i <= ($end_month + 12); $i++) {
				if ($i > 12) {
					$year = $end_year;
					$month = date('F', strtotime($year . '-' . ($i - 12) . '-01'));
				} else {
					$year = $start_year;
					$month = date('F', strtotime($year . '-' . $i . '-01'));
				}
				
				$days = date('t', strtotime($year . '-' . $month));

				$monthly = $yearly / 12;
				$daily = $yearly / 365;

				if ($i == $start_month) {
					$days = $days - $start_day + 1;

					$tenant_rent['amount']	=	round($days * $daily);
				} elseif ($i == ($end_month + 12)) {
					$tenant_rent['amount']	=	$yearly - $total_amount;
				} else {
					$tenant_rent['amount']	=	round($monthly);
				}

				$total_amount 		+=	$tenant_rent['amount'];

				$tenant_rent['month']		=	$month;
				$tenant_rent['year']		=	$year;
				$tenant_rent['invoice_id']	=	$invoice_id;
				$tenant_rent['tenant_id']	=	$tenant_id;
				$tenant_rent['created_on']	= 	time();
				$tenant_rent['created_by']	=	$this->session->userdata('user_id');
				$tenant_rent['timestamp']	=	time();
				$tenant_rent['updated_by']	=	$this->session->userdata('user_id');

				$this->db->insert('tenant_rent', $tenant_rent);
			}
		} else {
			for ($i = $start_month; $i <= $end_month; $i++) {
				$year = $start_year;
				$month = date('F', strtotime($year . '-' . $i . '-01'));
				$days = date('t', strtotime($year . '-' . $month));

				$monthly = $yearly / 12;
				$daily = $yearly / 365;

				if ($i == $start_month) {
					$days = $days - $start_day + 1;

					$tenant_rent['amount']	=	round($days * $daily);
				} elseif ($i == $end_month) {
					$tenant_rent['amount']	=	$yearly - $total_amount;
				} else {
					$tenant_rent['amount']	=	round($monthly);
				}

				$total_amount 				+=	$tenant_rent['amount'];

				$tenant_rent['month']		=	$month;
				$tenant_rent['year']		=	$year;
				$tenant_rent['invoice_id']	=	$invoice_id;
				$tenant_rent['tenant_id']	=	$tenant_id;
				$tenant_rent['created_on']	= 	time();
				$tenant_rent['created_by']	=	$this->session->userdata('user_id');
				$tenant_rent['timestamp']	=	time();
				$tenant_rent['updated_by']	=	$this->session->userdata('user_id');

				$this->db->insert('tenant_rent', $tenant_rent);
			}
		}

		$tenant_services = $this->db->get_where('service_tenant', array('tenant_id' => $tenant_id));
		if ($tenant_services->num_rows() > 0) {
			foreach ($tenant_services->result_array() as $tenant_service) {
				$invoice_service['service_id']	=	$tenant_service['service_id'];
				$invoice_service['year']     	=   date('Y');
				$invoice_service['month']      	=   date('F');
				$invoice_service['invoice_id'] 	=   $invoice_id;
				$invoice_service['amount']		=	$this->db->get_where('service', array('service_id' => $tenant_service['service_id']))->row()->cost;
				$invoice_service['created_on']	= 	time();
				$invoice_service['created_by']	=	$this->session->userdata('user_id');
				$invoice_service['timestamp']	=	time();
				$invoice_service['updated_by']	=	$this->session->userdata('user_id');
	
				$this->db->insert('invoice_service', $invoice_service);
			}
		}

		$invoice_log['invoice_id'] 		= 	$invoice_id;
		$invoice_log['invoice_type'] 	= 	3;
		$invoice_log['invoice_number'] 	= 	$invoice['invoice_number'];
		$invoice_log['generation_type']	= 	2;
		$invoice_log['created_on']		= 	time();
		$invoice_log['created_by']		=	$this->session->userdata('user_id');

		$this->db->insert('invoice_log', $invoice_log);

		$this->session->set_tempdata('success', $this->lang->line('rent_date_range_generated_successfully'), 3);

		redirect(base_url() . 'invoices', 'refresh');
	}

	function get_recurring_invoices_table()
	{
		$this->db->order_by('created_on', 'desc');
		return $this->db->get('recurring_invoice')->result_array();
	}

	function send_invoice_sms($invoice_id = '')
	{
		$tenant_id 		= 	$this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->tenant_id;
		$tenant_mobile	=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->mobile_number;
		$late_fee 		= 	$this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->late_fee;
		$this->db->select_sum('amount');
		$this->db->from('tenant_rent');
		$this->db->where('invoice_id', $invoice_id);
		$query = $this->db->get();
		$grand_total = $late_fee > 0 ? $query->row()->amount + $late_fee : $query->row()->amount;

        $message = $this->lang->line('sms_invoice_1') 
		. '#' 
		. $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->invoice_number 
		. $this->lang->line('sms_invoice_2') 
		. date('d M, Y', $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->due_date) 
		. '. '
		. $this->lang->line('sms_invoice_3') . $this->db->get_where('setting', array('name' => 'currency'))->row()->content . ' ' . number_format($grand_total) 
		. ' - '
		. $this->lang->line('from')
		. $this->db->get_where('setting', array('name' => 'system_name'))->row()->content;

        if ($this->db->get_where('setting', array('name' => 'number'))->row()->content) {
			if ($tenant_mobile) {
				$from = $this->db->get_where('setting', array('name' => 'number'))->row()->content;
				$to = $tenant_mobile;

				require APPPATH . 'third_party/twilio-php-main/src/Twilio/autoload.php';

				// Your Account SID and Auth Token from console.twilio.com
				$sid = $this->db->get_where('setting', array('name' => 'account_sid'))->row()->content;
				$token = $this->db->get_where('setting', array('name' => 'auth_token'))->row()->content;
				$client = new Twilio\Rest\Client($sid, $token);

				// Use the Client to make requests to the Twilio REST API
				$client->messages->create(
					// The number you'd like to send the message to
					$to,
					[
						// A Twilio phone number you purchased at https://console.twilio.com
						'from' => $from,
						// The body of the text message you'd like to send
						'body' => $message
					]
				);
				
				if ($client->sid) {
					$sms['sms'] =   1;

					$this->db->where('invoice_id', $invoice_id);
					$this->db->update('invoice', $sms);

					$this->session->set_tempdata('success', $this->lang->line('sms_sent_successfully'), 3);

					redirect(base_url() . 'invoices', 'refresh');
				} else {
					$this->session->set_tempdata('error', $client->sid, 3);

					redirect(base_url() . 'invoices', 'refresh');
				}
			} else {
				$this->session->set_tempdata('error', $this->lang->line('tenant_mobile_number_not_found'), 3);

				redirect(base_url() . 'invoices', 'refresh');
			}
        } else {
			$this->session->set_tempdata('error', $this->lang->line('twilio_conf_not_found') . '<a href="' . base_url('website_settings') . '">' . $this->lang->line('website_settings') . '</a>', 3);

			redirect(base_url() . 'invoices', 'refresh');
		}
	}

	function update_invoice($invoice_id = '', $invoice_type = '')
	{
		$data['month']					=	$this->input->post('month');
		$data['year']					=	$this->input->post('year');
		$data['amount']					=	$this->input->post('amount');

		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->where('tenant_rent_id', $invoice_id);
		$this->db->update('tenant_rent', $data);

		$this->session->set_tempdata('success', $this->lang->line('rent_invoice_updated_successfully'), 3);

		redirect(base_url() . 'invoices', 'refresh');
	}

	function update_invoice_late_fee($invoice_id = '')
	{
		$data['due_date']	=	strtotime($this->input->post('due_date') . '11:59:59 pm');
		$data['late_fee']	=	$this->input->post('late_fee') > 0 ? $this->input->post('late_fee') : 0;
		$data['timestamp']	=	time();
		$data['updated_by']	=	$this->session->userdata('user_id');

		$this->db->where('invoice_id', $invoice_id);
		$this->db->update('invoice', $data);

		$this->session->set_tempdata('success', $this->lang->line('rent_invoice_status_updated_successfully'), 3);

		redirect(base_url() . 'invoices', 'refresh');
	}

	function update_invoice_services($invoice_id = '')
	{
		$services_from_db	=	$this->db->get_where('invoice_service', array('invoice_id' => $invoice_id))->result_array();
		foreach ($services_from_db as $row) {
			$this->db->where('invoice_service_id', $row['invoice_service_id']);
			$this->db->delete('invoice_service');
		}

		$service_ids	= 	$this->input->post('service_ids');
		$years 			=	$this->input->post('years');
		$months 		= 	$this->input->post('months');

		foreach ($service_ids as $key => $value) {
            $data['service_id']	=	$value;
            $data['year']     	=   $years[$key];
            $data['month']      =   $months[$key];
            $data['invoice_id'] =   $invoice_id;
			$data['amount']		=	$this->db->get_where('service', array('service_id' => $value))->row()->cost;
            $data['created_on']	= 	time();
			$data['created_by']	=	$this->session->userdata('user_id');
			$data['timestamp']	=	time();
			$data['updated_by']	=	$this->session->userdata('user_id');

            $this->db->insert('invoice_service', $data);
        }

		redirect(base_url('invoice/' . $invoice_id), 'refresh');
	}

	function update_invoice_custom_services($invoice_id = '')
	{
		$services_from_db	=	$this->db->get_where('invoice_custom_service', array('invoice_id' => $invoice_id))->result_array();
		foreach ($services_from_db as $row) {
			$this->db->where('invoice_custom_service_id', $row['invoice_custom_service_id']);
			$this->db->delete('invoice_custom_service');
		}

		$names		= 	$this->input->post('names');
		$years 		=	$this->input->post('years');
		$months		= 	$this->input->post('months');
		$amounts 	= 	$this->input->post('amounts');

		foreach ($names as $key => $value) {
            $data['name']		=	$value;
            $data['year']     	=   $years[$key];
            $data['month']      =   $months[$key];
            $data['amount']   	=   $amounts[$key];
            $data['invoice_id'] =   $invoice_id;
            $data['created_on']	= 	time();
			$data['created_by']	=	$this->session->userdata('user_id');
			$data['timestamp']	=	time();
			$data['updated_by']	=	$this->session->userdata('user_id');

            $this->db->insert('invoice_custom_service', $data);
        }

		redirect(base_url('invoice/' . $invoice_id), 'refresh');
	}

	function remove_invoice($invoice_id = '')
	{
		$tenant_rents = $this->db->get_where('tenant_rent', array('invoice_id' => $invoice_id))->result_array();
		foreach ($tenant_rents as $tenant_rent) {
			$this->db->where('invoice_id', $tenant_rent['invoice_id']);
			$this->db->delete('tenant_rent');
		}

		$tenant_paids = $this->db->get_where('tenant_paid', array('invoice_id' => $invoice_id))->result_array();
		foreach ($tenant_paids as $tenant_paid) {
			$this->db->where('invoice_id', $tenant_paid['invoice_id']);
			$this->db->delete('tenant_paid');
		}

		$invoice_services = $this->db->get_where('invoice_service', array('invoice_id' => $invoice_id))->result_array();
		foreach ($invoice_services as $invoice_service) {
			$this->db->where('invoice_id', $invoice_service['invoice_id']);
			$this->db->delete('invoice_service');
		}

		$invoice_custom_services = $this->db->get_where('invoice_custom_service', array('invoice_id' => $invoice_id))->result_array();
		foreach ($invoice_custom_services as $invoice_custom_service) {
			$this->db->where('invoice_id', $invoice_custom_service['invoice_id']);
			$this->db->delete('invoice_custom_service');
		}

		$invoice_number = $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->invoice_number;
		if (isset($invoice_number)) unlink('uploads/invoices/' . $invoice_number . '.pdf');

		$this->db->where('invoice_id', $invoice_id);
		$this->db->delete('invoice');

		$this->session->set_tempdata('success', $this->lang->line('rent_invoice_deleted_successfully'), 3);

		redirect(base_url() . 'invoices', 'refresh');
	}

	function add_payment($invoice_id = '')
	{
		$data['amount']						=	$this->input->post('amount');
		$data['invoice_id']					=	$invoice_id;
		$data['tenant_id']					=	$this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->tenant_id;
		$data['payment_method_id']			=	$this->input->post('payment_method_id');
		$data['paid_on']					=	strtotime($this->input->post('paid_on'));
		$data['notes']						=	$this->input->post('notes');
		$data['month']						=	date('F', strtotime($this->input->post('paid_on')));
		$data['year']						=	date('Y', strtotime($this->input->post('paid_on')));
		$data['created_on']					=	time();
		$data['created_by']					=	$this->session->userdata('user_id');
		$data['timestamp']					=	time();
		$data['updated_by']					=	$this->session->userdata('user_id');

		$this->db->insert('tenant_paid', $data);

		$this->session->set_tempdata('success', $this->lang->line('payment_added_successfully'), 3);

		redirect(base_url('invoices'), 'refresh');
	}

	function update_payment($tenant_paid_id = '')
	{
		$data['amount']						=	$this->input->post('amount');
		$data['payment_method_id']			=	$this->input->post('payment_method_id');
		$data['paid_on']					=	strtotime($this->input->post('paid_on'));
		$data['notes']						=	$this->input->post('notes');
		$data['month']						=	date('F', strtotime($this->input->post('paid_on')));
		$data['year']						=	date('Y', strtotime($this->input->post('paid_on')));
		$data['timestamp']					=	time();
		$data['updated_by']					=	$this->session->userdata('user_id');

		$this->db->where('tenant_paid_id', $tenant_paid_id);
		$this->db->update('tenant_paid', $data);

		$this->session->set_tempdata('success', $this->lang->line('payment_updated_successfully'), 3);

		redirect(base_url('invoices'), 'refresh');
	}

	function remove_payment($tenant_paid_id = '')
	{
		$this->db->where('tenant_paid_id', $tenant_paid_id);
		$this->db->delete('tenant_paid');

		$this->session->set_tempdata('success', $this->lang->line('payment_deleted_successfully'), 3);

		redirect(base_url('invoices'), 'refresh');
	}

	function get_due_invoices()
	{
		$due_invoices = [];

		$query = $this->db->get_where('invoice', array('late_fee' => 0));
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $invoice) {
				$tenant_rent = 0;
				$invoice_service_amount = 0;
				$invoice_custom_service_amount = 0;
				$paid_amount = 0;

				$this->db->select_sum('amount');
				$this->db->from('tenant_rent');
				$this->db->where('invoice_id', $invoice['invoice_id']);
				$tenant_rent = $this->db->get()->row()->amount;

				$invoice_services = $this->db->get_where('invoice_service', array('invoice_id' => $invoice['invoice_id']))->result_array();
				foreach ($invoice_services as $invoice_service) {
					$invoice_service_query = $this->db->get_where('service', array('service_id' => $invoice_service['service_id']));
					if ($invoice_service_query->num_rows() > 0)
						$invoice_service_amount += $invoice_service_query->row()->cost;
				}

				$this->db->select_sum('amount');
				$this->db->from('invoice_custom_service');
				$this->db->where('invoice_id', $invoice['invoice_id']);
				$invoice_custom_service_amount = $this->db->get()->row()->amount;

				$this->db->select_sum('amount');
				$this->db->from('tenant_paid');
				$this->db->where('invoice_id', $invoice['invoice_id']);
				$paid_amount = $this->db->get()->row()->amount;

				if ((($tenant_rent + $invoice_service_amount + $invoice_custom_service_amount) > $paid_amount) && (time() > $invoice['due_date']))
					array_push($due_invoices, $invoice['invoice_id']);
			}
		}

		return $due_invoices;
	}

	function add_late_fees_to_due_invoices()
	{
		$due_invoices = json_decode($this->input->post('due_invoices'));
		$late_fee_amount = $this->db->get_where('setting', array('name' => 'late_fee_amount'))->row()->content;

		foreach ($due_invoices as $due_invoice) {
			$invoice['late_fee'] 	= 	$late_fee_amount;
			$invoice['timestamp']	= 	time();
			$invoice['updated_by']	= 	$this->session->userdata('user_id');

			$this->db->where('invoice_id', $due_invoice);
			$this->db->update('invoice', $invoice);
		}

		$late_fee_log['created_on'] = 	time();
		$late_fee_log['created_by']	= 	$this->session->userdata('user_id');

		$this->db->insert('late_fee_log', $late_fee_log);

		return count($due_invoices);
	}

	function get_recurring_invoices()
	{
		$recurring_invoice_tenant_ids = [];

		$query = $this->db->get_where('tenant', array('opt_in_for_recurring_invoice' => 'yes'));
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $tenant) {
				$invoice_start_date	=	strtotime(date('M') . ' ' . '01' . ', ' . date('Y'));
				$invoice_end_date	=	strtotime(date('M') . ' ' . date('t', strtotime(date('Y') . '-' . date('M'))) . ', ' . date('Y') . '11:59:59 pm');

				$lease_period = ((time() >= $tenant['lease_start']) && (time() < $tenant['lease_end']));
				$array = array('tenant_id' => $tenant['tenant_id'], 'start_date' => $invoice_start_date, 'end_date' => $invoice_end_date);
				$this->db->where($array);
				$generated_already = $this->db->get('invoice');

				if ($lease_period && $tenant['status'] && !$generated_already->num_rows()) {
					array_push($recurring_invoice_tenant_ids, $tenant['tenant_id']);
				}
			}
		}

		return $recurring_invoice_tenant_ids;
	}

	function generate_recurring_invoices()
	{
		$recurring_invoices = json_decode($this->input->post('recurring_invoices'));

		foreach ($recurring_invoices as $recurring_invoice_tenant_id) {
			$tenant						=	$this->db->get_where('tenant', array('tenant_id' => $recurring_invoice_tenant_id));
			$room_id 					=	$tenant->row()->room_id;
			$room 						=	$this->db->get_where('room', array('room_id' => $room_id));
			$room_number				= 	$room->row()->room_number;
			$property_id 				=	$this->db->get_where('property', array('property_id' => $room->row()->property_id));

			$month 						=	date('F');
			$year 						=	date('Y');

			$invoice['tenant_name']		=	$tenant->row()->name;
			$invoice['start_date']		=	strtotime($month . ' ' . '01' . ', ' . $year);
			$invoice['end_date']		=	strtotime($month . ' ' . date('t', strtotime($year . '-' . $month)) . ', ' . $year . '11:59:59 pm');
			$invoice['due_date']		=	strtotime($month . ' ' . $this->db->get_where('setting', array('name' => 'automatic_late_fee_add_day'))->row()->content . ', ' . $year . '11:59:59 pm');
			$invoice['invoice_type']	=	2;
			$invoice['tenant_mobile']	=	$tenant->row()->mobile_number;
			$invoice['room_number']		=	$room_number;
			$invoice['property_name']	=	$property_id->num_rows() > 0 ? $property_id->row()->name : '';
			$invoice['tenant_id']		=	$recurring_invoice_tenant_id;
			$invoice['late_fee']		=	0;
			$invoice['invoice_number']	=	$year . date('m', strtotime($month)) . rand(1, 9999) . $recurring_invoice_tenant_id;
			$invoice['created_on']		= 	time();
			$invoice['created_by']		=	$this->session->userdata('user_id');
			$invoice['timestamp']		=	time();
			$invoice['updated_by']		=	$this->session->userdata('user_id');

			$this->db->insert('invoice', $invoice);

			$invoice_id					=	$this->db->insert_id();

			$tenant_rent['month']		=	$month;
			$tenant_rent['year']		=	$year;
			$tenant_rent['amount']		=	$room->row()->monthly_rent;
			$tenant_rent['invoice_id']	=	$invoice_id;
			$tenant_rent['tenant_id']	=	$recurring_invoice_tenant_id;
			$tenant_rent['created_on']	= 	time();
			$tenant_rent['created_by']	=	$this->session->userdata('user_id');
			$tenant_rent['timestamp']	=	time();
			$tenant_rent['updated_by']	=	$this->session->userdata('user_id');

			$this->db->insert('tenant_rent', $tenant_rent);

			$invoice_log['invoice_id'] 		= 	$invoice_id;
			$invoice_log['invoice_type'] 	= 	2;
			$invoice_log['invoice_number'] 	= 	$invoice['invoice_number'];
			$invoice_log['generation_type']	= 	1;
			$invoice_log['created_on']		= 	time();
			$invoice_log['created_by']		=	$this->session->userdata('user_id');

			$this->db->insert('invoice_log', $invoice_log);
		}

		$recurring_invoice['number_of_invoices_generated']	= 	count($recurring_invoices);
		$recurring_invoice['created_on']					= 	time();
		$recurring_invoice['created_by']					= 	$this->session->userdata('user_id');

		$this->db->insert('recurring_invoice', $recurring_invoice);

		return count($recurring_invoices);
	}

	function get_invoice_logs()
	{
		$this->db->order_by('created_on', 'desc');
		return $this->db->get('invoice_log')->result_array();
	}

	function get_monthly_invoices($year = '', $month = '')
	{
		$monthly_invoices = [];

		$this->db->order_by('timestamp', 'desc');
		if ($this->session->userdata('user_type') == 3) {
			$tenant_id = $this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->row()->person_id;
			$invoices = $this->db->get_where('invoice', array('tenant_id' => $tenant_id))->result_array();
		} else {
			$invoices = $this->db->get('invoice')->result_array();
		}

		foreach ($invoices as $invoice) {
			$tenant_rents = $this->db->get_where('tenant_rent', array('invoice_id' => $invoice['invoice_id']))->result_array();
			foreach ($tenant_rents as $tenant_rent) {
				if ($tenant_rent['month'] == $month && $tenant_rent['year'] == $year) {
					$data = [];

					$data['invoice_id'] = $invoice['invoice_id'];
					$data['invoice_number'] = $invoice['invoice_number'];
					$data['tenant_name'] = $invoice['tenant_name'];
					$data['tenant_mobile'] = $invoice['tenant_mobile'];
					$data['room_number'] = $invoice['room_number'];
					$data['property'] = $invoice['property_name'];
					$data['late_fee'] = $invoice['late_fee'];
					$data['due_date'] = date('d M, Y', $invoice['due_date']);
					$data['sms'] = $invoice['sms'];
					$data['email'] = $invoice['email'];
					$data['timestamp'] = $invoice['timestamp'];
					$data['updated_by'] = $invoice['updated_by'];

					$tenant_rent = 0;
					$invoice_service = 0;
					$invoice_custom_service = 0;
					$paid = 0;

					$this->db->select_sum('amount');
					$this->db->from('tenant_rent');
					$this->db->where('invoice_id', $invoice['invoice_id']);
					$tenant_rent = $this->db->get()->row()->amount;

					$this->db->select_sum('amount');
					$this->db->from('invoice_service');
					$this->db->where('invoice_id', $invoice['invoice_id']);
					$invoice_service = $this->db->get()->row()->amount;

					$this->db->select_sum('amount');
					$this->db->from('invoice_custom_service');
					$this->db->where('invoice_id', $invoice['invoice_id']);
					$invoice_custom_service = $this->db->get()->row()->amount;

					$data['amount'] = $tenant_rent + $invoice_service + $invoice_custom_service;

					$this->db->select_sum('amount');
					$this->db->from('tenant_paid');
					$this->db->where('invoice_id', $invoice['invoice_id']);
					$paid = $this->db->get()->row()->amount;

					$data['paid'] = $paid ? intval($paid) : 0;
					$data['open_balance'] = $data['amount'] + $invoice['late_fee'] - $data['paid'];

					if ($data['paid'] > 0) {
						if ($data['paid'] < ($data['amount'] + $invoice['late_fee']))
							$data['status'] = '<span class="badge badge-warning">' . $this->lang->line('partially_paid') . '</span>'; 
						else if ($data['paid'] >= ($data['amount'] + $invoice['late_fee']))
							$data['status'] = '<span class="badge badge-primary">' . $this->lang->line('paid') . '</span>';
					} else {
						$data['status'] = '<span class="badge badge-danger">' . $this->lang->line('due') . '</span>';
					}
					
					array_push($monthly_invoices, $data);
				}
			}
		}
		
		return $monthly_invoices;
	}

	function get_monthly_total_rent($year = '', $month = '')
	{
		$this->db->select_sum('amount');
		$this->db->from('tenant_rent');
		$this->db->where('month', $month);
		$this->db->where('year', $year);
		$monthly_tenant_rent = $this->db->get()->row()->amount;

		$this->db->select_sum('amount');
		$this->db->from('invoice_service');
		$this->db->where('month', $month);
		$this->db->where('year', $year);
		$monthly_invoice_service = $this->db->get()->row()->amount;

		$this->db->select_sum('amount');
		$this->db->from('invoice_custom_service');
		$this->db->where('month', $month);
		$this->db->where('year', $year);
		$monthly_invoice_custom_service = $this->db->get()->row()->amount;

		$monthly_start_date = strtotime($month . ' ' . '01' . ', ' . $year);
		$monthly_end_date = strtotime($month . ' ' . date('t', strtotime($year . '-' . $month)) . ', ' . $year . '11:59:59 pm');

		$this->db->select_sum('late_fee');
		$this->db->from('invoice');
		$this->db->where('due_date >', $monthly_start_date);
		$this->db->where('due_date <', $monthly_end_date);
		$monthly_late_fee = $this->db->get()->row()->late_fee;

		return $monthly_tenant_rent + $monthly_invoice_service + $monthly_invoice_custom_service + $monthly_late_fee;
	}

	function get_monthly_paid_rent($year = '', $month = '')
	{
		$this->db->select_sum('amount');
		$this->db->from('tenant_paid');
		$this->db->where('month', $month);
		$this->db->where('year', $year);
		return $this->db->get()->row()->amount;
	}

	function get_tenant_invoices($tenant_id = '')
	{
		$tenant_invoices = [];

		$invoices = $this->db->get_where('invoice', array('tenant_id' => $tenant_id))->result_array();

		foreach ($invoices as $invoice) {
			$data = [];

			$data['invoice_id'] = $invoice['invoice_id'];
			$data['invoice_number'] = $invoice['invoice_number'];
			$data['tenant_name'] = $invoice['tenant_name'];
			$data['tenant_mobile'] = $invoice['tenant_mobile'];
			$data['room_number'] = $invoice['room_number'];
			$data['property'] = $invoice['property_name'];
			$data['late_fee'] = $invoice['late_fee'];
			$data['due_date'] = date('d M, Y', $invoice['due_date']);
			$data['sms'] = $invoice['sms'];
			$data['email'] = $invoice['email'];
			$data['timestamp'] = $invoice['timestamp'];
			$data['updated_by'] = $invoice['updated_by'];

			$tenant_rent = 0;
			$invoice_service = 0;
			$invoice_custom_service = 0;
			$paid = 0;

			$this->db->select_sum('amount');
			$this->db->from('tenant_rent');
			$this->db->where('invoice_id', $invoice['invoice_id']);
			$tenant_rent = $this->db->get()->row()->amount;

			$this->db->select_sum('amount');
			$this->db->from('invoice_service');
			$this->db->where('invoice_id', $invoice['invoice_id']);
			$invoice_service = $this->db->get()->row()->amount;

			$this->db->select_sum('amount');
			$this->db->from('invoice_custom_service');
			$this->db->where('invoice_id', $invoice['invoice_id']);
			$invoice_custom_service = $this->db->get()->row()->amount;

			$data['amount'] = $tenant_rent + $invoice_service + $invoice_custom_service;

			$this->db->select_sum('amount');
			$this->db->from('tenant_paid');
			$this->db->where('invoice_id', $invoice['invoice_id']);
			$paid = $this->db->get()->row()->amount;

			$data['paid'] = $paid ? intval($paid) : 0;
			$data['open_balance'] = $data['amount'] + $invoice['late_fee'] - $data['paid'];

			if ($data['paid'] > 0) {
				if ($data['paid'] < ($data['amount'] + $invoice['late_fee']))
					$data['status'] = '<span class="badge badge-warning">' . $this->lang->line('partially_paid') . '</span>'; 
				else if ($data['paid'] >= ($data['amount'] + $invoice['late_fee']))
					$data['status'] = '<span class="badge badge-primary">' . $this->lang->line('paid') . '</span>';
			} else {
				$data['status'] = '<span class="badge badge-danger">' . $this->lang->line('due') . '</span>';
			}
			
			array_push($tenant_invoices, $data);
		}

		return $tenant_invoices;
	}

	function get_tenant_total_rent($tenant_id = '')
	{
		$tenant_rent = 0;
		$invoice_service_amount = 0;
		$invoice_custom_service_amount = 0;
		$late_fee = 0;

		$invoices = $this->db->get_where('invoice', array('tenant_id' => $tenant_id))->result_array();
		foreach ($invoices as $invoice) {
			$this->db->select_sum('amount');
			$this->db->from('tenant_rent');
			$this->db->where('invoice_id', $invoice['invoice_id']);
			$tenant_rent += $this->db->get()->row()->amount;

			$this->db->select_sum('amount');
			$this->db->from('invoice_service');
			$this->db->where('invoice_id', $invoice['invoice_id']);
			$invoice_service_amount += $this->db->get()->row()->amount;

			$this->db->select_sum('amount');
			$this->db->from('invoice_custom_service');
			$this->db->where('invoice_id', $invoice['invoice_id']);
			$invoice_custom_service_amount += $this->db->get()->row()->amount;

			$late_fee += $invoice['late_fee'];
		}

		return $tenant_rent + $invoice_service_amount + $invoice_custom_service_amount + $late_fee;
	}

	function get_tenant_paid_rent($tenant_id = '')
	{
		$this->db->select_sum('amount');
		$this->db->from('tenant_paid');
		$this->db->where('tenant_id', $tenant_id);
		return $this->db->get()->row()->amount;
	}

	function add_notice()
	{
		$ext				= 	pathinfo($_FILES['image_link']['name'], PATHINFO_EXTENSION);

		$data['title']		=	$this->input->post('title');
		$data['notice']		=	$this->input->post('notice');
		$data['created_on']	=	time();
		$data['created_by']	=	$this->session->userdata('user_id');
		$data['timestamp']	=	time();
		$data['updated_by']	=	$this->session->userdata('user_id');

		$this->db->insert('notice', $data);

		if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'JPEG' || $ext == 'JPG' || $ext == 'PNG') {
			$data['image_link'] = 	strtolower(explode(" ", $data['title'])[0]) . '_' . time() . '.' . $ext;
			$data['timestamp']	=	time();
			$data['updated_by']	=	$this->session->userdata('user_id');

			move_uploaded_file($_FILES['image_link']['tmp_name'], 'uploads/notices/' . $data['image_link']);

			$this->db->where('notice_id', $this->db->insert_id());
			$this->db->update('notice', $data);

			$this->session->set_tempdata('success', $this->lang->line('notice_added_successfully'), 3);

			redirect(base_url() . 'notices', 'refresh');
		} else {
			$this->session->set_tempdata('warning', $this->lang->line('wrong_image_supported_type'), 3);

			redirect(base_url() . 'notices', 'refresh');
		}

		redirect(base_url() . 'notices', 'refresh');
	}

	function update_notice($notice_id = '')
	{
		$ext				= 	pathinfo($_FILES['image_link']['name'], PATHINFO_EXTENSION);

		$data['title']		=	$this->input->post('title');
		$data['notice']		=	$this->input->post('notice');
		$data['timestamp'] 	=	time();
		$data['updated_by']	=	$this->session->userdata('user_id');

		$this->db->where('notice_id', $notice_id);
		$this->db->update('notice', $data);

		if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'JPEG' || $ext == 'JPG' || $ext == 'PNG') {
			$image_link 		= 	$this->db->get_where('notice', array('notice_id' => $notice_id))->row()->image_link;

			if (isset($image_link)) unlink('uploads/notices/' . $image_link);

			$notice_title 		=	$this->db->get_where('notice', array('notice_id' => $notice_id))->row()->title;

			$data['image_link'] = 	strtolower(explode(" ", $notice_title)[0]) . '_' . time() . '.' . $ext;
			$data['timestamp']	=	time();
			$data['updated_by']	=	$this->session->userdata('user_id');

			move_uploaded_file($_FILES['image_link']['tmp_name'], 'uploads/notices/' . $data['image_link']);

			$this->db->where('notice_id', $notice_id);
			$this->db->update('notice', $data);

			$this->session->set_tempdata('success', $this->lang->line('notice_updated_successfully'), 3);

			redirect(base_url() . 'notices', 'refresh');
		} else {
			$this->session->set_tempdata('warning', $this->lang->line('wrong_image_supported_type'), 3);

			redirect(base_url() . 'notices', 'refresh');
		}

		redirect(base_url() . 'notices', 'refresh');
	}

	function remove_notice($notice_id = '')
	{
		$image_link	=	$this->db->get_where('notice', array('notice_id' => $notice_id))->row()->image_link;

		if (isset($image_link)) unlink('uploads/notices/' . $image_link);

		$this->db->where('notice_id', $notice_id);
		$this->db->delete('notice');

		$this->session->set_tempdata('success', $this->lang->line('notice_deleted_successfully'), 3);

		redirect(base_url() . 'notices', 'refresh');
	}

	function add_complaint()
	{
		$data['complaint_number']			=	$this->random_strings(11);

		$ext1 								= 	pathinfo($_FILES['complaint_picture_1']['name'], PATHINFO_EXTENSION);
		$ext2 								= 	pathinfo($_FILES['complaint_picture_2']['name'], PATHINFO_EXTENSION);
		$ext3 								= 	pathinfo($_FILES['complaint_video']['name'], PATHINFO_EXTENSION);

		if ($ext1 == 'pdf' || $ext1 == 'PDF' || $ext1 == 'jpeg' || $ext1 == 'JPEG' || $ext1 == 'png' || $ext1 == 'PNG' || $ext1 == 'jpg' || $ext1 == 'JPG') {
			$data['complaint_picture_1']	= 	$data['complaint_number'] . '_complaint_picture_1.' . $ext1;

			move_uploaded_file($_FILES['complaint_picture_1']['tmp_name'], 'uploads/complaints/' . $data['complaint_picture_1']);
		}

		if ($ext2 == 'pdf' || $ext2 == 'PDF' || $ext2 == 'jpeg' || $ext2 == 'JPEG' || $ext2 == 'png' || $ext2 == 'PNG' || $ext2 == 'jpg' || $ext2 == 'JPG') {
			$data['complaint_picture_2'] 	= 	$data['complaint_number'] . '_complaint_picture_2.' . $ext2;

			move_uploaded_file($_FILES['complaint_picture_2']['tmp_name'], 'uploads/complaints/' . $data['complaint_picture_2']);
		}

		if ($ext3 == 'mp4' || $ext3 == 'MP4') {
			$data['complaint_video']		= 	$data['complaint_number'] . '_complaint_video.' . $ext3;

			move_uploaded_file($_FILES['complaint_video']['tmp_name'], 'uploads/complaints/' . $data['complaint_video']);
		}

		$data['subject']					=	$this->input->post('subject');
		$data['status']						=	0;
		$data['tenant_id']					=	($this->session->userdata('user_type') == 3) ? $this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->row()->person_id : $this->input->post('tenant_id');
		$data['created_on']					=	time();
		$data['created_by']					=	$this->session->userdata('user_id');
		$data['timestamp']					=	time();
		$data['updated_by']					=	$this->session->userdata('user_id');

		$this->db->insert('complaint', $data);

		$data2['complaint_id']					=	$this->db->insert_id();
		$data2['content']					=	$this->input->post('content');
		$data2['created_on']				=	time();
		$data2['created_by']				=	$this->session->userdata('user_id');
		$data2['timestamp']					=	time();
		$data2['updated_by']				=	$this->session->userdata('user_id');

		$this->db->insert('complaint_details', $data2);

		$this->session->set_tempdata('success', $this->lang->line('complaint_added_successfully'), 3);

		redirect(base_url() . 'complaints', 'refresh');
	}

	private function random_strings($length_of_string)
	{
		$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

		return substr(str_shuffle($str_result), 0, $length_of_string);
	}

	function update_complaint($complaint_id = '')
	{
		$data['timestamp']					=	time();
		$data['updated_by']					=	$this->session->userdata('user_id');

		$this->db->where('complaint_id', $complaint_id);
		$this->db->update('complaint', $data);

		$data2['complaint_id']				=	$complaint_id;
		$data2['content']					=	$this->input->post('content');
		$data2['created_on']				=	time();
		$data2['created_by']				=	$this->session->userdata('user_id');
		$data2['timestamp']					=	time();
		$data2['updated_by']				=	$this->session->userdata('user_id');

		$this->db->insert('complaint_details', $data2);

		$this->session->set_tempdata('success', $this->lang->line('complaint_replied_successfully'), 3);

		redirect(base_url() . 'complaints', 'refresh');
	}

	function close_complaint($complaint_id = '')
	{
		$data['status']						=	1;
		$data['timestamp']					=	time();
		$data['updated_by']					=	$this->session->userdata('user_id');

		$this->db->where('complaint_id', $complaint_id);
		$this->db->update('complaint', $data);

		$this->session->set_tempdata('success', $this->lang->line('complaint_closed_successfully'), 3);

		redirect(base_url() . 'complaints', 'refresh');
	}

	function remove_complaint($complaint_id = '')
	{
		$complaint_picture_1 = $this->db->get_where('complaint', array('complaint_id' => $complaint_id))->row()->complaint_picture_1;
		$complaint_picture_2 = $this->db->get_where('complaint', array('complaint_id' => $complaint_id))->row()->complaint_picture_2;
		$complaint_video = $this->db->get_where('complaint', array('complaint_id' => $complaint_id))->row()->complaint_video;

		if (isset($complaint_picture_1)) unlink('uploads/complaints/' . $complaint_picture_1);
		if (isset($complaint_picture_2)) unlink('uploads/complaints/' . $complaint_picture_2);
		if (isset($complaint_video)) unlink('uploads/complaints/' . $complaint_video);

		$complaint_details = $this->db->get_where('complaint_details', array('complaint_id' => $complaint_id))->result_array();
		foreach ($complaint_details as $row) {
			$this->db->where('complaint_details_id', $row['complaint_details_id']);
			$this->db->delete('complaint_details');
		}

		$this->db->where('complaint_id', $complaint_id);
		$this->db->delete('complaint');

		$this->session->set_tempdata('success', $this->lang->line('complaint_deleted_successfully'), 3);

		redirect(base_url() . 'complaints', 'refresh');
	}

	// Function related to adding id type
	function add_id_type()
	{
		$data['name']					=	$this->input->post('name');
		$data['created_on']				= 	time();
		$data['created_by']				= 	$this->session->userdata('user_id');
		$data['timestamp']				= 	time();
		$data['updated_by']				= 	$this->session->userdata('user_id');

		$this->db->insert('id_type', $data);

		$this->session->set_tempdata('success', $this->lang->line('id_type_added_successfully'), 3);

		redirect(base_url('id_type_settings'), 'refresh');
	}

	// Function related to updating id type
	function update_id_type($id_type_id = '')
	{
		$data['name']					=	$this->input->post('name');
		$data['timestamp']				= 	time();
		$data['updated_by']				= 	$this->session->userdata('user_id');

		$this->db->where('id_type_id', $id_type_id);
		$this->db->update('id_type', $data);

		$this->session->set_tempdata('success', $this->lang->line('id_type_updated_successfully'), 3);

		redirect(base_url('id_type_settings'), 'refresh');
	}

	// Function related to removing id type
	function remove_id_type($id_type_id = '')
	{
		$this->db->where('id_type_id', $id_type_id);
		$this->db->delete('id_type');

		$this->session->set_tempdata('success', $this->lang->line('id_type_deleted_successfully'), 3);

		redirect(base_url('id_type_settings'), 'refresh');
	}
	
	// Function related to website settings
	function update_website_settings()
	{
		if ($this->input->post('system_name')) {
			$system_name['content']	= $this->input->post('system_name');

			$this->db->where('name', 'system_name');
			$this->db->update('setting', $system_name);
		}

		if ($this->input->post('currency')) {
			$currency['content'] = $this->input->post('currency');

			$this->db->where('name', 'currency');
			$this->db->update('setting', $currency);
		}

		if ($this->input->post('tagline')) {
			$tagline['content'] = $this->input->post('tagline');

			$this->db->where('name', 'tagline');
			$this->db->update('setting', $tagline);
		}

		if ($this->input->post('language')) {
			$language['content'] = $this->input->post('language');

			$this->db->where('name', 'language');
			$this->db->update('setting', $language);
		}

		if ($this->input->post('address_line_1') && $this->input->post('address_line_2')) {
			$address['content'] = $this->input->post('address_line_1') . '<br>' . $this->input->post('address_line_2');

			$this->db->where('name', 'address');
			$this->db->update('setting', $address);
		}

		if ($this->input->post('copyright')) {
			$copyright['content'] = $this->input->post('copyright');

			$this->db->where('name', 'copyright');
			$this->db->update('setting', $copyright);
		}

		if ($this->input->post('copyright_url')) {
			$copyright_url['content'] = $this->input->post('copyright_url');

			$this->db->where('name', 'copyright_url');
			$this->db->update('setting', $copyright_url);
		}

		if ($this->input->post('enable_booking')) {
			$enable_booking['content'] = $this->input->post('enable_booking');

			$this->db->where('name', 'enable_booking');
			$this->db->update('setting', $enable_booking);
		}

		if ($this->input->post('automatic_late_fee_add_day')) {
			$automatic_late_fee_add_day['content'] = $this->input->post('automatic_late_fee_add_day');

			$this->db->where('name', 'automatic_late_fee_add_day');
			$this->db->update('setting', $automatic_late_fee_add_day);
		}

		if ($this->input->post('late_fee_amount')) {
			$late_fee_amount['content'] = $this->input->post('late_fee_amount');

			$this->db->where('name', 'late_fee_amount');
			$this->db->update('setting', $late_fee_amount);
		}

		// Font changing switch case of the system
		if ($this->input->post('font')) {
			switch ($this->input->post('font')) {
				case 'PT Sans Narrow':
					$font['content']        =   "'PT Sans Narrow', sans-serif";
					$font_family['content'] =   "PT Sans Narrow";
					$font_src['content']    =   "https://fonts.googleapis.com/css2?family=PT+Sans+Narrow:wght@400;700&display=swap";
					break;
				case 'Josefin Sans':
					$font['content']        =   "'Josefin Sans', sans-serif";
					$font_family['content'] =   "Josefin Sans";
					$font_src['content']    =   "https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap";
					break;
				case 'Titillium Web':
					$font['content']        =   "'Titillium Web', sans-serif";
					$font_family['content'] =   "Titillium Web";
					$font_src['content']    =   "https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap";
					break;
				case 'Mukta':
					$font['content']        =   "'Mukta', sans-serif";
					$font_family['content'] =   "Mukta";
					$font_src['content']    =   "https://fonts.googleapis.com/css2?family=Mukta:wght@200;300;400;500;600;700;800&display=swap";
					break;
				case 'PT Sans':
					$font['content']        =   "'PT Sans', sans-serif";
					$font_family['content'] =   "PT Sans";
					$font_src['content']    =   "https://fonts.googleapis.com/css2?family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap";
					break;
				case 'Rubik':
					$font['content']        =   "'Rubik', sans-serif";
					$font_family['content'] =   "Rubik";
					$font_src['content']    =   "https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap";
					break;
				case 'Oswald':
					$font['content']        =   "'Oswald', sans-serif";
					$font_family['content'] =   "Oswald";
					$font_src['content']    =   "https://fonts.googleapis.com/css2?family=Oswald:wght@200;300;400;500;600;700&display=swap";
					break;
				case 'Poppins':
					$font['content']        =   "'Poppins', sans-serif";
					$font_family['content'] =   "Poppins";
					$font_src['content']    =   "https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap";
					break;
				case 'Open Sans':
					$font['content']        =   "'Open Sans', sans-serif";
					$font_family['content'] =   "Open Sans";
					$font_src['content']    =   "https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap";
					break;
				case 'Cantarell':
					$font['content']        =   "'Cantarell', sans-serif";
					$font_family['content'] =   "Cantarell";
					$font_src['content']    =   "https://fonts.googleapis.com/css2?family=Cantarell:ital,wght@0,400;0,700;1,400;1,700&display=swap";
					break;
				case 'Ubuntu':
					$font['content']        =   "'Ubuntu', sans-serif";
					$font_family['content'] =   "Ubuntu";
					$font_src['content']    =   "https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap";
					break;
				default:
					$font['content']        =   "'PT Sans Narrow', sans-serif";
					$font_family['content'] =   "PT Sans Narrow";
					$font_src['content']    =   "https://fonts.googleapis.com/css2?family=PT+Sans+Narrow:wght@400;700&display=swap";
			}
	
			$this->db->where('name', 'font');
			$this->db->update('setting', $font);
			$this->db->where('name', 'font_family');
			$this->db->update('setting', $font_family);
			$this->db->where('name', 'font_src');
			$this->db->update('setting', $font_src);
		}

		$this->session->set_tempdata('success', $this->lang->line('website_settings_updated_successfully'), 3);

		redirect(base_url() . 'website_settings', 'refresh');
	}

	// Function realted to website favicon update
	function update_website_favicon()
	{
		$ext 							= 	pathinfo($_FILES['favicon']['name'], PATHINFO_EXTENSION);

		if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'JPEG' || $ext == 'JPG' || $ext == 'PNG') {
			$favicon 					= 	$this->db->get_where('setting', array('name' => 'favicon'))->row()->content;

			if (isset($favicon)) unlink('uploads/website/' . $favicon);

			$data['content'] 			= 	$_FILES['favicon']['name'];
			$data['timestamp']			=	time();
			$data['updated_by']			=	$this->session->userdata('user_id');

			move_uploaded_file($_FILES['favicon']['tmp_name'], 'uploads/website/' . $data['content']);

			$this->db->where('name', 'favicon');
			$this->db->update('setting', $data);

			$this->session->set_tempdata('success', $this->lang->line('website_favicon_updated_successfully'), 3);

			redirect(base_url() . 'website_settings', 'refresh');
		} else {
			$this->session->set_tempdata('warning', $this->lang->line('wrong_image_supported_type'), 3);

			redirect(base_url() . 'website_settings', 'refresh');
		}
	}

	// Function realted to website login background update
	function update_website_login_bg()
	{
		$ext 							= 	pathinfo($_FILES['login_bg']['name'], PATHINFO_EXTENSION);

		if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'JPEG' || $ext == 'JPG' || $ext == 'PNG') {
			$login_bg 					= 	$this->db->get_where('setting', array('name' => 'login_bg'))->row()->content;

			if (isset($login_bg)) unlink('uploads/website/' . $login_bg);

			$data['content'] 			= 	$_FILES['login_bg']['name'];
			$data['timestamp']			=	time();
			$data['updated_by']			=	$this->session->userdata('user_id');

			move_uploaded_file($_FILES['login_bg']['tmp_name'], 'uploads/website/' . $data['content']);

			$this->db->where('name', 'login_bg');
			$this->db->update('setting', $data);

			$this->session->set_tempdata('success', $this->lang->line('website_login_background_updated_successfully'), 3);

			redirect(base_url() . 'website_settings', 'refresh');
		} else {
			$this->session->set_tempdata('warning', $this->lang->line('wrong_image_supported_type'), 3);

			redirect(base_url() . 'website_settings', 'refresh');
		}
	}

	// Function realted to website invoice logo update
	function update_website_invoice_logo()
	{
		$ext 							= 	pathinfo($_FILES['invoice_logo']['name'], PATHINFO_EXTENSION);

		if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'JPEG' || $ext == 'JPG' || $ext == 'PNG') {
			$invoice_logo 					= 	$this->db->get_where('setting', array('name' => 'invoice_logo'))->row()->content;

			if (isset($invoice_logo)) unlink('uploads/website/' . $invoice_logo);

			$data['content'] 			= 	$_FILES['invoice_logo']['name'];
			$data['timestamp']			=	time();
			$data['updated_by']			=	$this->session->userdata('user_id');

			move_uploaded_file($_FILES['invoice_logo']['tmp_name'], 'uploads/website/' . $data['content']);

			$this->db->where('name', 'invoice_logo');
			$this->db->update('setting', $data);

			$this->session->set_tempdata('success', $this->lang->line('website_invoice_logo_updated_successfully'), 3);

			redirect(base_url() . 'website_settings', 'refresh');
		} else {
			$this->session->set_tempdata('warning', $this->lang->line('wrong_image_supported_type'), 3);

			redirect(base_url() . 'website_settings', 'refresh');
		}
	}

	// Funmonthsction related to website smtp
	function update_website_smtp()
	{
		if ($this->input->post('smtp_host')) {
			$data0['content']			=	$this->input->post('smtp_host');

			$this->db->where('name', 'smtp_host');
			$this->db->update('setting', $data0);
		}
		
		if ($this->input->post('smtp_user')) {
			$data1['content']			=	$this->input->post('smtp_user');

			$this->db->where('name', 'smtp_user');
			$this->db->update('setting', $data1);
		}

		if ($this->input->post('smtp_pass')) {
			$data2['content']			=	$this->input->post('smtp_pass');

			$this->db->where('name', 'smtp_pass');
			$this->db->update('setting', $data2);
		}

		$this->session->set_tempdata('success', $this->lang->line('website_smtp_updated_successfully'), 3);

		redirect(base_url() . 'website_settings', 'refresh');
	}

	// Function related to website twilio
    function delete_website_smtp()
    {
        $data['content']			=	'';

        $this->db->where('name', 'smtp_host');
        $this->db->update('setting', $data);
		
		$this->db->where('name', 'smtp_user');
        $this->db->update('setting', $data);

        $this->db->where('name', 'smtp_pass');
        $this->db->update('setting', $data);

        $this->session->set_tempdata('success', $this->lang->line('website_smtp_deleted_successfully'), 3);

		redirect(base_url() . 'website_settings', 'refresh');
    }

	// Function related to website twilio
	function update_website_twilio()
	{
		if ($this->input->post('account_sid')) {
			$data1['content']			=	$this->input->post('account_sid');

			$this->db->where('name', 'account_sid');
			$this->db->update('setting', $data1);
		}

		if ($this->input->post('auth_token')) {
			$data2['content']			=	$this->input->post('auth_token');

			$this->db->where('name', 'auth_token');
			$this->db->update('setting', $data2);
		}

        if ($this->input->post('number')) {
			$data3['content']			=	$this->input->post('number');

			$this->db->where('name', 'number');
			$this->db->update('setting', $data3);
		}

		$this->session->set_tempdata('success', $this->lang->line('website_twilio_updated_successfully'), 3);

		redirect(base_url() . 'website_settings', 'refresh');
	}

    // Function related to website twilio
    function delete_website_twilio()
    {
        $data['content']			=	'';

        $this->db->where('name', 'account_sid');
        $this->db->update('setting', $data);

        $this->db->where('name', 'auth_token');
        $this->db->update('setting', $data);

        $this->db->where('name', 'number');
        $this->db->update('setting', $data);

        $this->session->set_tempdata('success', $this->lang->line('website_twilio_deleted_successfully'), 3);

		redirect(base_url() . 'website_settings', 'refresh');
    }

	// Function related to adding profession
	function add_profession()
	{
		$data['name']					=	$this->input->post('name');
		$data['created_on']				= 	time();
		$data['created_by']				= 	$this->session->userdata('user_id');
		$data['timestamp']				= 	time();
		$data['updated_by']				= 	$this->session->userdata('user_id');

		$this->db->insert('profession', $data);

		$this->session->set_tempdata('success', $this->lang->line('profession_added_successfully'), 3);

		redirect(base_url('profession_settings'), 'refresh');
	}

	// Function related to updating profession
	function update_profession($profession_id = '')
	{
		$data['name']					=	$this->input->post('name');
		$data['timestamp']				= 	time();
		$data['updated_by']				= 	$this->session->userdata('user_id');

		$this->db->where('profession_id', $profession_id);
		$this->db->update('profession', $data);

		$this->session->set_tempdata('success', $this->lang->line('profession_updated_successfully'), 3);

		redirect(base_url('profession_settings'), 'refresh');
	}

	// Function related to removing profession
	function remove_profession($profession_id = '')
	{
		$this->db->where('profession_id', $profession_id);
		$this->db->delete('profession');

		$this->session->set_tempdata('success', $this->lang->line('profession_deleted_successfully'), 3);

		redirect(base_url('profession_settings'), 'refresh');
	}

	// Function related to adding service
	function add_service()
	{
		$data['name']					=	$this->input->post('name');
		$data['cost']					=	$this->input->post('cost');
		$data['created_on']				= 	time();
		$data['created_by']				= 	$this->session->userdata('user_id');
		$data['timestamp']				= 	time();
		$data['updated_by']				= 	$this->session->userdata('user_id');

		$this->db->insert('service', $data);

		$this->session->set_tempdata('success', $this->lang->line('service_added_successfully'), 3);

		redirect(base_url('service_settings'), 'refresh');
	}

	// Function related to updating service
	function update_service($service_id = '')
	{
		$data['name']					=	$this->input->post('name');
		$data['cost']					=	$this->input->post('cost');
		$data['timestamp']				= 	time();
		$data['updated_by']				= 	$this->session->userdata('user_id');

		$this->db->where('service_id', $service_id);
		$this->db->update('service', $data);

		$this->session->set_tempdata('success', $this->lang->line('service_updated_successfully'), 3);

		redirect(base_url('service_settings'), 'refresh');
	}

	// Function related to removing service
	function remove_service($service_id = '')
	{
		$this->db->where('service_id', $service_id);
		$this->db->delete('service');

		$this->session->set_tempdata('success', $this->lang->line('service_deleted_successfully'), 3);

		redirect(base_url('service_settings'), 'refresh');
	}

	// Function related to adding currency
	function add_currency()
	{
		$data['name']					=	$this->input->post('name');
		$data['code']					=	$this->input->post('code');
		$data['created_on']				= 	time();
		$data['created_by']				= 	$this->session->userdata('user_id');
		$data['timestamp']				= 	time();
		$data['updated_by']				= 	$this->session->userdata('user_id');

		$this->db->insert('currency', $data);

		$this->session->set_tempdata('success', $this->lang->line('currency_added_successfully'), 3);

		redirect(base_url('currency_settings'), 'refresh');
	}

	// Function related to updating currency
	function update_currency($currency_id = '')
	{
		$data['name']					=	$this->input->post('name');
		$data['code']					=	$this->input->post('code');
		$data['timestamp']				= 	time();
		$data['updated_by']				= 	$this->session->userdata('user_id');

		$this->db->where('currency_id', $currency_id);
		$this->db->update('currency', $data);

		$this->session->set_tempdata('success', $this->lang->line('currency_updated_successfully'), 3);

		redirect(base_url('currency_settings'), 'refresh');
	}

	// Function related to removing currency
	function remove_currency($currency_id = '')
	{
		$this->db->where('currency_id', $currency_id);
		$this->db->delete('currency');

		$this->session->set_tempdata('success', $this->lang->line('currency_deleted_successfully'), 3);

		redirect(base_url('currency_settings'), 'refresh');
	}

	// Function related to adding payment method
	function add_payment_method()
	{
		$data['name']					=	$this->input->post('name');
		$data['created_on']				= 	time();
		$data['created_by']				= 	$this->session->userdata('user_id');
		$data['timestamp']				= 	time();
		$data['updated_by']				= 	$this->session->userdata('user_id');

		$this->db->insert('payment_method', $data);

		$this->session->set_tempdata('success', $this->lang->line('payment_method_added_successfully'), 3);

		redirect(base_url('payment_method_settings'), 'refresh');
	}

	// Function related to updating payment method
	function update_payment_method($payment_method_id = '')
	{
		$data['name']					=	$this->input->post('name');
		$data['timestamp']				= 	time();
		$data['updated_by']				= 	$this->session->userdata('user_id');

		$this->db->where('payment_method_id', $payment_method_id);
		$this->db->update('payment_method', $data);

		$this->session->set_tempdata('success', $this->lang->line('payment_method_updated_successfully'), 3);

		redirect(base_url('payment_method_settings'), 'refresh');
	}

	// Function related to deleting payment method
	function remove_payment_method($payment_method_id = '')
	{
		$this->db->where('payment_method_id', $payment_method_id);
		$this->db->delete('payment_method');

		$this->session->set_tempdata('success', $this->lang->line('payment_method_deleted_successfully'), 3);

		redirect(base_url('payment_method_settings'), 'refresh');
	}

	// Function related to adding board member
	function add_board_member()
	{
		if ($_FILES['image']['name']) {
			$ext 						= 	pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

			if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'JPEG' || $ext == 'JPG' || $ext == 'PNG') {
				$data['image']			=	$_FILES['image']['name'];

				move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/board_members/' . $data['image']);
			} else {
				$this->session->set_tempdata('warning', $this->lang->line('wrong_image_supported_type'), 3);

				redirect(base_url() . 'website_settings', 'refresh');
			}
		}		

		$data['name']					=	$this->input->post('name');
		$data['position']				=	$this->input->post('position');
		$data['serial']					=	$this->input->post('serial');
		$data['image']					=	$_FILES['image']['name'];
		$data['created_on']				= 	time();
		$data['created_by']				= 	$this->session->userdata('user_id');
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->insert('board_member', $data);

		$this->session->set_tempdata('success', $this->lang->line('board_member_added_successfully'), 3);

		redirect(base_url() . 'board_member_settings', 'refresh');
	}

	// Function related to updating board member
	function update_board_member($board_member_id = '')
	{
		if ($_FILES['image']['name']) {
			$ext 						= 	pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

			if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'JPEG' || $ext == 'JPG' || $ext == 'PNG') {
				$image 					= 	$this->db->get_where('board_member', array('board_member_id' => $board_member_id))->row()->image;
				
				if (isset($image)) unlink('uploads/board_members/' . $image);

				$data['image']			=	$_FILES['image']['name'];

				move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/board_members/' . $data['image']);
			} else {
				$this->session->set_tempdata('warning', $this->lang->line('wrong_image_supported_type'), 3);

				redirect(base_url() . 'board_member_settings', 'refresh');
			}
		}

		$data['name']					=	$this->input->post('name');
		$data['position']				=	$this->input->post('position');
		$data['serial']					=	$this->input->post('serial');
		$data['timestamp']				= 	time();
		$data['updated_by']				= 	$this->session->userdata('user_id');

		$this->db->where('board_member_id', $board_member_id);
		$this->db->update('board_member', $data);

		$this->session->set_tempdata('success', $this->lang->line('board_member_updated_successfully'), 3);

		redirect(base_url('board_member_settings'), 'refresh');
	}

	function remove_board_member($board_member_id = '')
	{
		$this->db->where('board_member_id', $board_member_id);
		$this->db->delete('board_member');

		redirect(base_url('board_member_settings'), 'refresh');
	}

	function update_profile_settings($user_id = '')
	{
		$db_password 					=	$this->db->get_where('user', array('user_id' => $user_id))->row()->password;
		$given_password 				=	$this->input->post('old_password');

		$existing_email 				= 	$this->db->get_where('user', array('user_id' => $user_id))->row()->email;

		if (password_verify($given_password, $db_password)) {
			if ($existing_email != $this->input->post('email')) {
				$users = $this->db->get('user')->result_array();
				foreach ($users as $user) {
					if ($user['email'] == $this->input->post('email')) {
						$this->session->set_tempdata('warning', $this->lang->line('tenant_email_already_registered'), 3);

						redirect(base_url() . 'profile_settings', 'refresh');
					}
				}
			}

			$data['email']				=	$this->input->post('email');
			if ($this->input->post('new_password') && ($this->input->post('new_password') == $this->input->post('confirm_password'))) {
				$data['password']		=	password_hash($this->input->post('new_password'), PASSWORD_DEFAULT);
			} else {
				$this->session->set_tempdata('warning', $this->lang->line('new_passwords_do_not_match'), 3);

				redirect(base_url() . 'profile_settings', 'refresh');
			}

			$this->db->where('user_id', $user_id);
			$this->db->update('user', $data);

			$this->session->set_tempdata('success', $this->lang->line('profile_updated_successfully'), 3);

			redirect(base_url() . 'profile_settings', 'refresh');
		} else {
			$this->session->set_tempdata('warning', $this->lang->line('passwords_do_not_match'), 3);

			redirect(base_url() . 'profile_settings', 'refresh');
		}
	}

	function reset_password()
	{
		$input_email = $this->input->post('email', TRUE);

        $found_email = $this->db->get_where('user', array('email' => $input_email));
        $found_email_with_status = $this->db->get_where('user', array('email' => $input_email, 'status' => 1));

        if ($found_email->num_rows() > 0) {
            if ($found_email_with_status->num_rows() > 0) {
                $new_password       =   $this->randomPassword();
                $data['password']   =   password_hash($new_password, PASSWORD_DEFAULT);

				$this->db->where('user_id', $found_email_with_status->row()->user_id);
				$this->db->update('user', $data);
                
                $message                =   $this->lang->line('forgot_user_email_1') . ' ' . $input_email . '<br>' . $this->lang->line('forgot_user_email_2') . ' ' . $new_password . '<br><br>' . $this->lang->line('forgot_user_email_3') . '<br><br>' . $this->lang->line('forgot_user_email_4') . ' ' . base_url('login');
                
				if ($found_email_with_status->row()->user_type == 2) {
                    $name               =   $this->db->get_where('staff', array('staff_id' => $found_email_with_status->row()->person_id))->row()->name;
                } elseif ($found_email_with_status->row()->user_type == 3) {
                    $name               =   $this->db->get_where('tenant', array('tenant_id' => $found_email_with_status->row()->person_id))->row()->name;
                } else {
                    $name               =   'Admin';
                }

				$email['input_email'] 	=	$input_email;
				$email['message']		=	$message;
				$email['name']			=	$name;
                
				$this->email_model->forgot_my_password($email);
            } else {
                $this->session->set_tempdata('warning', $this->lang->line('found_email_but_inactive'), 3);

				redirect(base_url('login'), 'refresh');
            }
        } else {
            $this->session->set_tempdata('error', $this->lang->line('email_not_found'), 3);

			redirect(base_url('login'), 'refresh');
        }
	}

	function randomPassword($len = 8) 
	{
        //enforce min length 8
        if($len < 8)
            $len = 8;
    
        //define character libraries - remove ambiguous characters like iIl|1 0oO
        $sets = array();
        $sets[] = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        $sets[] = '23456789';
        $sets[]  = '~!@#$%^&*(){}[],./?';
    
        $password = '';
        
        //append a character from each set - gets first 4 characters
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
        }
    
        //use all characters to fill up to $len
        while(strlen($password) < $len) {
            //get a random set
            $randomSet = $sets[array_rand($sets)];
            
            //add a random char from the random set
            $password .= $randomSet[array_rand(str_split($randomSet))]; 
        }
        
        //shuffle the password string before returning!
        return str_shuffle($password);
    }

	function update_tenant_services()
	{
		$tenants = $this->db->select('tenant_id')->get_where('tenant', array('status' => 1))->result_array();

		foreach ($this->db->select('service_id')->get('service')->result_array() as $service) {
			$remove_service = $this->db->get_where('service_tenant', array('service_id' => $service['service_id']));

			if ($remove_service->num_rows() > 0) {
				foreach ($remove_service->result_array() as $remove_service_row) {
					$this->db->where('service_tenant_id', $remove_service_row['service_tenant_id']);
					$this->db->delete('service_tenant');
				}
			}

			if ($this->input->post('tenants' . $service['service_id'])) {
				if ($this->input->post('tenants' . $service['service_id'])[0] != 'All') {
					foreach ($this->input->post('tenants' . $service['service_id']) as $service_tenant) {
						$data['tenant_id'] = $service_tenant;
						$data['service_id'] = $service['service_id'];
	
						$this->db->insert('service_tenant', $data);
					}
				} else {
					foreach ($tenants as $service_tenant) {
						$data['tenant_id'] = $service_tenant['tenant_id'];
						$data['service_id'] = $service['service_id'];
	
						$this->db->insert('service_tenant', $data);
					}
				}
			}
		}

		redirect(base_url('add_services_to_tenants'), 'refresh');
	}
}