<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_sales_order extends CI_model {

	public $array = array();

    var $table = 'status';

    var $column_order = array('preorder_item.*','preorder_customer.*','workorder_item.no_so','workorder_item.qore','workorder_item.lin','workorder_item.roll','workorder_item.ingredient','workorder_item.volume','workorder_item.annotation','workorder_item.porporasi','workorder_item.uk_bahan_baku','workorder_item.qty_bahan_baku','workorder_item.sources','workorder_item.merk','workorder_item.type','preorder_price.*','status.item_to','status.order_status','preorder_customer.id AS id_customer','preorder_item.id AS id_item','(preorder_item.price * preorder_item.qty) AS temp_ETD','setting.isi','g.total_ongkir','g.id_sj','company.company','user.name');

    var $column_search = array('preorder_item.detail','preorder_item.item','preorder_item.size','preorder_item.price','preorder_item.qty','preorder_item.unit','preorder_customer.customer','preorder_customer.po_date','preorder_customer.po_customer','workorder_item.no_so','workorder_item.qore','workorder_item.lin','workorder_item.roll','workorder_item.ingredient','workorder_item.volume','workorder_item.annotation','workorder_item.uk_bahan_baku','workorder_item.qty_bahan_baku','workorder_item.merk','setting.isi','company.company','user.name');

    var $order = array('preorder_customer.id' => 'DESC'); // default order 
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
    private function _get_datatables_query()
    {
        
        $this->db->select($this->column_order)->from($this->table)
        ->join('preorder_item', 'status.id_fk = preorder_item.id_fk AND status.item_to = preorder_item.item_to', 'left')
        ->join('preorder_customer', 'preorder_customer.id_fk = preorder_item.id_fk', 'left')
        ->join('workorder_item', 'preorder_item.id_fk = workorder_item.id_fk AND workorder_item.item_to = preorder_item.item_to', 'left')
        ->join('preorder_price', 'preorder_item.id_fk = preorder_price.id_fk', 'left')
        ->join('setting', 'preorder_item.detail = setting.id', 'left')
        ->join('(SELECT id_fk, id_sj, SUM(cost) AS total_ongkir FROM delivery_orders_customer GROUP BY id_fk) AS g', 'preorder_customer.id_fk = g.id_fk', 'left')
        ->join('company', 'preorder_customer.id_company = company.id', 'left')
        ->join('user', 'preorder_customer.input_by = user.id', 'left')
        ->where('status.hidden', '0');
        empty($this->input->post('curMonth'))? $this->db->like('preorder_customer.po_date', date('Y-m')) : $this->db->like('preorder_customer.po_date', str_replace('/', '-', $this->input->post('curMonth')));

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
        ->join('preorder_item', 'status.id_fk = preorder_item.id_fk AND status.item_to = preorder_item.item_to', 'left')
        ->join('preorder_customer', 'preorder_customer.id_fk = preorder_item.id_fk', 'left')
        ->where('status.hidden', '0');
        empty($this->input->post('curMonth'))? $this->db->like('preorder_customer.po_date', date('Y-m')) : $this->db->like('preorder_customer.po_date', str_replace('/', '-', $this->input->post('curMonth')));
        return $this->db->count_all_results();
    }
}

?>