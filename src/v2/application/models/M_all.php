<?php

class M_all extends CI_model {

	public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

	public function get_id( $table, $column, $orderby )
	{
		$query = $this->db->select( $column )->order_by( $orderby )->limit(1)->get( $table )->row();
		return $query;
	}

	public function select( $table, $column, $where = null )
	{
		$query = $this->db->select( $column )->where( $where )->get( $table );
		return $query;
	}

	public function insert( $table, $data )
	{
		$query = $this->db->insert( $table, $data );
		return $query;
	}

	public function update( $table, $set, $where )
	{
		$this->db->set($set)->where($where);
		return $this->db->update($table);
	}

	public function left_join( $table, $column, $join_data, $where = null )
	{
		$merge = ' LEFT JOIN '. implode(' LEFT JOIN ', $join_data);
		$query = $this->db->query( 'SELECT '. $column .' FROM '. $table .' '. $merge .' '. $where );
		return $query;
	}

	public function sort_month( $table, $column, $where = null, $orderby_column = null )
	{
		$query = $this->db->query('SELECT DISTINCT '. $column .' FROM '. $table .' '. $where .' '. $orderby_column);
		return $query;
	}

	public function bootstrap_select( $table, $column, $where = null )
	{
		$this->db->select( $column );
		$this->db->from( $table);
		empty($where)? '' : $this->db->where( $where );
		return $this->db->get();
	}

	public function insert_id( $table, $data )
	{
		$this->db->insert( $table, $data );
		return $this->db->insert_id();
	}

	public function bulk_insert_id( $table, $data )
	{
		$this->db->insert_batch( $table, $data );
		return $this->db->insert_id();
	}

	public function bulk_insert( $table, $data )
	{
		$query = $this->db->insert_batch( $table, $data );
		return $query;
	}

	public function delete( $table, $where )
	{
		$query = $this->db->delete( $table, $where );
		return $query;
	}


}