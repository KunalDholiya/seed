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

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
class BaseController extends Controller {

    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['url', 'file', 'form', 'security', 'filesystem', 'contstants'];
    protected $libraries = ['database', 'email', 'session', 'form_validation'];
    protected $session;
    protected $view;
    protected $validation;
    protected $router;
    protected $db;
    protected $uri;

    /**
     * Constructor.
     */
    public $data;
    public $common;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        $this->db = \Config\Database::connect();
        $this->validation = \Config\Services::validation();
        $this->view = \Config\Services::renderer();
        $this->session = Services::session();
        $this->router = service('router');
        $this->uri = new \CodeIgniter\HTTP\URI();

        // Preload any models, libraries, etc, here.
        // E.g.: $this->session = \Config\Services::session();
        $this->common = new \App\Models\Common();
        if (!session()->has('seed_admin')) {
            return redirect()->to('Login');
        }
        $this->data['adminID'] = session()->get('seed_admin');

        $adminDetails = $this->common->selectRecordById('admin', $this->data['adminID'], 'admin_id');
        $this->data['username'] = $adminDetails['username'];
        $this->data['name'] = ucwords($adminDetails['firstname'] . ' ' . $adminDetails['lastname']);
        $this->data['email'] = $adminDetails['email'];
        $this->data['adminprofileimage'] = $adminDetails['image'];
        $this->data['admindetail'] = $adminDetails;
        if ($adminDetails['role'] == 1) {
            $admin_array = array(
                'admin_id' => $this->data['adminID'],
                'deposit' => 1,
                'payout' => 1,
                'member' => 'Add,Edit,View,Remove',
                'full_report' => 1,
                'partial_report' => 1,
            );
            $this->data['role_data'] = $admin_array;
        } else {
            $this->data['role_data'] = $this->common->selectRecordById('role', $this->data['adminID'], 'admin_id');
        }

        //echo '<pre>'; print_r($this->data['role_data']); die;
        //get site related setting details
        $app_name = $this->common->selectRecordById('settings', '1', 'setting_id');
        $this->data['app_name'] = $app_name['setting_value'];
        $app_name = $this->common->selectRecordById('settings', '2', 'setting_id');
        $this->data['app_email'] = $app_name['setting_value'];
        $app_logo = $this->common->selectRecordById('settings', '7', 'setting_id');
        $this->data['app_logo'] = $app_logo['setting_value'];
    }

    function pr($content) {
        echo "<pre>";
        print_r($content);
        echo "</pre>";
    }

    function last_url() {
        return filter_input(INPUT_SERVER, 'HTTP_REFERER', FILTER_UNSAFE_RAW);
    }

    function datetime() {
        return date('Y-m-d H:i:s');
    }

    function last_query() {
        echo "<pre>";
        echo $this->db->getLastQuery();
        echo "</pre>";
    }

    // Function to get the client IP address

    public function verify_google_auth($code) {
        require_once(APPPATH . 'Authenticator/rfc6238.php');

        $checkAuth = $this->common->select_data_by_condition('admin', array('admin_id' => session()->get('seed_admin')), '*', '', '', '', '', array());

        if (TokenAuth6238::verify($checkAuth[0]['google_code'], $code)) {
            return true;
        } else {
            return false;
        }
    }

    function sendEmail($app_name, $app_email, $to_email, $subject, $mail_body) {
        $email = \Config\Services::email();
        $email->setFrom($app_email, $app_name);
        $email->setTo($to_email);
        //$email->setCC('another@another-example.com');
        //$email->setBCC('them@their-example.com');

        $email->setSubject($subject);
        $email->setMessage("<table border='0' cellpadding='0' cellspacing='0'><tr><td></td></tr><tr><td>" . $mail_body . "</td></tr></table>");
        $email->send();
//        if($email->send()){
//            return;
//        }else{
//            $data = $email->printDebugger(['headers']);
//            print_r($data);
//        }

        return;
    }

    function sendEmail_news($app_name, $app_email, $to_email, $subject, $mail_body) {

        $email = \Config\Services::email();
        $email->setFrom($app_email, $app_name);
        $email->setTo($to_email);
        //$email->setCC('another@another-example.com');
        //$email->setBCC('them@their-example.com');

        $email->setSubject($subject);
        $email->setMessage("<table border='0' cellpadding='0' cellspacing='0'><tr><td></td></tr><tr><td>" . $mail_body . "</td></tr></table>");

        $email->send();
        return;
    }

    // Function to get the client IP address
    function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    function slugify($text) {

        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }


        return $text;
    }

    function annoucementdatetime($d, $current_format = 'Y-m-d H:i:s') {
        $global_datetimeformat = 'm/d/y';
        $date = DateTime::createFromFormat($current_format, $d);
        return $date->format($global_datetimeformat);
    }

    function diplaydatetimewithformat($d, $current_format = 'Y-m-d H:i:s') {
        $global_datetimeformat = 'm/d/y H:i A';
        $date = DateTime::createFromFormat($current_format, $d);
        return $date->format($global_datetimeformat);
    }

}
