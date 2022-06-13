<?php

/*
 * Setting.php file contains functions for managing general setting of site.
 */

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Setting extends BaseController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        //$this->is_allowed();

        $response->setHeader('Last-Modified', gmdate('D, d M Y H:i:s') . 'GMT');
        $response->setHeader('Cache-Controln', 'o-store, no-cache, must-revalidate');
        $response->setHeader('Cache-Control', ' post-check=0, pre-check=0', false);
        $response->setHeader('Pragma', ' no-cache');
        $this->data['title'] = 'Setting : ' . $this->data['app_name'];
        $this->data['header'] = view('header', $this->data);
        $this->data['sidebar'] = view('sidebar', $this->data);
        $this->data['footer'] = view('footer', $this->data);
        $this->data['redirect_url'] = $this->last_url();
    }

    //load listing setting view
    public function index() {
        //Addingg Setting Result to variable

        $this->data['settings'] = $this->common->select_data_by_condition('settings', array('status' => 'Enable'), '*', '', '', '', '', array());

        return view('setting/index', $this->data);
    }

    //update general setting record
    function update() {
        $setting_id = base64_decode($this->request->getVar('setting_id'));
        if ($this->request->isAJAX()) {
            if ($setting_id != '' && $setting_id != 0) {
                $this->data['setting'] = $this->common->selectRecordById('settings', $setting_id, 'setting_id');
                return view('setting/edit', $this->data);
            } else {
                echo '<div class="alert alert-danger">
                       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                       <strong>Record not found with specified id. Try later!</strong>
                   </div>';
            }
            return;
        }
        if ($this->request->getMethod() == 'post') {

            if ($setting_id != 7) {
                $this->validation->setRule('setting_value', 'field_value', 'required');
                if ($this->validation->withRequest($this->request)->run() == FALSE) {
                    session()->setFlashdata('error', 'Please follow validation rules!');
                    return redirect()->to('Setting');
                }
            }

            if ($setting_id != '' && $setting_id != 0) {
                $info = $this->common->selectRecordById('settings', $setting_id, 'setting_id');

                $fieldvalue = ($this->request->getVar('setting_value'));

                if ($setting_id == 7) {


                    if (isset($_FILES['setting_value']['name']) && $_FILES['setting_value']['name'] != null && $_FILES['setting_value']['size'] > 0) {
                        $this->validation->setRule('setting_value', 'field_value', 'uploaded[setting_value]|mime_in[setting_value,' . config('CustomConfig')->upload_logo_allowed_types . ']');
                        if ($this->validation->withRequest($this->request)->run() == FALSE) {
                            session()->setFlashdata('error', $this->validation->listErrors());
                            return redirect()->to('Setting');
                        }
                        $isFile = $this->request->getFile('setting_value');
                        $fieldvalue = $isFile->getName();
                        $filename = rand(10, 99) . time();
                        $image = \Config\Services::image()
                                ->withFile($isFile)
                                ->resize(config('CustomConfig')->logo_thumb_width, config('CustomConfig')->logo_thumb_height, false, '')
                                ->save(FCPATH . config('CustomConfig')->upload_path_logo_thumb . $isFile->getName());

                        $isFile->move(FCPATH . config('CustomConfig')->upload_path_logo);
                        if ($info['setting_value'] != '') {
                            if (file_exists(FCPATH . config('CustomConfig')->upload_path_logo_thumb . $info['setting_value'])) {
                                @unlink(FCPATH . config('CustomConfig')->upload_path_logo_thumb . $info['setting_value']);
                            }
                            if (file_exists(FCPATH . config('CustomConfig')->upload_path_logo . $info['setting_value'])) {
                                @unlink(FCPATH . config('CustomConfig')->upload_path_logo . $info['setting_value']);
                            }
                        }
                    }
                }


                $settingdata = array('setting_value' => $fieldvalue);
                $settingInfo = $this->common->selectRecordById('settings', $setting_id, 'setting_id');
                $settingName = $settingInfo['setting_name'];
                if ($this->common->update_data($settingdata, "settings", "setting_id", $setting_id)) {

                    session()->setFlashdata('success', $settingName . ' updated successfully.');
                    return redirect()->to('Setting');
                } else {
                    session()->setFlashdata('error', 'There is error in updating ' . $settingName . '. Try later!');
                    return redirect()->to('Setting');
                }
            } else {
                session()->setFlashdata('error', 'Record not found with specified id. Try later!');
                return redirect()->to('Setting');
            }
            return;
        }
    }

}

/*  End of file Setting.php 
 *  Location: ./application/controllers/Setting.php 
 */
