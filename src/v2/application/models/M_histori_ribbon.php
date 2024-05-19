<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_histori_ribbon extends CI_model {

	public $array = array();

    var $table = 'ribbon';

    var $column_order = array('ribbon.rak','ribbon.product','ribbon.size','ribbon.fi_fo','user.name','histori_ribbon.id','histori_ribbon.id_fk','histori_ribbon.customer','histori_ribbon.date','histori_ribbon.no_sj','histori_ribbon.no_po','histori_ribbon.roll','histori_ribbon.gulungan','histori_ribbon.s_masuk','histori_ribbon.s_keluar','histori_ribbon.status','histori_ribbon.input_by');

    var $column_search = array('ribbon.rak','ribbon.product','ribbon.size','ribbon.fi_fo','user.name','histori_ribbon.id','histori_ribbon.id_fk','histori_ribbon.customer','histori_ribbon.date','histori_ribbon.no_sj','histori_ribbon.no_po','histori_ribbon.roll','histori_ribbon.gulungan','histori_ribbon.s_masuk','histori_ribbon.s_keluar');

    var $order = array('histori_ribbon.id' => 'DESC'); // default order 
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
    private function _get_datatables_query()
    {
        
        $this->db->select($this->column_order)->from($this->table)
        ->join('histori_ribbon', 'ribbon.id_fk = histori_ribbon.id_fk', 'left')
        ->join('user', 'histori_ribbon.input_by = user.id', 'left')
        ->where( array( 'ribbon.hidden' => '0', 'histori_ribbon.hidden' => '0'));

        $i = 0;
     
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1) $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        //print_r($this->db->last_query());
        return $query->result();
    }
 
    public function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        return $this->count_filtered();
    }
}

?>