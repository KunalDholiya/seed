<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\ConnectionInterface;

class Common extends Model {

    
    function insert_data($data, $tablename) {
        $builder = $this->db->table($tablename);
        if ($builder->insert($data)) {
            return true;
        } else {
            return false;
        }
    }

    //insert data into database and returns last insert id or 0
    function insert_data_getid($data, $tablename) {
        $builder = $this->db->table($tablename);
        if ($builder->insert($data)) {
            return $this->db->insertID();
        } else {
            return 0;
        }
    }

    public function insert_batch($data, $tablename) {
        $builder = $this->db->table($tablename);
        $res = $builder->insertBatch($data);
        if ($res) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function select_data_by_multiple_condition($tablename, $condition_array = array(), $data = '*', $where_in_col = '', $orderby = '', $limit = '', $offset = '', $join_str = array(), $groupby = '', $condition_or_arr = array(), $where_in_val = array()) {
        //select_data_by_multiple_condition('biometric_student_attendance', $condition_arr, $selected,$where_in,$orderby, '', '', $join_str,'','');
        $builder = $this->db->table($tablename);
        $builder->select($data);

        //if join_str array is not empty then implement the join query
        if (!empty($join_str)) {
            foreach ($join_str as $join) {
                if (!isset($join['join_type'])) {
                    $builder->join($join['table'], $join['join_table_id'] . '=' . $join['from_table_id']);
                } else {
                    $builder->join($join['table'], $join['join_table_id'] . '=' . $join['from_table_id'], $join['join_type']);
                }
            }
        }

        //condition array pass to where condition
        $builder->where($condition_array);
        //$this->db->where('student_assignment_reply.student_id is null');
        if (!empty($where_in_val)) {
            $$builder->whereIn($where_in_col, $where_in_val);
        } else {
            $builder->whereIn($where_in_col);
        }
        if (!empty($condition_or_arr)) {
            $builder->groupStart();
            $builder->orWhere($condition_or_arr);
            $builder->groupEnd();
        }
        //Setting Limit for Paging
        if ($offset == '') {
            $offset = 0;
        }
        if ($limit != '' && $offset == 0) {
            $builder->limit($limit);
        } else if ($limit != '' && $offset != 0) {
            $builder->limit($limit, $offset);
        }

        if ($groupby != '') {
            $builder->groupBy($groupby);
        }
        //order by query

        if ($orderby = '') {
            $builder->orderBy($orderby);
        }


        $query = $builder->get();

        //if limit is empty then returns total count
        if ($limit == '') {
            $builder->countAll();
        }
        //if limit is not empty then return result array
        return $query->getResultArray();
    }

    //update database and returns true and false based on single column
    function update_data($data, $tablename, $columnname, $columnid) {
        $builder = $this->db->table($tablename);
        $builder->where($columnname, $columnid);
        if ($builder->update($data)) {
            return true;
        } else {
            return false;
        }
    }

    function update_data_by_conditions($data, $tablename, $conditions) {
        $builder = $this->db->table($tablename);
        $builder->where($conditions);
        if ($builder->update($data)) {
            return true;
        } else {
            return false;
        }
    }

    // select data using column id
    function select_data_by_id($tablename, $columnname, $columnid, $data = '*', $join_str = array()) {
        $builder = $this->db->table($tablename);
        $builder->select($data);
        //if join_str array is not empty then implement the join query
        if (!empty($join_str)) {
            foreach ($join_str as $join) {
                //check for join type
                if (!isset($join['join_type'])) {
                    $builder->join($join['table'], $join['join_table_id'] . '=' . $join['from_table_id']);
                } else {
                    $builder->join($join['table'], $join['join_table_id'] . '=' . $join['from_table_id'], $join['join_type']);
                }
            }
        }
        $builder->where($columnname, $columnid);
        $query = $builder->get();
        return $query->getResultArray();
    }

    // select data using multiple conditions
    function select_data_by_condition($tablename, $condition_array = array(), $data = '*', $sortby = '', $orderby = '', $limit = '', $offset = '', $join_str = array()) {

        $builder = $this->db->table($tablename);
        $builder->select($data);

        //if join_str array is not empty then implement the join query
        if (!empty($join_str)) {
            foreach ($join_str as $join) {
                if (!isset($join['join_type'])) {
                    $builder->join($join['table'], $join['join_table_id'] . '=' . $join['from_table_id']);
                } else {
                    $builder->join($join['table'], $join['join_table_id'] . '=' . $join['from_table_id'], $join['join_type']);
                }
            }
        }

        //condition array pass to where condition
        $builder->where($condition_array);
        if ($offset == '') {
            $offset = 0;
        }
        //Setting Limit for Paging
        if ($limit != '' && $offset == 0) {
            $builder->limit($limit);
        } else if ($limit != '' && $offset != 0) {
            $builder->limit($limit, $offset);
        }

        //order by query
        if ($sortby != '' && $orderby != '') {
            $builder->orderBy($sortby, $orderby);
        }

        $query = $builder->get();

        //if limit is empty then returns total count
        if ($limit == '') {
            $builder->countAll();
        }
        //if limit is not empty then return result array
        return $query->getResultArray();
    }

    // select data using multiple conditions and search keyword
    function select_data_by_search($tablename, $search_condition, $condition_array = array(), $data = '*', $sortby = '', $orderby = '', $limit = '', $offset = '', $join_str = array()) {
        $builder = $this->db->table($tablename);
        $builder->select($data);

        //if join_str array is not empty then implement the join query
        if (!empty($join_str)) {
            foreach ($join_str as $join) {
                if (!isset($join['join_type'])) {
                    $builder->join($join['table'], $join['join_table_id'] . '=' . $join['from_table_id']);
                } else {
                    $builder->join($join['table'], $join['join_table_id'] . '=' . $join['from_table_id'], $join['join_type']);
                }
            }
        }

        if ($search_condition != '') {
            $builder->where($search_condition);
        }
        if (!empty($condition_array)) {
            $builder->where($condition_array);
        }

        //Setting Limit for Paging
        if ($offset == '') {
            $offset = 0;
        }
        if ($limit != '' && $offset == 0) {
            $builder->limit($limit);
        } else if ($limit != '' && $offset != 0) {
            $builder->limit($limit, $offset);
        }
        //order by query
        if ($sortby != '' && $orderby != '') {
            $builder->orderBy($sortby, $orderby);
        }

        $query = $builder->get();
        //if limit is empty then returns total count
        if ($limit == '') {
            $builder->countAll();
        }
        //if limit is not empty then return result array
        return $query->getResultArray();
    }

    //table records count
    function get_count_of_table($table) {
        $query = $this->db->table($table)->get()->getRowArray();
        return $query;
    }

    // delete data
    function delete_data($tablename, $columnname, $columnid) {
        $builder = $this->db->table($tablename);
        $builder->where($columnname, $columnid);
        if ($builder->delete()) {
            return true;
        } else {
            return false;
        }
    }

    // check unique avaliblity
    function check_unique_avalibility($tablename, $columname1, $columnid1_value, $columname2, $columnid2_value, $condition_array) {
        $builder = $this->db->table($tablename);
        // if edit than $columnid2_value use
        if ($columnid2_value != '') {
            $builder->where($columname2 . " != ", $columnid2_value);
        }

        if (!empty($condition_array)) {
            $builder->where($condition_array);
        }

        $builder->where($columname1, $columnid1_value);
        $query = $builder->get();
        return $query->getResult();
    }

    public function selectDataById($table, $id, $filed) {
        $builder = $this->db->table($table);
        $builder->where($filed, $id);
        // $this->db->where('status', 'Enable');
        if ($sortby != '' && $orderby != "") {
            $builder->orderBy($sortby, $orderby);
        }
        $query = $builder->get();
        if ($builder->countAll() > 0) {

            return $query->getResult();
        } else {
            return array();
        }
    }

    public function selectRecord($table) {
        $builder = $this->db->table($table);
        $query = $builder->get();
        return $query->getRowArray();
    }

    function get_all_record($tablename, $data, $sortby, $orderby) {
        $builder = $this->db->table($tablename);
        $builder->select($data);

        //$this->db->where('status', 'Enable');
        if ($sortby != '' && $orderby != "") {
            $builder->orderBy($sortby, $orderby);
        }
        $query = $$builder->get();
        if ($builder->countAll() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    /*
     * Function Name :selectRecordById
     * Parameters :variables
     * Return :array
     */

    public function selectRecordById($table, $id, $filed) {

        //$this->db->where($filed, $id);
        $query = $this->db->table($table)->where($filed, $id)->get();
        return $query->getRowArray();
    }

    public function selectRecordByName($table, $name, $filed) {

        $query = $this->db->table($table)->where($filed, $name)->get();
        return $query->getRowArray();
    }

    /*
     * Function Name :saveTableImg
     * Parameters :variables
     * Return :variable
     */

    public function saveTableImg($tablename, $filed, $id, $data) {
        $builder = $this->db->table($tablename);
        $builder->where($filed, $id);
        $que = $builder->update($data);
        return $que;
    }

    /*
     * Function Name :checkAddTimeRecord
     * Parameters :variables
     * Return :variable
     */

    public function checkAddTimeRecord($columnVal, $columnName, $table) {
        $builder = $this->db->table($table);
        $builder->where($columnName, $columnVal);
        $query = $builder->get();
        $num = $builder->countAll();

        if ($num != 0) {
            $res = 1;
        } else {
            $res = 0;
        }
        return $res;
    }

    /*
     * Function Name :checkEditTimeRecord
     * Parameters :variables
     * Return :variable
     */

    public function checkEditTimeRecord($columnVal, $columnName, $table, $id, $tableid) {

        $builder = $this->db->table($table);
        $notequal = '<>';
        $tableId = $tableid . " " . $notequal;

        $builder->where($tableId, $id);
        $builder->where($columnName, $columnVal);
        $query = $builder->get();
        $num = $builder->countAll();

        if ($num > 0) {
            $builder->where($columnName, $columnVal);
            $query = $builder->get();
            $rnum = $builder->countAll();
            if ($rnum > 0) {
                $res = 1;
            } else {
                $res = 0;
            }
        } else {
            $res = 0;
        }

        return $res;
    }

    function getSettingDetails() {
        return $this->db->table('settings')->get()->getResultArray();
    }

    function select_data_by_allcondition($tablename, $condition_array = array(), $data = '*', $sortby = '', $orderby = '', $limit = '', $offset = '', $join_str = array(), $groupby = '') {
        $builder = $this->db->table($tablename);
        $builder->select($data);
        //if join_str array is not empty then implement the join query
        if (!empty($join_str)) {
            foreach ($join_str as $join) {
                if (!isset($join['join_type'])) {
                    $builder->join($join['table'], $join['join_table_id'] . '=' . $join['from_table_id']);
                } else {
                    $builder->join($join['table'], $join['join_table_id'] . '=' . $join['from_table_id'], $join['join_type']);
                }
            }
        }

        //condition array pass to where condition
        $this->db->where($condition_array);

        //Setting Limit for Paging
        if ($offset == '') {
            $offset = 0;
        }
        if ($limit != '' && $offset == 0) {
            $builder->limit($limit);
        } else if ($limit != '' && $offset != 0) {
            $builder->limit($limit, $offset);
        }
        if ($groupby != '') {
            $builder->groupBy($groupby);
        }
        if ($sortby != '' && $orderby != '') {
            $builder->orderBy($sortby, $orderby);
        }

        $query = $builder->get();
        //if limit is empty then returns total count
        if ($limit == '') {
            $builder->countAll();
        }
        //if limit is not empty then return result array
        return $query->getResultArray();
    }

    /*
     * This function is to create the data source of the Jquery Datatable
     * 
     * @param $tablename Name of the Table in database
     * @param $datatable_fields Fields in datatable that are available for filtering in datatable andnumber of column and order sequence is must maintan with apearance in datatable and add blank filed for not related to database fileds.
     * @param $condition_array conditions for Query 
     * @param $data The field set to be return to datatables, it can contain any number of fileds
     * @param $request The Get or Post Request Sent from Datatable
     * @param $join_str Join array for Query
     * @param $group_by Group by clause array if present in query
     * @return JSON data for datatable
     */

    function getDataTableSource($tablename, $datatable_fields = array(), $conditions_array = array(), $data = '*', $request = '', $join_str = array(), $group_by = array()) {

        $output = array();
        //Fields tobe display in datatable
        $builder = $this->db->table($tablename);
        $builder->select($data);
        //Making Join with tables if provided
        if (!empty($join_str)) {
            foreach ($join_str as $join) {
                if (!isset($join['join_type'])) {
                    $builder->join($join['table'], $join['join_table_id'] . '=' . $join['from_table_id']);
                } else {
                    $builder->join($join['table'], $join['join_table_id'] . '=' . $join['from_table_id'], $join['join_type']);
                }
            }
        }

        //Conditions for Query  that is defaultly available to Datatable data source.
        if (!empty($conditions_array)) {
            $builder->where($conditions_array);
        }

        //Applying groupby clause to query
        if (!empty($group_by)) {
            $builder->groupBy($group_by);
        }

        //Total record in query tobe return
        $output['recordsTotal'] = $builder->countAllResults(false);

        //Filtering based on the datatable_fileds
        if ($request['search']['value'] != '') {
            $builder->groupStart();
            for ($i = 0; $i < count($datatable_fields); $i++) {
                if ($request['columns'][$i]['searchable'] == true) {
                    $builder->orLike($datatable_fields[$i], $request['search']['value']);
                    
                }
            }
            $builder->groupEnd();
        }
        

        //Total number of records return after filtering not no of record display on page.
        //It must be counted before limiting the resultset.
        $output['recordsFiltered'] = $builder->countAllResults(false);
       
        //Setting Limit for Paging
        $builder->limit($request['length'], $request['start']);
        
        //ordering the query
        if (isset($request['order']) && count($request['order'])) {
            for ($i = 0; $i < count($request['order']); $i++) {
                if ($request['columns'][$request['order'][$i]['column']]['orderable'] == true) {
                    $builder->orderBy($datatable_fields[$request['order'][$i]['column']] . ' ' . $request['order'][$i]['dir']);
                }
            }
        }
        
        $query = $builder->get();
       
        $output['draw'] = $request['draw'];
        $output['data'] = $query->getResultArray();
        
        return json_encode($output);
    }

    function getDataTableSource1($tablename, $datatable_fields = array(), $conditions_array = array(), $data = '*', $request = array(), $join_str = array(), $group_by = '') {
        $output = array();

        //Fields tobe display in datatable
        $builder = $this->db->table($tablename);
        $builder->distinct();
        $builder->select($data, FALSE);
        //Making Join with tables if provided
        if (!empty($join_str)) {
            foreach ($join_str as $join) {
                if (!isset($join['join_type'])) {
                    $builder->join($join['table'], $join['join_table_id'] . '=' . $join['from_table_id']);
                } else {
                    $builder->join($join['table'], $join['join_table_id'] . '=' . $join['from_table_id'], $join['join_type']);
                }
            }
        }
        //COnditions for Query
        if (!empty($conditions_array)) {
            $builder->where($conditions_array);
        }
        if ($group_by != '') {
            $builder->groupBy($group_by);
        }


        //Total record in query tobe return
        $output['recordsTotal'] = $builder->countAllResults(FALSE);
        //echo $this->db->last_query(); die();
        //Filtering based on the datatable_fileds
        if ($request['search']['value'] != '') {
            $builder->groupStart();
            for ($i = 0; $i < count($datatable_fields); $i++) {
                if ($request['columns'][$i]['searchable'] == 'true') {

                    $builder->orLike($datatable_fields[$i], $request['search']['value']);
                }
            }
            $builder->groupEnd();
        }

        //Total number of records return after filtering not no of record display on page.
        //It must be counted before limiting the resultset.
        $output['recordsFiltered'] = $builder->countAllResults(FALSE);

        //Setting Limit for Paging
        $builder->limit($request['length'], $request['start']);

        //ordering the query
        if (isset($request['order']) && count($request['order'])) {
            for ($i = 0; $i < count($request['order']); $i++) {
                if ($request['columns'][$request['order'][$i]['column']]['orderable'] == 'true') {
                    $builder->orderBy($datatable_fields[$request['order'][$i]['column']] . ' ' . $request['order'][$i]['dir']);
                }
            }
        }

        $query = $builder->get();
        $output['draw'] = $request['draw'];
        $output['data'] = $query->getResultArray();

        return json_encode($output);
    }

    // select data using multiple conditions. Only use when you want to create pagination like datatable.
    function select_data_by_condition_with_count($tablename, $condition_array = array(), $data = '*', $sortby = '', $orderby = '', $limit = '', $offset = '', $join_str = array()) {
        $builder = $this->db->table($tablename);
        $builder->select($data);

        //if join_str array is not empty then implement the join query
        if (!empty($join_str)) {
            foreach ($join_str as $join) {
                if ($join['join_type'] == '') {
                    $builder->join($join['table'], $join['join_table_id'] . '=' . $join['from_table_id']);
                } else {
                    $builder->join($join['table'], $join['join_table_id'] . '=' . $join['from_table_id'], $join['join_type']);
                }
            }
        }

        //condition array pass to where condition
        $builder->where($condition_array);

        //Applying groupby clause to query
        if (!empty($group_by)) {
            $builder->groupBy($group_by);
        }

        $output['recordsTotal'] = $builder->countAllResults(FALSE);
        //Setting Limit for Paging
        if ($offset == '') {
            $offset = 0;
        }
        if ($limit != '' && $offset == 0) {
            $builder->limit($limit);
        } else if ($limit != '' && $offset != 0) {
            $builder->limit($limit, $offset);
        }
        //order by query
        if ($sortby != '' && $orderby != '') {
            $builder->orderBy($sortby, $orderby);
        }

        $query = $builder->get();
        //if limit is empty then returns total count
        if ($limit == '') {
            $builder->countAll();
        }
        $output['data'] = $query->getResultArray();
        return $output;
    }

}
