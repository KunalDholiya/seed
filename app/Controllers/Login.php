<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Login extends Controller {

    public $data = [];
    public $common;
    protected $helpers = ['url', 'file', 'form', 'security'];
    protected $libraries = ['database', 'email', 'session', 'form_validation'];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

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
        $this->data['title'] = 'Login : ' . $this->data['app_name'];
        
        //get site related setting details
    }

    public function index() {
        if (session()->has('seed_admin')) {
            return redirect()->to('Dashboard');
        }
       $this->common = new \App\Models\Common();
        $app_name = $this->common->selectRecordById('settings', '1', 'setting_id');
        $this->data['app_name'] = $app_name['setting_value'];
        $this->data['title'] = 'Login : ' . $this->data['app_name'];
        return view('login/index', $this->data);
    }

    public function authenticate() {
        $this->common = new \App\Models\Common();
        $validation = \Config\Services::validation();
        $session = \Config\Services::session();
        $validation->setRule('username', 'Email', 'required');
        $validation->setRule('password', 'Password', 'required');

        if ($validation->withRequest($this->request)->run() == FALSE) {
            session()->setFlashdata('error', 'Please follow validation rules!');
            return redirect()->to('Login');
        } else {

            $userName = $this->request->getVar('username');
            $password = sha1($this->request->getVar('password'));
            $remember = $this->request->getVar('remember');
         
            $checkAuth = $this->common->selectRecordById('admin', $userName, 'email');

            if (!empty($checkAuth)) {
                $dbPassword = $checkAuth['password'];
                $dbusername = $checkAuth['email'];
                if ($userName == $dbusername && $password === $dbPassword) {

                    if ($remember != '' && $remember == 1) {
                        $cookie = array(
                            'name' => 'remember_user_id',
                            'value' => $checkAuth['admin_id'],
                            'expire' => '86500',
                        );
                        $this->request->set_cookie($cookie);
                    }
                    $session->set('seed_admin', $checkAuth['admin_id']);

                    //$this->session->set_flashdata('add_class', true);
                    return redirect()->to('Dashboard');
                } else {
                    session()->setFlashdata('error', 'Email or Password you entered is incorrect OR the account does not exist!');
                    return redirect()->to('Login');
                }
            } else {
                session()->setFlashdata('error', 'Email or Password you entered is incorrect OR the account does not exist!');
               return redirect()->to('Login');
            }
        }
    }

}
