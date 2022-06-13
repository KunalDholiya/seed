<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Members extends BaseController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        //$this->is_allowed();

        $response->setHeader('Last-Modified', gmdate('D, d M Y H:i:s') . 'GMT');
        $response->setHeader('Cache-Controln', 'o-store, no-cache, must-revalidate');
        $response->setHeader('Cache-Control', ' post-check=0, pre-check=0', false);
        $response->setHeader('Pragma', ' no-cache');
        $this->data['title'] = 'Member : ' . $this->data['app_name'];
        $this->data['header'] = view('header', $this->data);
        $this->data['sidebar'] = view('sidebar', $this->data);
        $this->data['footer'] = view('footer', $this->data);
        $this->data['redirect_url'] = $this->last_url();
        
        $this->data['country'] = $this->common->select_data_by_condition('country', array('id' => 231), '*', '', '', '', '', array());
        $this->data['state'] = $this->common->select_data_by_condition('state', array('country_id' => 231), '*', 'name', 'ASC', '', '', array());
        if (!empty($this->data['role_data'])) {
            $member_role = explode(',', $this->data['role_data']['member']);
            
            if (!in_array('View', $member_role)) {
                return redirect()->to('Members');
            }
            if ($this->router->methodName() == 'add') {
                if (!in_array('Add', $member_role)) {
                    return redirect()->to('Members');
                }
            }
            if ($this->router->methodName() == 'edit') {
                if (!in_array('Edit', $member_role)) {
                    return redirect()->to('Members');
                }
            }
        }
    }

    public function index() {
        return view('member/index', $this->data);
    }

    public function gettabledata() {
        $columns = array('id', 'member_no', 'name', 'email', 'contact_no', 'church_member', 'status');
        $request = $this->request->getGet();
        $condition = array();
        $join_str = array();

        $getfiled = "id,member.email,member.contact_no,member.status,name,member.member_no,church_member";
        echo $this->common->getDataTableSource('member', $columns, $condition, $getfiled, $request, $join_str, '');
        //echo '<pre>';        print_r($this->db->getLastQuery());
        die();
    }

    public function add() {
        return view('member/add', $this->data);
    }

    public function addnew() {

        $this->validation->setRule('firstname', 'Member name', 'required|trim|strip_tags');


        if ($this->validation->withRequest($this->request)->run() == FALSE) {
            session()->setFlashdata('error', $this->validation->listErrors());
           return redirect()->back();
        } else {
            $check_email = $this->common->select_data_by_condition('member', array('email' => $this->request->getVar('email')), '*', '', '', '', '', array());
            if (!empty($check_email)) {
                session()->setFlashdata('error', 'Email already exists.');
                return redirect()->to('Members');
            }
            $user_agent = $this->request->getUserAgent()->getBrowser();

            $insert_data = array(
                "email" => ($this->request->getVar('email')),
                "contact_no" => ($this->request->getVar('contact_no')),
                "name" => (ucwords($this->request->getVar('firstname'))),
                "church_member" => ($this->request->getVar('church_member')),
                "address" => ($this->request->getVar('address')),
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
            $member = $this->common->insert_data_getid($insert_data, "member");
            if ($member) {
                $update_data = array('member_no' => 'STH' . $member);
                $this->common->update_data($update_data, 'member', 'id', $member);
                session()->setFlashdata('success', 'Member added successfully.');
                return redirect()->to('Members');
            } else {
                session()->setFlashdata('error', 'There is an error occured. please try after again');
                return redirect()->to('Members');
            }
        }
    }

    public function edit($id) {
        $sub_id = base64_decode($id);
        $this->data['info'] = $this->common->select_data_by_condition('member', array('id' => $sub_id), '*', '', '', '', '', array());
        if (empty($this->data['info'])) {
            session()->setFlashdata('error', 'No information found!');
            return redirect()->back();
        } else {
            return view('member/edit', $this->data);
        }
    }

    public function editnew($id) {
        $sub_id = base64_decode($id);
        $this->validation->setRule('firstname', 'Member name', 'required|trim|strip_tags');


        if ($this->validation->withRequest($this->request)->run() == FALSE) {
            session()->setFlashdata('error', $this->validation->listErrors());
            return redirect()->back();
        } else {
            $info = $this->common->select_data_by_condition('member', array('id' => $sub_id), '*', '', '', '', '', array());

            $update_data = array(
                "email" => ($this->request->getVar('email')),
                "contact_no" => ($this->request->getVar('contact_no')),
                "name" => (ucwords($this->request->getVar('firstname'))),
                "church_member" => ($this->request->getVar('church_member')),
                "address" => ($this->request->getVar('address')),
                "modified_ip" => $this->request->getIPAddress(),
                "modified_datetime" => date('Y-m-d H:i:s'),
                "modified_browser" => $this->request->getUserAgent()->getBrowser(),
                "modified_os" => $this->request->getUserAgent()->getPlatform(),
                "modified_by" => $this->data['adminID'],
            );

            $admin = $this->common->update_data($update_data, 'member', 'id', $sub_id);

            if ($admin) {

                session()->setFlashdata('success', 'Member updated successfully.');
                return redirect()->to('Members');
            } else {
                session()->setFlashdata('error', 'There is an error occured. please try after again');
                return redirect()->to('Members');
            }
        }
    }

    function delete() {
        $json = array();
        $json['msg'] = '';
        $json['status'] = 'fail';
        $id = $this->request->getVar('id');
        //echo $id; die;
        $group = $this->common->select_data_by_condition('member', array('id' => $id), '*', '', '', '', '', array());
        if (!empty($group)) {

            $res = $this->common->delete_data('member', 'id', $id);
            if ($res) {
                $this->common->delete_data('payout', 'member_id', $id);
                $this->common->delete_data('deposit', 'member_id', $id);
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

    function update_status() {
        $json = array();
        $json['status'] = 'fail';
        $json['msg'] = '';
        $id = $this->request->getVar('id');
        $status = $this->request->getVar('status');
        $reason = $this->request->getVar('reason');
        if ($status == 'Enable') {
            $status = 'Disable';
        } else {
            $status = 'Enable';
        }

        $data = $this->common->select_data_by_condition('member', array('id' => $id), '*', '', '', '', '', array());
        if (empty($data)) {
            $json['msg'] = 'No information Found!';
        } else {
            $result = $this->common->update_data(array('status' => $status), 'member', 'id', $id);
            if ($result) {

                $json['status'] = 'success';
                $json['msg'] = 'Status has been updated';
            } else {
                $json['msg'] = 'Sorry! Something went wrong please try again!';
            }
        }
        echo json_encode($json);
        die();
    }

    public function emailExits() {
        $email = $this->request->getVar('email');

        if (trim($email) != '') {
            $res = $this->common->check_unique_avalibility('member', 'email', $email, '', '', array());

            if (empty($res)) {
                echo 'true';
                die();
            } else {
                echo 'false';
                die();
            }
        } else {
            echo 'true';
            die();
        }
    }

    public function emailExitsedit() {
        $email = $this->request->getVar('email');
        $id = $this->request->getVar('id');

        if (trim($email) != '') {
            $res = $this->common->check_unique_avalibility('member', 'email', $email, 'id', $id, array());

            if (empty($res)) {
                echo 'true';
                die();
            } else {
                echo 'false';
                die();
            }
        } else {
            echo 'true';
            die();
        }
    }

    public function usernameExits() {
        $email = $this->request->getVar('username');

        if (trim($email) != '') {
            $res = $this->common->check_unique_avalibility('member', 'email', $email, '', '', array());

            if (empty($res)) {
                echo 'true';
                die();
            } else {
                echo 'false';
                die();
            }
        } else {
            echo 'true';
            die();
        }
    }

    public function usernameExitsedit() {
        $email = $this->request->getVar('username');
        $id = $this->request->getVar('id');

        if (trim($email) != '') {
            $res = $this->common->check_unique_avalibility('member', 'email', $email, 'id', $id, array());

            if (empty($res)) {
                echo 'true';
                die();
            } else {
                echo 'false';
                die();
            }
        } else {
            echo 'true';
            die();
        }
    }

}
