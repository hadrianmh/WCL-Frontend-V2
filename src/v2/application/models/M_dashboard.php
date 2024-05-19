<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_dashboard extends CI_model {

	public $array = array();

    var $table = 'preorder_customer';

    var $column_order = array('preorder_item.item',' preorder_item.price',' preorder_item.qty AS req_qty',' preorder_item.unit',' preorder_customer.id_fk',' preorder_customer.order_grade',' preorder_customer.customer',' preorder_customer.po_customer',' preorder_customer.po_date','preorder_price.ppn',' workorder_item.no_so',' workorder_item.item',' workorder_item.size',' workorder_item.unit',' workorder_item.qore',' workorder_item.lin',' workorder_item.roll',' workorder_item.ingredient',' workorder_item.volume',' workorder_item.porporasi',' workorder_item.annotation',' workorder_item.uk_bahan_baku',' workorder_item.qty_bahan_baku',' workorder_item.sources',' workorder_item.merk',' workorder_item.type',' workorder_customer.spk_date',' delivery_orders_item.no_delivery',' delivery_orders_item.send_qty',' delivery_orders_customer.id_fk',' delivery_orders_customer.id_sj',' delivery_orders_customer.sj_date',' delivery_orders_customer.courier',' delivery_orders_customer.no_tracking',' delivery_orders_customer.cost',' status.order_status',' company.company',' setting.isi');

    var $column_search = array('preorder_customer.po_date');

    var $order = array('preorder_customer.id, delivery_orders_item.no_delivery' => 'ASC'); // default order 
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
    private function _get_datatables_query()
    {
    	$p_length = isset($_POST['length']) ? $_POST['length'] : '';
        $p_start = isset($_POST['start']) ? $_POST['start'] : '';
        
        if($this->input->post('curMonth'))
        {
            $this->db->like('preorder_customer.po_date', str_replace('/', '-', $this->input->post('curMonth')));
            $this->array[] = 'curMonth';
        }

        if( $p_length != -1 && empty($this->array)) {

            $query = '(SELECT * FROM '.$this->table.' ORDER BY id ASC LIMIT '.$p_start.','.$p_length.') '.$this->table;

        } else {
            $query = $this->table;
            if($p_length != -1) $this->db->limit($p_length, $p_start);
        }
        
        $this->db->select($this->column_order)->from($query)
        ->join('preorder_item', 'preorder_item.id_fk = preorder_customer.id_fk', 'left')
        ->join('preorder_price', 'preorder_price.id_fk = preorder_customer.id_fk', 'left')
        ->join('workorder_item', 'workorder_item.id_fk = preorder_item.id_fk AND workorder_item.item_to = preorder_item.item_to', 'left')
        ->join('workorder_customer', 'workorder_customer.id_fk = preorder_customer.id_fk', 'left')
        ->join('delivery_orders_item', 'delivery_orders_item.id_fk = preorder_item.id_fk AND delivery_orders_item.item_to = preorder_item.item_to', 'left')
        ->join('delivery_orders_customer', 'delivery_orders_customer.id_fk = preorder_customer.id_fk AND delivery_orders_customer.id_sj = delivery_orders_item.id_sj', 'left')
        ->join('status', 'status.id_fk = preorder_item.id_fk AND status.item_to = preorder_item.item_to', 'left')
        ->join('company', 'company.id = preorder_customer.id_company', 'left')
        ->join('setting', 'setting.id = workorder_item.detail', 'left');
        empty($this->input->post('curMonth'))? $this->db->like('preorder_customer.po_date', date('Y-m')) : '';

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
        $query = $this->db->get();
        //print_r($this->db->last_query());
        return $query->result();
    }
 
    public function count_filtered()
    {
        if(empty($this->array))
        {
            $count = $this->count_all();

        } else {
            $this->_get_datatables_query();
            $query = $this->db->get();
            $count = $query->num_rows();
        }

        return $count;
    }
 
    public function count_all()
    {
        $this->db->from($this->table)
        ->join('preorder_item', 'preorder_item.id_fk = preorder_customer.id_fk', 'left');
        $this->db->order_by( $this->table, 'ASC');
        empty($this->input->post('curMonth'))? $this->db->like('preorder_customer.po_date', date('Y-m')) : '';
        return $this->db->count_all_results();
    }

    public function statistics_po( $type )
    {
    	if( $type == 'now')
    	{
    		$post 	= empty($this->input->post('curMonth'))? explode('-', date('Y-m-d')) : explode('/', $this->input->post('curMonth'));
            $curMonth = (count($post) > 1 )? $post[0].'-'.$post[1] : $post[0];
    		$query 		= $this->db->query('SELECT count(id) AS jml_po FROM preorder_customer WHERE po_date LIKE "'. $curMonth .'%"');
    		return $query;
    	}
    }

    public function statistics_do( $type )
    {
    	if( $type == 'now')
    	{
    		$post     = empty($this->input->post('curMonth'))? explode('-', date('Y-m-d')) : explode('/', $this->input->post('curMonth'));
            $curMonth = (count($post) > 1 )? $post[0].'-'.$post[1] : $post[0];
    		$query 		= $this->db->query('SELECT count(a.id) as jml_do FROM delivery_orders_customer AS a LEFT JOIN status AS b ON a.id_fk = b.id_fk WHERE a.sj_date LIKE "'. $curMonth .'%" AND b.order_status <= 2 GROUP BY a.id_fk');
    		return $query;
    	}
    }

    public function statistics_inv( $type )
    {
    	if( $type == 'now')
    	{
    		$post     = empty($this->input->post('curMonth'))? explode('-', date('Y-m-d')) : explode('/', $this->input->post('curMonth'));
            $curMonth = (count($post) > 1 )? $post[0].'-'.$post[1] : $post[0];
    		$query 		= $this->db->query('SELECT count(DISTINCT no_invoice) as jml_in FROM invoice WHERE invoice_date LIKE "'. $curMonth .'%" AND status = 1');
    		return $query;
    	}
    }
}

?>