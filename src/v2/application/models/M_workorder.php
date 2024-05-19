<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_workorder extends CI_model {

	public $array = array();

    var $table = 'status';
    var $column_order = array('workorder_customer.input_by','workorder_customer.id AS id_customer',' workorder_customer.id_fk',' workorder_customer.po_date',' workorder_customer.spk_date',' workorder_customer.duration',' workorder_customer.po_customer',' workorder_customer.customer','workorder_item.id_fk','workorder_item.item_to','workorder_item.detail','workorder_item.no_so','workorder_item.item','workorder_item.size','workorder_item.unit','workorder_item.qore','workorder_item.lin','workorder_item.roll','workorder_item.ingredient','workorder_item.qty','workorder_item.volume','workorder_item.total','workorder_item.annotation','workorder_item.porporasi','workorder_item.uk_bahan_baku','workorder_item.qty_bahan_baku','workorder_item.sources','workorder_item.merk','workorder_item.type','status.order_status','user.name');

    var $column_search = array(' workorder_customer.po_date',' workorder_customer.spk_date',' workorder_customer.duration',' workorder_customer.po_customer',' workorder_customer.customer','workorder_item.detail','workorder_item.no_so','workorder_item.item','workorder_item.size','workorder_item.unit','workorder_item.qore','workorder_item.lin','workorder_item.roll','workorder_item.ingredient','workorder_item.qty','workorder_item.volume','workorder_item.total','workorder_item.annotation','workorder_item.uk_bahan_baku','workorder_item.qty_bahan_baku','workorder_item.sources','workorder_item.merk','workorder_item.type');

    var $order = array('workorder_item.id' => 'DESC'); // default order 
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
    private function _get_datatables_query()
    {
        $this->db->select($this->column_order)->from($this->table)
        ->join('workorder_customer', 'status.id_fk = workorder_customer.id_fk', 'left')
        ->join('workorder_item', 'workorder_customer.id_fk = workorder_item.id_fk AND status.item_to = workorder_item.item_to', 'left')
        ->join('user', 'workorder_customer.input_by = user.id', 'left')
        ->where('status.hidden', '0');
        empty($this->input->post('curMonth'))? $this->db->like('workorder_customer.po_date', date('Y-m')) : $this->db->like('workorder_customer.po_date', str_replace('/', '-', $this->input->post('curMonth')));

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
        ->join('workorder_customer', 'status.id_fk = workorder_customer.id_fk', 'left')
        ->join('workorder_item', 'workorder_customer.id_fk = workorder_item.id_fk AND status.item_to = workorder_item.item_to', 'left')
        ->join('user', 'workorder_customer.input_by = user.id', 'left')
        ->where('status.hidden', '0');
        empty($this->input->post('curMonth'))? $this->db->like('workorder_customer.po_date', date('Y-m')) : $this->db->like('workorder_customer.po_date', str_replace('/', '-', $this->input->post('curMonth')));
        return $this->db->count_all_results();
    }
}

?>