<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class json extends CI_controller {

	private $data = array();

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('M_all');

	}

	public function po_company()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$keyword = trim(preg_replace('!\s+!',' ', strip_tags($this->input->post('q'))));
			if( strlen($keyword) > 2 )
			{
				$select = $this->M_all->bootstrap_select( 'company', array('id','company','address'), 'hidden = "0" AND company LIKE "%'. $keyword .'%"');
				foreach($select->result() as $row)
				{
					$this->data[] = array(
						'id'		=> $row->id,
						'company'	=> $row->company,
						'address'	=> $row->address,
					);
				}

				$sortir = array_filter($this->data, function( $val ) use ($keyword) {
					if( stripos($val['company'], $keyword )  !== false ) {
						return true;
					} else {
						return false;
					}
				});

				$output = array_slice(array_values($sortir), 0, 20 );

			} else {

				$output = array( 'id' => '', 'company' => '', 'address' => '' );
			}

			header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function po_vendor()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$keyword = trim(preg_replace('!\s+!',' ', strip_tags($this->input->post('q'))));
			if( strlen($keyword) > 2 )
			{
				$prevVal = '';
				$leftjoin = array(
					'po_customer ON vendor.id = po_customer.id_vendor',
					'po_item ON po_customer.id = po_item.id_fk',
					'setting ON po_customer.type = setting.id'
				);

				$select = $this->M_all->left_join("vendor", "vendor.id AS id_vendor, vendor.vendor, po_customer.id AS id_po, po_customer.nopo, po_customer.type, GROUP_CONCAT(po_item.detail SEPARATOR ' ] - [ ') AS detail, setting.isi", $leftjoin, " WHERE vendor.vendor LIKE '%$keyword%' GROUP BY po_customer.id ORDER BY vendor.id DESC");

				if( $select->num_rows() > 0 )
				{
					foreach($select->result() as $row)
					{
						$vendor = str_replace(' ', '_', strtolower($row->vendor));
						$nopo = str_replace(' ', '_', $row->nopo);
						$detail = empty($row->detail)? '' : ' [ '.$row->detail.' ]';

						if(empty($nopo))
						{
							$this->data[] = array(
			  					'id_vendor'		=> $row->id_vendor,
								'id_po'			=> null,
					  			'name'			=> $row->vendor,
				  				'subtext'		=> 'Buat order baru',
				  			);

						} else {

							if($prevVal != $vendor)
							{
								$this->data[] = array(
				  					'id_vendor'		=> $row->id_vendor,
									'id_po'			=> null,
						  			'name'			=> $row->vendor,
					  				'subtext'		=> 'Buat order baru',
					  			);
							}

							$this->data[] = array(
								'id_vendor'		=> $row->id_vendor,
								'id_po'			=> $row->id_po,
								'name' 			=> $row->vendor,
								'subtext' 		=> $row->isi.' '. $detail ,
							);
						}

						$prevVal = $vendor;
					}

				} else {

					$this->data[] = array(
						'id_vendor'		=> null,
						'id_po'			=> null,
			  			'name'			=> $keyword,
		  				'subtext'		=> 'Tidak terdaftar, silakan daftar sebagai vendor baru.',
					);
				}


				$output = $this->data;

			} else {

				$output = array( 'id_vendor' => '', 'id_po' => '', 'name' => '', 'subtext' => '' );
			}

			header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function po_type()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$select = $this->M_all->select('setting', 'id,isi', 'ket = "PO_ITEM"');
			foreach($select->result() as $row)
			{
				$this->data[] = array(
					'id'	=> $row->id,
  					'item'	=> $row->isi,
				);
			}

			$output = array(
				'data'	=> $this->data
			);

			header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function po_attribute()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$select = $this->M_all->select('setting', 'value', 'id = '.$this->input->post('id'));
			foreach($select->result() as $row)
			{
				$obj = json_decode($row->value); 
				$this->data[] = array(
					'field'	=> $obj->{'input'},
				);
			}

			$output = array(
				'data'	=> $this->data
			);

			header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function po_item()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('item')))
			{
				$count = 0;
				$leftjoin = array(
					'po_customer ON po_item.id_fk = po_customer.id',
					'setting ON po_customer.type = setting.id'
				);

				$select = $this->M_all->left_join( 'po_item' , 'po_item.id AS id_item, po_item.id_fk, po_item.item_to, po_item.detail, po_item.size, po_item.price_1, po_item.price_2, po_item.qty, po_item.unit, po_item.merk, po_item.type AS po_item_type, po_item.core, po_item.gulungan, po_item.bahan, po_item.hidden, po_customer.type AS po_customer_type, setting.value', $leftjoin, ' WHERE po_item.id_fk = "'.$this->input->post('item').'" AND po_item.hidden = "0" ORDER BY po_item.id ASC');

				if($select->num_rows() > 0)
				{
					foreach($select->result() as $row)
					{
						$obj = json_decode($row->value);
						
						if( $count < 1)
						{
							$this->data['input'][] = array(
								'type' 		=> $row->po_customer_type,
								'attribute' => $obj->{'input'},
							);
						}

						$this->data['value'][] = array(
							'id_vendor'		=> $this->input->post('vendor'),
							'id_po'			=> $this->input->post('item'),
				  			'item_to'		=> $row->item_to,
				  			'detail'		=> $row->detail,
				  			'size'			=> $row->size,
				  			'price_1'		=> str_replace('.', ',', $row->price_1),
				  			'price_2'		=> str_replace('.', ',', $row->price_2),
				  			'qty'			=> $row->qty,
				  			'unit'			=> $row->unit,
				  			'merk'			=> $row->merk,
				  			'type'			=> $row->po_item_type,
				  			'core'			=> $row->core,
				  			'gulungan'		=> $row->gulungan,
				  			'bahan'			=> $row->bahan,
						);
						
						$count + 1;
					}

				} else {

					$this->data['value'][] = array(
						'id_vendor'		=> $this->input->post('vendor'),
						'id_po'			=> '0'
					);
				}

			} else {

				$this->data['value'][] = array(
					'id_vendor'		=> $this->input->post('vendor'),
					'id_po'			=> '0'
				);
			}

			header('Content-Type: application/json');
			echo json_encode($this->data);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function so_customer()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$keyword = trim(preg_replace('!\s+!',' ', strip_tags($this->input->post('q'))));
			if( strlen($keyword) > 2 )
			{
				$prevVal = '';
				$leftjoin = array(
					'preorder_customer ON customer.id = preorder_customer.id_customer',
					'preorder_item ON preorder_customer.id_fk = preorder_item.id_fk'
				);

				$select = $this->M_all->left_join('customer', "customer.id AS id_customer, customer.nama, preorder_customer.po_customer, preorder_customer.id AS id_po, GROUP_CONCAT(preorder_item.item SEPARATOR ' ] - [ ') AS item", $leftjoin, " WHERE customer.nama LIKE '%$keyword%' GROUP BY preorder_customer.id_fk ORDER BY preorder_customer.id DESC");

				if( $select->num_rows() > 0 )
				{
					foreach($select->result() as $row)
					{
						$customer = str_replace(' ', '_', strtolower($row->nama));
						$nopo = str_replace(' ', '_', $row->po_customer );

						if(empty($nopo))
						{
							$this->data[] = array(
			  					'id_customer'	=> $row->id_customer,
								'id_po'			=> null,
					  			'name'			=> $row->nama,
				  				'subtext'		=> 'Buat order baru',
				  			);

						} else {

							if($prevVal != $customer)
							{
								$this->data[] = array(
				  					'id_customer'	=> $row->id_customer,
									'id_po'			=> null,
						  			'name'			=> $row->nama,
					  				'subtext'		=> 'Buat order baru',
					  			);
							}

							$this->data[] = array(
								'id_customer'	=> $row->id_customer,
								'id_po'			=> $row->id_po,
								'name' 			=> $row->nama,
								'subtext' 		=> '[ '. $row->item .' ]',
							);
						}

						$prevVal = $customer;
					}

				} else {

					$this->data[] = array(
						'id_vendor'		=> null,
						'id_po'			=> null,
			  			'name'			=> $keyword,
		  				'subtext'		=> 'Tidak terdaftar, silakan daftar sebagai customer baru.',
					);
				}


				$output = $this->data;

			} else {

				$output = array( 'id_vendor' => '', 'id_po' => '', 'name' => '', 'subtext' => '' );
			}

			header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function so_item()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('po')))
			{
				$leftjoin = array(
					'preorder_customer ON customer.id = preorder_customer.id_customer',
					'preorder_item ON preorder_item.id_fk = preorder_customer.id_fk',
					'workorder_item ON workorder_item.id_fk = preorder_item.id_fk AND workorder_item.item_to = preorder_item.item_to'
				);

				$select = $this->M_all->left_join( 'customer', 'customer.id AS id_customer, preorder_customer.id AS id_po, preorder_item.item_to, preorder_item.price, workorder_item.item, workorder_item.size, workorder_item.unit, workorder_item.qore, workorder_item.lin, workorder_item.roll, workorder_item.ingredient, workorder_item.qty, workorder_item.volume, workorder_item.annotation, workorder_item.porporasi, workorder_item.uk_bahan_baku, workorder_item.qty_bahan_baku, workorder_item.roll, workorder_item.detail, workorder_item.merk, workorder_item.type', $leftjoin, ' WHERE customer.id = "'.$this->input->post('customer').'" AND preorder_customer.id = "'.$this->input->post('po').'"');

				if($select->num_rows() > 0)
				{
					foreach($select->result() as $row)
					{

						$this->data[] = array(
							'id_customer'	=> $row->id_customer,
							'id_po'			=> $row->id_po,
				  			'item_to'		=> $row->item_to,
				  			'price'			=> $row->price,
				  			'item'			=> $row->item,
				  			'size'			=> $row->size,
				  			'unit'			=> $row->unit,
				  			'qore'			=> $row->qore,
				  			'roll'			=> $row->roll,
				  			'lin'			=> $row->lin,
				  			'ingredient'	=> $row->ingredient,
				  			'qty'			=> $row->qty,
				  			'volume'		=> $row->volume,
				  			'annotation'	=> $row->annotation,
				  			'porporasi'		=> $row->porporasi,
				  			'uk_bahan_baku'	=> $row->uk_bahan_baku,
				  			'qty_bahan_baku'=> $row->qty_bahan_baku,
				  			'detail'		=> $row->detail,
				  			'merk'			=> $row->merk,
				  			'type'			=> $row->type,
						);
					}

				} else {

					$this->data[] = array(
						'id_customer'	=> $this->input->post('customer'),
						'id_po'			=> '0'
					);
				}

			} else {

				$this->data[] = array(
					'id_customer'	=> $this->input->post('customer'),
					'id_po'			=> '0'
				);
			}

			header('Content-Type: application/json');
			echo json_encode($this->data);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function so_detail()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$select = $this->M_all->select('setting', 'id,isi', 'ket = "SO_ITEM"');
			foreach($select->result() as $row)
			{ 
				$this->data[] = array(
					'id'	=> $row->id,
  					'item'	=> $row->isi,
				);
			}

			$output = array(
				'data'	=> $this->data
			);

			header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function so_attribute()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$select = $this->M_all->select('setting', 'value', 'id = "'.$this->input->post('id').'"');
			foreach($select->result() as $row)
			{
				$obj = json_decode($row->value);
				$this->data[] = array(
					'field'	=> $obj->{'input'}
				);
			}

			$output = array(
				'data'	=> $this->data
			);

			header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}





}