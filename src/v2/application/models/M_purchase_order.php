<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_purchase_order extends CI_model {

	public $array = array();

    var $table = 'po_item';

    var $column_order = array('po_customer.id AS id_po','po_customer.po_date','po_customer.nopo', 'po_customer.note','po_customer.ppn','po_customer.input_by','vendor.vendor','po_item.id AS id_po_item','po_item.detail','po_item.size','po_item.price_1','po_item.price_2','po_item.qty','po_item.unit','po_item.merk','po_item.type','po_item.core','po_item.gulungan','po_item.bahan','user.id','user.name','company.company','setting.isi');

    var $column_search = array('po_customer.po_date','po_customer.nopo', 'po_customer.note','vendor.vendor','po_item.detail','po_item.size','po_item.price_1','po_item.price_2','po_item.qty','po_item.unit','po_item.merk','po_item.type','po_item.core','po_item.gulungan','po_item.bahan','user.name','company.company','setting.isi');

    var $order = array('po_item.id' => 'DESC'); // default order 
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
    private function _get_datatables_query()
    {
        
        $this->db->select($this->column_order)->from($this->table)
        ->join('po_customer', 'po_item.id_fk = po_customer.id', 'left')
        ->join('vendor', 'po_customer.id_vendor = vendor.id', 'left')
        ->join('user', 'po_customer.input_by = user.id', 'left')
        ->join('company', 'po_customer.id_company = company.id', 'left')
        ->join('setting', 'po_customer.type = setting.id', 'left')
        ->where('po_item.hidden', '0');
        empty($this->input->post('curMonth'))? $this->db->like('po_customer.po_date', date('Y-m')) : $this->db->like('po_customer.po_date', str_replace('/', '-', $this->input->post('curMonth')));

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
        $this->db->from($this->table)
        ->join('po_customer', ' po_item.id_fk = po_customer.id', 'left')
        ->where('po_item.hidden', 0);
        empty($this->input->post('curMonth'))? $this->db->like('po_customer.po_date', date('Y-m')) : $this->db->like('po_customer.po_date', str_replace('/', '-', $this->input->post('curMonth')));
        return $this->db->count_all_results();
    }
}

?>