<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class TransactionSetting extends BaseController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        //$this->is_allowed();

        $response->setHeader('Last-Modified', gmdate('D, d M Y H:i:s') . 'GMT');
        $response->setHeader('Cache-Controln', 'o-store, no-cache, must-revalidate');
        $response->setHeader('Cache-Control', ' post-check=0, pre-check=0', false);
        $response->setHeader('Pragma', ' no-cache');
        $this->data['title'] = 'Transaction Settings : ' . $this->data['app_name'];
        $this->data['header'] = view('header', $this->data);
        $this->data['sidebar'] = view('sidebar', $this->data);
        $this->data['footer'] = view('footer', $this->data);
    }

    //load listing setting view
    public function index() {
        //Addingg TransactionSetting Result to variable

        $this->data['settings'] = $this->common->select_data_by_condition('transaction_setting', array('status' => 'Enable'), '*', '', '', '', '', array());

        return view('transactionsetting/index', $this->data);
    }

    //update general setting record
    function update() {
        $id = base64_decode($this->request->getVar('id'));
        if ($this->request->isAJAX()) {
            if ($id != '' && $id != 0) {
                $this->data['setting'] = $this->common->selectRecordById('transaction_setting', $id, 'id');
                return view('transactionsetting/edit', $this->data);
            } else {
                echo '<div class="alert alert-danger">
                       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                       <strong>Record not found with specified id. Try later!</strong>
                   </div>';
            }
            return;
        }
        if ($this->request->getMethod() == 'post') {
            $this->validation->setRule('setting_value', 'field_value', 'required');
            if ($this->validation->withRequest($this->request)->run() == FALSE) {
                session()->setFlashdata('error', $this->validation->listErrors());
                return redirect()->back();
            } else {
                if ($id != '' && $id != 0) {
                    $fieldvalue = ($this->request->getVar('setting_value'));
                    $settingdata = array(
                        'value' => $fieldvalue,
                        'manual_value' => ($this->request->getVar('manual_value')),
                        "modified_ip" => $this->request->getIPAddress(),
                        "modified_datetime" => date('Y-m-d H:i:s'),
                        "modified_browser" => $this->request->getUserAgent()->getBrowser(),
                        "modified_os" => $this->request->getUserAgent()->getPlatform(),
                        "modified_by" => $this->data['adminID'],
                    );
                    $settingInfo = $this->common->selectRecordById('transaction_setting', $id, 'id');
                    $settingName = $settingInfo['name'];
                    if ($this->common->update_data($settingdata, "transaction_setting", "id", $id)) {
                        session()->setFlashdata('success', $settingName . ' updated successfully.');
                        return redirect()->to('TransactionSetting');
                    } else {
                        session()->setFlashdata('error', 'There is error in updating ' . $settingName . '. Try later!');
                        return redirect()->to('TransactionSetting');
                    }
                } else {
                    session()->setFlashdata('error', 'Record not found with specified id. Try later!');
                    return redirect()->to('TransactionSetting');
                }
                return;
            }
        }
    }

}

/*  End of file TransactionSetting.php 
 *  Location: ./application/controllers/TransactionSetting.php 
 */
