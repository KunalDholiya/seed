<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use CodeIgniter\HTTP\Response;
use Config\Services;

/*
 * ForgotPassword.php file contains functions for authenticate admin for login
 */

class ForgotPassword extends Controller {

    public $data;
    public $common;
    protected $helpers = ['url', 'file', 'form', 'security'];
    protected $libraries = ['database', 'email', 'session', 'form_validation'];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        $this->db = \Config\Database::connect();
        $this->validation = \Config\Services::validation();
        $this->view = \Config\Services::renderer();
        $this->session = Services::session();
        $this->router = service('router');
        $this->uri = new \CodeIgniter\HTTP\URI();
        // Load the model

        if (session()->has('seed_admin')) {
            return redirect()->to('Dashboard');
        }
        //after logout not to open page on back in browser so clear cache


        $response->setHeader('Last-Modified', gmdate('D, d M Y H:i:s') . 'GMT');
        $response->setHeader('Cache-Controln', 'o-store, no-cache, must-revalidate');
        $response->setHeader('Cache-Control', ' post-check=0, pre-check=0', false);
        $response->setHeader('Pragma', ' no-cache');
        $this->common = new \App\Models\Common();

        $app_name = $this->common->selectRecordById('settings', '1', 'setting_id');
        $this->data['app_name'] = $app_name['setting_value'];
        $app_name = $this->common->selectRecordById('settings', '2', 'setting_id');
        $this->data['app_email'] = $app_name['setting_value'];
        $app_logo = $this->common->selectRecordById('settings', '7', 'setting_id');
        $this->data['app_logo'] = $app_logo['setting_value'];
        $this->data['title'] = 'Reset Password : ' . $this->data['app_name'];

        //get site related setting details
    }

    //forgot password
    public function index() {

        $forgotEmail = $this->request->getVar('forgot_email');
        $checkAuth = $this->common->selectRecordById('admin', $forgotEmail, 'email');

        if (!empty($checkAuth)) {
            $slug = $checkAuth['firstname'] . '_' . $checkAuth['lastname'] . '_' . rand(1000, 9999) . $checkAuth['admin_id'];
            $update_data = array('admin_slug' => $slug, 'modified_date' => date('Y-m-d H:i:s'));
            $this->common->update_data($update_data, 'admin', 'admin_id', $checkAuth['admin_id']);
            $name = $checkAuth['firstname'] . ' ' . $checkAuth['lastname'];
            $new_password_link = '<a title="Reset Password" href="' . site_url('ForgotPassword/reset_password/' . $slug) . '">Click Here</a>';
            $year = date('Y');
            if ($checkAuth['role'] == 1) {
                $mailData = $this->common->selectRecordById('email_format', '1', 'id');
                $subject = $mailData['subject'];
                $mailformat = $mailData['emailformat'];
                $app_name = $this->common->selectRecordById('settings', '2', 'setting_id');
                $app_email = $app_name['setting_value'];
                $this->data['mail_body'] = str_replace("%name%", $name, str_replace("%reset_link%", $new_password_link, str_replace("%site_name%", $this->data['app_name'], str_replace("%siteurl%", $this->data['app_name'], stripslashes($mailformat)))));
                //$this->data['mail_header'] = '<img id="headerImage campaign-icon" src="' . $site_logo . '" title="' . $this->data["site_name"] . '" width="250" /> ';
                $this->data['mail_header'] = $this->data['app_name'];
                $this->data['mail_footer'] = '<a href="' . site_url() . '">' . $this->data['app_name'] . '</a> | Copyright &copy;' . $year . ' | All rights reserved</p>';
                $mail_body = view('mail', $this->data);

                $this->sendEmail($this->data['app_name'], $app_email, $forgotEmail, $subject, $mail_body);
            } else if ($checkAuth['role'] == 2) {
                $mailData = $this->common->selectRecordById('email_format', '9', 'id');
                $subject = $mailData['subject'];
                $mailformat = $mailData['emailformat'];
                $app_name = $this->common->selectRecordById('settings', '2', 'setting_id');
                $app_email = $app_name['setting_value'];
                $this->data['mail_body'] = str_replace("%name%", $name, str_replace("%reset_link%", $new_password_link, str_replace("%site_name%", $this->data['app_name'], str_replace("%siteurl%", $this->data['app_name'], stripslashes($mailformat)))));
                //$this->data['mail_header'] = '<img id="headerImage campaign-icon" src="' . $site_logo . '" title="' . $this->data["site_name"] . '" width="250" /> ';
                $this->data['mail_header'] = $this->data['app_name'];
                $this->data['mail_footer'] = '<a href="' . site_url() . '">' . $this->data['app_name'] . '</a> | Copyright &copy;' . $year . ' | All rights reserved</p>';
                $mail_body = view('mail', $this->data);

                $this->sendEmail($this->data['app_name'], $app_email, $forgotEmail, $subject, $mail_body);
            }

            session()->setFlashdata('success', 'An Email has been sent to the given email address. Follow the instructions in the email to reset your password!');
            return redirect()->to('login');
        } else {
            session()->setFlashdata('error', 'Oops, it is not a registered email address!');
            return redirect()->to('login');
        }
    }

    //reset password
    public function reset_password($slug = '') {
        $checkAuth = $this->common->selectRecordById('admin', $slug, 'admin_slug');

        if ($this->request->getMethod() == 'post') {
            $newpassword = $this->request->getVar('password');
            $confirmpass = $this->request->getVar('cnfpassword');
            if ($newpassword != $confirmpass) {
                session()->setFlashdata('error', 'New password and Confirm password must be same.');
                return redirect()->to('login');
            }
            $time = $checkAuth['modified_date'];
            if ($this->request->getServer('REQUEST_TIME') - strtotime($time) > 60 * 60 * 24) {
                session()->setFlashdata('error', 'You password reset link is expired.');
                return redirect()->to('login');
            }
            $updatedPassword = sha1($newpassword);
            $slug = $checkAuth['firstname'] . '_' . $checkAuth['lastname'] . '_' . rand(1000, 9999) . $checkAuth['admin_id'];
            if ($this->common->update_data(array('password' => $updatedPassword, 'admin_slug' => $slug, 'modified_date' => date('Y-m-d H:i:s')), 'admin', 'admin_id', $checkAuth['admin_id'])) {
                session()->setFlashdata('success', 'New password set successfully.');
                return redirect()->to('login');
            } else {
                session()->setFlashdata('error', 'Error Occurred. Try Again!');
                return redirect()->to('login');
            }
        }
        $this->data['slug'] = $this->request->uri->getSegment(2);
        
        return view('Login/changePassword', $this->data);
    }

    //send email
    function sendEmail($app_name, $app_email, $to_email, $subject, $mail_body) {
        $email = \Config\Services::email();
        $email->setFrom($app_email, $app_name);
        $email->setTo($to_email);
        //$email->setCC('another@another-example.com');
        //$email->setBCC('them@their-example.com');

        $email->setSubject($subject);
        $email->setMessage("<table border='0' cellpadding='0' cellspacing='0'><tr><td></td></tr><tr><td>" . $mail_body . "</td></tr></table>");
        //$email->send();
        if ($email->send()) {
            return;
        } else {
            $data = $email->printDebugger(['headers']);
            print_r($data);
        }

        return;
    }

}

/* 
 * End of file ForgotPassword.php
 * Location: ./application/admincp/controllers/ForgotPassword.php 
 */
