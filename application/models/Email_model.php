<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Email_model extends CI_Model
{
    function send_invoice($invoice_id = '')
    {
        $invoice_number         =   $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->invoice_number;
        $subject                =   'Invoice';
        $page_name              =   'contact';
        $from                   =   $this->db->get_where('setting', array('name' => 'smtp_user'))->row()->content;
        $to                     =   $this->db->get_where('tenant', array('tenant_id' => $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->tenant_id))->row()->email;
        $message                =   'This is your invoice number ' . $invoice_number;
        $name                   =   $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->tenant_name;
        $system_name            =   $this->db->get_where('setting', array('name' => 'system_name'))->row()->content;

        $data['title']          =   $this->db->get_where('setting', array('name' => 'system_name'))->row()->content;
        $data['name']           =   $name;
        $data['message']        =   $message;
        $data['url']            =   base_url();
        $data['copyright']      =   $this->db->get_where('setting', array('name' => 'copyright'))->row()->content;

        $body = $this->load->view('email/' . $page_name, $data, TRUE);

        $config['smtp_host']    =     $this->db->get_where('setting', array('name' => 'smtp_host'))->row()->content;
        $config['smtp_user']    =     $this->db->get_where('setting', array('name' => 'smtp_user'))->row()->content;
        $config['smtp_pass']    =     $this->db->get_where('setting', array('name' => 'smtp_pass'))->row()->content;

        $this->email->initialize($config);

        if ($to) {
            if ($this->email->from($from, $system_name)->reply_to($from)->to($to)->subject($subject)->message($body)->attach('uploads/invoices/' . $invoice_number . '.pdf')->send()) {
                $this->session->set_tempdata('success', $this->lang->line('email_invoice_sent'), 3);

                $email['email'] =   1;

                $this->db->where('invoice_id', $invoice_id);
                $this->db->update('invoice', $email);

                header('Location: ' . base_url('invoices'));
            } else {
                $this->session->set_tempdata('warning', $this->lang->line('email_not_sent'), 3);

                header('Location: ' . base_url('invoices'));
            }
        } else {
            $this->session->set_tempdata('warning', $this->lang->line('email_not_found'), 3);

            header('Location: ' . base_url('invoices'));
        }
    }

    function forgot_my_password($data = '')
    {
        $subject                =   'Password Reset';
        $page_name              =   'contact';
        $from                   =   $this->db->get_where('setting', array('name' => 'smtp_user'))->row()->content;
        $to                     =   $data['input_email'];
        $message                =   $data['message'];
        $name                   =   $data['name'];
        $system_name            =   $this->db->get_where('setting', array('name' => 'system_name'))->row()->content;

        $data['title']          =   $this->db->get_where('setting', array('name' => 'system_name'))->row()->content;
        $data['name']           =   $name;
        $data['message']        =   $message;
        $data['url']            =   base_url();
        $data['copyright']      =   $this->db->get_where('setting', array('name' => 'copyright'))->row()->content;

        $body = $this->load->view('email/' . $page_name, $data, TRUE);

        $config['smtp_host']    =     $this->db->get_where('setting', array('name' => 'smtp_host'))->row()->content;
        $config['smtp_user']    =     $this->db->get_where('setting', array('name' => 'smtp_user'))->row()->content;
        $config['smtp_pass']    =     $this->db->get_where('setting', array('name' => 'smtp_pass'))->row()->content;

        $this->email->initialize($config);

        if ($this->email->from($from, $system_name)->reply_to($from)->to($to)->subject($subject)->message($body)->send()) {
            $this->session->set_tempdata('success', $this->lang->line('email_sent_with_password'), 3);

            header('Location: ' . base_url('login'));
        } else {
            $this->session->set_tempdata('warning', $this->lang->line('email_not_sent'), 3);

            header('Location: ' . base_url('login'));
        }

        redirect(base_url('login'), 'refresh');
    }
}
