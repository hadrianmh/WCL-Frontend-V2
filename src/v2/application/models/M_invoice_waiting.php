<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_invoice_waiting extends CI_model {

	public $array = array();

    var $table = 'delivery_orders_item';

    var $column_order = array('delivery_orders_item.id_fk','delivery_orders_item.id_sj','delivery_orders_item.no_delivery','delivery_orders_item.send_qty','preorder_item.price','preorder_item.unit','workorder_item.no_so','preorder_customer.customer','preorder_customer.po_customer','delivery_orders_customer.sj_date','delivery_orders_customer.cost','delivery_orders_customer.input_by','preorder_price.ppn');

    var $column_search = array('delivery_orders_item.no_delivery','delivery_orders_item.send_qty','preorder_item.price','preorder_item.unit','workorder_item.no_so','preorder_customer.customer','preorder_customer.po_customer','delivery_orders_customer.sj_date','delivery_orders_customer.cost');

    var $order = array('delivery_orders_item.id' => 'DESC'); // default order 
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
    private function _get_datatables_query()
    {
        $this->db->select($this->column_order)->from($this->table)
        ->join('invoice', 'invoice.id_fk = delivery_orders_item.id_fk AND invoice.id_sj = delivery_orders_item.id_sj', 'left')
        ->join('preorder_item', 'preorder_item.id_fk = delivery_orders_item.id_fk AND preorder_item.item_to = delivery_orders_item.item_to', 'left')
        ->join('workorder_item', 'workorder_item.id_fk = delivery_orders_item.id_fk AND workorder_item.item_to = delivery_orders_item.item_to', 'left')
        ->join('preorder_customer', 'preorder_customer.id_fk = delivery_orders_item.id_fk', 'left')
        ->join('delivery_orders_customer', 'delivery_orders_customer.id_fk = delivery_orders_item.id_fk AND delivery_orders_customer.id_sj = delivery_orders_item.id_sj', 'left')
        ->join('preorder_price', 'preorder_price.id_fk = delivery_orders_item.id_fk', 'left')
        ->join('status', 'status.id_fk = delivery_orders_item.id_fk AND status.item_to = delivery_orders_item.item_to', 'left')
        ->where('status.hidden = 0 AND delivery_orders_item.send_qty > 0 AND status.order_status BETWEEN 1 AND 2 AND invoice.id IS NULL');

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
        $this->db->select($this->column_order)->from($this->table)
        ->join('invoice', 'invoice.id_fk = delivery_orders_item.id_fk AND invoice.id_sj = delivery_orders_item.id_sj', 'left')
        ->join('preorder_item', 'preorder_item.id_fk = delivery_orders_item.id_fk AND preorder_item.item_to = delivery_orders_item.item_to', 'left')
        ->join('workorder_item', 'workorder_item.id_fk = delivery_orders_item.id_fk AND workorder_item.item_to = delivery_orders_item.item_to', 'left')
        ->join('preorder_customer', 'preorder_customer.id_fk = delivery_orders_item.id_fk', 'left')
        ->join('delivery_orders_customer', 'delivery_orders_customer.id_fk = delivery_orders_item.id_fk AND delivery_orders_customer.id_sj = delivery_orders_item.id_sj', 'left')
        ->join('preorder_price', 'preorder_price.id_fk = delivery_orders_item.id_fk', 'left')
        ->join('status', 'status.id_fk = delivery_orders_item.id_fk AND status.item_to = delivery_orders_item.item_to', 'left')
        ->where('status.hidden = 0 AND delivery_orders_item.send_qty > 0 AND status.order_status BETWEEN 1 AND 2 AND invoice.id IS NULL');
        return $this->db->count_all_results();
    }
}

?>