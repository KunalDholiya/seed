<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
class Transaction extends BaseController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        //$this->is_allowed();

        $response->setHeader('Last-Modified', gmdate('D, d M Y H:i:s') . 'GMT');
        $response->setHeader('Cache-Controln', 'o-store, no-cache, must-revalidate');
        $response->setHeader('Cache-Control', ' post-check=0, pre-check=0', false);
        $response->setHeader('Pragma', ' no-cache');
        $this->data['title'] = 'Transaction : ' . $this->data['app_name'];
        $this->data['header'] = view('header', $this->data);
        $this->data['sidebar'] = view('sidebar', $this->data);
        $this->data['footer'] = view('footer', $this->data);
        $this->data['member'] = $this->common->select_data_by_condition('member', array('status' => "Enable"), '*', 'name', 'ASC', '', '', array());
        $this->data['country'] = $this->common->select_data_by_condition('country', array('id' => 231), '*', '', '', '', '', array());
        $this->data['state'] = $this->common->select_data_by_condition('state', array('country_id' => 231), '*', 'name', 'ASC', '', '', array());
        if ($this->data['role_data']['transaction'] == 0) {
            return redirect()->to('Dashboard');
        }
    }

    public function index() {
        return view('transaction/index', $this->data);
    }

    public function gettabledata() {
        $columns = array('id', 'member_no', 'week', 'amount_planted_week', 'amount_planted_date', 'week_per_person', 'total_per_week', 'church_received', 'member_received', 'amount_return_to_pool', 'admin.firstname');
        $request = $this->request->getGet();
        $condition = array();
        $join_str = array();
        $join_str[0] = array(
            'table' => 'admin',
            'join_table_id' => 'admin.admin_id',
            'from_table_id' => 'transaction.added_by',
            'join_type' => 'left'
        );
        $getfiled = "id,member_no,week,date_of_seed,date_of_harvest,amount_planted_week,amount_planted_date,week_per_person,total_per_week,church_received,member_received,amount_return_to_pool,admin.firstname,admin.lastname";
        echo $this->common->getDataTableSource('transaction', $columns, $condition, $getfiled, $request, $join_str, '');
        //echo '<pre>';        print_r($this->db->last_query());
        die();
    }

    public function add() {
        return view('transaction/add', $this->data);
    }

    public function addnew() {

        $this->validation->setRule('member_id[]', 'Member name', 'required|trim|strip_tags');
        $this->validation->setRule('week', 'Member name', 'required|trim|strip_tags');
        $this->validation->setRule('date_of_seed', 'Date of seed', 'required|trim|strip_tags');
        $this->validation->setRule('date_of_harvest', 'Date of harvest', 'required|trim|strip_tags');
        $this->validation->setRule('amount_planted_week', 'Amount planted this week', 'required|trim|strip_tags');
        $this->validation->setRule('amount_planted_date', 'Total amount planted to date', 'required|trim|strip_tags');


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

            $insert_data = array(
                "member_id" => implode(',', $this->request->getVar('member_id[]')),
                "member_no" => implode(',', $member_no),
                "week" => ($this->request->getVar('week')),
                "date_of_seed" => ($this->request->getVar('date_of_seed')),
                "date_of_harvest" => (($this->request->getVar('date_of_harvest'))),
                "amount_planted_week" => (($this->request->getVar('amount_planted_week'))),
                "amount_planted_date" => (($this->request->getVar('amount_planted_date'))),
                "week_per_person" => (($this->request->getVar('week_per_person'))),
                "total_per_week" => (($this->request->getVar('total_per_week'))),
                "church_received" => (($this->request->getVar('church_received'))),
                "member_received" => (($this->request->getVar('member_received'))),
                "amount_return_to_pool" => (($this->request->getVar('amount_return_to_pool'))),
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
            $member = $this->common->insert_data_getid($insert_data, "transaction");
            if ($member) {
                session()->setFlashdata('success', 'Transaction added successfully.');
                return redirect()->to('Transaction');
            } else {
                session()->setFlashdata('error', 'There is an error occured. please try after again');
                return redirect()->to('Transaction');
            }
        }
    }

    public function edit($id) {
        $sub_id = base64_decode($id);
        $this->data['info'] = $this->common->select_data_by_condition('transaction', array('id' => $sub_id), '*', '', '', '', '', array());
        if (empty($this->data['info'])) {
            session()->setFlashdata('error', 'No information found!');
            return redirect()->back();
        } else {
            return view('transaction/edit', $this->data);
        }
    }

    public function editnew($id) {

        $this->validation->setRule('member_id[]', 'Member name', 'required|trim|strip_tags');
        $this->validation->setRule('week', 'Member name', 'required|trim|strip_tags');
        $this->validation->setRule('date_of_seed', 'Date of seed', 'required|trim|strip_tags');
        $this->validation->setRule('date_of_harvest', 'Date of harvest', 'required|trim|strip_tags');
        $this->validation->setRule('amount_planted_week', 'Amount planted this week', 'required|trim|strip_tags');
        $this->validation->setRule('amount_planted_date', 'Total amount planted to date', 'required|trim|strip_tags');


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

            $update_data = array(
                "member_id" => implode(',', $this->request->getVar('member_id[]')),
                "member_no" => implode(',', $member_no),
                "week" => ($this->request->getVar('week')),
                "date_of_seed" => ($this->request->getVar('date_of_seed')),
                "date_of_harvest" => (($this->request->getVar('date_of_harvest'))),
                "amount_planted_week" => (($this->request->getVar('amount_planted_week'))),
                "amount_planted_date" => (($this->request->getVar('amount_planted_date'))),
                "week_per_person" => (($this->request->getVar('week_per_person'))),
                "total_per_week" => (($this->request->getVar('total_per_week'))),
                "church_received" => (($this->request->getVar('church_received'))),
                "member_received" => (($this->request->getVar('member_received'))),
                "amount_return_to_pool" => (($this->request->getVar('amount_return_to_pool'))),
                "modified_ip" => $this->request->getIPAddress(),
                "modified_datetime" => date('Y-m-d H:i:s'),
                "modified_browser" => $this->request->getUserAgent()->getBrowser(),
                "modified_os" => $this->request->getUserAgent()->getPlatform(),
                "modified_by" => $this->data['adminID'],
            );
            $member = $this->common->update_data($update_data, 'transaction', 'id', $sub_id);
            if ($member) {
                session()->setFlashdata('success', 'Transaction Updated successfully.');
                return redirect()->to('Transaction');
            } else {
                session()->setFlashdata('error', 'There is an error occured. please try after again');
                return redirect()->to('Transaction');
            }
        }
    }

    function delete() {
        $json = array();
        $json['msg'] = '';
        $json['status'] = 'fail';
        $id = $this->request->getVar('id');
        //echo $id; die;
        $group = $this->common->select_data_by_condition('transaction', array('id' => $id), '*', '', '', '', '', array());
        if (!empty($group)) {

            $res = $this->common->delete_data('transaction', 'id', $id);
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
