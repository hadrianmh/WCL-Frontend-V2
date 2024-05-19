<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_do_waiting extends CI_model {

	public $array = array();

    var $table = 'status';
    var $column_order = array('workorder_customer.input_by','workorder_customer.id','workorder_customer.id_fk','workorder_customer.spk_date','workorder_customer.customer','workorder_customer.po_customer','workorder_customer.duration','GROUP_CONCAT(CONCAT(SUBSTRING_INDEX(workorder_item.no_so,"/",2)','SUBSTRING_INDEX(workorder_item.no_so,"/",-1)) SEPARATOR ", ") AS no_so');

    var $column_search = array('workorder_customer.spk_date','workorder_customer.customer','workorder_customer.po_customer','workorder_customer.duration','GROUP_CONCAT(CONCAT(SUBSTRING_INDEX(workorder_item.no_so,"/",2)','SUBSTRING_INDEX(workorder_item.no_so,"/", -1)) SEPARATOR ", ") AS no_so');

    var $order = array('workorder_customer.id' => 'ASC'); // default order 
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
    private function _get_datatables_query()
    {
        $this->db->select($this->column_order)->from($this->table)
        ->join('workorder_customer', 'status.id_fk = workorder_customer.id_fk', 'left')
        ->join('workorder_item', 'workorder_item.id_fk = workorder_customer.id_fk AND workorder_item.item_to = status.item_to', 'left')
        ->where('status.hidden = 0 AND status.order_status BETWEEN 2 AND 3 GROUP BY workorder_customer.id_fk');

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
        ->join('workorder_item', 'workorder_item.id_fk = workorder_customer.id_fk AND workorder_item.item_to = status.item_to', 'left')
        ->where('status.hidden = 0 AND status.order_status BETWEEN 2 AND 3 GROUP BY workorder_customer.id_fk');
        return $this->db->count_all_results();
    }
}

?>