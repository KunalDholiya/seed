<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Payout extends BaseController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        //$this->is_allowed();

        $response->setHeader('Last-Modified', gmdate('D, d M Y H:i:s') . 'GMT');
        $response->setHeader('Cache-Controln', 'o-store, no-cache, must-revalidate');
        $response->setHeader('Cache-Control', ' post-check=0, pre-check=0', false);
        $response->setHeader('Pragma', ' no-cache');
        $this->data['title'] = 'Payout : ' . $this->data['app_name'];
        $this->data['header'] = view('header', $this->data);
        $this->data['sidebar'] = view('sidebar', $this->data);
        $this->data['footer'] = view('footer', $this->data);
        $this->data['member'] = $this->common->select_data_by_condition('member', array('status' => "Enable"), '*', 'name', 'ASC', '', '', array());
        $this->data['country'] = $this->common->select_data_by_condition('country', array('id' => 231), '*', '', '', '', '', array());
        $this->data['state'] = $this->common->select_data_by_condition('state', array('country_id' => 231), '*', 'name', 'ASC', '', '', array());
        if ($this->data['role_data']['payout'] == 0) {
            return redirect()->to('Dashboard');
        }
    }

    public function index() {
        return view('payout/index', $this->data);
    }

    public function gettabledata() {
        $columns = array('payout.id', 'payout.member_no', 'payout.amount_planted_date', 'admin.firstname');
        $request = $this->request->getGet();
        $condition = array();
        $join_str = array();
        $join_str[0] = array(
            'table' => 'admin',
            'join_table_id' => 'admin.admin_id',
            'from_table_id' => 'payout.added_by',
            'join_type' => 'left'
        );
        $join_str[1] = array(
            'table' => 'member',
            'join_table_id' => 'member.id',
            'from_table_id' => 'payout.member_id',
            'join_type' => 'left'
        );
        $getfiled = "payout.id,payout.member_no,member.name,date_of_harvest,amount_planted_date,admin.firstname,admin.lastname";
        echo $this->common->getDataTableSource('payout', $columns, $condition, $getfiled, $request, $join_str, '');
        //echo '<pre>';        print_r($this->db->last_query());
        die();
    }

    public function add() {
        return view('payout/add', $this->data);
    }

    public function addnew() {

        $this->validation->setRule('member_id', 'Member name', 'required|trim|strip_tags');
        $this->validation->setRule('date_of_harvest', 'Date of harvest', 'required|trim|strip_tags');
        $this->validation->setRule('amount_planted_date', 'Total amount planted to date', 'required|trim|strip_tags');


        if ($this->validation->withRequest($this->request)->run() == FALSE) {
            session()->setFlashdata('error', $this->validation->listErrors());
            return redirect()->back();
        } else {

            $church_received = $this->common->select_data_by_condition('transaction_setting', array('id' => 2), '*', '', '', '', '', array());
            $member_received = $this->common->select_data_by_condition('transaction_setting', array('id' => 7), '*', '', '', '', '', array());
            $amount_return_to_pool = $this->common->select_data_by_condition('transaction_setting', array('id' => 3), '*', '', '', '', '', array());

            $user_agent = $this->request->getUserAgent()->getBrowser();
            $member = $this->request->getVar('member_id');
            $member_no = array();
            $church_receives = 0;
            $member_receives = 0;
            if ($member != '') {
                $m = $member;
                $data_member = $this->common->select_data_by_condition('member', array('id' => $m), '*', 'name', 'ASC', '', '', array());
                if (!empty($data_member)) {
                    array_push($member_no, $data_member[0]['member_no']);
                    if ($data_member[0]['church_member'] == 'Yes') {
                        $church_receives = $church_received[0]['value'];
                    } else {
                        $member_receives = $member_received[0]['value'];
                    }
                }
            }


            $insert_data = array(
                "member_id" => $this->request->getVar('member_id'),
                "member_no" => implode(',', $member_no),
                "date_of_harvest" => (($this->request->getVar('date_of_harvest'))),
                "amount_planted_date" => (($this->request->getVar('amount_planted_date'))),
                "church_received" => $church_receives,
                "member_received" => $member_receives,
                "amount_return_to_pool" => $amount_return_to_pool[0]['value'],
                "created_datetime" => date('Y-m-d H:i:s'),
                "created_ip" => $this->request->getIPAddress(),
                "modified_ip" => $this->request->getIPAddress(),
                "modified_datetime" => date('Y-m-d H:i:s'),
                "created_browser" => $this->request->getUserAgent()->getBrowser(),
                "created_os" => $this->request->getUserAgent()->getPlatform(),
                "added_by" => $this->data['adminID'],
                "modified_browser" => $this->request->getUserAgent()->getBrowser(),
                "modified_os" => $this->request->getUserAgent()->getPlatform(),
                "modified_by" => $this->data['adminID'],
            );
            $member = $this->common->insert_data_getid($insert_data, "payout");
            if ($member) {
                session()->setFlashdata('success', 'Payout added successfully.');
                return redirect()->to('Payout');
            } else {
                session()->setFlashdata('error', 'There is an error occured. please try after again');
                return redirect()->to('Payout');
            }
        }
    }

    public function edit($id) {
        $sub_id = base64_decode($id);
        $this->data['info'] = $this->common->select_data_by_condition('payout', array('id' => $sub_id), '*', '', '', '', '', array());
        if (empty($this->data['info'])) {
            session()->setFlashdata('error', 'No information found!');
           return redirect()->back();
        } else {
            return view('payout/edit', $this->data);
        }
    }

    public function editnew($id) {

        $this->validation->setRule('member_id', 'Member name', 'required|trim|strip_tags');
        $this->validation->setRule('date_of_harvest', 'Date of harvest', 'required|trim|strip_tags');
        $this->validation->setRule('amount_planted_date', 'Total amount planted to date', 'required|trim|strip_tags');


        if ($this->validation->withRequest($this->request)->run() == FALSE) {
            session()->setFlashdata('error', $this->validation->listErrors());
            return redirect()->back();
        } else {
            $sub_id = base64_decode($id);
            $church_received = $this->common->select_data_by_condition('transaction_setting', array('id' => 2), '*', '', '', '', '', array());
            $member_received = $this->common->select_data_by_condition('transaction_setting', array('id' => 7), '*', '', '', '', '', array());
            $amount_return_to_pool = $this->common->select_data_by_condition('transaction_setting', array('id' => 3), '*', '', '', '', '', array());

            $user_agent = $this->request->getUserAgent()->getBrowser();
            $member = $this->request->getVar('member_id');
            $member_no = array();
            $church_receives = 0;
            $member_receives = 0;
            if ($member != '') {
                $m = $member;
                $data_member = $this->common->select_data_by_condition('member', array('id' => $m), '*', 'name', 'ASC', '', '', array());
                if (!empty($data_member)) {
                    array_push($member_no, $data_member[0]['member_no']);
                    if ($data_member[0]['church_member'] == 'Yes') {
                        $church_receives = $church_received[0]['value'];
                    } else {
                        $member_receives = $member_received[0]['value'];
                    }
                }
            }


            $update_data = array(
                "member_id" => $this->request->getVar('member_id'),
                "member_no" => implode(',', $member_no),
                "date_of_harvest" => (($this->request->getVar('date_of_harvest'))),
                "amount_planted_date" => (($this->request->getVar('amount_planted_date'))),
                "church_received" => $church_receives,
                "member_received" => $member_receives,
                "amount_return_to_pool" => $amount_return_to_pool[0]['value'],
                "modified_ip" => $this->request->getIPAddress(),
                "modified_datetime" => date('Y-m-d H:i:s'),
                "modified_browser" => $this->request->getUserAgent()->getBrowser(),
                "modified_os" => $this->request->getUserAgent()->getPlatform(),
                "modified_by" => $this->data['adminID'],
            );
            $member = $this->common->update_data($update_data, 'payout', 'id', $sub_id);
            if ($member) {
                session()->setFlashdata('success', 'Payout Updated successfully.');
                return redirect()->to('Payout');
            } else {
                session()->setFlashdata('error', 'There is an error occured. please try after again');
                return redirect()->to('Payout');
            }
        }
    }

    function delete() {
        $json = array();
        $json['msg'] = '';
        $json['status'] = 'fail';
        $id = $this->request->getVar('id');
        //echo $id; die;
        $group = $this->common->select_data_by_condition('payout', array('id' => $id), '*', '', '', '', '', array());
        if (!empty($group)) {

            $res = $this->common->delete_data('payout', 'id', $id);
            if ($res) {
                $json['msg'] = 'Record has been deleted successfully';
                $json['status'] = 'success';
            } else {
                $json['msg'] = 'Sorry! something went wrong Please try later!';
            }
        } else {
            $json['msg'] = 'Sorry! No information found!';
        }
        echo json_encode($json);
        die();
    }

}
