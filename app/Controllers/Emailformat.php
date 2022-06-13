<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Emailformat extends BaseController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        //$this->is_allowed();

        $response->setHeader('Last-Modified', gmdate('D, d M Y H:i:s') . 'GMT');
        $response->setHeader('Cache-Controln', 'o-store, no-cache, must-revalidate');
        $response->setHeader('Cache-Control', ' post-check=0, pre-check=0', false);
        $response->setHeader('Pragma', ' no-cache');
        $this->data['title'] = 'Emailformat : ' . $this->data['app_name'];
        $this->data['header'] = view('header', $this->data);
        $this->data['sidebar'] = view('sidebar', $this->data);
        $this->data['footer'] = view('footer', $this->data);

        
    }

    public function index() {
        $this->data['formattype'] = '1';
        return view('emailformat/index', $this->data);
    }

    public function university() {
        $this->data['formattype'] = '2';
        return view('emailformat/index', $this->data);
    }

    public function instructor() {
        $this->data['formattype'] = '3';
        return view('emailformat/index', $this->data);
    }

    public function student() {
        $this->data['formattype'] = '4';
        return view('emailformat/index', $this->data);
    }

    public function SubAdmin() {
        $this->data['formattype'] = '5';
        return view('emailformat/index', $this->data);
    }

    function getdata() {

        $columns = array('title', 'subject');
        $request = $this->request->getGet();

        $condition = array('type' => $request['type1']);
        $join_str = array();


        $getfiled = "id,title,subject";

        echo $this->common->getDataTableSource('email_format', $columns, $condition, $getfiled, $request, $join_str, '');

        die();
    }

    public function edit($id) {

        $id = base64_decode($id);

        $email_format = $this->common->select_data_by_condition('email_format', array('id' => $id), '*', '', '', '1', '', array());

        if (empty($email_format)) {
            session()->setFlashdata('error', 'No information found!');
            return redirect()->back();
        }
        $this->data['editinfo'] = $email_format[0];
        $this->data['formattype'] = $email_format[0]['type'];

        return view('emailformat/edit', $this->data);
    }

    function update() {


        $redirect = '';
        $last_url = $this->request->getVar('last_url_params');
        if ($last_url != '') {
            $redirect = $last_url;
        } else {
            $redirect = 'Emailformat/index';
        }

        $id = base64_decode($this->request->getVar('id'));
        $tabledata = $this->common->select_data_by_condition('email_format', array('id' => $id), '*', '', '', '', '', array());
        if (empty($tabledata)) {
            session()->setFlashdata('error', 'No information found!');
           return redirect()->back();
        }


        $this->validation->setRule('esubject', 'Subject', 'required');
        $this->validation->setRule('eemailformat', 'Email Format', 'required');


        if ($this->validation->withRequest($this->request)->run() == FALSE) {
            session()->setFlashdata('error', $this->validation->listErrors());
            return redirect()->back();
        }


        $subject = $this->request->getVar('esubject');
        $emailformat = $this->request->getVar('eemailformat');


        $data = array(
            'subject' => $subject,
            'emailformat' => $emailformat,
        );

        if ($this->common->update_data($data, 'email_format', 'id', $id)) {
            if ($tabledata[0]['type'] == '1') {
                session()->setFlashdata('success', 'Email format has been updated successfully');
                return redirect()->to($redirect);
            } else if ($tabledata[0]['type'] == '2') {
                session()->setFlashdata('success', 'Email format has been updated successfully');
                return redirect()->to('Emailformat/university');
            } else if ($tabledata[0]['type'] == '3') {
                session()->setFlashdata('success', 'Email format has been updated successfully');
                return redirect()->to('Emailformat/instructor');
            } else if ($tabledata[0]['type'] == '4') {
                session()->setFlashdata('success', 'Email format has been updated successfully');
                return redirect()->to('Emailformat/student');
            } else if ($tabledata[0]['type'] == '5') {
                session()->setFlashdata('success', 'Email format has been updated successfully');
                return redirect()->to('Emailformat/SubAdmin');
            }
        } else {
            session()->setFlashdata('error', 'Sorry! Something went wrong please try later!');
            return redirect()->to($redirect);
        }
    }

}
