<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Report extends BaseController {

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        //$this->is_allowed();

        $response->setHeader('Last-Modified', gmdate('D, d M Y H:i:s') . 'GMT');
        $response->setHeader('Cache-Controln', 'o-store, no-cache, must-revalidate');
        $response->setHeader('Cache-Control', ' post-check=0, pre-check=0', false);
        $response->setHeader('Pragma', ' no-cache');
        $this->data['title'] = 'Report : ' . $this->data['app_name'];
        $this->data['header'] = view('header', $this->data);
        $this->data['sidebar'] = view('sidebar', $this->data);
        $this->data['footer'] = view('footer', $this->data);
        $this->data['member'] = $this->common->select_data_by_condition('member', array('status' => "Enable"), '*', 'name', 'ASC', '', '', array());
        $this->data['country'] = $this->common->select_data_by_condition('country', array('id' => 231), '*', '', '', '', '', array());
        $this->data['state'] = $this->common->select_data_by_condition('state', array('country_id' => 231), '*', 'name', 'ASC', '', '', array());
        if ($this->data['role_data']['payout'] == 0) {
            return redirect()->to('Dashboard');
        }
    }

    public function index() {
         return view('report/index', $this->data);
    }

    public function gettabledata() {
        $columns = array('deposit.id', 'deposit.member_no', 'deposit.amount_planted_week', 'payout.amount_planted_date',  'admin.firstname');
        $request = $this->request->getGet();
        $condition = array();
        if ($request['from_date'] != '' && $request['to_date'] == '') {
            $request['search']['value'] = '';
            $condition['DATE(' . $this->db->getPrefix() . 'deposit.created_datetime)'] = $request['from_date'];
        }
        if ($request['from_date'] != '' && $request['to_date'] != '') {
            $request['search']['value'] = '';
            $condition['DATE(' . $this->db->getPrefix() . 'deposit.created_datetime) >='] = $request['from_date'];
            $condition['DATE(' . $this->db->getPrefix() . 'deposit.created_datetime) <='] = $request['to_date'];
        }
        $join_str = array();
        $join_str[0] = array(
            'table' => 'admin',
            'join_table_id' => 'admin.admin_id',
            'from_table_id' => 'deposit.added_by',
            'join_type' => 'left'
        );
        $join_str[1] = array(
            'table' => 'payout',
            'join_table_id' => 'payout.member_id',
            'from_table_id' => 'deposit.member_id',
            'join_type' => 'left'
        );
        $join_str[2] = array(
            'table' => 'member',
            'join_table_id' => 'member.id',
            'from_table_id' => 'deposit.member_id',
            'join_type' => 'left'
        );
        $getfiled = "deposit.id,deposit.member_no,member.name,deposit.date_of_seed,deposit.harvest_date,deposit.amount_planted_week,payout.amount_planted_date,admin.firstname,admin.lastname";
        echo $this->common->getDataTableSource('deposit', $columns, $condition, $getfiled, $request, $join_str, '');
        //echo '<pre>';        print_r($this->db->last_query());
        die();
    }

    public function seed() {
         return view('report/seed', $this->data);
    }

    public function harvest() {
         return view('report/receipt', $this->data);
    }
    public function receipt() {
         return view('report/receipt', $this->data);
    }
    public function getseedtabledata() {
        $columns = array('deposit.id', 'member.member_no', 'amount_planted_week',  'admin.firstname');
        $request = $this->request->getGet();
        $condition = array();
        if ($request['from_date'] != '' && $request['to_date'] == '') {
            $request['search']['value'] = '';
            $condition['DATE(' . $this->db->getPrefix() . 'deposit.date_of_seed)'] = $request['from_date'];
        }
        if ($request['from_date'] != '' && $request['to_date'] != '') {
            $request['search']['value'] = '';
            $condition['DATE(' . $this->db->getPrefix() . 'deposit.date_of_seed) >='] = $request['from_date'];
            $condition['DATE(' . $this->db->getPrefix() . 'deposit.date_of_seed) <='] = $request['to_date'];
        }
        $join_str = array();
        $join_str[0] = array(
            'table' => 'admin',
            'join_table_id' => 'admin.admin_id',
            'from_table_id' => 'deposit.added_by',
            'join_type' => 'left'
        );
        $join_str[1] = array(
            'table' => 'member',
            'join_table_id' => 'member.id',
            'from_table_id' => 'deposit.member_id',
            'join_type' => 'left'
        );
        $getfiled = "deposit.id,deposit.member_no,member.name,date_of_seed,amount_planted_week,admin.firstname,admin.lastname";
        echo $this->common->getDataTableSource('deposit', $columns, $condition, $getfiled, $request, $join_str, '');
        //echo '<pre>';        print_r($this->db->last_query());
        die();
    }
    
    public function getharvesttabledata() {
        $columns = array('deposit.id', 'deposit.member_no', 'deposit.amount_planted_week', 'payout.amount_planted_date',  'admin.firstname');
        $request = $this->request->getGet();
        $condition = array();
        if ($request['from_date'] != '' && $request['to_date'] == '') {
            $request['search']['value'] = '';
            $condition['DATE(' . $this->db->getPrefix() . 'deposit.harvest_date)'] = $request['from_date'];
        }
        if ($request['from_date'] != '' && $request['to_date'] != '') {
            $request['search']['value'] = '';
            $condition['DATE(' . $this->db->getPrefix() . 'deposit.harvest_date) >='] = $request['from_date'];
            $condition['DATE(' . $this->db->getPrefix() . 'deposit.harvest_date) <='] = $request['to_date'];
        }
        $join_str = array();
        $join_str[0] = array(
            'table' => 'admin',
            'join_table_id' => 'admin.admin_id',
            'from_table_id' => 'deposit.added_by',
            'join_type' => 'left'
        );
        $join_str[1] = array(
            'table' => 'payout',
            'join_table_id' => 'payout.member_id',
            'from_table_id' => 'deposit.member_id',
            'join_type' => 'left'
        );
        $join_str[2] = array(
            'table' => 'member',
            'join_table_id' => 'member.id',
            'from_table_id' => 'deposit.member_id',
            'join_type' => 'left'
        );
        $getfiled = "deposit.id,member.name,deposit.member_no,deposit.date_of_seed,deposit.harvest_date,deposit.amount_planted_week,payout.amount_planted_date,admin.firstname,admin.lastname";
        echo $this->common->getDataTableSource('payout', $columns, $condition, $getfiled, $request, $join_str, '');
        //echo '<pre>';        print_r($this->db->last_query());
        die();
    }
    
    public function getreceipttabledata(){
        $columns = array('payout.id', 'payout.member_no', 'payout.amount_planted_date', 'payout.church_received','payout.member_received','payout.amount_return_to_pool', 'admin.firstname');
        $request = $this->request->getGet();
        $condition = array();
        if ($request['from_date'] != '' && $request['to_date'] == '') {
            $request['search']['value'] = '';
            $condition['DATE(' . $this->db->getPrefix() . 'payout.date_of_harvest)'] = $request['from_date'];
        }
        if ($request['from_date'] != '' && $request['to_date'] != '') {
            $request['search']['value'] = '';
            $condition['DATE(' . $this->db->getPrefix() . 'payout.date_of_harvest) >='] = $request['from_date'];
            $condition['DATE(' . $this->db->getPrefix() . 'payout.date_of_harvest) <='] = $request['to_date'];
        }
        $join_str = array();
        $join_str[0] = array(
            'table' => 'admin',
            'join_table_id' => 'admin.admin_id',
            'from_table_id' => 'payout.added_by',
            'join_type' => 'left'
        );
        $join_str[1] = array(
            'table' => 'member',
            'join_table_id' => 'member.id',
            'from_table_id' => 'payout.member_id',
            'join_type' => 'left'
        );
        $getfiled = "payout.id,payout.member_no,member.name,payout.date_of_harvest as harvest_date,payout.amount_planted_date,payout.church_received,payout.member_received,payout.amount_return_to_pool,admin.firstname,admin.lastname";
        echo $this->common->getDataTableSource('payout', $columns, $condition, $getfiled, $request, $join_str, '');
        //echo '<pre>';        print_r($this->db->last_query());
        die();
    }

}
