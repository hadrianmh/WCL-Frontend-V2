<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_aging extends CI_model {

	public $array = array();

    var $table = 'invoice';

    var $column_order = array('preorder_customer.customer','company.company','invoice.no_invoice','GROUP_CONCAT(DISTINCT(delivery_orders_item.no_delivery) SEPARATOR " - ") AS no_delivery','GROUP_CONCAT(DISTINCT(CONCAT(SUBSTRING_INDEX(workorder_item.no_so,"/",2),SUBSTRING_INDEX(workorder_item.no_so,"/",-1))) SEPARATOR " - ") AS no_so','preorder_customer.po_customer','invoice.invoice_date','invoice.duration AS duedate','SUM(delivery_orders_item.send_qty * preorder_item.price) AS subtotal','preorder_price.ppn','invoice.complete_date','invoice.status','SUM(DISTINCT(delivery_orders_customer.cost)) AS cost','invoice.note');

    var $column_search = array('preorder_customer.customer','company.company','invoice.no_invoice','preorder_customer.po_customer','invoice.invoice_date','invoice.duration','preorder_price.ppn','invoice.complete_date','invoice.note');

    var $order = array('invoice.id' => 'DESC'); // default order 
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
    private function _get_datatables_query()
    {
        $this->db->select($this->column_order)->from($this->table)
        ->join('preorder_customer', 'preorder_customer.id_fk = invoice.id_fk', 'left')
        ->join('delivery_orders_item', 'delivery_orders_item.id_fk = invoice.id_fk AND delivery_orders_item.id_sj = invoice.id_sj', 'left')
        ->join('workorder_item', 'workorder_item.id_fk = delivery_orders_item.id_fk AND workorder_item.item_to = delivery_orders_item.item_to', 'left')
        ->join('preorder_item', 'preorder_item.id_fk = delivery_orders_item.id_fk AND preorder_item.item_to = delivery_orders_item.item_to', 'left')
        ->join('preorder_price', 'preorder_price.id_fk = invoice.id_fk', 'left')
        ->join('company', 'company.id = preorder_customer.id_company', 'left')
        ->join('delivery_orders_customer', 'delivery_orders_customer.id_fk = invoice.id_fk AND delivery_orders_customer.id_sj = invoice.id_sj', 'left')
        ->join('status', 'status.id_fk = delivery_orders_item.id_fk AND status.item_to = delivery_orders_item.item_to', 'left')
        ->where('status.hidden = 0');
        empty($this->input->post('curMonth'))? $this->db->like('invoice.invoice_date', date('Y-m')) : $this->db->like('invoice.invoice_date', str_replace('/', '-', $this->input->post('curMonth')));
        $this->db->group_by('invoice.no_invoice');

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
        ->join('preorder_customer', 'preorder_customer.id_fk = invoice.id_fk', 'left')
        ->join('delivery_orders_item', 'delivery_orders_item.id_fk = invoice.id_fk AND delivery_orders_item.id_sj = invoice.id_sj', 'left')
        ->join('workorder_item', 'workorder_item.id_fk = delivery_orders_item.id_fk AND workorder_item.item_to = delivery_orders_item.item_to', 'left')
        ->join('preorder_item', 'preorder_item.id_fk = delivery_orders_item.id_fk AND preorder_item.item_to = delivery_orders_item.item_to', 'left')
        ->join('preorder_price', 'preorder_price.id_fk = invoice.id_fk', 'left')
        ->join('company', 'company.id = preorder_customer.id_company', 'left')
        ->join('delivery_orders_customer', 'delivery_orders_customer.id_fk = invoice.id_fk AND delivery_orders_customer.id_sj = invoice.id_sj', 'left')
        ->join('status', 'status.id_fk = delivery_orders_item.id_fk AND status.item_to = delivery_orders_item.item_to', 'left')
        ->where('status.hidden = 0');
        empty($this->input->post('curMonth'))? $this->db->like('invoice.invoice_date', date('Y-m')) : $this->db->like('invoice.invoice_date', str_replace('/', '-', $this->input->post('curMonth')));
        $this->db->group_by('invoice.no_invoice');
        return $this->db->get();
    }
}

?>