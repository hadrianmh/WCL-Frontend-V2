<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_customer extends CI_model {

	public $array = array();

    var $table = 'customer';

    var $column_order = array('id','nama',' alamat',' kota',' negara',' provinsi',' kodepos',' telp',' s_nama',' s_alamat',' s_kota',' s_negara',' s_provinsi',' s_kodepos',' s_telp','input_by');

    var $column_search = array('nama',' alamat',' kota',' negara',' provinsi',' kodepos',' telp',' s_nama',' s_alamat',' s_kota',' s_negara',' s_provinsi',' s_kodepos',' s_telp');

    var $order = array('nama' => 'ASC'); // default order 
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
    private function _get_datatables_query()
    {
        
        $this->db->select($this->column_order)->from($this->table)->where('hidden', 0);

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
        $this->db->from($this->table)->where('hidden', 0);
        return $this->db->count_all_results();
    }
}

?>