<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Deposit extends BaseController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        //$this->is_allowed();

        $response->setHeader('Last-Modified', gmdate('D, d M Y H:i:s') . 'GMT');
        $response->setHeader('Cache-Controln', 'o-store, no-cache, must-revalidate');
        $response->setHeader('Cache-Control', ' post-check=0, pre-check=0', false);
        $response->setHeader('Pragma', ' no-cache');
        $this->data['title'] = 'Deposit : ' . $this->data['app_name'];
        $this->data['header'] = view('header', $this->data);
        $this->data['sidebar'] = view('sidebar', $this->data);
        $this->data['footer'] = view('footer', $this->data);
        if ($this->data['role_data']['deposit'] == 0) {
            return redirect()->to('Dashboard');
        }
        $this->data['member'] = $this->common->select_data_by_condition('member', array('status' => "Enable"), '*', 'name', 'ASC', '', '', array());
        $this->data['country'] = $this->common->select_data_by_condition('country', array('id' => 231), '*', '', '', '', '', array());
        $this->data['state'] = $this->common->select_data_by_condition('state', array('country_id' => 231), '*', 'name', 'ASC', '', '', array());
        $this->data['transaction_setting'] = $this->common->select_data_by_condition('transaction_setting', array('id' => 1), '*', '', '', '', '', array());
    }

    public function index() {
        return view('deposit/index', $this->data);
    }

    public function gettabledata() {
        $columns = array('deposit.id', 'deposit.member_no', 'amount_planted_week', 'admin.firstname');
        $request = $this->request->getGet();
        $condition = array();
        $join_str = array();
        $join_str[0] = array(
            'table' => 'admin',
            'join_table_id' => 'admin.admin_id',
            'from_table_id' => 'deposit.added_by',
            'join_type' => 'left'
        );
        $join_str[1] = array(
            'table' => 'member',
            'join_table_id' => 'member.id',
            'from_table_id' => 'deposit.member_id',
            'join_type' => 'left'
        );
        $getfiled = "deposit.id,deposit.member_no,date_of_seed,member.name,amount_planted_week,harvest_date,admin.firstname,admin.lastname";
        echo $this->common->getDataTableSource('deposit', $columns, $condition, $getfiled, $request, $join_str, '');
        //echo '<pre>';        print_r($this->db->getLastQuery());
        die();
    }

    public function add() {
        return view('deposit/add', $this->data);
    }

    public function addnew() {

        $this->validation->setRule('member_id', 'Member name', 'required');
        $this->validation->setRule('date_of_seed', 'Date of seed', 'required|trim|strip_tags');
        $this->validation->setRule('amount_planted_week', 'Amount planted', 'required|trim|strip_tags');

        if ($this->validation->withRequest($this->request)->run() == FALSE) {
            session()->setFlashdata('error', $this->validation->listErrors());
            return redirect()->back();
        } else {

            $user_agent = $this->request->getUserAgent()->getBrowser();
            $member = $this->request->getVar('member_id[]');
            $member_no = array();
            if (!empty($member)) {
                foreach ($member as $m) {
                    $data_member = $this->common->select_data_by_condition('member', array('id' => $m), '*', 'name', 'ASC', '', '', array());
                    if (!empty($data_member)) {
                        array_push($member_no, $data_member[0]['member_no']);
                    }
                }
            }
            $harvestdate = $this->common->select_data_by_condition('transaction_setting', array('id' => 1), '*', '', '', '', '', array());

            $thirtyDaysUnix = strtotime('+' . $harvestdate[0]['value'] . ' days', strtotime($this->request->getVar('date_of_seed')));
            if ($this->request->getVar('manual_harvest') != '') {
                $harvest_date = $this->request->getVar('manual_harvest');
            } else {
                $harvest_date = date("Y-m-d", $thirtyDaysUnix);
            }
            $insert_data = array(
                "member_id" => implode(',', $this->request->getVar('member_id[]')),
                "member_no" => implode(',', $member_no),
                "date_of_seed" => ($this->request->getVar('date_of_seed')),
                "amount_planted_week" => (($this->request->getVar('amount_planted_week'))),
                "harvest_date" => $harvest_date,
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
            $member = $this->common->insert_data_getid($insert_data, "deposit");
            if ($member) {
                session()->setFlashdata('success', 'Deposit added successfully.');
                return redirect()->to('Deposit');
            } else {
                session()->setFlashdata('error', 'There is an error occured. please try after again');
                return redirect()->to('Deposit');
            }
        }
    }

    public function edit($id) {
        $sub_id = base64_decode($id);
        $this->data['info'] = $this->common->select_data_by_condition('deposit', array('id' => $sub_id), '*', '', '', '', '', array());
        if (empty($this->data['info'])) {
            session()->setFlashdata('error', 'No information found!');
            return redirect()->back();
        } else {
            return view('deposit/edit', $this->data);
        }
    }

    public function editnew($id) {

        $this->validation->setRule('member_id', 'Member name', 'required');
        $this->validation->setRule('date_of_seed', 'Date of seed', 'required|trim|strip_tags');
        $this->validation->setRule('amount_planted_week', 'Amount planted', 'required|trim|strip_tags');


        if ($this->validation->withRequest($this->request)->run() == FALSE) {
            session()->setFlashdata('error', $this->validation->listErrors());
            return redirect()->back();
        } else {
            $sub_id = base64_decode($id);
            $user_agent = $this->request->getUserAgent()->getBrowser();
            $member = $this->request->getVar('member_id[]');
            $member_no = array();
            if (!empty($member)) {
                foreach ($member as $m) {
                    $data_member = $this->common->select_data_by_condition('member', array('id' => $m), '*', 'name', 'ASC', '', '', array());
                    if (!empty($data_member)) {
                        array_push($member_no, $data_member[0]['member_no']);
                    }
                }
            }
            $harvestdate = $this->common->select_data_by_condition('transaction_setting', array('id' => 1), '*', '', '', '', '', array());

            $thirtyDaysUnix = strtotime('+' . $harvestdate[0]['value'] . ' days', strtotime($this->request->getVar('date_of_seed')));

            $update_data = array(
                "member_id" => implode(',', $this->request->getVar('member_id[]')),
                "member_no" => implode(',', $member_no),
                "date_of_seed" => ($this->request->getVar('date_of_seed')),
                "amount_planted_week" => (($this->request->getVar('amount_planted_week'))),
                "harvest_date" => date("Y-m-d", $thirtyDaysUnix),
                "modified_ip" => $this->request->getIPAddress(),
                "modified_datetime" => date('Y-m-d H:i:s'),
                "modified_browser" => $this->request->getUserAgent()->getBrowser(),
                "modified_os" => $this->request->getUserAgent()->getPlatform(),
                "modified_by" => $this->data['adminID'],
            );
            $member = $this->common->update_data($update_data, 'deposit', 'id', $sub_id);
            if ($member) {
                session()->setFlashdata('success', 'Deposit Updated successfully.');
                return redirect()->to('Deposit');
            } else {
                session()->setFlashdata('error', 'There is an error occured. please try after again');
                return redirect()->to('Deposit');
            }
        }
    }

    function delete() {
        $json = array();
        $json['msg'] = '';
        $json['status'] = 'fail';
        $id = $this->request->getVar('id');
        //echo $id; die;
        $group = $this->common->select_data_by_condition('deposit', array('id' => $id), '*', '', '', '', '', array());
        if (!empty($group)) {

            $res = $this->common->delete_data('deposit', 'id', $id);
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
