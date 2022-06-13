<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class User extends BaseController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        //$this->is_allowed();

        $response->setHeader('Last-Modified', gmdate('D, d M Y H:i:s') . 'GMT');
        $response->setHeader('Cache-Controln', 'o-store, no-cache, must-revalidate');
        $response->setHeader('Cache-Control', ' post-check=0, pre-check=0', false);
        $response->setHeader('Pragma', ' no-cache');
        $this->data['title'] = 'User : ' . $this->data['app_name'];
        $this->data['header'] = view('header', $this->data);
        $this->data['sidebar'] = view('sidebar', $this->data);
        $this->data['footer'] = view('footer', $this->data);
        $this->data['country'] = $this->common->select_data_by_condition('country', array('id' => 231), '*', '', '', '', '', array());
        $this->data['state'] = $this->common->select_data_by_condition('state', array('country_id' => 231), '*', 'name', 'ASC', '', '', array());
    }

    public function index() {
        return view('user/index', $this->data);
    }

    public function gettabledata() {
        $columns = array('user.id', 'user.name', 'user.email', 'user.contact_no', 'user.status');
        $request = $this->request->getGet();
        $condition = array('user.is_deleted' => '0',);
        $join_str = array();
        $getfiled = "user.id,user.name,user.email,user.contact_no,user.status";
        echo $this->common->getDataTableSource('user', $columns, $condition, $getfiled, $request, $join_str, '');
        //echo '<pre>';        print_r($this->db->last_query());
        die();
    }

    public function add() {

        return view('user/add', $this->data);
    }

    public function addnew() {
        $this->validation->setRule('name', 'User name', 'required|trim|strip_tags');
        $this->validation->setRule('email', 'Email', 'required|trim|strip_tags');
        $this->validation->setRule('address1', 'Address1', 'required|trim|strip_tags');
        $this->validation->setRule('country_id', 'Country', 'required|trim|strip_tags');
        $this->validation->setRule('state_id', 'State', 'required|trim|strip_tags');
        $this->validation->setRule('city', 'City', 'required|trim|strip_tags');
        $this->validation->setRule('zipcode', 'Zipcode', 'required|trim|strip_tags');

        if ($this->validation->withRequest($this->request)->run() == FALSE) {
            session()->setFlashdata('error', $this->validation->listErrors());
            return redirect()->back();
        } else {
            $check_email = $this->common->select_data_by_condition('user', array('email' => $this->request->getVar('email'), 'is_deleted' => '0'), '*', '', '', '', '', array());
            if (!empty($check_email)) {
                session()->setFlashdata('error', 'Email already exists.');
                return redirect()->back();
            }
            $dataimage = '';
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != null && $_FILES['image']['size'] > 0) {

                $config['upload_path'] = $this->config->item('upload_path_user');
                $config['allowed_types'] = $this->config->item('upload_user_allowed_types');
                $config['file_name'] = rand(10, 99) . time();
                $this->load->library('upload');
                //$this->load->library('image_lib');
                // Initialize the new config
                $this->upload->initialize($config);
                //Uploading Image
                $this->upload->do_upload('image');
                //Getting Uploaded Image File Data
                $imgdata = $this->upload->data();
                $imgerror = $this->upload->display_errors();

                // print_r($imgerror);die();
                if ($imgerror == '') {
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $this->upload->upload_path . $this->upload->file_name;
                    $config['new_image'] = $this->config->item('upload_path_user_thumb') . $imgdata['file_name'];
                    $dataimage = $imgdata['file_name'];
                    $filename = $_FILES['image']['tmp_name'];

                    $config['maintain_ratio'] = FALSE;
                    $imgedata = exif_read_data($this->upload->upload_path . $this->upload->file_name, 'IFD0');


                    list($width, $height) = getimagesize($filename);

                    $config['width'] = $this->config->item('user_thumb_width');

                    $config['height'] = $this->config->item('user_thumb_height');

                    //$config['master_dim'] = 'auto';


                    $this->load->library('image_lib', $config);

                    if ($this->image_lib->resize()) {

                        $this->image_lib->clear();
                        $config = array();

                        $config['image_library'] = 'gd2';
                        $config['source_image'] = $this->config->item('upload_path_user_thumb') . $imgdata['file_name'];

                        switch ($imgedata['Orientation']) {
                            case 3:
                                $config['rotation_angle'] = '180';
                                break;
                            case 6:
                                $config['rotation_angle'] = '270';
                                break;
                            case 8:
                                $config['rotation_angle'] = '90';
                                break;
                        }

                        $this->image_lib->initialize($config);
                        $this->image_lib->rotate();
                    }
                } else {
                    $thumberror = '';
                    $dataimage = '';
                }
            }
            require_once(BASEPATH . 'Authenticator/rfc6238.php');
            $authenticatore_secrete = TokenAuth6238::generateRandomClue(16);

            $investorData['google_code'] = $authenticatore_secrete;
            $password = $this->password_generate(8);
            $password_reset_token = time() . rand(100, 20000);
            $insert_data = array(
                "name" => (ucwords($this->request->getVar('name'))),
                "email" => ($this->request->getVar('email')),
                "contact_no" => ($this->request->getVar('contact_no')),
                "address2" => ($this->request->getVar('address2')),
                "address1" => ($this->request->getVar('address1')),
                "country_id" => ($this->request->getVar('country_id')),
                "state_id" => ($this->request->getVar('state_id')),
                "city" => ($this->request->getVar('city')),
                "zipcode" => ($this->request->getVar('zipcode')),
                "auth_code"=>$authenticatore_secrete,
                "image" => $dataimage,
                "status" => 'Disable',
                "email_verify" => '0',
                "password" => sha1($password),
                "reset_token" => $password_reset_token,
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
            $user = $this->common->insert_data_getid($insert_data, "user");
            if ($user) {
                $name = ucwords($this->request->getVar('name'));
                $email = ($this->request->getVar('email'));

                $site_logo = base_url() . '/assets/images/logo.jpg';
                $url = $this->config->item('front_url');

                $year = date('Y');
                $mailData = $this->common->select_data_by_id('email_format', 'id', 3, '*', array());
                $subject = str_replace('%site_name%', $this->data['app_name'], $mailData[0]['subject']);
                $mailformat = $mailData[0]['emailformat'];
                $activation_link = '<a href="' . site_url('../Login/verifyemail/' . $password_reset_token) . '" class="btn-primary" itemprop="url" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #348eda; margin: 0; border-color: #348eda; border-style: solid; border-width: 10px 20px;">Confirm email address</a>';

                $this->data['mail_body'] = str_replace("%site_logo%", $site_logo, str_replace("%name%", $name, str_replace("%activation_link%", $activation_link, str_replace("%password%", $password, str_replace("%email%", $email, str_replace("%url%", $url, str_replace("%site_name%", $this->data['app_name'], str_replace("%year%", $year, stripslashes($mailformat)))))))));
                //$this->data['mail_header'] = '<img id="headerImage campaign-icon" src="' . $site_logo . '" title="' . $this->data["site_name"] . '" width="250" /> ';
                $this->data['mail_header'] = $this->data['app_name'];
                $this->data['mail_footer'] = '<a href="' . site_url() . '">' . $this->data['app_name'] . '</a> | Copyright &copy;' . $year . ' | All rights reserved</p>';
                $mail_body = view('mail', $this->data);
                // echo '<pre>';                    print_r($mail_body); die;
                $this->sendEmail($this->data['app_name'], $this->data['app_email'], $email, $subject, $mail_body);

                session()->setFlashdata('success', 'User added successfully.');
                return redirect()->to('User');
            } else {
                session()->setFlashdata('error', 'There is an error occured. please try after again');
                return redirect()->to('User');
            }
        }
    }

    function password_generate($chars) {
        $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($data), 0, $chars);
    }

    public function edit($id) {
        $sub_id = base64_decode($id);
        $this->data['info'] = $this->common->select_data_by_condition('user', array('user.id' => $sub_id), '*', '', '', '', '', array());
        if (empty($this->data['info'])) {
            session()->setFlashdata('error', 'No information found!');
           return redirect()->back();
        } else {
            return view('user/edit', $this->data);
        }
    }

    public function editnew($id) {
        $sub_id = base64_decode($id);

        $this->validation->setRule('name', 'User name', 'required|trim|strip_tags');
        $this->validation->setRule('email', 'Email', 'required|trim|strip_tags');
        $this->validation->setRule('contact_no', 'Contact no', 'required|trim|strip_tags');
        $this->validation->setRule('address1', 'Address1', 'required|trim|strip_tags');
        $this->validation->setRule('country_id', 'Country', 'required|trim|strip_tags');
        $this->validation->setRule('state_id', 'State', 'required|trim|strip_tags');
        $this->validation->setRule('city', 'City', 'required|trim|strip_tags');
        $this->validation->setRule('zipcode', 'Zipcode', 'required|trim|strip_tags');

        if ($this->validation->withRequest($this->request)->run() == FALSE) {
            session()->setFlashdata('error', $this->validation->listErrors());
            return redirect()->back();
        } else {
            $info = $this->common->select_data_by_condition('user', array('user.id' => $sub_id), '*', '', '', '', '', array());
            $dataimage = $info[0]['image'];
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != null && $_FILES['image']['size'] > 0) {

                $config['upload_path'] = $this->config->item('upload_path_user');
                $config['allowed_types'] = $this->config->item('upload_user_allowed_types');
                $config['file_name'] = rand(10, 99) . time();
                $this->load->library('upload');
                //$this->load->library('image_lib');
                // Initialize the new config
                $this->upload->initialize($config);
                //Uploading Image
                $this->upload->do_upload('image');
                //Getting Uploaded Image File Data
                $imgdata = $this->upload->data();
                $imgerror = $this->upload->display_errors();

                // print_r($imgerror);die();
                if ($imgerror == '') {
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $this->upload->upload_path . $this->upload->file_name;
                    $config['new_image'] = $this->config->item('upload_path_user_thumb') . $imgdata['file_name'];
                    $dataimage = $imgdata['file_name'];
                    $filename = $_FILES['image']['tmp_name'];

                    $config['maintain_ratio'] = FALSE;
                    $imgedata = exif_read_data($this->upload->upload_path . $this->upload->file_name, 'IFD0');


                    list($width, $height) = getimagesize($filename);

                    $config['width'] = $this->config->item('user_thumb_width');

                    $config['height'] = $this->config->item('user_thumb_height');

                    //$config['master_dim'] = 'auto';


                    $this->load->library('image_lib', $config);

                    if ($this->image_lib->resize()) {

                        $this->image_lib->clear();
                        $config = array();

                        $config['image_library'] = 'gd2';
                        $config['source_image'] = $this->config->item('upload_path_user_thumb') . $imgdata['file_name'];

                        switch ($imgedata['Orientation']) {
                            case 3:
                                $config['rotation_angle'] = '180';
                                break;
                            case 6:
                                $config['rotation_angle'] = '270';
                                break;
                            case 8:
                                $config['rotation_angle'] = '90';
                                break;
                        }

                        $this->image_lib->initialize($config);
                        $this->image_lib->rotate();
                        if ($info[0]['image'] != '') {
                            if (file_exists($this->config->item('upload_path_user') . $info[0]['image'])) {
                                @unlink($this->config->item('upload_path_user') . $info[0]['image']);
                            }
                            if (file_exists($this->config->item('upload_path_user_thumb') . $info[0]['image'])) {
                                @unlink($this->config->item('upload_path_user_thumb') . $info[0]['image']);
                            }
                        }
                    }
                } else {
                    $thumberror = '';
                    $dataimage = '';
                }
            }

            $update_data = array(
                "name" => (ucwords($this->request->getVar('name'))),
                "email" => ($this->request->getVar('email')),
                "contact_no" => ($this->request->getVar('contact_no')),
                "address2" => ($this->request->getVar('address2')),
                "address1" => ($this->request->getVar('address1')),
                "country_id" => ($this->request->getVar('country_id')),
                "state_id" => ($this->request->getVar('state_id')),
                "city" => ($this->request->getVar('city')),
                "zipcode" => ($this->request->getVar('zipcode')),
                "image" => $dataimage,
                "modified_ip" => $this->request->getIPAddress(),
                "modified_datetime" => date('Y-m-d H:i:s'),
                "modified_browser" => $this->request->getUserAgent()->getBrowser(),
                "modified_os" => $this->request->getUserAgent()->getPlatform(),
                "modified_by" => $this->data['adminID'],
            );
            //echo '<pre>';            print_r($update_data); die;
            $user = $this->common->update_data($update_data, 'user', 'user.id', $sub_id);

            if ($user) {
                session()->setFlashdata('success', 'User updated successfully.');
                return redirect()->to('User');
            } else {
                session()->setFlashdata('error', 'There is an error occured. please try after again');
                return redirect()->to('User');
            }
        }
    }

    function delete() {
        $json = array();
        $json['msg'] = '';
        $json['status'] = 'fail';
        $id = $this->request->getVar('id');
        //echo $id; die;
        $group = $this->common->select_data_by_condition('user', array('user.id' => $id), '*', '', '', '', '', array());
        if (!empty($group)) {

            $res = $this->common->update_data(array('is_deleted' => '1'), 'user', 'user.id', $id);
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

        $data = $this->common->select_data_by_condition('user', array('user.id' => $id), '*', '', '', '', '', array());
        if (empty($data)) {
            $json['msg'] = 'No information Found!';
        } else {
            $result = $this->common->update_data(array('status' => $status), 'user', 'user.id', $id);
            if ($result) {
                $name = ucfirst($data[0]['name']);
                $email = $data[0]['email'];
                $site_logo = base_url() . '/assets/images/logo.jpg';
                $year = date('Y');
                if ($status == 'Enable') {

                    $mailData = $this->common->select_data_by_id('email_format', 'id', 13, '*', array());
                    $subject = str_replace('%site_name%', $this->data['app_name'], $mailData[0]['subject']);
                    $mailformat = $mailData[0]['emailformat'];
                    $this->data['mail_body'] = str_replace("%site_logo%", $site_logo, str_replace("%name%", $name, str_replace("%reason%", $reason, str_replace("%site_name%", $this->data['app_name'], str_replace("%year%", $year, stripslashes($mailformat))))));
                    //$this->data['mail_header'] = '<img id="headerImage campaign-icon" src="' . $site_logo . '" title="' . $this->data["site_name"] . '" width="250" /> ';
                    $this->data['mail_header'] = $this->data['app_name'];
                    $this->data['mail_footer'] = '<a href="' . site_url() . '">' . $this->data['app_name'] . '</a> | Copyright &copy;' . $year . ' | All rights reserved</p>';
                    $mail_body = view('mail', $this->data);
                    // echo '<pre>';                    print_r($mail_body); die;
                    $this->sendEmail($this->data['app_name'], $this->data['app_email'], $email, $subject, $mail_body);
                } else {
                    $mailData = $this->common->select_data_by_id('email_format', 'id', 16, '*', array());
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
            $res = $this->common->check_unique_avalibility('user', 'email', $email, '', '', array('is_deleted' => '0'));

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
            $res = $this->common->check_unique_avalibility('user', 'email', $email, 'user.id', $id, array('is_deleted' => '0'));

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
        $info = $this->common->select_data_by_condition('user', array('user.id' => $id), '*', '', '', '', '', array());
        //echo '<pre>';        print_r($info); die;
        if (empty($info)) {
            $json['msg'] = 'No information found!';
            echo json_encode($json);
            die();
        }
        $password = $this->request->getVar('newpass');

        $info1 = array(
            "password" => sha1($password),
            "modified_ip" => $this->request->getIPAddress(),
            "modified_datetime" => date('Y-m-d H:i:s'),
        );
        $res = $this->common->update_data($info1, 'user', 'user.id', $info[0]['user.id']);

        $name = ucfirst($info[0]['name']);
        $email = $info[0]['email'];

        $site_logo = base_url() . '/assets/images/logo.jpg';

        $year = date('Y');
        $mailData = $this->common->select_data_by_id('email_format', 'id', 5, '*', array());
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
            $json['msg'] = 'Password has been resent successfully.';
        } else {
            $json['msg'] = 'Sorry! something went wrong please try later!';
        }
        echo json_encode($json);
        die();
    }

}
