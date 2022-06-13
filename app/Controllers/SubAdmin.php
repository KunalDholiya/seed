<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class SubAdmin extends BaseController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        //$this->is_allowed();

        $response->setHeader('Last-Modified', gmdate('D, d M Y H:i:s') . 'GMT');
        $response->setHeader('Cache-Controln', 'o-store, no-cache, must-revalidate');
        $response->setHeader('Cache-Control', ' post-check=0, pre-check=0', false);
        $response->setHeader('Pragma', ' no-cache');
        $this->data['title'] = 'Subadmin : ' . $this->data['app_name'];
        $this->data['header'] = view('header', $this->data);
        $this->data['sidebar'] = view('sidebar', $this->data);
        $this->data['footer'] = view('footer', $this->data);
        $this->data['country'] = $this->common->select_data_by_condition('country', array('id' => 231), '*', '', '', '', '', array());
        $this->data['state'] = $this->common->select_data_by_condition('state', array('country_id' => 231), '*', 'name', 'ASC', '', '', array());
    }

    public function index() {
        return view('subadmin/index', $this->data);
    }

    public function gettabledata() {
        $columns = array('admin_id', 'admin.firstname', 'admin.email', 'admin.contact_no', 'admin.status');
        $request = $this->request->getGet();
        $condition = array('admin.is_deleted' => '0', 'admin.role' => 2);
        $join_str = array();

        $getfiled = "admin_id,admin.email,admin.contact_no,admin.status,firstname,lastname";
        echo $this->common->getDataTableSource('admin', $columns, $condition, $getfiled, $request, $join_str, '');
        //echo '<pre>';        print_r($this->db->last_query());
        die();
    }

    public function add() {
        return view('subadmin/add', $this->data);
    }

    public function addnew() {

        $this->validation->setRule('firstname', 'Sub Admin name', 'required|trim|strip_tags');
        $this->validation->setRule('lastname', 'lastname', 'required|trim|strip_tags');
        //$this->validation->setRule('username', 'username', 'required|trim|strip_tags');
        $this->validation->setRule('email', 'Email', 'required|trim|strip_tags');
        $this->validation->setRule('contact_no', 'Contact no', 'required|trim|strip_tags');
        //$this->validation->setRule('course_id', 'Course', 'required|trim|strip_tags');
        //$this->validation->setRule('address1', 'Address1', 'required|trim|strip_tags');
        //$this->validation->setRule('country_id', 'Country', 'required|trim|strip_tags');
        //$this->validation->setRule('state_id', 'State', 'required|trim|strip_tags');
        //$this->validation->setRule('city', 'City', 'required|trim|strip_tags');
        //$this->validation->setRule('zipcode', 'Zipcode', 'required|trim|strip_tags');
        // $this->validation->setRule('university_id', 'University', 'required|trim|strip_tags');
        if ($this->validation->withRequest($this->request)->run() == FALSE) {
            session()->setFlashdata('error', $this->validation->listErrors());
            return redirect()->back();
        } else {
            $check_email = $this->common->select_data_by_condition('admin', array('email' => $this->request->getVar('email'), 'is_deleted' => '0'), '*', '', '', '', '', array());
            if (!empty($check_email)) {
                session()->setFlashdata('error', 'Email already exists.');
                return redirect()->back();
            }
            $user_agent = $this->request->getUserAgent()->getBrowser();

            $filename = '';
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
                
            }
            $password = $this->password_generate(8);
            $password_reset_token = time() . rand(100, 20000);
            $insert_data = array(
                //"username" => (($this->request->getVar('username'))),
                "email" => ($this->request->getVar('email')),
                "contact_no" => ($this->request->getVar('contact_no')),
                "firstname" => (ucwords($this->request->getVar('firstname'))),
                "lastname" => (ucfirst($this->request->getVar('lastname'))),
//                "referred_by" => (($this->request->getVar('referred'))),
//                "join_date" => (($this->request->getVar('join_date'))),
//                "address2" => ($this->request->getVar('address2')),
//                "address1" => ($this->request->getVar('address1')),
//                "country" => ($this->request->getVar('country_id')),
//                "state" => ($this->request->getVar('state_id')),
//                "city" => ($this->request->getVar('city')),
//                "zipcode" => ($this->request->getVar('zipcode')),
                "image" => $filename,
                "status" => 'Enable',
                "role" => 2,
                "password" => sha1($password),
                "admin_slug" => str_replace(' ', '_', $this->request->getVar('firstname')) . '_' . rand(1000, 9999),
                "created_date" => date('Y-m-d H:i:s'),
                "created_ip" => $this->request->getIPAddress(),
                "modified_ip" => $this->request->getIPAddress(),
                "modified_date" => date('Y-m-d H:i:s'),
            );
            $admin = $this->common->insert_data_getid($insert_data, "admin");
            if ($admin) {
                $role_data = array(
                    'admin_id' => $admin,
                    'deposit' => $this->request->getVar('deposit'),
                    'payout' => $this->request->getVar('payout'),
                    'member' => implode(',', $this->request->getVar('view_member')),
                    'full_report' => $this->request->getVar('full_report'),
                    'partial_report' => $this->request->getVar('partial_report'),
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
                $this->common->insert_data_getid($role_data, "role");
                $name = ucwords($this->request->getVar('firstname'));
                $email = ($this->request->getVar('email'));

                $site_logo = base_url() . '/assets/images/logo.jpg';
                $ins_url = $this->common->select_data_by_id('settings', 'setting_id ', 5, '*', array());
                $url = base_url();
                $year = date('Y');
                $mailData = $this->common->select_data_by_id('email_format', 'id', 7, '*', array());
                $subject = str_replace('%site_name%', $this->data['app_name'], $mailData[0]['subject']);
                $mailformat = $mailData[0]['emailformat'];
                $this->data['mail_body'] = str_replace("%site_logo%", $site_logo, str_replace("%name%", $name, str_replace("%password%", $password, str_replace("%email%", $email, str_replace("%url%", $url, str_replace("%site_name%", $this->data['app_name'], str_replace("%year%", $year, stripslashes($mailformat))))))));
                //$this->data['mail_header'] = '<img id="headerImage campaign-icon" src="' . $site_logo . '" title="' . $this->data["site_name"] . '" width="250" /> ';
                $this->data['mail_header'] = $this->data['app_name'];
                $this->data['mail_footer'] = '<a href="' . site_url() . '">' . $this->data['app_name'] . '</a> | Copyright &copy;' . $year . ' | All rights reserved</p>';
                $mail_body = view('mail', $this->data);
                // echo '<pre>';                    print_r($mail_body); die;
                $this->sendEmail($this->data['app_name'], $this->data['app_email'], $email, $subject, $mail_body);

                session()->setFlashdata('success', 'SubAdmin added successfully.');
                return redirect()->to('SubAdmin');
            } else {
                session()->setFlashdata('error', 'There is an error occured. please try after again');
                return redirect()->to('SubAdmin');
            }
        }
    }

    function password_generate($chars) {
        $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($data), 0, $chars);
    }

    public function edit($id) {
        $sub_id = base64_decode($id);
        $this->data['info'] = $this->common->select_data_by_condition('admin', array('admin_id' => $sub_id), '*', '', '', '', '', array());
        if (empty($this->data['info'])) {
            session()->setFlashdata('error', 'No information found!');
            return redirect()->back();
        } else {
            $this->data['role'] = $this->common->select_data_by_condition('role', array('admin_id' => $sub_id), '*', '', '', '', '', array());
            //echo '<pre>'; print_r($this->data['role']); die;
            return view('subadmin/edit', $this->data);
        }
    }

    public function editnew($id) {
        $sub_id = base64_decode($id);
        $this->validation->setRule('firstname', 'Sub Admin name', 'required|trim|strip_tags');
        $this->validation->setRule('lastname', 'lastname', 'required|trim|strip_tags');
        //$this->validation->setRule('username', 'username', 'required|trim|strip_tags');
        $this->validation->setRule('email', 'Email', 'required|trim|strip_tags');
        $this->validation->setRule('contact_no', 'Contact no', 'required|trim|strip_tags');
        //$this->validation->setRule('course_id', 'Course', 'required|trim|strip_tags');
//        $this->validation->setRule('address1', 'Address1', 'required|trim|strip_tags');
//        $this->validation->setRule('country_id', 'Country', 'required|trim|strip_tags');
//        $this->validation->setRule('state_id', 'State', 'required|trim|strip_tags');
//        $this->validation->setRule('city', 'City', 'required|trim|strip_tags');
//        $this->validation->setRule('zipcode', 'Zipcode', 'required|trim|strip_tags');
        //$this->validation->setRule('university_id', 'University', 'required|trim|strip_tags');

        if ($this->validation->withRequest($this->request)->run() == FALSE) {
            session()->setFlashdata('error', $this->validation->listErrors());
            return redirect()->back();
        } else {
            $info = $this->common->select_data_by_condition('admin', array('admin_id' => $sub_id), '*', '', '', '', '', array());
            $dataimage = $info[0]['image'];
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != null && $_FILES['image']['size'] > 0) {

               $this->validation->setRule('image', 'Image', 'uploaded[image]|mime_in[image,' . config('CustomConfig')->upload_admin_allowed_types . ']');
                if ($this->validation->withRequest($this->request)->run() == FALSE) {
                    session()->setFlashdata('error', $this->validation->listErrors());
                    return redirect()->back();
                }
                $isFile = $this->request->getFile('image');
                $fieldvalue = $isFile->getName();
                $dataimage = $isFile->getRandomName();
                $image = \Config\Services::image()
                        ->withFile($isFile)
                        ->resize(config('CustomConfig')->admin_thumb_width, config('CustomConfig')->admin_thumb_height, false, '')
                        ->save(FCPATH . config('CustomConfig')->upload_path_admin_thumb . $dataimage);

                $isFile->move(FCPATH . config('CustomConfig')->upload_path_admin,$dataimage);
                if ($info[0]['image'] != '') {
                    if (file_exists(FCPATH . config('CustomConfig')->upload_path_admin . $info[0]['image'])) {
                        @unlink(FCPATH . config('CustomConfig')->upload_path_admin . $info[0]['image']);
                    }
                    if (file_exists(FCPATH . config('CustomConfig')->upload_path_admin_thumb . $info[0]['image'])) {
                        @unlink(FCPATH . config('CustomConfig')->upload_path_admin_thumb . $info[0]['image']);
                    }
                }
            }
            $update_data = array(
                //"username" => (($this->request->getVar('username'))),
                "email" => ($this->request->getVar('email')),
                "contact_no" => ($this->request->getVar('contact_no')),
                "firstname" => (ucwords($this->request->getVar('firstname'))),
                "lastname" => (ucfirst($this->request->getVar('lastname'))),
//                "referred_by" => (($this->request->getVar('referred'))),
//                "join_date" => (($this->request->getVar('join_date'))),
//                "address2" => ($this->request->getVar('address2')),
//                "address1" => ($this->request->getVar('address1')),
//                "country" => ($this->request->getVar('country_id')),
//                "state" => ($this->request->getVar('state_id')),
//                "city" => ($this->request->getVar('city')),
//                "zipcode" => ($this->request->getVar('zipcode')),
                "image" => $dataimage,
                "role" => 2,
               
                "admin_slug" => str_replace(' ', '_', $this->request->getVar('firstname')) . '_' . rand(1000, 9999),
                "modified_ip" => $this->request->getIPAddress(),
                "modified_date" => date('Y-m-d H:i:s'),
            );

            $admin = $this->common->update_data($update_data, 'admin', 'admin_id', $sub_id);

            if ($admin) {
                $role = $this->common->select_data_by_condition('role', array('admin_id' => $sub_id), '*', '', '', '', '', array());
                if (!empty($role)) {
                    $role_data = array(
                        'deposit' => $this->request->getVar('deposit'),
                        'payout' => $this->request->getVar('payout'),
                        'member' => implode(',', $this->request->getVar('view_member')),
                        'full_report' => $this->request->getVar('full_report'),
                        'partial_report' => $this->request->getVar('partial_report'),
                        "modified_ip" => $this->request->getIPAddress(),
                        "modified_datetime" => date('Y-m-d H:i:s'),
                        "modified_browser" => $this->request->getUserAgent()->getBrowser(),
                        "modified_os" => $this->request->getUserAgent()->getPlatform(),
                        "modified_by" => $this->data['adminID'],
                    );
                    $this->common->update_data($role_data, 'role', 'id', $role[0]['id']);
                } else {
                    $role_data = array(
                        'admin_id' => $sub_id,
                        'deposit' => $this->request->getVar('deposit'),
                        'payout' => $this->request->getVar('payout'),
                        'member' => implode(',', $this->request->getVar('view_member')),
                        'full_report' => $this->request->getVar('full_report'),
                        'partial_report' => $this->request->getVar('partial_report'),
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
                    $this->common->insert_data_getid($role_data, "role");
                }

                //$this->common->update_data(array("university_id" => (($this->request->getVar('university_id'))),), 'course', 'course_id', (($this->request->getVar('course_id'))));

                session()->setFlashdata('success', 'SubAdmin updated successfully.');
                return redirect()->to('SubAdmin');
            } else {
                session()->setFlashdata('error', 'There is an error occured. please try after again');
                return redirect()->to('SubAdmin');
            }
        }
    }

    function delete() {
        $json = array();
        $json['msg'] = '';
        $json['status'] = 'fail';
        $id = $this->request->getVar('id');
        //echo $id; die;
        $group = $this->common->select_data_by_condition('admin', array('admin_id' => $id), '*', '', '', '', '', array());
        if (!empty($group)) {

            $res = $this->common->update_data(array('is_deleted' => '1'), 'admin', 'admin_id', $id);
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

        $data = $this->common->select_data_by_condition('admin', array('admin_id' => $id), '*', '', '', '', '', array());
        if (empty($data)) {
            $json['msg'] = 'No information Found!';
        } else {
            $result = $this->common->update_data(array('status' => $status), 'admin', 'admin_id', $id);
            if ($result) {
                $name = ucfirst($data[0]['firstname']);
                $email = $data[0]['email'];
                $site_logo = base_url() . '/assets/images/logo.jpg';
                $year = date('Y');
                if ($status == 'Enable') {

                    $mailData = $this->common->select_data_by_id('email_format', 'id', 12, '*', array());
                    $subject = str_replace('%site_name%', $this->data['app_name'], $mailData[0]['subject']);
                    $mailformat = $mailData[0]['emailformat'];
                    $this->data['mail_body'] = str_replace("%site_logo%", $site_logo, str_replace("%name%", $name, str_replace("%reason%", $reason, str_replace("%site_name%", $this->data['app_name'], str_replace("%year%", $year, stripslashes($mailformat))))));
                    //$this->data['mail_header'] = '<img id="headerImage campaign-icon" src="' . $site_logo . '" title="' . $this->data["site_name"] . '" width="250" /> ';
                    $this->data['mail_header'] = $this->data['app_name'];
                    $this->data['mail_footer'] = '<a href="' . site_url() . '">' . $this->data['app_name'] . '</a> | Copyright &copy;' . $year . ' | All rights reserved</p>';
                    $mail_body =  view('mail', $this->data);
                    //  echo '<pre>';                    print_r($mail_body); die;
                    $this->sendEmail($this->data['app_name'], $this->data['app_email'], $email, $subject, $mail_body);
                } else {
                    $mailData = $this->common->select_data_by_id('email_format', 'id', 17, '*', array());
                    $subject = str_replace('%site_name%', $this->data['app_name'], $mailData[0]['subject']);
                    $mailformat = $mailData[0]['emailformat'];
                    $this->data['mail_body'] = str_replace("%site_logo%", $site_logo, str_replace("%name%", $name, str_replace("%reason%", $reason, str_replace("%site_name%", $this->data['app_name'], str_replace("%year%", $year, stripslashes($mailformat))))));
                    //$this->data['mail_header'] = '<img id="headerImage campaign-icon" src="' . $site_logo . '" title="' . $this->data["site_name"] . '" width="250" /> ';
                    $this->data['mail_header'] = $this->data['app_name'];
                    $this->data['mail_footer'] = '<a href="' . site_url() . '">' . $this->data['app_name'] . '</a> | Copyright &copy;' . $year . ' | All rights reserved</p>';
                    $mail_body = view('mail', $this->data);
                    //  echo '<pre>';                    print_r($mail_body); die;
                    $this->sendEmail($this->data['app_name'], $this->data['app_email'], $email, $subject, $mail_body);
                }
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
            $res = $this->common->check_unique_avalibility('admin', 'email', $email, '', '', array('is_deleted' => "0"));

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
            $res = $this->common->check_unique_avalibility('admin', 'email', $email, 'admin_id', $id, array('is_deleted' => "0"));

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
            $res = $this->common->check_unique_avalibility('admin', 'username', $email, '', '', array('is_deleted' => "0"));

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
            $res = $this->common->check_unique_avalibility('admin', 'username', $email, 'admin_id', $id, array('is_deleted' => "0"));

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

    public function resendPass() {
        $json = array();
        $json['status'] = 'fail';
        $json['msg'] = '';
        $id = $this->request->getVar('id');
        $info = $this->common->select_data_by_condition('admin', array('admin_id' => $id), '*', '', '', '', '', array());
        //echo '<pre>';        print_r($info); die;
        if (empty($info)) {
            $json['msg'] = 'No information found!';
            echo json_encode($json);
            die();
        }
        $password = $this->request->getVar('newpass');

        $info1 = array(
            "password" => sha1($password),
        );
        $res = $this->common->update_data($info1, 'admin', 'admin_id', $info[0]['admin_id']);

        $name = ucfirst($info[0]['firstname']);
        $email = $info[0]['email'];

        $site_logo = base_url() . '/assets/images/logo.jpg';

        $year = date('Y');
        $mailData = $this->common->select_data_by_id('email_format', 'id', 6, '*', array());
        $subject = str_replace('%site_name%', $this->data['app_name'], $mailData[0]['subject']);
        $mailformat = $mailData[0]['emailformat'];
        $this->data['mail_body'] = str_replace("%site_logo%", $site_logo, str_replace("%name%", $name, str_replace("%password%", $password, str_replace("%email%", $email, str_replace("%site_name%", $this->data['app_name'], str_replace("%year%", $year, stripslashes($mailformat)))))));
        //$this->data['mail_header'] = '<img id="headerImage campaign-icon" src="' . $site_logo . '" title="' . $this->data["site_name"] . '" width="250" /> ';
        $this->data['mail_header'] = $this->data['app_name'];
        $this->data['mail_footer'] = '<a href="' . site_url() . '">' . $this->data['app_name'] . '</a> | Copyright &copy;' . $year . ' | All rights reserved</p>';
        $mail_body = view('mail', $this->data);
        //  echo '<pre>';                    print_r($mail_body); die;
        $this->sendEmail($this->data['app_name'], $this->data['app_email'], $email, $subject, $mail_body);

        if ($res) {
            $json['status'] = 'success';
            $json['msg'] = 'Password has been resend successfully.';
        } else {
            $json['msg'] = 'Sorry! something went wrong please try later!';
        }
        echo json_encode($json);
        die();
    }

}
