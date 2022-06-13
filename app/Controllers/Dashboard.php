<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Dashboard extends BaseController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        //$this->is_allowed();

        $response->setHeader('Last-Modified', gmdate('D, d M Y H:i:s') . 'GMT');
        $response->setHeader('Cache-Controln', 'o-store, no-cache, must-revalidate');
        $response->setHeader('Cache-Control', ' post-check=0, pre-check=0', false);
        $response->setHeader('Pragma', ' no-cache');
        $this->data['title'] = 'Dashboard : ' . $this->data['app_name'];
        $this->data['header'] = view('header', $this->data);
        $this->data['sidebar'] = view('sidebar', $this->data);
        $this->data['footer'] = view('footer', $this->data);
        $this->data['redirect_url'] = $this->last_url();
    }

    public function index() {

        $this->data['total_email'] = count($this->common->select_data_by_condition('email_format', array(), '*', '', '', '', '', array()));
        $this->data['total_member'] = count($this->common->select_data_by_condition('member', array(), '*', '', '', '', '', array()));
        $this->data['total_transaction'] = count($this->common->select_data_by_condition('deposit', array(), '*', '', '', '', '', array()));

        $this->data['total_student'] = count($this->common->select_data_by_condition('admin', array('is_deleted' => '0', 'role' => 2), '*', '', '', '', '', array()));
        return view('dashboard/index', $this->data);
    }

    function logout() {
        if ((session()->has('seed_admin'))) {
            session()->remove('seed_admin');
            session()->destroy();
            return redirect()->to(base_url('login'));
        } else {
            session()->remove('seed_admin');
            session()->destroy();
            return redirect()->to(base_url('login'));
        }
    }

    //check admin name,admin email value is unique in database 
    public function checkExits() {
        $fval = $this->request->getVar('filed_name');
        switch ($fval) {
            case 'admin_name':
                $fieldName = 'username';
                $fieldValue = ($this->request->getVar('admin_name'));
                break;

            case 'admin_email':
                $fieldName = 'email';
                $fieldValue = ($this->request->getVar('admin_email'));
                break;

            default:
                $fieldValue = '';
                $fieldName = '';
                break;
        }

        if (trim($fieldValue) != '') {
            $res = $this->common->checkName('admin', $fieldName, $fieldValue, 'admin_id', $this->data['adminID']);
            if (empty($res)) {
                echo 'true';
                die();
            } else {
                echo 'false';
                die();
            }
        }
    }

    public function changePassword() {
        return view('dashboard/changepass', $this->data);
    }

    public function Profile() {
        $this->data['admin'] = $this->common->selectRecordById('admin', $this->data['adminID'], 'admin_id');

        return view('dashboard/profile', $this->data);
    }

    //update record 
    public function editProfile() {

        $this->validation->setRule('first_name', 'firstname', 'trim|required|regex_match[/^([a-zA-Z])+$/i]|min_length[2]|max_length[20]|htmlspecialchars');
        $this->validation->setRule('last_name', 'lastname', 'trim|required|regex_match[/^([a-zA-Z])+$/i]|min_length[2]|max_length[20]|htmlspecialchars');
        //$this->validation->setRule('user_name', 'username', 'trim|required|regex_match[/^([a-zA-Z])+$/i]|min_length[2]|max_length[20]|htmlspecialchars');
        $this->validation->setRule('email', 'email', 'required|valid_email|trim|htmlspecialchars');
        if ($this->validation->withRequest($this->request)->run() == FALSE) {
            session()->setFlashdata('error', $this->validation->listErrors());
            return redirect()->back();
        } else {


            $filename = $this->data['adminprofileimage'];
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != null && $_FILES['image']['size'] > 0) {
                $this->validation->setRule('image', 'Image', 'uploaded[image]|mime_in[image,' . config('CustomConfig')->upload_admin_allowed_types . ']');
                if ($this->validation->withRequest($this->request)->run() == FALSE) {
                    session()->setFlashdata('error', $this->validation->listErrors());
                    return redirect()->back();
                }
                $isFile = $this->request->getFile('image');
                $fieldvalue = $isFile->getName();
                $filename = $isFile->getRandomName();
                $image = \Config\Services::image()
                        ->withFile($isFile)
                        ->resize(config('CustomConfig')->admin_thumb_width, config('CustomConfig')->admin_thumb_height, false, '')
                        ->save(FCPATH . config('CustomConfig')->upload_path_admin_thumb . $filename);

                $isFile->move(FCPATH . config('CustomConfig')->upload_path_admin,$filename);
                if ($this->data['adminprofileimage'] != '') {
                    if (file_exists(FCPATH . config('CustomConfig')->upload_path_admin . $this->data['adminprofileimage'])) {
                        @unlink(FCPATH . config('CustomConfig')->upload_path_admin . $this->data['adminprofileimage']);
                    }
                    if (file_exists(FCPATH . config('CustomConfig')->upload_path_admin_thumb . $this->data['adminprofileimage'])) {
                        @unlink(FCPATH . config('CustomConfig')->upload_path_admin_thumb . $this->data['adminprofileimage']);
                    }
                }
            }

            $updateData = array(
                "firstname" => $this->request->getVar('first_name'),
                "lastname" => $this->request->getVar('last_name'),
                //"username" => $this->request->getVar('user_name'),
                "email" => $this->request->getVar('email'),
                "image" => $filename,
                "modified_date" => date('Y-m-d H:i:s'),
                "modified_ip" => $this->request->getIPAddress()
            );

            $res = $this->common->update_data($updateData, 'admin', 'admin_id', $this->data['adminID']);
            if ($res) {
                session()->setFlashdata('success', 'Profile updated successfully.');
                return redirect()->to('Dashboard');
            } else {
                session()->setFlashdata('error', 'There is error in updated profile. Try later!');
                return redirect()->to('Dashboard');
            }
        }
    }

    //password exist
    function pwdexist() {

        $pwd = sha1($this->request->getVar('old_password'));
        $res = $this->common->select_data_by_id('admin', 'admin_id', $this->data['adminID'], 'password', array());
        $encryptedPassword = $res[0]['password'];
        if ($pwd != '') {
            if ($pwd === $encryptedPassword) {
                echo 'true';
                die();
            } else {
                echo 'false';
                die();
            }
        } else {
            echo 'false';
            die();
        }
    }

    //change password
    public function change_password() {

        if ($this->request->getMethod() == 'post') {
            $redirect = '';
            $last_url = $this->last_url();
            if ($last_url != '') {
                $redirect = $last_url;
            } else {
                $redirect = 'dashboard';
            }
            
            $this->validation->setRule('old_password', 'Old Password', 'trim|required|min_length[6]|max_length[16]|htmlspecialchars');
            $this->validation->setRule('new_password', 'New Password', 'trim|required|min_length[6]|max_length[16]|htmlspecialchars');
            $this->validation->setRule('confirm_password', 'Confirm Password', 'trim|required|min_length[6]|max_length[16]|htmlspecialchars');
            if ($this->validation->withRequest($this->request)->run() == FALSE) {
                session()->setFlashdata('error', $this->validation->listErrors());
                return redirect()->back();
            }
            $checkAuth = $this->common->selectRecordById('admin', $this->data['adminID'], 'admin_id');
            $password = sha1($this->request->getVar('old_password'));
            $dbPassword = $checkAuth['password'];
            if ($password !== $dbPassword) {
                session()->setFlashdata('error', 'Please enter correct old password.');
                return redirect()->to($redirect);
            }
            $newpassword = $this->request->getVar('new_password');
            $confirmpass = $this->request->getVar('confirm_password');
            if ($newpassword != $confirmpass) {
                session()->setFlashdata('error', 'New password and Confirm password must be same.');
                return redirect()->to($redirect);
            }
            $updatedPassword = sha1($newpassword);
            $data = array('password' => $updatedPassword, 'modified_date' => date('Y-m-d H:i:s'));
            if ($this->common->update_data($data, 'admin', 'admin_id', $this->data['adminID'])) {
                //echo $this->last_query();die();
                session()->setFlashdata('success', 'Password changed successfully.');
                return redirect()->to($redirect);
            } else {
                session()->setFlashdata('error', 'Error Occurred. Try Again!');
                return redirect()->to($redirect);
            }
        }
    }

}
