<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class all extends CI_controller {

	private $mysqli_data = array();

	private function priceFilter( $price )
	{
		$filter = str_replace('.', '', $price);
		$filter = str_replace(",", ".", $filter);
		return $filter;
	}

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		$this->load->model('M_all');
		$this->load->helper('cookie');
		$this->load->library('online');
		$this->online->check(
			$this->session->userdata('email'),
			$this->session->userdata('password'),
			$this->session->userdata('last_action')
		);
	}

	public function dashboard()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$this->load->model('M_dashboard');
			$data = array();
			$data_cost = array();
			$list = $this->M_dashboard->get_datatables();
			foreach ($list as $column)
			{
		        $ppn = ($column->ppn > 0)? (floatval($column->send_qty) * floatval($column->price))/10 : 0;
				$ex_no_so = empty($column->no_so)? explode("/", '///'): explode("/", $column->no_so);
				$ex_sources = explode("|", $column->sources);
				if($ex_sources[0] == 1){ $sources = 'Internal'; }
				if($ex_sources[0] == 2){ $sources = 'SUBCONT ('.$ex_sources[1].', '.date("d-M-Y", strtotime($ex_sources[2])).')'; }
				if($ex_sources[0] == 3){ $sources = 'IN STOCK ('.$ex_sources[1].' '.$column->unit.')'; }
		        $sources = '';

		        if($column->send_qty > 0) { $order_status = 'Delivery'; } 
		        else {
		        	if($column->order_status == '0'){ $order_status = 'PO baru dibuat'; }
		        	elseif($column->order_status == '1' || $column->order_status == '2'){ $order_status = 'Delivery'; }
		        	elseif($column->order_status == '3'){ $order_status = 'Packing';}
		        	elseif($column->order_status == '4'){ $order_status = 'Cetak SPK'; }
		        	elseif($column->order_status == '5'){ $order_status = 'Pembuatan Pisau'; }
		        	elseif($column->order_status == '6'){ $order_status = 'Antri Sliting'; }
		        	elseif($column->order_status == '7'){ $order_status = 'Antri Cetak'; }
		        }

				$row = array();
				$row[] = $column->company;
				$row[] = ($column->order_grade == 0)? 'Reguler' : 'Spesial';
				$row[] = empty($ex_no_so[0])? '' : $ex_no_so[0]."/".$ex_no_so[1].$ex_no_so[2];
				$row[] = date("d/m/Y", strtotime($column->po_date));
				$row[] = date('d/m/Y', strtotime($column->po_date. '+16 day'));
				$row[] = $column->customer;
				$row[] = $column->po_customer;
				$row[] = date("d/m/Y", strtotime($column->po_date));
				$row[] = $column->item;
				$row[] = $column->isi;
				$row[] = $column->merk;
				$row[] = $column->type;
				$row[] = $column->size;
				$row[] = $column->qore;
				$row[] = $column->lin;
				$row[] = $column->roll;
				$row[] = $column->ingredient;
				$row[] = ($column->porporasi > 0)? 'YA' : 'TIDAK';
				$row[] = $column->req_qty;
				$row[] = $column->unit;
				$row[] = $column->volume;
				$row[] = $column->uk_bahan_baku;
				$row[] = $column->qty_bahan_baku;
				$row[] = $column->annotation;
				$row[] = $sources;
				$row[] = $column->price;
				$row[] = ((int)$column->send_qty*(int)$column->price);
				$row[] = $ppn;
				$row[] = ((int)$column->send_qty*(int)$column->price + (int)$ppn);
				$row[] = ($column->spk_date == '0000-00-00')? '' : date("d/m/Y", strtotime($column->spk_date));
				$row[] = $order_status;
				$row[] = $column->no_delivery;
				$row[] = empty($column->sj_date)? '' : date("d/m/Y", strtotime($column->sj_date));
				$row[] = $column->courier;
				$row[] = $column->no_tracking;
				$row[] = empty($column->send_qty)? '' : $column->send_qty." ".$column->unit;
				$row[] = !in_array($column->id_fk.'-'.$column->id_sj.'-'.$column->cost, $data_cost)? $column->cost : '';
				$data[] = $row;
				$data_cost[] = $column->id_fk.'-'.$column->id_sj.'-'.$column->cost;
        	}
 
		    $output = array(
		        "draw" => $_POST['draw'],
		        "recordsTotal" => $this->M_dashboard->count_all(),
		        "recordsFiltered" => $this->M_dashboard->count_filtered(),
		        "data" => $data,
		    );

		    header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function sort_month()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
      		$array_montly = array();
      		$array_year = array();
      		$table = $this->input->post('table');
      		$column = $this->input->post('column');
      		$where = $this->input->post('where');
      		$order = $this->input->post('order');
			$list = $this->M_all->sort_month( $table, $column , $where, 'ORDER BY '. $order .' DESC');
			foreach($list->result() as $row)
			{
				$date = explode('-', $row->$column);
				$montly = $date[0].'/'.$date[1];
		        $year = date("Y", strtotime($row->$column));
		        if(!in_array($montly, $array_montly)) $array_montly[] = $montly;
		        if(!in_array($year, $array_year)) $array_year[] = $year;
		    }

		    $data[] = array(
		    	'montly'  => $array_montly,
		    	'year'    => $array_year,
		    );

		    $output = array(
		    	'result'	=> 'success',
			    'message' 	=> 'query success',
			    'data'		=> $data
			);

		    header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function banklist()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$select = $this->M_all->select('setting', 'isi', 'ket = "BANK"');

			if($select->num_rows() > 0)
			{
				foreach ($select->result() as $k => $row) {
					$ex = explode('-', $row->isi);
					$this->mysqli_data[] = array(
						'value' => $row->isi,
						'text'	=> $ex[0].' - '.$ex[1].' - '.$ex[2]
					);				
				}

				$result  = 'success';
				$message = 'Berhasil mengambil daftar bank';

			} else {
				$result  = 'error';
				$message = 'Bank list cant found';
			}

			$output = array(
		    	'result'	=> $result,
			    'message' 	=> $message,
			    'data'		=> $this->mysqli_data
			);

			header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}

	}

	public function statistics()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$this->load->model('M_dashboard');
			if($this->input->post('action') == 'now')
			{
				$po = $this->M_dashboard->statistics_po('now')->result();
				$do = $this->M_dashboard->statistics_do('now');
				$inv = $this->M_dashboard->statistics_inv('now')->result();

				$data[] = array(
			    	'jml_po'  	=> $po[0]->jml_po,
			    	'jml_do'    => $do->num_rows(),
			    	'jml_in'    => $inv[0]->jml_in,
			    );

			    $output = array(
			    	'result'	=> 'success',
				    'message' 	=> 'query success',
				    'data'		=> $data
				);

			    header('Content-Type: application/json');
				echo json_encode($output);
				exit();

			} elseif ($this->input->post('action') == 'periode') {



				
			} else {
				redirect(base_url('index.php/action/auth/signout'));
			}

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function notification()
	{
		if($this->input->cookie('notification') == false)
		{
			$data_1 = $this->M_all->select( 'status', 'count(id_fk) as jml1', 'order_status = 0' )->result();
			$data_2 = $this->M_all->left_join( 'delivery_orders_customer', 'delivery_orders_customer.id_fk', array(
				"status ON delivery_orders_customer.id_fk = status.id_fk",
				"invoice ON delivery_orders_customer.id_fk = invoice.id_fk"
			), ' WHERE status.order_status BETWEEN 1 AND 2 AND invoice.id_fk IS NULL GROUP BY delivery_orders_customer.id_fk')->num_rows();
			
			$data_3 = $this->M_all->left_join( 'workorder_customer', 'count(workorder_customer.id) AS jml3', array(
				"status ON workorder_customer.id_fk = status.id_fk"
			), ' WHERE workorder_customer.duration >= "'.date('Y-m-d').'" AND status.order_status BETWEEN 3 AND 2')->result();
			
			$data_4 = $this->M_all->left_join( 'workorder_customer', 'count(workorder_customer.id) AS jml4', array(
				"status ON workorder_customer.id_fk = status.id_fk"
			), ' WHERE workorder_customer.duration < "'.date('Y-m-d').'" AND status.order_status BETWEEN 3 AND 2')->result();
			
			$data_5 = $this->M_all->left_join( 'delivery_orders_customer', 'delivery_orders_customer.id_fk', array(
				"status ON delivery_orders_customer.id_fk = status.id_fk",
				"invoice ON delivery_orders_customer.id_fk = invoice.id_fk"
			), ' WHERE status.order_status BETWEEN 1 AND 2 AND invoice.id_fk IS NOT NULL AND invoice.duration < "'.date('Y-m-d').'" GROUP BY delivery_orders_customer.id_fk')->num_rows();

			if( $this->session->userdata('account') == 1 )
			{
				if( $this->session->userdata('role') ==	1 || $this->session->userdata('role') == 5)
				{
					$value[] = $data_1[0]->jml1;
					$value[] = $data_2;
					$value[] = $data_3[0]->jml3;
					$value[] = $data_4[0]->jml4;
					$value[] = $data_5;
					$element[] = ($data_1[0]->jml1 > 0)? "<li class='looping-notif'><a href='".base_url('index.php/dashboard/workorder')."'><i class='fa fa-send-o text-aqua'></i> ".$data_1[0]->jml1." SPK baru</a></li>" : "";
					$element[] = ($data_2 > 0)? "<li class='looping-notif'><a href='".base_url('index.php/dashboard/invoice_waiting')."'><i class='fa fa-sticky-note-o text-yellow'></i> ".$data_2." Faktur baru</a></li>" : "";
					$element[] = ($data_3[0]->jml3 > 0)? "<li class='looping-notif'><a href='".base_url('index.php/dashboard/do_waiting')."'><i class='fa fa-truck text-green'></i> ".$data_3[0]->jml3." Surat jalan belum diproses</a></li>" : "";
					$element[] = ($data_4[0]->jml4 > 0)? "<li class='looping-notif'><a href='".base_url('index.php/dashboard/do_waiting')."'><i class='fa fa-clock-o text-red'></i> ".$data_4[0]->jml4." SPK masuk tenggat waktu</a></li>" : "";
					$element[] = ($data_5 > 0)? "<li class='looping-notif'><a href='".base_url('index.php/dashboard/invoice_duedate')."'><i class='fa fa-sticky-note-o text-yellow'></i> ".$data_5." Faktur jatuh tempo</a></li>" : "";
				}

				elseif( $this->session->userdata('role') == 4 )
				{
					$value[] = $data_2;
					$value[] = $data_5;
					$element[] = ($data_2 > 0)? "<li class='looping-notif'><a href='".base_url('index.php/dashboard/invoice_waiting')."'><i class='fa fa-sticky-note-o text-yellow'></i> ".$data_2." Faktur baru</a></li>" : "";
					$element[] = ($data_5 > 0)? "<li class='looping-notif'><a href='".base_url('index.php/dashboard/invoice_duedate')."'><i class='fa fa-sticky-note-o text-yellow'></i> ".$data_5." Faktur jatuh tempo</a></li>" : "";
				}

				elseif( $this->session->userdata('role') == 2 )
				{
					$value[] = $data_1[0]->jml1;
					$value[] = $data_4[0]->jml4;
					$value[] = $data_3[0]->jml3;
					$element[] = ($data_1[0]->jml1 > 0)? "<li class='looping-notif'><a href='".base_url('index.php/dashboard/workorder')."'><i class='fa fa-send-o text-aqua'></i> ".$data_1[0]->jml1." SPK baru</a></li>" : "";
					$element[] = ($data_4[0]->jml4 > 0)? "<li class='looping-notif'><a href='".base_url('index.php/dashboard/do_waiting')."'><i class='fa fa-clock-o text-red'></i> ".$data_4[0]->jml4." SPK masuk tenggat waktu</a></li>" : "";
					$element[] = ($data_3[0]->jml3 > 0)? "<li class='looping-notif'><a href='".base_url('index.php/dashboard/do_waiting')."'><i class='fa fa-truck text-green'></i> ".$data_3[0]->jml3." Surat jalan belum diproses</a></li>" : "";
				}

				$data[] = array(
					'count'		=> array_sum(array_filter($value)),
					'item'		=> array_filter($element)
				);
			}

			$output = json_encode(array( 
				'result' => 'success', 
				'data' => $data
			));

			$cookie = array(
				'name'		=> 'notification',
				'value' 	=> 	$output,
				'expire'	=> '86400',
			);

			$this->input->set_cookie($cookie);

		} else {

			$output = $this->input->cookie('notification');
		}

		header('Content-Type: application/json');
		echo $output;
		exit();
	}

	private function _uploadImage()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$config['upload_path']          = 'assets/uploads/img/';
			$config['allowed_types']        = 'jpg|png';
			$config['file_name']            = round(microtime(true));
			$config['overwrite']			= true;
			$config['max_size']             = 1024;

			$this->load->library('upload', $config);
			if ($this->upload->do_upload('logo')) {
				return '../../assets/uploads/img/'.$this->upload->data("file_name");
			}

			return '';
			
		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function company()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$this->load->model('M_company');
			$data = array();
			$list = $this->M_company->get_datatables();
			foreach ($list as $k => $column)
			{
				$functions = "<div class='row text-center'>";
				if($this->session->userdata('id') == $column->input_by){
					$functions .= '<button type="button" class="btn btn-flat btn-primary function_edit" data-id="'.$column->id.'"><i class="fa fa-pencil-square-o"></i></button><button type="button" class="btn btn-flat btn-danger function_delete" data-id="'.$column->id.'" data-name="'.$column->company.'"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
				} else {
					$functions .= "Not allowed";
				}
				$functions .= "</div>";
				
				$row = array();
				$row[] = $column->company;
				$row[] = $column->address;
				$row[] = $column->email;
				$row[] = $column->phone;
				$row[] = $column->logo;
				$row[] = $functions;
				$data[] = $row;
        	}
 
		    $output = array(
		        "draw" => $_POST['draw'],
		        "recordsTotal" => $this->M_company->count_all(),
		        "recordsFiltered" => $this->M_company->count_filtered(),
		        "data" => $data,
		    );

		    header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function add_company()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(empty($_FILES['logo']['size']))
			{
				$data = array(
					'company' 	=> $this->input->post('company'),
					'address' 	=> $this->input->post('address'),
					'email' 	=> $this->input->post('email'),
					'phone' 	=> $this->input->post('phone'),
					'input_by' 	=> $this->session->userdata('id')
				);

				if($this->M_all->insert('company', $data))
				{
					$result  = 'success';
					$message = 'Berhasil memasukan data';

				} else {
					$result  = 'error';
					$message = 'Gagal memasukan data';
				}

			} else {

				$upload = $this->_uploadImage();
				if($upload)
				{
					$data = array(
						'company' 	=> $this->input->post('company'),
						'address' 	=> $this->input->post('address'),
						'email' 	=> $this->input->post('email'),
						'phone' 	=> $this->input->post('phone'),
						'logo' 		=> $upload,
						'input_by' 	=> $this->session->userdata('id')
					);

					if($this->M_all->insert('company', $data))
					{
						$result  = 'success';
						$message = 'Berhasil memasukan data';

					} else {
						$result  = 'error';
						$message = 'Gagal memasukan data';
					}

				} else {
					$result  = 'error';
					$message = 'Gagal mengunggah gambar';
				}
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function get_company()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$select = $this->M_all->select('company', '*', 'id ='.$this->input->post('id') );
				if($select)
				{
					foreach($select->result() as $row){
						$this->mysqli_data[] = array(
							"company"    => $row->company,
				            "address"    => $row->address,
				            "email"      => $row->email,
				            "phone"      => $row->phone,
				            "logo"       => $row->logo,
			        	);
			        }

			        $result  = 'success';
					$message = 'query success';

				} else {
					$result  = 'error';
					$message = 'query error';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function edit_company()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(empty($this->input->get('id')))
			{
				$result  = 'error';
				$message = 'ID missing';

			} else {

				if(empty($_FILES['logo']['size']))
				{
					$data = array(
						'company' 	=> $this->input->post('company'),
						'address' 	=> $this->input->post('address'),
						'email' 	=> $this->input->post('email'),
						'phone' 	=> $this->input->post('phone'),
						'logo' 		=> $this->input->post('tmp_logo'),
						'input_by' 	=> $this->session->userdata('id')
					);

					if($this->M_all->update('company', $data, ' id= '.$this->input->get('id')))
					{
						$result  = 'success';
						$message = 'Berhasil memasukan data';

					} else {
						$result  = 'error';
						$message = 'Gagal memasukan data';
					}

				} else {

					$upload = $this->_uploadImage();
					if($upload)
					{
						$data = array(
							'company' 	=> $this->input->post('company'),
							'address' 	=> $this->input->post('address'),
							'email' 	=> $this->input->post('email'),
							'phone' 	=> $this->input->post('phone'),
							'logo' 		=> $upload,
							'input_by' 	=> $this->session->userdata('id')
						);

						if($this->M_all->update('company', $data, 'id ='.$this->input->get('id')))
						{
							$result  = 'success';
							$message = 'Berhasil memasukan data';
							empty($tmp_logo)? '': unlink($tmp_logo);

						} else {
							$result  = 'error';
							$message = 'Gagal memasukan data';
						}

					} else {
						$result  = 'error';
						$message = 'Gagal mengunggah gambar';
					}
				}
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function delete_company()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$update = $this->M_all->update('company', array('hidden' => 1), 'id = '.$this->input->post('id'));
				if($update)
				{
					$result  = 'success';
					$message = 'Berhasil menghapus data';

				} else {
					$result  = 'error';
					$message = 'Gagal menghapus data';
				}
				
			} else {

				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function vendor()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$this->load->model('M_vendor');
			$data = array();
			$list = $this->M_vendor->get_datatables();
			foreach ($list as $k => $column)
			{
				$functions = "<div class='row text-center'>";
				if($this->session->userdata('id') == $column->input_by){
					$functions .= '<button type="button" class="btn btn-flat btn-primary function_edit" data-id="'.$column->id.'"><i class="fa fa-pencil-square-o"></i></button><button type="button" class="btn btn-flat btn-danger function_delete" data-id="'.$column->id.'" data-name="'.$column->vendor.'"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
				} else {
					$functions .= "Not allowed";
				}
				$functions .= "</div>";
				
				$row = array();
				$row[] = $column->vendor;
				$row[] = $column->address;
				$row[] = $column->phone;
				$row[] = $functions;
				$data[] = $row;
        	}
 
		    $output = array(
		        "draw" => $_POST['draw'],
		        "recordsTotal" => $this->M_vendor->count_all(),
		        "recordsFiltered" => $this->M_vendor->count_filtered(),
		        "data" => $data,
		    );

		    header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function add_vendor()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$vendor = $this->input->post('vendor');
			$address = $this->input->post('address');
			$phone = $this->input->post('phone');

			if(!empty($vendor) && !empty($address) && !empty($phone))
			{
				$data = array(
					'vendor'	=> $vendor,
					'address'	=> $address,
					'phone'		=> $phone,
					'input_by' 	=> $this->session->userdata('id')
				);

				if($this->M_all->insert('vendor', $data))
				{
					$result  = 'success';
					$message = 'Berhasil memasukan data';

				} else {
					$result  = 'error';
					$message = 'Gagal memasukan data';
				}

			} else {

				$result  = 'error';
				$message = 'Mohon untuk mengisi semua kolom yang disediakan';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function get_vendor()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$select = $this->M_all->select('vendor', '*', 'id ='.$this->input->post('id') );
				if($select)
				{
					foreach($select->result() as $row){
						$this->mysqli_data[] = array(
							"vendor"    => $row->vendor,
				            "address"   => $row->address,
				            "phone"     => $row->phone
			        	);
			        }

			        $result  = 'success';
					$message = 'query success';

				} else {
					$result  = 'error';
					$message = 'query error';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function edit_vendor()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(empty($this->input->get('id')))
			{
				$result  = 'error';
				$message = 'ID missing';

			} else {

				$data = array(
					'vendor'    => $this->input->post('vendor'),
					'address'   => $this->input->post('address'),
					'phone'     => $this->input->post('phone'),
					'input_by' 	=> $this->session->userdata('id')
				);

				if($this->M_all->update('vendor', $data, 'id ='.$this->input->get('id')))
				{
					$result  = 'success';
					$message = 'Berhasil memasukan data';

				} else {
					$result  = 'error';
					$message = 'Gagal memasukan data';
				}
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function delete_vendor()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$update = $this->M_all->update('vendor', array('hidden' => 1), 'id = '.$this->input->post('id'));
				if($update)
				{
					$result  = 'success';
					$message = 'Berhasil menghapus data';

				} else {
					$result  = 'error';
					$message = 'Gagal menghapus data';
				}
				
			} else {

				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function customer()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$this->load->model('M_customer');
			$data = array();
			$list = $this->M_customer->get_datatables();
			foreach ($list as $k => $column)
			{
				$functions = "<div class='row text-center'>";
				if($this->session->userdata('id') == $column->input_by){
					$functions .= '<button type="button" class="btn btn-flat btn-primary function_edit" data-id="'.$column->id.'"><i class="fa fa-pencil-square-o"></i></button><button type="button" class="btn btn-flat btn-danger function_delete" data-id="'.$column->id.'" data-name="'.$column->nama.'"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
				} else {
					$functions .= "Not allowed";
				}
				$functions .= "</div>";

				$alamat = empty($column->alamat)? '' : $column->alamat.'. ';
				$kota = empty($column->kota)? '' : $column->kota.'. ';
				$prov = empty($column->provinsi)? '' : $column->provinsi.'. ';
				$neg = empty($column->negara)? '' : $column->negara.'. ';
				$kode = empty($column->kodepos)? '' : $column->kodepos.'. ';
				$tlp = empty($column->telp)? '' : $column->telp.'. ';
				$s_alamat = empty($column->s_alamat)? '' : $column->s_alamat.'. ';
				$s_kota = empty($column->s_kota)? '' : $column->s_kota.'. ';
				$s_prov = empty($column->s_provinsi)? '' : $column->s_provinsi.'. ';
				$s_neg = empty($column->s_negara)? '' : $column->s_negara.'. ';
				$s_kode = empty($column->s_kodepos)? '' : $column->s_kodepos.'. ';
				$s_tlp = empty($column->s_telp)? '' : $column->s_telp.'. ';

				$row = array();
				$row[] = $column->nama;
				$row[] = $alamat.$kota.$prov.$neg.$kode.$tlp;
				$row[] = $column->s_nama;
				$row[] = $s_alamat.$s_kota.$s_prov.$s_neg.$s_kode.$s_tlp;
				$row[] = $functions;
				$data[] = $row;
        	}
 
		    $output = array(
		        "draw" => $_POST['draw'],
		        "recordsTotal" => $this->M_customer->count_all(),
		        "recordsFiltered" => $this->M_customer->count_filtered(),
		        "data" => $data,
		    );

		    header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function add_customer()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('b_nama')) && !empty($this->input->post('b_alamat')) && !empty($this->input->post('b_kota')) && !empty($this->input->post('b_negara')) && !empty($this->input->post('b_provinsi')) && !empty($this->input->post('b_kodepos')) && !empty($this->input->post('b_telp')))
			{
				$data = array(
					'nama'			=> $this->input->post('b_nama'),
    				'alamat'		=> $this->input->post('b_alamat'),
    				'kota'			=> $this->input->post('b_kota'),
    				'negara'		=> $this->input->post('b_negara'),
    				'provinsi'		=> $this->input->post('b_provinsi'),
    				'kodepos'		=> $this->input->post('b_kodepos'),
    				'telp'			=> $this->input->post('b_telp'),
				    's_nama'		=> $this->input->post('s_nama'),
				    's_alamat'		=> $this->input->post('s_alamat'),
				    's_kota'		=> $this->input->post('s_kota'),
				    's_negara'		=> $this->input->post('s_negara'),
				    's_provinsi'	=> $this->input->post('s_provinsi'),
				    's_kodepos'		=> $this->input->post('s_kodepos'),
				    's_telp'		=> $this->input->post('s_telp'),
					'input_by' 		=> $this->session->userdata('id')
				);

				if($this->M_all->insert('customer', $data))
				{
					$result  = 'success';
					$message = 'Berhasil memasukan data';

				} else {
					$result  = 'error';
					$message = 'Gagal memasukan data';
				}

			} else {

				$result  = 'error';
				$message = 'Mohon untuk mengisi semua kolom yang disediakan';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function get_customer()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$select = $this->M_all->select('customer', '*', 'id ='.$this->input->post('id') );
				if($select)
				{
					foreach($select->result() as $row){
						$this->mysqli_data[] = array(
							'b_nama' 	=> $row->nama,
			                'b_alamat' 	=> $row->alamat,
			                'b_kota' 	=> $row->kota,
			                'b_negara' 	=> $row->negara,
			                'b_provinsi' => $row->provinsi,
			                'b_kodepos' => $row->kodepos,
			                'b_telp' 	=> $row->telp,
			                's_nama' 	=> $row->s_nama,
			                's_alamat' 	=> $row->s_alamat,
			                's_kota' 	=> $row->s_kota,
			                's_negara' 	=> $row->s_negara,
			                's_provinsi'=> $row->s_provinsi,
			                's_kodepos'	=> $row->s_kodepos,
			                's_telp' 	=> $row->s_telp
			        	);
			        }

			        $result  = 'success';
					$message = 'query success';

				} else {
					$result  = 'error';
					$message = 'query error';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function edit_customer()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(empty($this->input->get('id')))
			{
				$result  = 'error';
				$message = 'ID missing';

			} else {

				$data = array(
					'nama' 		=> $this->input->post('b_nama'),
	                'alamat' 	=> $this->input->post('b_alamat'),
	                'kota' 		=> $this->input->post('b_kota'),
	                'negara' 	=> $this->input->post('b_negara'),
	                'provinsi' 	=> $this->input->post('b_provinsi'),
	                'kodepos' 	=> $this->input->post('b_kodepos'),
	                'telp' 		=> $this->input->post('b_telp'),
	                's_nama' 	=> $this->input->post('s_nama'),
	                's_alamat' 	=> $this->input->post('s_alamat'),
	                's_kota' 	=> $this->input->post('s_kota'),
	                's_negara' 	=> $this->input->post('s_negara'),
	                's_provinsi'=> $this->input->post('s_provinsi'),
	                's_kodepos'	=> $this->input->post('s_kodepos'),
	                's_telp' 	=> $this->input->post('s_telp')
				);

				if($this->M_all->update('customer', $data, 'id ='.$this->input->get('id')))
				{
					$result  = 'success';
					$message = 'Berhasil memasukan data';

				} else {
					$result  = 'error';
					$message = 'Gagal memasukan data';
				}
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function delete_customer()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$update = $this->M_all->update('customer', array('hidden' => 1), 'id = '.$this->input->post('id'));
				if($update)
				{
					$result  = 'success';
					$message = 'Berhasil menghapus data';

				} else {
					$result  = 'error';
					$message = 'Gagal menghapus data';
				}
				
			} else {

				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function label()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$this->load->model('M_label');
			$data = array();
			$list = $this->M_label->get_datatables();
			foreach ($list as $k => $column)
			{
				$functions = "<div class='row text-center'>";
				if($this->session->userdata('role') == '5')
				{
					$functions  .= 'Not Allowed';

				} else {

					$functions .= '<button type="button" class="btn btn-flat btn-success function_ins" data-id="'.$column->id_fk.'"><i class="fa fa-plus" aria-hidden="true"></i></button><button type="button" class="btn btn-flat btn-warning function_outs" data-id="'.$column->id_fk.'"><i class="fa fa-minus" aria-hidden="true"></i></button><button type="button" class="btn btn-flat btn-primary function_edit" data-id="'.$column->id.'"><i class="fa fa-pencil-square-o"></i></button><button type="button" class="btn btn-flat btn-danger function_delete" data-id="'.$column->id.'" data-name="'.$column->product.'"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
				}

				$functions .= "</div'>";

				$row = array();
				$row[] = $column->rak;
				$row[] = $column->customer;
				$row[] = $column->product;
				$row[] = $column->size;
				$row[] = $column->material;
				$row[] = $column->core;
				$row[] = $column->line;
				$row[] = $column->per_roll;
				$row[] = $column->masuk - $column->keluar;
				$row[] = $column->name;
				$row[] = $functions;
				$data[] = $row;
        	}
 
		    $output = array(
		        "draw" => $_POST['draw'],
		        "recordsTotal" => $this->M_label->count_all(),
		        "recordsFiltered" => $this->M_label->count_filtered(),
		        "data" => $data,
		    );

		    header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function add_label()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('product')) && !empty($this->input->post('size')) && !empty($this->input->post('core')) && !empty($this->input->post('line')) && !empty($this->input->post('material')) && !empty($this->input->post('per_roll')) && !empty($this->input->post('stock')))
			{
				$get_id = $this->M_all->get_id( 'label', 'id_fk', 'id DESC' );
				$id_fk = $get_id->id_fk + 1;
			    $label = array(
			    	'id_fk' 	=> $id_fk,
			    	'rak'		=> $this->input->post('rak'),
			    	'customer'	=> $this->input->post('customer'),
			    	'product'	=> $this->input->post('product'),
			    	'size'		=> $this->input->post('size'),
			    	'core'		=> $this->input->post('core'),
			    	'line'		=> $this->input->post('line'),
			    	'material'	=> $this->input->post('material'),
			    	'per_roll'	=> $this->input->post('per_roll'),
			    	'input_by'	=> $this->session->userdata('id'),
			    );

			    $history = array(
			    	'id_fk' 	=> $id_fk,
			    	'date'		=> date('Y-m-d'),
			    	's_masuk'	=> $this->input->post('stock'),
			    	'input_by'	=> $this->session->userdata('id')
			    );

			    if($this->M_all->insert( 'label', $label))
			    {
			    	if($this->M_all->insert( 'histori_label', $history))
			    	{
			    		$result  = 'success';
			    		$message = 'Berhasil memasukan data';

			    	} else {
			    		$result  = 'error';
			    		$message = 'Gagal memasukan data histori';
			    	}

			    } else {

			    	$result  = 'error';
					$message = 'Gagal memasukan data label';
			    }

			} else {

				$result  = 'error';
				$message = 'Mohon untuk mengisi semua kolom yang disediakan';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function get_label()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$select = $this->M_all->left_join('label', 'label.*, SUM(histori_label.s_masuk) AS t_masuk, SUM(histori_label.s_keluar) AS t_keluar', array('histori_label ON label.id_fk = histori_label.id_fk'), ' WHERE label.id = '.$this->input->post('id') );
				if($select->num_rows() > 0)
				{
					foreach($select->result() as $row){
						$this->mysqli_data[] = array(
							'rak' 		=> $row->rak,
			                'customer' 	=> $row->customer,
			                'product' 	=> $row->product,
			                'size' 		=> $row->size,
			                'material' 	=> $row->material,
			                'core' 		=> $row->core,
			                'line'	 	=> $row->line,
			                'per_roll' 	=> $row->per_roll,
			                'stock' 	=> $row->t_masuk - $row->t_keluar
			        	);
			        }

			        $result  = 'success';
					$message = 'query success';

				} else {
					$result  = 'error';
					$message = 'query error';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function edit_label()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(empty($this->input->get('id')))
			{
				$result  = 'error';
				$message = 'ID missing';

			} else {

				$data = array(
					'rak'		=> $this->input->post('rak'),
			    	'customer'	=> $this->input->post('customer'),
			    	'product'	=> $this->input->post('product'),
			    	'size'		=> $this->input->post('size'),
			    	'core'		=> $this->input->post('core'),
			    	'line'		=> $this->input->post('line'),
			    	'material'	=> $this->input->post('material'),
			    	'per_roll'	=> $this->input->post('per_roll'),
			    	'input_by'	=> $this->session->userdata('id')
				);

				if($this->M_all->update('label', $data, 'id ='.$this->input->get('id')))
				{
					$result  = 'success';
					$message = 'Berhasil memasukan data';

				} else {
					$result  = 'error';
					$message = 'Gagal memasukan data';
				}
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function delete_label()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$update = $this->M_all->update('label', array('hidden' => 1), 'id = '.$this->input->post('id'));
				if($update)
				{
					$result  = 'success';
					$message = 'Berhasil menghapus data';

				} else {
					$result  = 'error';
					$message = 'Gagal menghapus data';
				}
				
			} else {

				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function histori_label()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$this->load->model('M_histori_label');
			$data = array();
			$list = $this->M_histori_label->get_datatables();
			foreach ($list as $k => $column)
			{
				if($column->status == 0){ $status = 'STOK'; }
				elseif($column->status == 1){ $status = 'MASUK'; }
				else { $status = 'KELUAR'; }
				
				$functions = "<div class='row text-center'>";
				if($this->session->userdata('role') == '5' || $this->session->userdata('id') != $column->input_by)
				{
					$functions  .= 'Not Allowed';

				} elseif($column->status == '0') {
					$functions .= '';

				} else {

					$functions .= '<button type="button" class="btn btn-flat btn-danger delhis" data-id="'.$column->id.'" data-name="'.$column->product.'"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
				}

				$functions .= "</div'>";

				$row = array();
				$row[] = date("d/m/Y", strtotime($column->date));
				$row[] = $column->rak;
				$row[] = $column->customer;
				$row[] = $column->no_sj;
				$row[] = $column->no_po;
				$row[] = $status;
				$row[] = $column->product;
				$row[] = $column->size;
				$row[] = $column->material;
				$row[] = $column->core;
				$row[] = $column->line;
				$row[] = $column->roll;
				$row[] = $column->content;
				$row[] = $column->unit;
				$row[] = ($column->status == 0)? $column->s_masuk : '0';
				$row[] = ($column->status == 0)? '0' : $column->s_masuk;
				$row[] = $column->s_keluar;
				$row[] = '';
				$row[] = $column->name;
				$row[] = $functions;
				$data[] = $row;
        	}
 
		    $output = array(
		        "draw" => $_POST['draw'],
		        "recordsTotal" => $this->M_histori_label->count_all(),
		        "recordsFiltered" => $this->M_histori_label->count_filtered(),
		        "data" => $data,
		    );

		    header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function delhis_label()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$update = $this->M_all->update('histori_label', array('hidden' => 1), 'id = '.$this->input->post('id'));
				if($update)
				{
					$result  = 'success';
					$message = 'Berhasil menghapus data';

				} else {
					$result  = 'error';
					$message = 'Gagal menghapus data';
				}
				
			} else {

				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function mashis_label()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('nama_')) && !empty($this->input->post('tgl_')) && !empty($this->input->post('nosj_')) && !empty($this->input->post('nopo_')) && !empty($this->input->post('roll_')) && !empty($this->input->post('content_')) && !empty($this->input->post('unit_')) && !empty($this->input->post('smasuk_')))
			{

			    $history = array(
			    	'id_fk' 	=> $this->input->post('id'),
			    	'customer' 	=> $this->input->post('nama_'),
			    	'date'		=> $this->input->post('tgl_'),
			    	'no_sj'	=> $this->input->post('nosj_'),
			    	'no_po'	=> $this->input->post('nopo_'),
			    	'roll'		=> $this->input->post('roll_'),
			    	'content'		=> $this->input->post('content_'),
			    	'unit'		=> $this->input->post('unit_'),
			    	's_masuk'	=> $this->input->post('smasuk_'),
			    	'status'	=> '1',
			    	'input_by'	=> $this->session->userdata('id'),
			    );

			    if($this->M_all->insert( 'histori_label', $history))
			    {
		    		$result  = 'success';
		    		$message = 'Berhasil memasukan data';

			    } else {

			    	$result  = 'error';
					$message = 'Gagal memasukan data';
			    }

			} else {

				$result  = 'error';
				$message = 'Mohon untuk mengisi semua kolom yang disediakan';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function kelhis_label()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('nama_')) && !empty($this->input->post('tgl_')) && !empty($this->input->post('nosj_')) && !empty($this->input->post('nopo_')) && !empty($this->input->post('roll_')) && !empty($this->input->post('content_')) && !empty($this->input->post('unit_')) && !empty($this->input->post('skeluar_')))
			{

			    $history = array(
			    	'id_fk' 	=> $this->input->post('id'),
			    	'customer' 	=> $this->input->post('nama_'),
			    	'date'		=> $this->input->post('tgl_'),
			    	'no_sj'	=> $this->input->post('nosj_'),
			    	'no_po'	=> $this->input->post('nopo_'),
			    	'roll'		=> $this->input->post('roll_'),
			    	'content'		=> $this->input->post('content_'),
			    	'unit'		=> $this->input->post('unit_'),
			    	's_keluar'	=> $this->input->post('skeluar_'),
			    	'status'	=> '2',
			    	'input_by'	=> $this->session->userdata('id'),
			    );

			    if($this->M_all->insert( 'histori_label', $history))
			    {
		    		$result  = 'success';
		    		$message = 'Berhasil memasukan data';

			    } else {

			    	$result  = 'error';
					$message = 'Gagal memasukan data';
			    }

			} else {

				$result  = 'error';
				$message = 'Mohon untuk mengisi semua kolom yang disediakan';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function ribbon()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$this->load->model('M_ribbon');
			$data = array();
			$list = $this->M_ribbon->get_datatables();
			foreach ($list as $k => $column)
			{
				$functions = "<div class='row text-center'>";
				if($this->session->userdata('role') == '5')
				{
					$functions  .= 'Not Allowed';

				} else {

					$functions .= '<button type="button" class="btn btn-flat btn-success function_ins" data-id="'.$column->id_fk.'"><i class="fa fa-plus" aria-hidden="true"></i></button><button type="button" class="btn btn-flat btn-warning function_outs" data-id="'.$column->id_fk.'"><i class="fa fa-minus" aria-hidden="true"></i></button><button type="button" class="btn btn-flat btn-primary function_edit" data-id="'.$column->id.'"><i class="fa fa-pencil-square-o"></i></button><button type="button" class="btn btn-flat btn-danger function_delete" data-id="'.$column->id.'" data-name="'.$column->size.'"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
				}

				$functions .= "</div'>";

				$row = array();
				$row[] = $column->rak;
				$row[] = $column->customer;
				$row[] = $column->product;
				$row[] = $column->size;
				$row[] = $column->fi_fo;
				$row[] = $column->masuk - $column->keluar;
				$row[] = $column->name;
				$row[] = $functions;
				$data[] = $row;
        	}
 
		    $output = array(
		        "draw" => $_POST['draw'],
		        "recordsTotal" => $this->M_ribbon->count_all(),
		        "recordsFiltered" => $this->M_ribbon->count_filtered(),
		        "data" => $data,
		    );

		    header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function add_ribbon()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('fi-fo')) && !empty($this->input->post('stock')))
			{
				$get_id = $this->M_all->get_id( 'ribbon', 'id_fk', 'id DESC' );
				$id_fk = $get_id->id_fk + 1;
			    $ribbon = array(
			    	'id_fk' 	=> $id_fk,
			    	'customer'	=> $this->input->post('customer'),
			    	'rak'		=> $this->input->post('rak'),
			    	'product'	=> $this->input->post('product'),
			    	'size'		=> $this->input->post('size'),
			    	'fi_fo'		=> $this->input->post('fi-fo'),
			    	'input_by'	=> $this->session->userdata('id'),
			    );

			    $history = array(
			    	'id_fk' 	=> $id_fk,
			    	'date'		=> date('Y-m-d'),
			    	's_masuk'	=> $this->input->post('stock'),
			    	'input_by'	=> $this->session->userdata('id')
			    );

			    if($this->M_all->insert( 'ribbon', $ribbon))
			    {
			    	if($this->M_all->insert( 'histori_ribbon', $history))
			    	{
			    		$result  = 'success';
			    		$message = 'Berhasil memasukan data';

			    	} else {
			    		$result  = 'error';
			    		$message = 'Gagal memasukan data histori';
			    	}

			    } else {

			    	$result  = 'error';
					$message = 'Gagal memasukan data label';
			    }

			} else {

				$result  = 'error';
				$message = 'Mohon untuk mengisi semua kolom yang disediakan';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function get_ribbon()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$select = $this->M_all->left_join('ribbon', 'ribbon.*, SUM(histori_ribbon.s_masuk) AS t_masuk, SUM(histori_ribbon.s_keluar) AS t_keluar', array('histori_ribbon ON ribbon.id_fk = histori_ribbon.id_fk'), ' WHERE ribbon.id = '.$this->input->post('id') );
				if($select->num_rows() > 0)
				{
					foreach($select->result() as $row){
						$this->mysqli_data[] = array(
							'rak' 		=> $row->rak,
			                'customer' 	=> $row->customer,
			                'product' 	=> $row->product,
			                'size' 		=> $row->size,
			                'fi_fo' 	=> $row->fi_fo,
			                'stock' 	=> $row->t_masuk - $row->t_keluar
			        	);
			        }

			        $result  = 'success';
					$message = 'query success';

				} else {
					$result  = 'error';
					$message = 'query error';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function edit_ribbon()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(empty($this->input->get('id')))
			{
				$result  = 'error';
				$message = 'ID missing';

			} else {

				$data = array(
					'rak'		=> $this->input->post('rak'),
			    	'customer'	=> $this->input->post('customer'),
			    	'product'	=> $this->input->post('product'),
			    	'size'		=> $this->input->post('size'),
			    	'fi_fo'		=> $this->input->post('fi-fo'),
			    	'input_by'	=> $this->session->userdata('id')
				);

				if($this->M_all->update('ribbon', $data, 'id ='.$this->input->get('id')))
				{
					$result  = 'success';
					$message = 'Berhasil memasukan data';

				} else {
					$result  = 'error';
					$message = 'Gagal memasukan data';
				}
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function delete_ribbon()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$update = $this->M_all->update('ribbon', array('hidden' => 1), 'id = '.$this->input->post('id'));
				if($update)
				{
					$result  = 'success';
					$message = 'Berhasil menghapus data';

				} else {
					$result  = 'error';
					$message = 'Gagal menghapus data';
				}
				
			} else {

				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function histori_ribbon()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$this->load->model('M_histori_ribbon');
			$data = array();
			$list = $this->M_histori_ribbon->get_datatables();
			foreach ($list as $k => $column)
			{
				if($column->status == 0){ $status = 'STOK'; }
				elseif($column->status == 1){ $status = 'MASUK'; }
				else { $status = 'KELUAR'; }
				
				$functions = "<div class='row text-center'>";
				if($this->session->userdata('role') == '5' || $this->session->userdata('id') != $column->input_by)
				{
					$functions  .= 'Not Allowed';

				} elseif($column->status == '0') {
					$functions .= '';

				} else {

					$functions .= '<button type="button" class="btn btn-flat btn-danger delhis" data-id="'.$column->id.'" data-name="'.$column->product.'"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
				}

				$functions .= "</div'>";

				$row = array();
				$row[] = date("d/m/Y", strtotime($column->date));
				$row[] = $column->rak;
				$row[] = $column->customer;
				$row[] = $column->no_sj;
				$row[] = $column->no_po;
				$row[] = $status;
				$row[] = $column->product;
				$row[] = $column->size;
				$row[] = $column->fi_fo;
				$row[] = $column->gulungan;
				$row[] = ($column->status == 0)? $column->s_masuk : '0';
				$row[] = ($column->status == 0)? '0' : $column->s_masuk;
				$row[] = $column->s_keluar;
				$row[] = '';
				$row[] = $column->name;
				$row[] = $functions;
				$data[] = $row;
        	}
 
		    $output = array(
		        "draw" => $_POST['draw'],
		        "recordsTotal" => $this->M_histori_ribbon->count_all(),
		        "recordsFiltered" => $this->M_histori_ribbon->count_filtered(),
		        "data" => $data,
		    );

		    header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function mashis_ribbon()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('customer')) && !empty($this->input->post('tgl_')) && !empty($this->input->post('nosj_')) && !empty($this->input->post('nopo_')) && !empty($this->input->post('gulungan_')) && !empty($this->input->post('s_masuk')))
			{

			    $history = array(
			    	'id_fk' 	=> $this->input->post('id'),
			    	'customer' 	=> $this->input->post('customer'),
			    	'date'		=> $this->input->post('tgl_'),
			    	'no_sj'		=> $this->input->post('nosj_'),
			    	'no_po'		=> $this->input->post('nopo_'),
			    	'gulungan'	=> $this->input->post('gulungan_'),
			    	's_masuk'	=> $this->input->post('s_masuk'),
			    	'status'	=> '1',
			    	'input_by'	=> $this->session->userdata('id'),
			    );

			    if($this->M_all->insert( 'histori_ribbon', $history))
			    {
		    		$result  = 'success';
		    		$message = 'Berhasil memasukan data';

			    } else {

			    	$result  = 'error';
					$message = 'Gagal memasukan data';
			    }

			} else {

				$result  = 'error';
				$message = 'Mohon untuk mengisi semua kolom yang disediakan';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function kelhis_ribbon()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('customer')) && !empty($this->input->post('tgl_')) && !empty($this->input->post('nosj_')) && !empty($this->input->post('nopo_')) && !empty($this->input->post('gulungan_')) && !empty($this->input->post('s_keluar')))
			{

			    $history = array(
			    	'id_fk' 	=> $this->input->post('id'),
			    	'customer' 	=> $this->input->post('customer'),
			    	'date'		=> $this->input->post('tgl_'),
			    	'no_sj'		=> $this->input->post('nosj_'),
			    	'no_po'		=> $this->input->post('nopo_'),
			    	'gulungan'	=> $this->input->post('gulungan_'),
			    	's_keluar'	=> $this->input->post('s_keluar'),
			    	'status'	=> '2',
			    	'input_by'	=> $this->session->userdata('id'),
			    );

			    if($this->M_all->insert( 'histori_ribbon', $history))
			    {
		    		$result  = 'success';
		    		$message = 'Berhasil memasukan data';

			    } else {

			    	$result  = 'error';
					$message = 'Gagal memasukan data';
			    }

			} else {

				$result  = 'error';
				$message = 'Mohon untuk mengisi semua kolom yang disediakan';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function delhis_ribbon()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$update = $this->M_all->update('histori_ribbon', array('hidden' => 1), 'id = '.$this->input->post('id'));
				if($update)
				{
					$result  = 'success';
					$message = 'Berhasil menghapus data';

				} else {
					$result  = 'error';
					$message = 'Gagal menghapus data';
				}
				
			} else {

				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function material()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$this->load->model('M_material');
			$data = array();
			$list = $this->M_material->get_datatables();
			foreach ($list as $k => $column)
			{
				$functions = "<div class='row text-center'>";
				if($this->session->userdata('role') == '5')
				{
					$functions  .= 'Not Allowed';

				} else {

					$functions .= '<button type="button" class="btn btn-flat btn-success function_ins" data-id="'.$column->id_fk.'"><i class="fa fa-plus" aria-hidden="true"></i></button><button type="button" class="btn btn-flat btn-warning function_outs" data-id="'.$column->id_fk.'"><i class="fa fa-minus" aria-hidden="true"></i></button><button type="button" class="btn btn-flat btn-primary function_edit" data-id="'.$column->id_histori.'"><i class="fa fa-pencil-square-o"></i></button><button type="button" class="btn btn-flat btn-danger function_delete" data-id="'.$column->id_bahan.'" data-name="'.$column->size.'"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
				}

				$functions .= "</div'>";

				$row = array();
				$row[] = $column->size;
				$row[] = $column->ingredient;
				$row[] = $column->color;
				$row[] = $column->note;
				$row[] = $column->unit;
				$row[] = $column->stock;
				$row[] = $column->name;
				$row[] = $functions;
				$data[] = $row;
        	}
 
		    $output = array(
		        "draw" => $_POST['draw'],
		        "recordsTotal" => $this->M_material->count_all(),
		        "recordsFiltered" => $this->M_material->count_filtered(),
		        "data" => $data,
		    );

		    header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function add_material()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('size')) && !empty($this->input->post('ingredient')) && !empty($this->input->post('color')) && !empty($this->input->post('note')) && !empty($this->input->post('stock')) && !empty($this->input->post('unit')))
			{
				$get_id = $this->M_all->get_id( 'bahan_baku', 'id_fk', 'id DESC' );
				$id_fk = $get_id->id_fk + 1;
			    $bahan = array(
			    	'id_fk' 	=> $id_fk,
			    	'size'		=> $this->input->post('size'),
			    	'ingredient'=> $this->input->post('ingredient'),
			    	'color'		=> $this->input->post('color'),
			    	'input_by'	=> $this->session->userdata('id'),
			    );

			    $history = array(
			    	'id_fk' 	=> $id_fk,
			    	'date'		=> date('Y-m-d'),
			    	's_masuk'	=> $this->input->post('stock'),
			    	'unit'		=> $this->input->post('unit'),
			    	'note'		=> $this->input->post('note'),
			    	'input_by'	=> $this->session->userdata('id')
			    );

			    if($this->M_all->insert( 'bahan_baku', $bahan))
			    {
			    	if($this->M_all->insert( 'histori_bahan', $history))
			    	{
			    		$result  = 'success';
			    		$message = 'Berhasil memasukan data';

			    	} else {
			    		$result  = 'error';
			    		$message = 'Gagal memasukan data histori';
			    	}

			    } else {

			    	$result  = 'error';
					$message = 'Gagal memasukan data label';
			    }

			} else {

				$result  = 'error';
				$message = 'Mohon untuk mengisi semua kolom yang disediakan';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function get_material()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$select = $this->M_all->left_join('bahan_baku', 'bahan_baku.*, SUM(histori_bahan.s_masuk - histori_bahan.s_keluar) AS stock, histori_bahan.note, histori_bahan.unit', array('histori_bahan ON bahan_baku.id_fk = histori_bahan.id_fk'), ' WHERE histori_bahan.id = '.$this->input->post('id') );
				if($select->num_rows() > 0)
				{
					foreach($select->result() as $row){
						$this->mysqli_data[] = array(
			                'size' 		=> $row->size,
							'ingredient'=> $row->ingredient,
			                'color' 	=> $row->color,
			                'unit' 		=> $row->unit,
			                'note' 		=> $row->note,
			                'stock' 	=> $row->stock
			        	);
			        }

			        $result  = 'success';
					$message = 'query success';

				} else {
					$result  = 'error';
					$message = 'query error';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function edit_material()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(empty($this->input->get('id')))
			{
				$result  = 'error';
				$message = 'ID missing';

			} else {

				$get = $this->M_all->select('histori_bahan', 'id_fk', ' id ="'.$this->input->get('id').'"')->result();

				$bahan = array(
			    	'size'		=> $this->input->post('size'),
			    	'ingredient'=> $this->input->post('ingredient'),
			    	'color'		=> $this->input->post('color'),
			    	'input_by'	=> $this->session->userdata('id'),
			    );

			    $history = array(
			    	'date'		=> date('Y-m-d'),
			    	's_masuk'	=> $this->input->post('stock'),
			    	'unit'		=> $this->input->post('unit'),
			    	'note'		=> $this->input->post('note'),
			    	'input_by'	=> $this->session->userdata('id')
			    );

				if($this->M_all->update('bahan_baku', $bahan, 'id_fk = '.$get[0]->id_fk))
				{
					if($this->M_all->update('histori_bahan', $history, 'id = '.$this->input->get('id')))
					{
 						$result  = 'success';
						$message = 'Berhasil memasukan data';

					} else {
						$result  = 'error';
						$message = 'Gagal memasukan data histori';
					}

				} else {
					$result  = 'error';
					$message = 'Gagal memasukan data material';
				}
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function delete_material()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$update = $this->M_all->update('bahan_baku', array('hidden' => 1), 'id = '.$this->input->post('id'));
				if($update)
				{
					$result  = 'success';
					$message = 'Berhasil menghapus data';

				} else {
					$result  = 'error';
					$message = 'Gagal menghapus data';
				}
				
			} else {

				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function histori_material()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$this->load->model('M_histori_material');
			$data = array();
			$list = $this->M_histori_material->get_datatables();
			foreach ($list as $k => $column)
			{
				if($column->status == 0){ $status = 'STOK'; }
				elseif($column->status == 1){ $status = 'MASUK'; }
				else { $status = 'KELUAR'; }
				
				$functions = "<div class='row text-center'>";
				if($this->session->userdata('role') == '5' || $this->session->userdata('id') != $column->input_by)
				{
					$functions  .= 'Not Allowed';

				} elseif($column->status == '0') {
					$functions .= '';

				} else {

					$functions .= '<button type="button" class="btn btn-flat btn-danger delhis" data-id="'.$column->id.'" data-name="'.$column->size.'"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
				}

				$functions .= "</div'>";

				$row = array();
				$row[] = date("d/m/Y", strtotime($column->date));
				$row[] = $column->size;
				$row[] = $column->ingredient;
				$row[] = $column->color;
				$row[] = $column->customer;
				$row[] = $column->no_po;
				$row[] = $status;
				$row[] = $column->ukuran;
				$row[] = $column->note;
				$row[] = $column->unit;
				$row[] = ($column->status == 0)? $column->s_masuk : '0';
				$row[] = ($column->status == 0)? '0' : $column->s_masuk;
				$row[] = $column->s_keluar;
				$row[] = '';
				$row[] = $column->name;
				$row[] = $functions;
				$data[] = $row;
        	}
 
		    $output = array(
		        "draw" => $_POST['draw'],
		        "recordsTotal" => $this->M_histori_material->count_all(),
		        "recordsFiltered" => $this->M_histori_material->count_filtered(),
		        "data" => $data,
		    );

		    header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function mashis_material()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('customer')) && !empty($this->input->post('date')) && !empty($this->input->post('nopo')) && !empty($this->input->post('ukuran')) && !empty($this->input->post('note')) && !empty($this->input->post('unit')) && !empty($this->input->post('s_masuk')))
			{

			    $history = array(
			    	'id_fk' 	=> $this->input->post('id'),
			    	'customer' 	=> $this->input->post('customer'),
			    	'date'		=> $this->input->post('date'),
			    	'no_po'		=> $this->input->post('nopo'),
			    	'ukuran'	=> $this->input->post('ukuran'),
			    	'note'		=> $this->input->post('note'),
			    	'unit'		=> $this->input->post('unit'),
			    	's_masuk'	=> $this->input->post('s_masuk'),
			    	'status'	=> '1',
			    	'input_by'	=> $this->session->userdata('id'),
			    );

			    if($this->M_all->insert( 'histori_bahan', $history))
			    {
		    		$result  = 'success';
		    		$message = 'Berhasil memasukan data';

			    } else {

			    	$result  = 'error';
					$message = 'Gagal memasukan data';
			    }

			} else {

				$result  = 'error';
				$message = 'Mohon untuk mengisi semua kolom yang disediakan';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function kelhis_material()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('customer')) && !empty($this->input->post('date')) && !empty($this->input->post('nopo')) && !empty($this->input->post('ukuran')) && !empty($this->input->post('note')) && !empty($this->input->post('unit')) && !empty($this->input->post('s_keluar')))
			{

			    $history = array(
			    	'id_fk' 	=> $this->input->post('id'),
			    	'customer' 	=> $this->input->post('customer'),
			    	'date'		=> $this->input->post('date'),
			    	'no_po'		=> $this->input->post('nopo'),
			    	'ukuran'	=> $this->input->post('ukuran'),
			    	'note'		=> $this->input->post('note'),
			    	'unit'		=> $this->input->post('unit'),
			    	's_keluar'	=> $this->input->post('s_keluar'),
			    	'status'	=> '2',
			    	'input_by'	=> $this->session->userdata('id'),
			    );

			    if($this->M_all->insert( 'histori_bahan', $history))
			    {
		    		$result  = 'success';
		    		$message = 'Berhasil memasukan data';

			    } else {

			    	$result  = 'error';
					$message = 'Gagal memasukan data';
			    }

			} else {

				$result  = 'error';
				$message = 'Mohon untuk mengisi semua kolom yang disediakan';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function delhis_material()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$update = $this->M_all->update('histori_bahan', array('hidden' => 1), 'id = '.$this->input->post('id'));
				if($update)
				{
					$result  = 'success';
					$message = 'Berhasil menghapus data';

				} else {
					$result  = 'error';
					$message = 'Gagal menghapus data';
				}
				
			} else {

				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function purchase_order()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$this->load->model('M_purchase_order');
			$data = array();
			$list = $this->M_purchase_order->get_datatables();
			foreach ($list as $k => $column)
			{
				$functions = "<div class='row text-center'>";
				if($this->session->userdata('role') == '5' || $this->session->userdata('id') !== $column->input_by)
				{
					$functions  .= 'Not Allowed';

				} else {

					$functions .= '<button type="button" class="btn btn-flat btn-success UbahVendor" data-id="'.$column->id_po.'"><i class="fa fa-user-o" aria-hidden="true"></i></button><button type="button" class="btn btn-flat btn-warning UbahItem" data-id="'.$column->id_po_item.'"><i class="fa fa-cube" aria-hidden="true"></i></button><button type="button" class="btn btn-flat btn-primary PrintView" data-id="'.$column->id_po.'"><i class="fa fa-print"></i></button>';
					
					if($this->session->userdata('role') == '1')
					{
						$functions .= '<button type="button" class="btn btn-flat btn-danger HapusItem" data-id="'.$column->id_po_item.'" data-name="'.$column->detail.'"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
					}
				}

				$functions .= "</div'>";

				$subtotal = empty($column->price_2)? $column->qty*$column->price_1 : $column->qty*$column->price_2;
				$tax = empty($column->ppn)? '0' : $subtotal/10;
				$total = $subtotal + $tax;

				$row = array();
				$row[] = date("d/m/Y", strtotime($column->po_date));
				$row[] = $column->company;
				$row[] = $column->vendor;
				$row[] = $column->nopo;
				$row[] = $column->isi;
				$row[] = $column->detail;
				$row[] = $column->size;
				$row[] = $column->price_1;
				$row[] = $column->price_2;
				$row[] = $column->qty;
				$row[] = $column->unit;
				$row[] = $column->merk;
				$row[] = $column->type;
				$row[] = $column->core;
				$row[] = $column->gulungan;
				$row[] = $column->bahan;
				$row[] = $column->note;
				$row[] = $subtotal;
				$row[] = $tax;
				$row[] = $total;
				$row[] = $column->name;
				$row[] = $functions;
				$data[] = $row;
        	}
 
		    $output = array(
		        "draw" => $_POST['draw'],
		        "recordsTotal" => $this->M_purchase_order->count_all(),
		        "recordsFiltered" => $this->M_purchase_order->count_filtered(),
		        "data" => $data,
		    );

		    header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function add_po()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if($this->input->post('id_vendor') > 0 && $this->input->post('company') > 0)
			{
				$data = $this->M_all->get_id( 'po_customer', 'id,po_date,nopo', 'id DESC' );
				if($data->id > 0)
				{
					$item_to = 0;
					$item = $this->input->post('data');
					$po_date = explode('/', $this->input->post('po_date'));
					$c_time = date('y').''.date('m');
					$nopo = ($c_time > date('ym', strtotime($data->po_date)))? $c_time .'1' : date('ym', strtotime($data->po_date)).''.substr($data->nopo, 7) + 1;

					$data_po = array(
						'id_vendor'		=> $this->input->post('id_vendor'),
						'id_company'	=> $this->input->post('company'),
						'po_date'		=> $po_date[2].'-'.$po_date[1].'-'.$po_date[0],
						'nopo'			=> 'PO '.$nopo,
						'note'			=> $this->input->post('note'),
						'ppn'			=> $this->input->post('ppns'),
						'type'			=> $this->input->post('po_type'),
						'input_by'		=> $this->session->userdata('id'),
					);

					foreach(array_filter($item['detail']) as $k => $v)
					{
						$data_item[] = array(
							'id_fk'		=> $data->id + 1,
							'item_to'	=> $item_to + 1,
							'detail'	=> $item['detail'][$k],
							'size'		=> $item['size'][$k],
							'price_1'	=> empty($item['price_1'][$k])? '0' : $this->priceFilter($item['price_1'][$k]),
							'price_2'	=> empty($item['price_2'][$k])? '0' : $this->priceFilter($item['price_2'][$k]),
							'qty'		=> empty($item['qty'][$k])? '0' : $item['qty'][$k],
							'unit'		=> $item['unit'][$k],
							'merk'		=> $item['merk'][$k],
							'type'		=> $item['type'][$k],
							'core'		=> $item['core'][$k],
							'gulungan'	=> $item['gulungan'][$k],
							'bahan'		=> $item['bahan'][$k],
						);
					}

					$insert_po = $this->M_all->insert_id('po_customer', $data_po);
					if($insert_po > 0)
					{
						$insert_item = $this->M_all->bulk_insert( 'po_item', $data_item );
						if($insert_item)
						{
							$result  = 'success';
							$message = 'Berhasil memasukan data';

						} else {

							$result  = 'error';
							$message = 'Gagal memasukan data item';
							$this->M_all->delete( 'po_customer', array( 'id' => $insert_po ));
						}

					} else {

						$result  = 'error';
						$message = 'Gagal memasukan data purchase order';
					}

				} else {

					$result  = 'error';
					$message = 'Failed to connect database';
				}

			} else {

				$result  = 'error';
				$message = 'Invalid id vendor';
			}
			

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}


	public function get_po()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$select = $this->M_all->left_join('vendor', 'vendor.vendor, po_customer.id_vendor, po_customer.id_company, po_customer.nopo, po_customer.po_date, po_customer.note, po_customer.type, po_customer.ppn, GROUP_CONCAT(po_item.detail SEPARATOR " ] - [ ") as detail, company.company, company.address, setting.isi', array('po_customer ON vendor.id = po_customer.id_vendor','po_item ON po_customer.id = po_item.id_fk','company ON po_customer.id_company = company.id', 'setting ON po_customer.type = setting.id'), ' WHERE po_customer.id = '.$this->input->post('id') );
				if($select->num_rows() > 0)
				{
					foreach($select->result() as $row){
						$this->mysqli_data[] = array(
							'id_company'	=> $row->id_company,
							'vendor'    	=> $row->vendor,
							'id_vendor'   	=> $row->id_vendor,
							'po_date'     	=> date('d/m/Y', strtotime($row->po_date)),
							'po_type'     	=> $row->type,
							'note'  		=> $row->note,
							'ppn'  			=> $row->ppn,
							'detail'  		=> '[ '. $row->detail .' ]',
							'company'  		=> $row->company,
							'address'  		=> $row->address,
							'isi'  			=> $row->isi,
			        	);
			        }

			        $result  = 'success';
					$message = 'query success';

				} else {
					$result  = 'error';
					$message = 'query error';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function edit_po()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$date = explode('/', $this->input->post('po_date'));
				$set = array(
					'id_vendor'		=> $this->input->post('id_vendor'),
					'id_company'	=> $this->input->post('company'),
					'po_date'		=> $date[2].'-'.$date[1].'-'.$date[0],
					'type'			=> $this->input->post('type'),
					'note'			=> $this->input->post('note'),
					'ppn'			=> $this->input->post('ppns'),
				);

				$update = $this->M_all->update( 'po_customer', $set, 'id = '.$this->input->post('id'));
				if($update)
				{
					$result  = 'success';
					$message = 'Berhasil menyunting data';

				} else {

					$result  = 'error';
					$message = 'Gagal menyunting data';
				}

			} else {

				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function get_item()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$select = $this->M_all->left_join('po_item', 'po_item.*, setting.value', array('po_customer ON po_item.id_fk = po_customer.id','setting ON po_customer.type = setting.id'), ' WHERE po_item.id = '.$this->input->post('id') );
				if($select->num_rows() > 0)
				{
					foreach($select->result() as $row){
						$this->mysqli_data['value'][] = array(
							'detail'    => $row->detail,
				          	'size'     	=> $row->size,
				          	'price_1'  	=> str_replace('.', ',', $row->price_1),
				          	'price_2'  	=> str_replace('.', ',', $row->price_2),
				          	'qty'  		=> $row->qty,
				          	'unit'		=> $row->unit,
				          	'merk'  	=> $row->merk,
				          	'type'  	=> $row->type,
				          	'core'  	=> $row->core,
				          	'gulungan'  => $row->gulungan,
				          	'bahan'		=> $row->bahan,
			        	);

			        	$obj = json_decode($row->value);
	            		$input = $obj->{'input'};
			        }

			        $this->mysqli_data['input'][] = array(
	        			'attribute' => $input
	        		);

			        $result  = 'success';
					$message = 'query success';

				} else {
					$result  = 'error';
					$message = 'query error';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function edit_item()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$set = array(
					'detail'		=> $this->input->post('detail'),
					'size'			=> $this->input->post('size'),
					'price_1'		=> $this->priceFilter($this->input->post('price_1')),
					'price_2'		=> $this->priceFilter($this->input->post('price_2')),
					'qty'			=> $this->input->post('qty'),
					'unit'			=> $this->input->post('unit'),
					'merk'			=> $this->input->post('merk'),
					'type'			=> $this->input->post('type'),
					'core'			=> $this->input->post('core'),
					'gulungan'		=> $this->input->post('gulungan'),
					'bahan'			=> $this->input->post('bahan')
				);

				$update = $this->M_all->update( 'po_item', $set, 'id = '.$this->input->post('id'));
				if($update)
				{
					$result  = 'success';
					$message = 'Berhasil menyunting data';

				} else {

					$result  = 'error';
					$message = 'Gagal menyunting data';
				}

			} else {

				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function get_print_po()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$select = $this->M_all->left_join('vendor', 'vendor.vendor, vendor.address, po_customer.po_date, po_customer.nopo, po_customer.note, po_customer.ppn, po_item.detail, po_item.size, po_item.price_1, po_item.price_2, po_item.qty, po_item.unit, po_item.merk, po_item.type, po_item.core, po_item.gulungan, po_item.bahan, setting.isi, setting.value', array('po_customer ON vendor.id = po_customer.id_vendor','po_item ON po_customer.id = po_item.id_fk','setting ON po_customer.type = setting.id'), ' WHERE po_item.hidden = 0 AND po_customer.id = '.$this->input->post('id') );
				if($select->num_rows() > 0)
				{
					foreach($select->result() as $row){
						$this->mysqli_data['value'][] = array(
							'vendor'	=> $row->vendor,
		  					'address'	=> $row->address,
		  					'po_date'	=> $row->po_date,
		  					'po_vendor'	=> $row->nopo,
		  					'po_type'	=> strtoupper($row->isi),
		  					'note'		=> $row->note,
		  					'ppn'		=> $row->ppn,
		  					"detail"    => $row->detail,
				          	"size"     	=> $row->size,
				          	"price_1"  	=> $row->price_1,
				          	"price_2"  	=> $row->price_2,
				          	"qty"  		=> $row->qty,
				          	"unit"		=> $row->unit,
				          	"merk"  	=> $row->merk,
				          	"type"  	=> $row->type,
				          	"core"  	=> $row->core,
				          	"gulungan"  => $row->gulungan,
				          	"bahan"		=> $row->bahan,
		  					'ttd'		=> 'Iskandar Zulkarnain'//$_SESSION->name,
			        	);

			        	$obj = json_decode($row->value);
	            		$input = $obj->{'input'};
			        }

			        $this->mysqli_data['input'][] = array(
	        			'attribute' => $input
	        		);

			        $result  = 'success';
					$message = 'query success';

				} else {
					$result  = 'error';
					$message = 'query error';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function print_po()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$array = array();
				$select = $this->M_all->left_join('vendor', 'vendor.vendor, vendor.address, po_customer.po_date, po_customer.nopo, po_customer.note, po_customer.ppn, po_item.detail, po_item.size, po_item.price_1, po_item.price_2, po_item.qty, po_item.unit, po_item.item_to, po_item.merk, po_item.type, po_item.core, po_item.gulungan, po_item.bahan, setting.isi, setting.value, company.company, company.address AS alamat, company.email, company.phone, company.logo', array('po_customer ON vendor.id = po_customer.id_vendor','po_item ON po_customer.id = po_item.id_fk','setting ON po_customer.type = setting.id', 'company ON po_customer.id_company = company.id'), ' WHERE po_item.hidden = 0 AND po_customer.id = '.$this->input->post('id') );
				if($select->num_rows() > 0)
				{
					foreach($select->result() as $row)
					{
						$subtotal = ($row->price_2 > 0)? $row->price_2*$row->qty : $row->price_1*$row->qty;
						$tax = ($row->ppn > 0)? $subtotal/10 : 0;
						$total = $subtotal + $tax;
						$obj = json_decode($row->value);

						$array['vendor']	= $row->vendor;
		  				$array['address']	= $row->address;
		  				$array['po_date']	= date("d F Y", strtotime($row->po_date));
		  				$array['nopo']		= $row->nopo;
		  				$array['note']		= $row->note;
		  				$array['ppn']		= $row->ppn;
		  				$array['detail'][]	= $row->detail;
		  				$array['size'][]	= $row->size;
		  				$array['price_1'][]	= $row->price_1;
		  				$array['price_2'][]	= $row->price_2;
		  				$array['qty'][]		= $row->qty;
		  				$array['unit'][]	= $row->unit;
		  				$array['ttd']		= $this->input->post('tanda_tangan');
		  				$array['item_to'][]	= $row->item_to;
		  				$array['tgl']		= date('d F Y');
		  				$array['ppn']		= $row->ppn;
		  				$array['subtotal'][]= $subtotal;
		  				$array['tax'][]		= $tax;
		  				$array['total'][]	= $total;
		  				$array['company']	= strtoupper($row->company);
		  				$array['alamat']	= $row->alamat;
		  				$array['email']		= $row->email;
		  				$array['phone']		= $row->phone;
		  				$array['logo']		= $row->logo;
		  				$array['print']		= $obj->{'print'};
			        }

			        $this->mysqli_data['value'][] = array(
			        	'vendor'		=> $array['vendor'],
						'address'		=> $array['address'],
						'po_date'		=> $array['po_date'],
						'nopo'			=> $array['nopo'],
						'note'			=> $array['note'],
						'ppn'			=> $array['ppn'],
						'detail'		=> $array['detail'],
						'size'			=> $array['size'],
						'price_1'		=> $array['price_1'],
						'price_2'		=> $array['price_2'],
						'qty'			=> $array['qty'],
						'unit'			=> $array['unit'],
						'ttd'			=> $array['ttd'],
						'item_to'		=> $array['item_to'],
						'tgl'			=> $array['tgl'],
						'ppn'			=> $array['ppn'],
						'ttl_price_item'=> $array['subtotal'],
						'subtotal'		=> array_sum($array['subtotal']),
						'tax'			=> array_sum($array['tax']),
						'total'			=> array_sum($array['total']),
						'company'		=> $array['company'],
						'alamat'		=> $array['alamat'],
						'email'			=> $array['email'],
						'phone'			=> $array['phone'],
						'logo'			=> $array['logo'],
			        );

			        $this->mysqli_data['print'][] = array(
	        			'attribute' => $array['print']
	        		);

			        $result  = 'success';
					$message = 'query success';

				} else {
					$result  = 'error';
					$message = 'query error';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function delete_po()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$update = $this->M_all->update('po_item', array('hidden' => 1), 'id = '.$this->input->post('id'));
				if($update)
				{
					$result  = 'success';
					$message = 'Berhasil menghapus data';

				} else {
					$result  = 'error';
					$message = 'Gagal menghapus data';
				}
				
			} else {

				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function sales_order()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$this->load->model('M_sales_order');
			$data = array();
			$array = array();
			$arrays = array();
			$list = $this->M_sales_order->get_datatables();
			foreach ($list as $k => $column)
			{
				$functions = "<div class='row text-center'>";
				if($this->session->userdata('role') == '5' || $this->session->userdata('id') !== $column->input_by)
				{
					$functions  .= 'Not Allowed';

				} else {

					$functions .= '<button type="button" class="btn btn-flat btn-success UbahCustomer" data-id="'.$column->id_customer.'-'.$column->id_fk.'"><i class="fa fa-user-o" aria-hidden="true"></i></button><button type="button" class="btn btn-flat btn-warning UbahItem" data-id="'.$column->id_item.'"><i class="fa fa-cube" aria-hidden="true"></i></button>';
					
					$functions .= !in_array($column->id_fk, $arrays)? '<button type="button" class="btn btn-flat btn-primary ongkirs" data-id="'.$column->id_fk.'"><i class="fa fa-truck"></i></button>' : '';
					
					if($this->session->userdata('role') == '1')
					{
						$functions .= '<button type="button" class="btn btn-flat btn-danger HapusItem" data-id="'.$column->id_fk.'-'.$column->item_to.'" data-name="'.$column->item.'"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
					}
				}

				$functions .= "</div'>";

				$no_so = explode('/', $column->no_so);
				$ex_sources = explode("|", $column->sources);
				if($ex_sources[0] == 1){
		    		$sources = 'Internal';
		    	} elseif($ex_sources[0] == 2){
		    		$sources = 'SUBCONT ('.$ex_sources[1].', '.date("d/m/Y", strtotime($ex_sources[2])).')';
		    	} elseif($ex_sources[0] == 3){
		    		$sources = 'IN STOCK ('.$ex_sources[1].' '.$column->unit.')';
		    	} else {
		    		$sources = '';
		    	}

				$row = array();
				$row[] = date("d/m/Y", strtotime($column->po_date));
				$row[] = $column->customer;
				$row[] = date('d/m/Y', strtotime($column->po_date. '+16 day'));
				$row[] = $column->company;
				$row[] = ($column->order_grade > 0)? 'Spesial' : 'Reguler';
				$row[] = $column->po_customer;
				$row[] = $no_so[0].'/'.$no_so[1].$no_so[2];
				$row[] = $column->item;
				$row[] = $column->isi;
				$row[] = $column->size;
				$row[] = $column->merk;
				$row[] = $column->type;
				$row[] = $column->uk_bahan_baku;
				$row[] = $column->qty;
				$row[] = $column->unit;
				$row[] = $column->qore;
				$row[] = $column->lin;
				$row[] = $column->qty_bahan_baku;
				$row[] = $column->roll;
				$row[] = $column->ingredient;
				$row[] = ($column->porporasi == '1')? 'YA' : 'TIDAK';
				$row[] = $column->volume;
				$row[] = $column->price;
				$row[] = $column->temp_ETD;
				$row[] = ($column->ppn > 0)? $column->temp_ETD/10 : 0;
				$row[] = ($column->ppn > 0)? $column->temp_ETD + ($column->temp_ETD/10) : $column->temp_ETD ;
				$row[] = $column->annotation;
				$row[] = $sources;
				$row[] = in_array($column->id_fk.$column->id_sj.$column->total_ongkir, $array)? '0' : $column->total_ongkir;
				$row[] = $column->name;
				$row[] = $functions;
				$data[] = $row;
				$array[] = $column->id_fk.$column->id_sj.$column->total_ongkir;
				$arrays[] = $column->id_fk;
        	}
 
		    $output = array(
		        "draw" => $_POST['draw'],
		        "recordsTotal" => $this->M_sales_order->count_all(),
		        "recordsFiltered" => $this->M_sales_order->count_filtered(),
		        "data" => $data,
		    );

		    header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function add_so()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$id_customer = $this->input->post('id_customer');
			$id_company = $this->input->post('company');
			$post = $this->input->post('data');
			$item_to = 0;
			$urutSPK = array();
			$total = 0;

			if($id_customer > 0 && $id_company > 0)
			{
				$leftjoin = array( 'workorder_item ON preorder_customer.id_fk = workorder_item.id_fk' );
				$get = $this->M_all->left_join('preorder_customer', 'preorder_customer.id_fk, workorder_item.no_so', $leftjoin, 'ORDER BY preorder_customer.id DESC, workorder_item.id LIMIT 1')->result();
				$no_so = explode('/', $get[0]->no_so);
				$po_date = explode('/', empty($this->input->post('po_date')) ? date('d/m/Y') : $this->input->post('po_date'));
				if(empty($get[0]->id_fk) || date('ym') > $no_so[1])
				{
					$data_1 = array(
						'id_fk'			=> $get[0]->id_fk + 1,
						'id_company' 	=> $id_company,
						'id_customer' 	=> $id_customer,
						'customer' 		=> $this->input->post('customers'),
						'order_grade' 	=> $this->input->post('order_grade'),
						'po_date'		=> $po_date[2].'-'.$po_date[1].'-'.$po_date[0],
						'po_customer'	=> $this->input->post('po_customer'),
						'input_by'  	=> $this->session->userdata('id')
					);

					$insert_1 = $this->M_all->insert_id('preorder_customer', $data_1);
					if( $insert_1 > 0 )
					{
						foreach($post['item'] as $key => $value)
						{
							$item_to = $item_to + 1;
							$price_filter1 = str_replace('.', '', $post['price'][$key]);
							$price_filter2 = str_replace(",", ".", $price_filter1);
							$unit = empty($post['unit'][$key]) ? '' : $post['unit'][$key];
							$qty = empty($post['qty'][$key]) || !is_numeric($post['qty'][$key]) ? 0 : $post['qty'][$key];
							$volume = empty($post['volume'][$key]) || !is_numeric($post['volume'][$key]) ? 0 : $post['volume'][$key];
							if($unit === "PCS" && $qty > 0 && $volume > 0) {$total = $qty/$volume;}
							if($unit === "ROLL" && $qty > 0 && $volume > 0){$total = $qty*$volume;}
							if(!empty($post['sources'][$key]) AND $post['sources'][$key] == '3')
							{
								$sources = $post['sources'][$key]."|".$post['etc1'][$key];

							} elseif(!empty($post['sources'][$key]) AND $post['sources'][$key] == '2') {
								$etc2 = explode('/', empty($post['etc2'][$key]) ? date('d/m/Y') : $post['etc2'][$key]);
								$sources = $post['sources'][$key]."|".str_replace("|", '-', $post['etc1'][$key])."|".$etc2[2].'-'.$etc2[1].'-'.$etc2[0];
							
							} else {
								$sources = $post['sources'][$key];
							}

							$data_2[] = array(
								'id_fk'		=> $get[0]->id_fk + 1,
								'item_to'	=> $item_to,
								'detail'	=> empty($post['detail'][$key]) ? '' : $post['detail'][$key],
								'item'		=> empty($post['item'][$key]) ? '' : $post['item'][$key],
								'size'		=> empty($post['size'][$key]) ? '' : $post['size'][$key],
								'price'		=> empty($post['price'][$key]) ? '' : $price_filter2,
								'qty'		=> $qty,
								'unit'		=> $unit,
								'input_by'	=> $this->session->userdata('id')
							);

							$data_3[] = array(
								'id_fk'		=> $get[0]->id_fk + 1,
								'item_to'	=> $item_to,
								'detail'	=> empty($post['detail'][$key]) ? '' : $post['detail'][$key],
								'no_so'		=> 'WSO/'.date('ym').'/00'.$item_to,
								'item'		=> empty($post['item'][$key]) ? '' : $post['item'][$key],
								'size'		=> empty($post['size'][$key]) ? '' : $post['size'][$key],
								'unit'		=> $unit,
								'qore'		=> empty($post['qore'][$key]) ? '' : $post['qore'][$key],
								'lin'		=> empty($post['lin'][$key]) ? '' : $post['lin'][$key],
								'roll'		=> empty($post['roll'][$key]) ? '' : $post['roll'][$key],
								'ingredient'=> empty($post['ingredient'][$key]) ? '' : $post['ingredient'][$key],
								'qty'		=> $qty,
								'volume'	=> $volume,
								'total'		=> $total,
								'annotation'=> empty($post['annotation'][$key]) ? '' : $post['annotation'][$key],
								'porporasi'	=> empty($post['porporasi'][$key]) ? '' : $post['porporasi'][$key],
								'uk_bahan_baku' => empty($post['uk_bahan_baku'][$key]) ? '' : $post['uk_bahan_baku'][$key],
								'qty_bahan_baku' => empty($post['qty_bahan_baku'][$key]) ? '' : $post['qty_bahan_baku'][$key],
								'sources'	=> $sources,
								'merk'		=> empty($post['merk'][$key]) ? '' : $post['merk'][$key],
								'type'		=> empty($post['type'][$key]) ? '' : $post['type'][$key]
							);

							$data_6[] = array(
								'id_fk'		=> $get[0]->id_fk + 1,
								'item_to'	=> $item_to,
								'order_status' => 0
							);
						}

						$insert_2 = $this->M_all->bulk_insert_id('preorder_item', $data_2);
						if( $insert_2 > 0 )
						{
							$insert_3 = $this->M_all->bulk_insert_id('workorder_item', $data_3);
							if( $insert_3 > 0 )
							{
								$data_4 = array(
									'id_fk'	=> $get[0]->id_fk + 1,
									'ppn'	=> ( $this->input->post('ppns') > 0 )? '1' : '0'
								);

								$insert_4 = $this->M_all->insert_id('preorder_price', $data_4);
								if( $insert_4 > 0 )
								{

									$data_5 = array(
										'id_fk'			=> $get[0]->id_fk + 1,
										'po_date'		=> $po_date[2].'-'.$po_date[1].'-'.$po_date[0],
										'po_customer'	=> $this->input->post('po_customer'),
										'customer'		=> $this->input->post('customers'),
										'input_by'		=> $this->session->userdata('id')
									);

									$insert_5 = $this->M_all->insert_id('workorder_customer', $data_5);
									if( $insert_5 > 0 )
									{
										$insert_6 = $this->M_all->bulk_insert_id('status', $data_6);
										if( $insert_6 > 0 )
										{
											$result  = 'success';
											$message = 'Berhasil memasukan data';

										} else {
											$result  = 'error';
											$message = 'Gagal memasukan data status';
										}

									} else {
										$result  = 'error';
										$message = 'Gagal memasukan data sales order price';
									}

								} else {
									$result  = 'error';
									$message = 'Gagal memasukan data sales order price';
								}

							} else {
								$result  = 'error';
								$message = 'Gagal memasukan data workorder item';
							}

						} else {
							$result  = 'error';
							$message = 'Gagal memasukan data sales order item';
						}

					} else {
						$result  = 'error';
						$message = 'Gagal memasukan data sales order customer';
					}

				} else {

					for($i = $no_so[2]; $i<=999; $i++) $urutSPK[] = str_pad($i, 3, "0", STR_PAD_LEFT);
					$data_1 = array(
						'id_fk'			=> $get[0]->id_fk + 1,
						'id_company' 	=> $id_company,
						'id_customer' 	=> $id_customer,
						'customer' 		=> $this->input->post('customers'),
						'order_grade' 	=> $this->input->post('order_grade'),
						'po_date'		=> $po_date[2].'-'.$po_date[1].'-'.$po_date[0],
						'po_customer'	=> $this->input->post('po_customer'),
						'input_by'  	=> $this->session->userdata('id')
					);

					$insert_1 = $this->M_all->insert_id('preorder_customer', $data_1);
					if( $insert_1 > 0 )
					{
						foreach($post['item'] as $key => $value)
						{
							$item_to = $item_to + 1;
							$price_filter1 = str_replace('.', '', $post['price'][$key]);
							$price_filter2 = str_replace(",", ".", $price_filter1);
							$unit = empty($post['unit'][$key]) ? '' : $post['unit'][$key];
							$qty = empty($post['qty'][$key])? '' : $post['qty'][$key];
							$volume = empty($post['volume'][$key]) || !is_numeric($post['volume'][$key]) ? 0 : $post['volume'][$key];
							if($unit === "PCS" && $qty > 0 && $volume > 0) {$total = $qty/$volume;}
							if($unit === "ROLL" && $qty > 0 && $volume > 0){$total = $qty*$volume;}
							if(!empty($post['sources'][$key]) AND $post['sources'][$key] == '3')
							{
								$sources = $post['sources'][$key]."|".$post['etc1'][$key];

							} elseif(!empty($post['sources'][$key]) AND $post['sources'][$key] == '2') {

								$etc2 = explode('/', empty($post['etc2'][$key]) ? date('d/m/Y') : $post['etc2'][$key]);
								$sources = $post['sources'][$key]."|".str_replace("|", '-', $post['etc1'][$key])."|".$etc2[2].'-'.$etc2[1].'-'.$etc2[0];
							
							} else {
								$sources = $post['sources'][$key];
							}

							$data_2[] = array(
								'id_fk'		=> $get[0]->id_fk + 1,
								'item_to'	=> $item_to,
								'detail'	=> empty($post['detail'][$key]) ? '' : $post['detail'][$key],
								'item'		=> empty($post['item'][$key]) ? '' : $post['item'][$key],
								'size'		=> empty($post['size'][$key]) ? '' : $post['size'][$key],
								'price'		=> empty($post['price'][$key]) ? '' : $price_filter2,
								'qty'		=> $qty,
								'unit'		=> $unit,
								'input_by'	=> $this->session->userdata('id')
							);

							$data_3[] = array(
								'id_fk'		=> $get[0]->id_fk + 1,
								'item_to'	=> $item_to,
								'detail'	=> empty($post['detail'][$key]) ? '' : $post['detail'][$key],
								'no_so'		=> 'WSO/'.date('ym').'/00'.$item_to,
								'item'		=> empty($post['item'][$key]) ? '' : $post['item'][$key],
								'size'		=> empty($post['size'][$key]) ? '' : $post['size'][$key],
								'unit'		=> $unit,
								'qore'		=> empty($post['qore'][$key]) ? '' : $post['qore'][$key],
								'lin'		=> empty($post['lin'][$key]) ? '' : $post['lin'][$key],
								'roll'		=> empty($post['roll'][$key]) ? '' : $post['roll'][$key],
								'ingredient'=> empty($post['ingredient'][$key]) ? '' : $post['ingredient'][$key],
								'qty'		=> $qty,
								'volume'	=> $volume,
								'total'		=> $total,
								'annotation'=> empty($post['annotation'][$key]) ? '' : $post['annotation'][$key],
								'porporasi'	=> empty($post['porporasi'][$key]) ? '' : $post['porporasi'][$key],
								'uk_bahan_baku' => empty($post['uk_bahan_baku'][$key]) ? '' : $post['uk_bahan_baku'][$key],
								'qty_bahan_baku' => empty($post['qty_bahan_baku'][$key]) ? '' : $post['qty_bahan_baku'][$key],
								'sources'	=> $sources,
								'merk'		=> empty($post['merk'][$key]) ? '' : $post['merk'][$key],
								'type'		=> empty($post['type'][$key]) ? '' : $post['type'][$key]
							);

							$data_6[] = array(
								'id_fk'		=> $get[0]->id_fk + 1,
								'item_to'	=> $item_to,
								'order_status' => 0
							);
						}

						$insert_2 = $this->M_all->bulk_insert_id('preorder_item', $data_2);
						if( $insert_2 > 0 )
						{
							$insert_3 = $this->M_all->bulk_insert_id('workorder_item', $data_3);
							if( $insert_3 > 0 )
							{
								$data_4 = array(
									'id_fk'	=> $get[0]->id_fk + 1,
									'ppn'	=> ( $this->input->post('ppns') > 0 )? '1' : '0'
								);

								$insert_4 = $this->M_all->insert_id('preorder_price', $data_4);
								if( $insert_4 > 0 )
								{
									$data_5 = array(
										'id_fk'			=> $get[0]->id_fk + 1,
										'po_date'		=> $po_date[2].'-'.$po_date[1].'-'.$po_date[0],
										'po_customer'	=> $this->input->post('po_customer'),
										'customer'		=> $this->input->post('customers'),
										'input_by'		=> $this->session->userdata('id')
									);

									$insert_5 = $this->M_all->insert_id('workorder_customer', $data_5);
									if( $insert_5 > 0 )
									{
										$insert_6 = $this->M_all->bulk_insert_id('status', $data_6);
										if( $insert_6 > 0 )
										{
											$result  = 'success';
											$message = 'Berhasil memasukan data';

										} else {
											$result  = 'error';
											$message = 'Gagal memasukan data status';
										}

									} else {
										$result  = 'error';
										$message = 'Gagal memasukan data sales order price';
									}

								} else {
									$result  = 'error';
									$message = 'Gagal memasukan data sales order price';
								}

							} else {
								$result  = 'error';
								$message = 'Gagal memasukan data workorder item';
							}

						} else {
							$result  = 'error';
							$message = 'Gagal memasukan data sales order item';
						}

					} else {
						$result  = 'error';
						$message = 'Gagal memasukan data sales order customer';
					}
				}

			} else {
				$result  = 'error';
				$message = 'Invalid id customer, please refresh page';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function get_so()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$leftjoin = array(
					'preorder_price ON preorder_customer.id_fk = preorder_price.id_fk',
					'company ON preorder_customer.id_company = company.id'
				);
				$select = $this->M_all->left_join('preorder_customer','preorder_customer.*,preorder_price.*,company.id AS id_company, company.company, company.address', $leftjoin, ' WHERE preorder_customer.id = '.$this->input->post('id'));
				if($select->num_rows() > 0)
				{
					foreach($select->result() as $row){
						$this->mysqli_data[] = array(
							'id_company' => $row->id_company,
							'id_customer'=> $row->id_customer,
							'company'    => $row->company,
							'address'    => $row->address,
							'customer'   => $row->customer,
							'order_grade'=> $row->order_grade,
							'po_date'    => date("d/m/Y", strtotime($row->po_date)),
							'po_customer'=> $row->po_customer,
							'ppn'		 => $row->ppn
			        	);
			        }

			        $result  = 'success';
					$message = 'query success';

				} else {
					$result  = 'error';
					$message = 'query error';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  => $result,
				"message" => $message,
				"data"	=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function edit_so()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')) || !empty($this->input->post('fk')))
			{
				$date = explode('/', $this->input->post('po_date'));
				$set_1 = array(
					'id_company'	=> $this->input->post('company'),
					'id_customer'	=> $this->input->post('id_customer'),
					'customer'		=> $this->input->post('customers'),
					'po_date'		=> $date[2].'-'.$date[1].'-'.$date[0],
					'po_customer'	=> $this->input->post('po_customer'),
					'order_grade'	=> $this->input->post('order_grade'),
				);

				$set_2 = array(
					'customer'		=> $this->input->post('customers'),
					'po_date'		=> $date[2].'-'.$date[1].'-'.$date[0],
					'po_customer'	=> $this->input->post('po_customer'),
				);

				$set_3 = array(
					'ppn'			=> $this->input->post('ppns'),
				);

				$update1 = $this->M_all->update( 'preorder_customer', $set_1, 'id = '.$this->input->post('id'));
				if($update1)
				{
					$update2 = $this->M_all->update( 'workorder_customer', $set_2, 'id_fk = '.$this->input->post('fk'));
					if($update2)
					{
						$update3 = $this->M_all->update( 'preorder_price', $set_3, 'id_fk = '.$this->input->post('fk'));
						if($update3)
						{
							$result  = 'success';
							$message = 'Berhasil menyunting data';

						} else {
							$result  = 'error';
							$message = 'Gagal menyunting data harga';
						}

					} else {
						$result  = 'error';
						$message = 'Gagal menyunting data workorder customer';
					}

				} else {
					$result  = 'error';
					$message = 'Gagal menyunting data sales order customer';
				}

			} else {

				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function get_so_item()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$leftjoin = array(
					'workorder_item ON preorder_item.id_fk = workorder_item.id_fk AND preorder_item.item_to = workorder_item.item_to'
				);
				$select = $this->M_all->left_join('preorder_item','preorder_item.*, workorder_item.id AS id_wo, workorder_item.qore, workorder_item.lin, workorder_item.roll, workorder_item.ingredient, workorder_item.volume, workorder_item.annotation, workorder_item.porporasi, workorder_item.uk_bahan_baku, workorder_item.qty_bahan_baku, workorder_item.sources, workorder_item.detail, workorder_item.type, workorder_item.merk', $leftjoin, ' WHERE preorder_item.id = '.$this->input->post('id'));
				if($select->num_rows() > 0)
				{
					foreach($select->result() as $row)
					{
						$price_filter = str_replace('.', ',', $row->price);
						$this->mysqli_data[] = array(
							'id_wo'    	=> $row->id_wo,
		          			'item'    	=> $row->item,
				          	'size'     	=> $row->size,
				          	'uk_bahan_baku' => $row->uk_bahan_baku,
				          	'qore'     	=> $row->qore,
				          	'lin'     	=> $row->lin,
				          	'qty_bahan_baku' => $row->qty_bahan_baku,
				          	'roll'     	=> $row->roll,
				          	'ingredient'=> $row->ingredient,
				          	'unit'		=> $row->unit,
				          	'volume'	=> $row->volume,
				          	'annotation'=> $row->annotation,
				          	'price' 	=> $price_filter,
				          	'qty'  		=> $row->qty,
				          	'unit'  	=> $row->unit,
				          	'sources'  	=> $row->sources,
				          	'porporasi' => $row->porporasi,
				          	'detail' 	=> $row->detail,
				          	'merk' 		=> $row->merk,
				          	'type' 		=> $row->type,
			        	);
			        }

			        $result  = 'success';
					$message = 'query success';

				} else {
					$result  = 'error';
					$message = 'query error';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  => $result,
				"message" => $message,
				"data"	=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function edit_so_item()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$price_filter = str_replace('.', '', $this->input->post('price'));
				$price = str_replace(",", ".", $price_filter);
				$unit = empty($this->input->post('unit'))? '' : $this->input->post('unit');
				$qty = empty($this->input->post('qty')) || !is_numeric($this->input->post('qty')) ? 0 : $this->input->post('qty');
				$volume = empty($this->input->post('volume')) || !is_numeric($this->input->post('volume')) ? 0 : $this->input->post('volume');
				if($unit === "PCS" && $qty > 0 && $volume > 0) $total = $qty/$volume;
				if($unit === "ROLL" && $qty > 0 && $volume > 0) $total = $qty*$volume;
				if(!empty($this->input->post('sources')) AND $this->input->post('sources') == '3')
				{
					$sources = $this->input->post('sources')."|".$this->input->post('etc1');
				
				} elseif(!empty($this->input->post('sources')) AND $this->input->post('sources') == '2') {
					$etc2 = explode('/', empty($this->input->post('etc2')) ? date('d/m/Y') : $this->input->post('etc2'));
					$sources = $this->input->post('sources')."|".$this->input->post('etc1')."|".$etc2[2].'-'.$etc2[1].'-'.$etc2[0];

				} else {
					$sources = $this->input->post('sources');
				}

				$set_1 = array(
					'item'	=> $this->input->post('item'),
					'size'	=> $this->input->post('size'),
					'qty'	=> $this->input->post('qty'),
					'unit'	=> $this->input->post('unit'),
					'price'	=> $price,
				);

				$set_2 = array(
					'item'		=> $this->input->post('item'),
					'detail'	=> $this->input->post('detail'),
					'merk'		=> $this->input->post('merk'),
					'type'		=> $this->input->post('type'),
					'size'		=> $this->input->post('size'),
					'unit'		=> $this->input->post('unit'),
					'qty'		=> $this->input->post('qty'),
					'volume'	=> $this->input->post('volume'),
					'uk_bahan_baku'	=> $this->input->post('uk_bahan_baku'),
					'qty_bahan_baku'=> $this->input->post('qty_bahan_baku'),
					'qore'		=> $this->input->post('qore'),
					'lin'		=> $this->input->post('lin'),
					'roll'		=> $this->input->post('roll'),
					'ingredient'=> $this->input->post('ingredient'),
					'annotation'=> $this->input->post('annotation'),
					'porporasi'	=> $this->input->post('porporasi'),
					'sources'	=> $sources,
				);

				$update1 = $this->M_all->update( 'preorder_item', $set_1, 'id = '.$this->input->post('id'));
				if($update1)
				{
					$update2 = $this->M_all->update( 'workorder_item', $set_2, 'id = '.$this->input->post('id_wo'));
					if($update2)
					{
						$result  = 'success';
						$message = 'Berhasil menyunting data';

						$select = $this->M_all->left_join('preorder_item', 'preorder_item.id_fk, preorder_item.item_to, sum(delivery_orders_item.send_qty) AS total_send_qty', array('delivery_orders_item ON delivery_orders_item.id_fk = preorder_item.id_fk AND delivery_orders_item.item_to = preorder_item.item_to'), ' WHERE preorder_item.id = '.$this->input->post('id'))->result();

						if(empty($select[0]->total_send_qty))
						{
							$set = array('order_status' => '0');
							$this->M_all->update('status', $set, 'id_fk = '.$select[0]->id_fk.' AND item_to = '.$select[0]->item_to);
						
						} elseif( $this->input->post('qty') > $select[0]->total_send_qty) {
							$set = array('order_status' => '2');
							$this->M_all->update('status', $set, 'id_fk = '.$select[0]->id_fk.' AND item_to = '.$select[0]->item_to);

						} else {
							$set = array('order_status' => '1');
							$this->M_all->update('status', $set, 'id_fk = '.$select[0]->id_fk.' AND item_to = '.$select[0]->item_to);
						}

					} else {
						$result  = 'error';
						$message = 'Gagal menyunting data workorder customer';
					}

				} else {
					$result  = 'error';
					$message = 'Gagal menyunting data sales order customer';
				}

			} else {

				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function get_ongkir()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$leftjoin = array(
					'delivery_orders_item ON delivery_orders_customer.id_fk = delivery_orders_item.id_fk AND delivery_orders_customer.id_sj = delivery_orders_item.id_sj'
				);

				$select = $this->M_all->left_join('delivery_orders_customer','delivery_orders_customer.id, delivery_orders_customer.courier, delivery_orders_customer.no_tracking, delivery_orders_customer.cost, delivery_orders_customer.ekspedisi, delivery_orders_customer.uom, delivery_orders_customer.jml, delivery_orders_item.no_delivery, delivery_orders_item.send_qty', $leftjoin, ' WHERE delivery_orders_customer.id_fk = '.$this->input->post('id').' GROUP BY delivery_orders_item.no_delivery ORDER BY delivery_orders_item.no_delivery ASC');

				if($select->num_rows() > 0)
				{
					foreach($select->result() as $row)
					{
						if($row->send_qty > 0)
						{
							$no_delivery = empty($row->no_delivery)? '' : 'SJ: '.$row->no_delivery;
			  				$courier = empty($row->courier)? '' : ' - Kurir: '.$row->courier;
			  				$no_tracking = empty($row->no_tracking)? '' : ' - No Tracking: '.$row->no_tracking;
			  				$cost = empty($row->cost)? ' - Ongkir: 0' : ' - Ongkir: '.$row->cost;

							$this->mysqli_data[] = array(
								'detail'	=> $no_delivery.$courier.$no_tracking.$cost,
			  					'cost'		=> $row->cost,
			  					'id'		=> $row->id,
			  					'ekspedisi'	=> $row->ekspedisi,
			  					'uom'		=> $row->uom,
			  					'jml'		=> $row->jml,
				        	);
						}
			        }

			        $result  = 'success';
					$message = 'query success';

				} else {
					$result  = 'error';
					$message = 'No item delivered';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  => $result,
				"message" => $message,
				"data"	=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function edit_ongkir()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('surat_jalan')) && is_numeric($this->input->post('surat_jalan')))
			{
				$set = array(
					'cost'		=> str_replace('.', '', $this->input->post('ongkos_kirim')),
					'ekspedisi'	=> $this->input->post('ekspedisi'),
					'uom'		=> $this->input->post('uom'),
					'jml'		=> $this->input->post('jml'),
				);

				$update = $this->M_all->update( 'delivery_orders_customer', $set, 'id = '.$this->input->post('surat_jalan'));
				if($update)
				{
					$result  = 'success';
					$message = 'Berhasil menyunting data';

				} else {
					$result  = 'error';
					$message = 'Gagal menyunting data ongkos kirim';
				}

			} else {

				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function delete_so()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')) && is_numeric($this->input->post('id')))
			{
				$set = array( 'hidden' => 1 );

				$update = $this->M_all->update( 'status', $set, 'id_fk = '.$this->input->post('id').' AND item_to = '.$this->input->post('item'));
				if($update)
				{
					$result  = 'success';
					$message = 'Berhasil menghapus data';

				} else {
					$result  = 'error';
					$message = 'Gagal menghapus data ongkos kirim';
				}

			} else {

				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function workorder()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$this->load->model('M_workorder');
			$list = $this->M_workorder->get_datatables();
			foreach ($list as $k => $column)
			{
				$functions = "<div class='row text-center'>";
				if($this->session->userdata('role') == '5' /*|| $this->session->userdata('id') !== $column->input_by */) {
					$functions  .= 'Not Allowed';
				} else {
					$functions .= '<button type="button" class="btn btn-flat btn-success UbahCustomer" data-id="'.$column->id_customer.'-'.$column->item_to.'"><i class="fa fa-user-o" aria-hidden="true"></i></button><button type="button" class="btn btn-flat btn-warning printWO" data-id="'.$column->id_customer.'-'.$column->item_to.'"><i class="fa fa-print" aria-hidden="true"></i></button>';
				}
				$functions .= "</div'>";

				$no_so = explode('/', $column->no_so);
				$sources = explode('|', $column->sources);
				$spk_date = explode('-', $column->spk_date);
				$duration = explode('-', $column->duration);

				if($sources[0] == 1){
					$source = 'Internal';
				} elseif($sources[0] == 2){
					$source = 'SUBCONT ('.$sources[1].', '.date("d/m/Y", strtotime($sources[2])).')';
				} elseif($sources[0] == 3){
					$source = 'IN STOCK ('.$sources[1].' '.$column->unit.')';
				}

				if($column->order_status == '0'){
					$order_status = 'PO baru dibuat';
				} elseif($column->order_status == '1' || $column->order_status == '2'){
					$order_status = 'Delivery';
    			} elseif($column->order_status == '3'){
      				$order_status = 'Packing';
    			} elseif($column->order_status == '4'){
      				$order_status = 'Cetak SPK';
    			} elseif($column->order_status == '5'){
      				$order_status = 'Pembuatan Pisau';
    			} elseif($column->order_status == '6'){
     			 	$order_status = 'Antri Sliting';
    			} elseif($column->order_status == '7'){
      				$order_status = 'Antri Cetak';
    			}


				$row = array();
				$row[] = ($column->spk_date == '0000-00-00')? '' : $spk_date[2].'/'.$spk_date[1].'/'.$spk_date[0];
				$row[] = ($column->spk_date == '0000-00-00')? '' : $duration[2].'/'.$duration[1].'/'.$duration[0];
				$row[] = $column->customer;
				$row[] = $column->po_customer;
				$row[] = $no_so[0].'/'.$no_so[1].$no_so[2];
				$row[] = $column->item;
				$row[] = $column->size;
				$row[] = $column->qore;
				$row[] = $column->lin;
				$row[] = $column->roll;
				$row[] = $column->ingredient;
				$row[] = ($column->porporasi == '1')? 'YA' : 'TIDAK';
				$row[] = $column->qty;
				$row[] = $column->unit;
				$row[] = $column->volume;
				$row[] = $column->annotation;
				$row[] = $column->uk_bahan_baku;
				$row[] = $column->qty_bahan_baku;
				$row[] = $source;
				$row[] = $order_status;
				$row[] = $column->name;
				$row[] = $functions;
				$data[] = $row;
        	}
 
		    $output = array(
		        "draw" => $_POST['draw'],
		        "recordsTotal" => $this->M_workorder->count_all(),
		        "recordsFiltered" => $this->M_workorder->count_filtered(),
		        "data" => $data,
		    );

			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function get_wo()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$leftjoin = array(
					'status ON workorder_customer.id_fk = status.id_fk',
					'workorder_item ON workorder_item.id_fk = workorder_customer.id_fk'
				);

				$select = $this->M_all->left_join('workorder_customer','workorder_customer.*, status.order_status, status.item_to, workorder_item.no_so', $leftjoin, ' WHERE workorder_customer.id = '.$this->input->post('id').' AND status.item_to = '.$this->input->post('item_to').' AND workorder_item.item_to = '.$this->input->post('item_to'));

				if($select->num_rows() > 0)
				{
					foreach($select->result() as $row)
					{
						$no_so = explode('/', $row->no_so);
						$po_date = explode('-', $row->po_date);
						$spk_date = explode('-', $row->spk_date);
						$this->mysqli_data[] = array(
							'po_date'       => $po_date[2].'/'.$po_date[1].'/'.$po_date[0],
            				'spk_date'      => ($row->spk_date === '0000-00-00')? date('d/m/Y') : $spk_date[2].'/'.$spk_date[1].'/'.$spk_date[0],
            				'no_spk'        => $no_so[0].'/'.$no_so[1].$no_so[2],
            				'po_customer'   => $row->po_customer,
            				'customer'      => $row->customer,
            				'order_status'  => $row->order_status
            			);
			        }

			        $result  = 'success';
					$message = 'query success';

				} else {
					$result  = 'error';
					$message = 'Item not found';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  => $result,
				"message" => $message,
				"data"	=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function edit_wo()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')) && is_numeric($this->input->post('id')) && !empty($this->input->post('item_to')) && is_numeric($this->input->post('item_to')))
			{

				$select = $this->M_all->select('workorder_customer', 'id_fk', 'id = '.$this->input->post('id'));
				if( $select->num_rows() > 0 )
				{
					$row = $select->result();
					$spk_date = explode('/', $this->input->post('spk_date'));

					$set_1 = array(
						
						'spk_date'	=> $spk_date[2].'-'.$spk_date[1].'-'.$spk_date[0],
						'duration'	=> date('Y-m-d', strtotime($spk_date[2].'-'.$spk_date[1].'-'.$spk_date[0]. '+16 day')),
						'input_by'	=> $this->session->userdata('id'),
					);

					$set_2 = array( 'order_status' => $this->input->post('order_status'));

					$update = $this->M_all->update( 'workorder_customer', $set_1, 'id = '.$this->input->post('id'));
					if($update)
					{
						$result  = 'success';
						$message = 'Berhasil menyunting data';
						$this->M_all->update( 'status', $set_2, 'id_fk = '.$row[0]->id_fk.' AND item_to = '.$this->input->post('item_to'));

					} else {
						$result  = 'error';
						$message = 'Gagal menyunting data ongkos kirim';
					}

				} else {
					$result  = 'error';
					$message = 'ID missing';
				}

			} else {

				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function get_print_wo()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$leftjoin = array(
					'workorder_item ON workorder_customer.id_fk = workorder_item.id_fk',
					'status ON workorder_customer.id_fk = status.id_fk AND workorder_item.item_to = status.item_to'
				);

				$select = $this->M_all->left_join('workorder_customer','workorder_customer.*, workorder_item.*, status.item_to', $leftjoin, ' WHERE status.hidden = 0 AND workorder_customer.id = '.$this->input->post('id').' AND workorder_item.item_to = '.$this->input->post('item_to'));

				if($select->num_rows() > 0)
				{
					foreach($select->result() as $row)
					{
						$no_spk = explode('/', $row->no_so);
						$sources = explode("|", $row->sources);
						$spk_date = explode('-', $row->spk_date);
						if($sources[0] == 3)
						{
							if($row->unit == 'PCS')
							{
								if($sources[1] >= $row->qty){
									$total = 0;
									$qty = 0;
									$isi = 0;

								} else {
									$qty = $row->qty - $sources[1];
									$isi = $row->volume;
									$total = round($qty/$isi, 1);
								}

							} else if($row->unit == 'ROLL') {
								
								if($sources[1] >= $row->qty)
								{
									$qty = 0;
									$total = 0;
									$isi = 0;
								} else {
									$qty = $row->qty - $sources[1];
									$isi = $row->volume;
									$total = $qty * $isi;
								}

							} else {
								$qty = $row->qty;
								$isi = $row->volume;
								$total = $row->total;
							}

						} else {
							$qty = $row->qty;
							$total = $row->total;
							$isi = $row->volume;
						}

						$this->mysqli_data[] = array(
							'spk_date'    => $spk_date[2].'/'.$spk_date[1].'/'.$spk_date[0],
				            'customer'    => $row->customer,
				            'no_spk'      => $no_spk[0].'/'.$no_spk[1].$no_spk[2],
				            'size_label'  => $row->size,
				            'unit'        => $row->unit,
				            'total'       => $total,
				            'kor'         => $row->qore,
				            'line'        => $row->lin,
				            'gulungan'    => $row->roll,
				            'bahan'       => $row->ingredient,
				            'qty_produksi'=> $qty,
				            'isi'         => $isi,
				            'annotation'  => $row->annotation,
				            'po_customer' => $row->po_customer,
				            'size_baku'   => $row->uk_bahan_baku,
				            'qty_baku'    => $row->qty_bahan_baku,
				            'porporasi'   => ($row->porporasi > 0)? 'TIDAK' : 'YA',
            			);
			        }

			        $result  = 'success';
					$message = 'query success';

				} else {
					$result  = 'error';
					$message = 'Item not found';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  => $result,
				"message" => $message,
				"data"	=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function print_wo()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$spk_date = explode('/', $this->input->post('tgl'));
			$this->mysqli_data[] = array(
				'spk_date'        => date("d F Y", strtotime($spk_date[2].'-'.$spk_date[1].'-'.$spk_date[0])),
		        'customer'        => $this->input->post('custom'),
		        'no_spk'          => $this->input->post('nospk'),
		        'annotation'      => $this->input->post('keterangan'),
		        'size_label'      => $this->input->post('size_label'),
		        'size_baku'       => $this->input->post('size_baku'),
		        'bahan'           => $this->input->post('bahan'),
		        'gulungan'        => $this->input->post('gulungan'),
		        'kor'             => $this->input->post('kor'),
		        'line'            => $this->input->post('lins'),
		        'porporasi'       => $this->input->post('porporasi'),
		        'qty_baku'        => $this->input->post('qty_baku'),
		        'qty_produksi'    => $this->input->post('qty_produksi'),
		        'isi'             => $this->input->post('isi'),
		        'ttd'             => $this->input->post('ttd'),
		        'po_customer'     => $this->input->post('pcus'),
			);

			$output = array(
				"result"  => 'success',
				"message" => 'Berhasil mencetak',
				"data"	=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function do_waiting()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$this->load->model('M_do_waiting');
			$data = array();
			$list = $this->M_do_waiting->get_datatables();
			foreach ($list as $k => $column)
			{
				$spk_date = explode('-', $column->spk_date);
				$duration = explode('-', $column->duration);
				$functions = "<div class='row text-center'>";
				if($this->session->userdata('role') == '5' || $this->session->userdata('id') !== $column->input_by)
				{
					$functions  .= 'Not Allowed';

				} else {

					$functions .= '<button type="button" class="btn btn-flat btn-success prosesDO" data-id="'.$column->id.'-'.$column->id_fk.'"><i class="fa fa-share-square-o" aria-hidden="true"></i></button>';
				}

				$functions .= "</div'>";

				$row = array();
				$row[] = $spk_date[2].'/'.$spk_date[1].'/'.$spk_date[0];
				$row[] = $column->customer;
				$row[] = $column->po_customer;
				$row[] = $column->no_so;
				$row[] = $duration[2].'/'.$duration[1].'/'.$duration[0];
				$row[] = $functions;
				$data[] = $row;
        	}
 
		    $output = array(
		        "draw" => $_POST['draw'],
		        "recordsTotal" => $this->M_do_waiting->count_all(),
		        "recordsFiltered" => $this->M_do_waiting->count_filtered(),
		        "data" => $data,
		    );

		    header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function get_do_waiting()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$leftjoin = array(
					'workorder_item ON workorder_customer.id_fk = workorder_item.id_fk',
					'status ON workorder_customer.id_fk = status.id_fk AND workorder_item.item_to = status.item_to',
					'(SELECT delivery_orders_item.id_fk, delivery_orders_item.item_to, sum(delivery_orders_item.send_qty) AS total_send_qty FROM workorder_customer LEFT JOIN delivery_orders_item ON workorder_customer.id_fk = delivery_orders_item.id_fk WHERE workorder_customer.id = "'.$this->input->post('id').'" GROUP BY delivery_orders_item.item_to) AS d ON workorder_customer.id_fk = d.id_fk AND workorder_item.item_to = d.item_to',
					'(SELECT id_fk, shipto FROM delivery_orders_customer ORDER BY id DESC LIMIT 1) AS e ON workorder_customer.id_fk = e.id_fk',
					'preorder_customer ON preorder_customer.id_fk = workorder_customer.id_fk',
					'customer ON customer.id = preorder_customer.id_customer'
				);

				$select = $this->M_all->left_join('workorder_customer','workorder_customer.*, workorder_item.no_so, workorder_item.item, workorder_item.unit, workorder_item.qty, status.item_to, status.order_status, d.total_send_qty, e.shipto, customer.alamat, customer.kota, customer.provinsi, customer.negara, customer.kodepos, customer.s_alamat, customer.s_kota, customer.s_provinsi, customer.s_negara, customer.s_kodepos', $leftjoin, ' WHERE status.hidden = 0 AND workorder_customer.id = "'.$this->input->post('id').'" AND status.order_status BETWEEN 2 AND 3');

				if($select->num_rows() > 0)
				{
					foreach($select->result() as $row)
					{
						$spk_date = explode('-', $row->spk_date);
						if(empty($row->shipto))
						{
							$s_alamat = empty($row->s_alamat)? $row->alamat.'. ' : $row->s_alamat.'. ';
							$s_kota = empty($row->s_kota)? $row->kota.' - ' : $row->s_kota.' - ';
							$s_provinsi = empty($row->s_provinsi)? $row->provinsi.', ' : $row->s_provinsi.', ';
							$s_negara = empty($row->s_negara)? $row->negara.'. ' : $row->s_negara.'. ';
							$s_kodepos = empty($row->s_kodepos)? $row->kodepos : $row->s_kodepos;
							$shipto = $s_alamat.$s_kota.$s_provinsi.$s_negara.$s_kodepos;

						} else {

							$shipto = $row->shipto;
						}

						if(!empty($row->total_send_qty))
						{
							if($row->total_send_qty > $row->qty){
								$total_send_qty = '0';
							} else {
								$total_send_qty = $row->qty - $row->total_send_qty;
							}

						} else {
							$total_send_qty = $row->qty;
						}

						$no_so = explode('/', $row->no_so);
						$this->mysqli_data[] = array(
							'spk_date'		=> $spk_date[2].'/'.$spk_date[1].'/'.$spk_date[0],
				  			'customer'		=> $row->customer,
				  			'po_customer'	=> $row->po_customer,
				  			'no_so'			=> $no_so[0]."/".$no_so[1].$no_so[2],
				  			'item'			=> $row->item,
				  			'unit'			=> $row->unit,
				  			'req_qty'		=> $total_send_qty,
				  			'item_to'		=> $row->item_to,
				  			'shipto'		=> $shipto,
            			);
			        }

			        $result  = 'success';
					$message = 'query success';

				} else {
					$result  = 'error';
					$message = 'Item not found';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  => $result,
				"message" => $message,
				"data"	=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function no_sj()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$urutSJ = array();
			$get_id = $this->M_all->get_id('delivery_orders_customer', 'id_sj, sj_date', 'id DESC');
			$sj_date = explode('-', $get_id->sj_date);

			if(empty($get_id->id_sj) || date('y') > date('y', strtotime($sj_date[0])))
			{
	      		$this->mysqli_data[] = array( 'no_sj' => date('y').'000001' );
	      	
	      	} else {

	      		$get = $this->M_all->get_id('delivery_orders_item', 'no_delivery', 'id DESC');
				$nosj = explode('/', $get->no_delivery);
				$substr = substr($nosj[0], 2);
				$index = $substr + 1;
				for($i = $index; $i<=999999; $i++){
					$urutSJ[] = str_pad($i, 6, "0", STR_PAD_LEFT);
				}

	      		$this->mysqli_data[] = array( 'no_sj' => date('y').$urutSJ[0] );
	      	}

	      	$output = array(
				"result"  => 'success',
				"message" => 'Berhasil membuat nomor surat jalan',
				"data"	=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function add_do_waiting()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$data_item = array();
				$post = $this->input->post('data');
				$id = explode('-', $this->input->post('id'));
				$tanggal = explode('/', $this->input->post('tanggal'));
				$select = $this->M_all->select('delivery_orders_customer', 'id_sj', "id_fk = '".$id[1]."' ORDER BY id DESC LIMIT 1");
				$id_sj = $select->result();
				//Data delivery_orders_customer
				if( $select->num_rows() > 0 )
				{
					$data_customer = array(
						'id_fk'		=> $id[1],
						'id_sj'		=> $id_sj[0]->id_sj + 1,
						'sj_date'	=> $tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0],
						'shipto'	=> $this->input->post('shipto'),
						'courier'	=> $this->input->post('nama_kurir'),
						'no_tracking' => $this->input->post('no_resi'),
						'input_by'	=> $this->session->userdata('id')
					);
				
				} else {

					$data_customer = array(
						'id_fk'		=> $id[1],
						'id_sj'		=> 1,
						'sj_date'	=> $tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0],
						'shipto'	=> $this->input->post('shipto'),
						'courier'	=> $this->input->post('nama_kurir'),
						'no_tracking' => $this->input->post('no_resi'),
						'input_by'	=> $this->session->userdata('id')
					);
				}

				foreach($post['item_to'] as $key => $val)
				{
					$item_to = empty($post['item_to'][$key])? '' : $post['item_to'][$key];
					$qty = empty($post['qty'][$key])? '0' : $post['qty'][$key];

					//Data delivery_orders_item
					if( $select->num_rows() > 0 )
					{
						$data_item[] = array(
							'id_fk' 	=> $id[1],
							'id_sj'		=> $id_sj[0]->id_sj + 1,
							'item_to'	=> $item_to,
							'no_delivery' => $this->input->post('no_sj'),
							'send_qty'	=> $qty
						);

					} else {

						$data_item[] = array(
							'id_fk' 	=> $id[1],
							'id_sj'		=> 1,
							'item_to'	=> $item_to,
							'no_delivery' => $this->input->post('no_sj'),
							'send_qty'	=> $qty
						);
					}

					$put = $this->M_all->left_join('preorder_item', 'preorder_item.qty AS req_qty, sum(delivery_orders_item.send_qty) AS send_qty', array('delivery_orders_item ON preorder_item.id_fk = delivery_orders_item.id_fk AND preorder_item.item_to = delivery_orders_item.item_to'),  ' WHERE preorder_item.id_fk = '.$id[1].' AND preorder_item.item_to = '.$item_to)->result();

					if( ( $put[0]->send_qty + $qty ) >= $put[0]->req_qty )
					{
						$this->M_all->update('status', array( 'order_status' => '1' ), 'id_fk = '.$id[1].' AND item_to = '.$item_to);
					} else {

						$this->M_all->update('status', array( 'order_status' => '2' ), 'id_fk = '.$id[1].' AND item_to = '.$item_to);
					}
				}

				$insert_1 = $this->M_all->insert_id( 'delivery_orders_customer', $data_customer );
				if( $insert_1 )
				{
					$insert_2 = $this->M_all->bulk_insert('delivery_orders_item', $data_item);
					if($insert_2)
					{
						$result  = 'success';
						$message = 'Berhasil memasukkan data';

					} else {
						$result  = 'error';
						$message = 'Gagal memasukkan data delivery orders customer';
						$this->M_all->delete('delivery_orders_customer', array( 'id' => $insert_1));
					}

				} else {
					$result  = 'error';
					$message = 'Gagal memasukkan data delivery orders customer';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  => $result,
				"message" => $message,
				"data"	=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

		public function do_delivery()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$this->load->model('M_do_delivery');
			$data = array();
			$list = $this->M_do_delivery->get_datatables();
			foreach ($list as $k => $column)
			{
				$sj_date = explode("-", $column->sj_date);
				$no_so = explode("/", $column->no_so);
				$functions = "<div class='row text-center'>";
				if($this->session->userdata('role') == '5' /*|| $this->session->userdata('id') !== $column->input_by*/)
				{
					$functions  .= 'Not Allowed';

				} else {

					$functions .= '<button type="button" class="btn btn-flat btn-primary PrintView" data-id="'.$column->id.'-'.$column->id_fk.'-'.$column->id_sj.'"><i class="fa fa-print"></i></button>';

					if($this->session->userdata('role') == '1')
					{
						$functions .= '<button type="button" class="btn btn-flat btn-danger HapusItem" data-id="'.$column->id.'-'.$column->id_fk.'-'.$column->item_to.'-'.$column->id_sj.'" data-name="'.$column->item.'"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
					}
				}

				$functions .= "</div'>";
				
				$row = array();
				$row[] = $sj_date[2].'/'.$sj_date[1].'/'.$sj_date[0];
				$row[] = $column->no_delivery;
				$row[] = $column->customer;
				$row[] = $column->po_customer;
				$row[] = $no_so[0]."/".$no_so[1].$no_so[2];
				$row[] = $column->shipto;
				$row[] = $column->item;
				$row[] = $column->send_qty;
				$row[] = $column->unit;
				$row[] = $column->courier;
				$row[] = $column->no_tracking;
				$row[] = $column->cost;
				$row[] = $column->name;
				$row[] = $functions;
				$data[] = $row;
				
        	}
 
		    $output = array(
		        "draw" => $_POST['draw'],
		        "recordsTotal" => $this->M_do_delivery->count_all(),
		        "recordsFiltered" => $this->M_do_delivery->count_filtered(),
		        "data" => $data,
		    );

		    header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function get_print_do()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')) && !empty($this->input->post('id_fk')) &&  !empty($this->input->post('id_sj')) )
			{
				$leftjoin = array(
					'delivery_orders_customer ON delivery_orders_item.id_fk = delivery_orders_customer.id_fk AND delivery_orders_item.id_sj = delivery_orders_customer.id_sj',
					'workorder_customer ON delivery_orders_item.id_fk = workorder_customer.id_fk',
					'workorder_item ON delivery_orders_item.id_fk = workorder_item.id_fk AND delivery_orders_item.item_to = workorder_item.item_to'
				);

				$select = $this->M_all->left_join('delivery_orders_item','delivery_orders_item.no_delivery, delivery_orders_item.send_qty, delivery_orders_customer.shipto, delivery_orders_customer.sj_date, workorder_customer.customer, workorder_customer.po_customer, workorder_item.item, workorder_item.unit, workorder_item.ingredient, workorder_item.size, workorder_item.volume', $leftjoin, ' WHERE delivery_orders_item.id_fk = "'.$this->input->post('id_fk').'" AND delivery_orders_item.id_sj = "'.$this->input->post('id_sj').'"');

				if($select->num_rows() > 0)
				{
					$count = array();
					foreach($select->result() as $row)
					{
						if($row->send_qty > 0)
						{
							$count[] = 1;
							$sj_date = explode("-", $row->sj_date);
							$this->mysqli_data[] = array(
								'customer'    => $row->customer,
								'sj_date'     => $sj_date[2].'/'.$sj_date[1].'/'.$sj_date[0],
								'shipto'      => $row->shipto,
								'no_delivery' => $row->no_delivery,
								'po_customer' => $row->po_customer,
								'item'        => strtoupper($row->item),
								'qty'         => $row->send_qty,
								'unit'        => $row->unit
							);
						}
			        }

			        if(array_sum($count) > 0)
					{
						$result  = 'success';
						$message = 'query success';

					} else {

						$result  = 'error';
						$message = 'Item not found';
					}

				} else {
					$result  = 'error';
					$message = 'Item not found';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  => $result,
				"message" => $message,
				"data"	=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function print_do()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')) && !empty($this->input->post('id_fk')) && !empty($this->input->post('id_sj')) )
			{
				$leftjoin = array(
					'delivery_orders_item ON delivery_orders_item.id_fk = workorder_item.id_fk AND delivery_orders_item.item_to = workorder_item.item_to',
					'preorder_customer ON preorder_customer.id_fk = "'.$this->input->post('id_fk').'"',
					'company ON company.id = preorder_customer.id_company'
				);

				$select = $this->M_all->left_join('workorder_item','workorder_item.no_so, workorder_item.item, workorder_item.unit, workorder_item.ingredient, workorder_item.size, workorder_item.volume, delivery_orders_item.send_qty, company.company, company.address, company.logo, company.phone', $leftjoin, ' WHERE workorder_item.id_fk = "'.$this->input->post('id_fk').'" AND delivery_orders_item.id_sj = "'.$this->input->post('id_sj').'" GROUP BY workorder_item.id');

				if($select->num_rows() > 0)
				{
					$no = 1;
					$count = array();
					foreach($select->result() as $row)
					{
						if( $row->send_qty > 0 )
						{
							$count[] = 1;
							$sj_date = explode('/', $this->input->post('sj_date'));
							$this->mysqli_data[] = array(
								'no'        => $no++,
					            'item'      => strtoupper($row->item),
					            'qty'       => $row->send_qty,
					            'unit'      => $row->unit,
					            'sj_date'     => date("d F Y", strtotime($sj_date[2].'-'.$sj_date[1].'-'.$sj_date[0])),
					            'po_customer' => $this->input->post('no_po_pratinjau'),
					            'no_delivery' => $this->input->post('no_delivery'),
					            'customer'    => $this->input->post('custom'),
					            'shipto'    => str_replace("\\r\\n"," ", $this->input->post('shipto')),
					            'ttd'       => $this->input->post('ttd'),
					            'no_so'     => $row->no_so,
					            'company'   => strtoupper($row->company),
					            'address'   => $row->address,
					            'phone'     => $row->phone,
					            'logo'      => $row->logo,
							);
						}
					}

					if(array_sum($count) > 0)
					{
						$result  = 'success';
						$message = 'Berhasil mencetak';

					} else {

						$result  = 'error';
						$message = 'Item not found';
					}

				} else {
					$result  = 'error';
					$message = 'Item not found';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  => $result,
				"message" => $message,
				"data"	=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function delete_do()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')) && !empty($this->input->post('id_fk')) && !empty($this->input->post('item_to')) && !empty($this->input->post('id_sj')) )
			{
				$select_1 = $this->M_all->select('delivery_orders_item', 'id', 'id_fk = '.$this->input->post('id_fk').' AND item_to = '.$this->input->post('item_to'));

				$select_2 = $this->M_all->select('delivery_orders_item', 'id', 'id_fk = '.$this->input->post('id_fk').' AND id_sj = '.$this->input->post('id_sj'));
				
				if($select_1->num_rows() < 2)
				{
					$delete = $this->M_all->delete('delivery_orders_item', array('id' => $this->input->post('id')));

					if($delete)
					{
						$result  = 'success';
						$message = 'Berhasil menghapus data';

						$this->M_all->update('status', array('order_status' => 3), 'id_fk = "'.$this->input->post('id_fk').'" AND item_to = "'.$this->input->post('item_to').'"' );

						if($select_2->num_rows() < 2)
						{
							$this->M_all->delete('delivery_orders_customer', array(
								'id_fk' => $this->input->post('id_fk'),
								'id_sj' => $this->input->post('id_sj')
							));

							$this->M_all->delete('invoice', array(
								'id_fk' => $this->input->post('id_fk'),
								'id_sj' => $this->input->post('id_sj')
							));
						}

					} else {
						$result  = 'error';
						$message = 'Gagal menghapus status order';
					}

				} else {

					$delete = $this->M_all->delete('delivery_orders_item', array('id' => $this->input->post('id')));

					if($delete)
					{
						$result  = 'success';
						$message = 'Berhasil menghapus data';

						$this->M_all->update('status', array('order_status' => 2), 'id_fk = "'.$this->input->post('id_fk').'" AND item_to = "'.$this->input->post('item_to').'"' );

						if($select_2->num_rows() < 2)
						{
							$this->M_all->delete('delivery_orders_customer', array(
								'id_fk' => $this->input->post('id_fk'),
								'id_sj' => $this->input->post('id_sj')
							));

							$this->M_all->delete('invoice', array(
								'id_fk' => $this->input->post('id_fk'),
								'id_sj' => $this->input->post('id_sj')
							));
						}

					} else {
						$result  = 'error';
						$message = 'Gagal menghapus status order';
					}
				}

			} else {

				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function invoice_waiting()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$this->load->model('M_invoice_waiting');
			$data = array();
			$data_cost = array();
			$data_idx = array();
			$list = $this->M_invoice_waiting->get_datatables();
			foreach ($list as $k => $column)
			{
				$functions = "<div class='row text-center'>";
				if($this->session->userdata('role') == '5' || $this->session->userdata('id') !== $column->input_by)
				{
					$functions  .= 'Not Allowed';

				} else {
					
					if(!in_array($column->id_fk.'-'.$column->id_sj, $data_idx)){
						$functions .= '<button type="button" class="btn btn-flat btn-success single_invoice" data-id="'.$column->id_fk.'-'.$column->id_sj.'" data-name="'.$column->no_delivery.'"><i class="fa fa-share-square-o" aria-hidden="true"></i></button>';
					}
				}
				$functions .= "</div'>";

				$sj_date = empty($column->sj_date)? explode('-', '---') : explode('-', $column->sj_date);
				$no_so = explode('/', $column->no_so);
				$biaya_kirim = !in_array($column->id_fk.'-'.$column->id_sj.'-'.$column->cost, $data_cost)? $column->cost : '';

				$row = array();
				$row[] = $column->id_fk.'-'.$column->id_sj;
	            $row[] = empty(array_filter($sj_date))? '' : $sj_date[2].'/'.$sj_date[1].'/'.$sj_date[0];
	            $row[] = $column->customer;
	            $row[] = $column->po_customer;
	            $row[] = $no_so[0].'/'.$no_so[1].$no_so[2];
	            $row[] = $column->no_delivery;
	            $row[] = $column->send_qty;
	            $row[] = $column->unit;
	            $row[] = $column->price;
	            $row[] = $column->send_qty * $column->price;
	            $row[] = ($column->ppn > 0)? ($column->send_qty * $column->price)/10 : 0;
	            $row[] = ($column->ppn > 0)? ($column->send_qty * $column->price) + (($column->send_qty * $column->price)/10) : ($column->send_qty * $column->price);
	            $row[] = $biaya_kirim;
				$row[] = $functions;
				$data[] = $row;

				$data_idx[] = $column->id_fk.'-'.$column->id_sj;
				$data_cost[] = $column->id_fk.'-'.$column->id_sj.'-'.$column->cost;
        	}
 
		    $output = array(
		        "draw" => $_POST['draw'],
		        "recordsTotal" => $this->M_invoice_waiting->count_all(),
		        "recordsFiltered" => $this->M_invoice_waiting->count_filtered(),
		        "data" => $data,
		    );

		    header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function create_single_invoice()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$id_fk = $this->input->post('id_fk');
			$id_sj = $this->input->post('id_sj');
			$date = explode('/', $this->input->post('date'));

			if(!empty($id_fk) && !empty($id_sj) && !empty($this->input->post('date')))
			{
				$get = $this->M_all->get_id('invoice', 'invoice_date, no_invoice', 'id DESC');
				$invoice_date = empty($get->invoice_date)? '0000-00-00' : explode('-', $get->invoice_date);
				$invoice = empty($get->invoice)? '000/' : explode('/', $get->no_invoice);

				if(empty($get->no_invoice) || empty($get->invoice_date) || date('y') > $invoice_date[0])
				{
					$no_invoice = date('y').'000001';

				} else {
					$substr = substr($invoice[0], 2);
					$index = $substr + 1;
					for($i = $index; $i<=999999; $i++) $urutInvoice[] = str_pad($i, 6, "0", STR_PAD_LEFT);
					$no_invoice = date('y').$urutInvoice[0];
				}

				$data = array(
					'id_fk' 	=> $id_fk,
					'id_sj'		=> $id_sj,
					'no_invoice'=> $no_invoice,
					'invoice_date' => $date[2].'-'.$date[1].'-'.$date[0],
					'duration'	=> date('Y-m-d', strtotime($date[2].'-'.$date[1].'-'.$date[0]. '+30 day')),
					'input_by'	=> $this->session->userdata('id')
				);

				$insert = $this->M_all->insert('invoice', $data);
				if($insert)
				{
					$result  = 'success';
					$message = 'Berhasil memasukan data';
				
				} else {
					$result  = 'error';
					$message = 'Gagal memasukan data';
				}

			} else {

				$result  = 'error';
				$message = 'Mohon untuk mengisi semua kolom yang disediakan';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}


	public function create_multi_invoice()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$id = $this->input->post('id');
			$date = explode('/', $this->input->post('date'));

			if(!empty($id) && !empty($this->input->post('date')))
			{
				$array = array();
				$data = array();
				$get = $this->M_all->get_id('invoice', 'invoice_date, no_invoice', 'id DESC');
				$invoice_date = empty($get->invoice_date)? '0000-00-00' : explode('-', $get->invoice_date);
				$invoice = empty($get->invoice)? '000/' : explode('/', $get->no_invoice);
				$idx = explode(',', $id);

				if(empty($get->no_invoice) || empty($get->invoice_date) || date('y') > $invoice_date[0])
				{
					$no_invoice = date('y').'000001';

				} else {
					$substr = substr($invoice[0], 2);
					$index = $substr + 1;
					for($i = $index; $i<=999999; $i++) $urutInvoice[] = str_pad($i, 6, "0", STR_PAD_LEFT);
					$no_invoice = date('y').$urutInvoice[0];
				}

				foreach($idx as $val){$ex = explode('-', $val); $array[] = $ex[0];}
				if(count(array_unique($array)) > 1)
				{
					$result  = 'error';
					$message = 'Gagal berbeda customer';
				
				} else {

					foreach(array_unique($idx) as $k => $v)
					{
						$ex_id = explode("-", $v);
						$data[] = array(
							'id_fk' 	=> $ex_id[0],
							'id_sj'		=> $ex_id[1],
							'no_invoice'=> $no_invoice,
							'invoice_date' => $date[2].'-'.$date[1].'-'.$date[0],
							'duration'	=> date('Y-m-d', strtotime($date[2].'-'.$date[1].'-'.$date[0]. '+30 day')),
							'input_by'	=> $this->session->userdata('id')
						);
					}

					$insert = $this->M_all->bulk_insert('invoice', $data);
					if($insert)
					{
						$result  = 'success';
						$message = 'Berhasil memasukan data';
					
					} else {
						$result  = 'error';
						$message = 'Gagal memasukan data';
					}
				}

			} else {

				$result  = 'error';
				$message = 'Mohon untuk mengisi semua kolom yang disediakan';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function invoice_procces()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$this->load->model('M_invoice_procces');
			$data = array();
			$data_invoice = array();
			$data_cost = array();
			$list = $this->M_invoice_procces->get_datatables();
			foreach ($list as $k => $column)
			{
				$functions = "<div class='row text-center'>";
				if($this->session->userdata('role') == '5' || $this->session->userdata('id') !== $column->input_by)
				{
					$functions  .= 'Not Allowed';

				} else {

					if(!in_array($column->no_invoice, $data_invoice))
					{
						$functions .= '<button type="button" class="btn btn-flat btn-primary PrintView" data-id="'.$column->id.'"><i class="fa fa-print"></i></button>';

						$functions .= '<button type="button" class="btn btn-flat btn-success complete" data-id="'.$column->id.'" data-name="'.$column->no_invoice.'"><i class="fa fa-check-circle-o" aria-hidden="true"></i></button>';

						if($this->session->userdata('role') == '1')
						{
							$functions .= '<button type="button" class="btn btn-flat btn-danger HapusItem" data-id="'.$column->id.'" data-name="'.$column->no_invoice.'"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
						}
					}
				}
				$functions .= "</div'>";

				$invoice_date = empty($column->invoice_date)? explode('-', '---') : explode('-', $column->invoice_date);
				$duration = empty($column->duration)? explode('-', '---') : explode('-', $column->duration);
				$no_so = explode('/', $column->no_so);
				$biaya_kirim = !in_array($column->id_fk.'-'.$column->id_sj.'-'.$column->cost, $data_cost)? $column->cost : '';

				if(!in_array($column->no_invoice, $data_invoice))
				{
					if($column->print == '1'){ $print = 'SUDAH'; } else { $print = 'BELUM'; }
					$name = $column->name;
				} else {
					$print = ''; $name = '';
				}

				$row = array();
	            $row[] = empty(array_filter($invoice_date))? '' : $invoice_date[2].'/'.$invoice_date[1].'/'.$invoice_date[0];
	            $row[] = empty(array_filter($duration))? '' : $duration[2].'/'.$duration[1].'/'.$duration[0];
	            $row[] = $column->customer;
	            $row[] = $column->po_customer;
	            $row[] = $no_so[0].'/'.$no_so[1].$no_so[2];
	            $row[] = $column->no_delivery;
	            $row[] = $column->no_invoice;
	            $row[] = $column->send_qty;
	            $row[] = $column->unit;
	            $row[] = $column->price;
	            $row[] = $column->send_qty * $column->price;
	            $row[] = ($column->ppn > 0)? ($column->send_qty * $column->price)/10 : 0;
	            $row[] = ($column->ppn > 0)? ($column->send_qty * $column->price) + (($column->send_qty * $column->price)/10) : ($column->send_qty * $column->price);
	            $row[] = $biaya_kirim;
	            $row[] = $column->ekspedisi;
	            $row[] = $column->uom;
	            $row[] = $column->jml;
	            $row[] = $print;
	            $row[] = $name;
				$row[] = $functions;
				$data[] = $row;

				$data_cost[] = $column->id_fk.'-'.$column->id_sj.'-'.$column->cost;
				$data_invoice[] = $column->no_invoice;
        	}
 
		    $output = array(
		        "draw" => $_POST['draw'],
		        "recordsTotal" => $this->M_invoice_procces->count_all(),
		        "recordsFiltered" => $this->M_invoice_procces->count_filtered(),
		        "data" => $data,
		    );

		    header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

		public function get_print_invoice_procces()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$select_1 = $this->M_all->select('invoice', 'no_invoice', 'id = '.$this->input->post('id'));

				if($select_1->num_rows() > 0)
				{
					$data_cost = array();
					$array = array();
					$invoice = $select_1->result();

					$leftjoin = array(
						'delivery_orders_item ON delivery_orders_item.id_fk = invoice.id_fk AND delivery_orders_item.id_sj = invoice.id_sj',
						'preorder_item ON preorder_item.id_fk = invoice.id_fk AND preorder_item.item_to = delivery_orders_item.item_to',
						'preorder_customer ON preorder_customer.id_fk = invoice.id_fk',
						'preorder_price ON preorder_price.id_fk = invoice.id_fk',
						'delivery_orders_customer ON delivery_orders_customer.id_fk = delivery_orders_item.id_fk AND delivery_orders_customer.id_sj = delivery_orders_item.id_sj',
						'workorder_item ON workorder_item.id_fk = invoice.id_fk AND workorder_item.item_to = delivery_orders_item.item_to',
						'customer ON customer.id = preorder_customer.id_customer',
						'company ON company.id = preorder_customer.id_company',
						'status ON delivery_orders_item.id_fk = status.id_fk AND delivery_orders_item.item_to = status.item_to'
					);

					$select = $this->M_all->left_join('invoice', 'invoice.id_fk, invoice.no_invoice, invoice.input_by, invoice.invoice_date, delivery_orders_item.id_sj, delivery_orders_item.item_to, delivery_orders_item.no_delivery, delivery_orders_item.send_qty, preorder_item.item, preorder_item.unit, preorder_item.price, preorder_item.price, preorder_customer.customer, preorder_customer.po_customer, preorder_price.ppn, delivery_orders_customer.shipto, delivery_orders_customer.cost, workorder_item.no_so, workorder_item.ingredient, workorder_item.size, workorder_item.volume, customer.alamat, customer.kota, customer.negara, customer.provinsi, customer.kodepos, customer.telp, customer.s_nama, customer.s_alamat, customer.s_kota, customer.s_negara, customer.s_provinsi, customer.s_kodepos, company.company, company.address, company.phone', $leftjoin, ' WHERE status.hidden = 0 AND invoice.no_invoice = "'.$invoice[0]->no_invoice.'" ORDER BY delivery_orders_item.no_delivery ASC' );

					if($select->num_rows() > 0)
					{
						foreach($select->result() as $row)
						{
							$no_so = explode('/', $row->no_so);
							$alamat = empty($row->alamat)? '' : $row->alamat.'. ';
							$kota = empty($row->kota)? '' : $row->kota.'. ';
							$prov = empty($row->provinsi)? '' : $row->provinsi.'. ';
							$neg = empty($row->negara)? '' : $row->negara.'. ';
							$pos = empty($row->kodepos)? '' : $row->kodepos.'. ';
							$invoice_date = explode('-', $row->invoice_date);
							
							$biaya_kirim = !in_array($row->id_fk.'-'.$row->id_sj.'-'.$row->cost, $data_cost)? $row->cost : '';

							if($row->send_qty > 0)
							{
								$array['id_fk']        = $row->id_fk;
					            $array['company']      = $row->company;
					            $array['address']      = $row->address;
					            $array['phone']        = $row->phone;
					            $array['customer']     = $row->customer;
					            $array['billto']       = $alamat.$kota.$prov.$neg.$pos;
					            $array['shipto']       = $row->shipto;
					            $array['ship_name']    = empty($row->s_nama)? $row->customer : $row->s_nama;
					            $array['no_po']        = $row->po_customer;
					            $array['no_sj'][]      = $row->no_delivery;
					            $array['no_invoice']   = $row->no_invoice;
					            $array['item'][]       = strtoupper($row->item);
					            $array['send_qty'][]   = $row->send_qty;
					            $array['unit'][]       = $row->unit;
					            $array['tagihan'][]    = ($row->ppn > 0)? ($row->send_qty * $row->price) + (($row->send_qty * $row->price)/10) : ($row->send_qty * $row->price);
					            $array['biaya_kirim'][]= $biaya_kirim;
					            $array['telp']         = $row->telp;
					            $array['price'][]      = $row->price;
					            $array['ppn'][]        = $row->send_qty * $row->price;
					            $array['no_so'][]      = $no_so[0]."/".$no_so[1].$no_so[2];
					            $array['invoice_date'] = $invoice_date[2].'/'.$invoice_date[1].'/'.$invoice_date[0];
							}

							$data_cost[] = $row->id_fk.'-'.$row->id_sj.'-'.$row->cost;
				        }

				        $this->mysqli_data[] = array(
				        	'id_fk'         => $array['id_fk'],
				        	'company'       => $array['company'],
				        	'address'       => $array['address'],
				        	'phone'         => $array['phone'],
				        	'customer'      => $array['customer'],
				        	'billto'        => $array['billto'],
				        	'shipto'        => $array['shipto'],
				        	'ship_name'     => $array['ship_name'],
				        	'no_po'         => $array['no_po'],
				        	'no_sj'         => $array['no_sj'],
				        	'no_invoice'    => $array['no_invoice'],
				        	'item'          => $array['item'],
				        	'send_qty'      => $array['send_qty'],
				        	'unit'          => $array['unit'],
				        	'tagihan'       => array_sum($array['tagihan']),
				        	'biaya_kirim'   => array_sum($array['biaya_kirim']),
				        	'telp'          => $array['telp'],
				        	'price'         => $array['price'],
				        	'ppn'           => array_sum($array['ppn']),
				        	'no_so'         => $array['no_so'],
				        	'invoice_date'  => $array['invoice_date'],
				        );

				        $result  = 'success';
						$message = 'query success';

					} else {
						$result  = 'error';
						$message = 'ID missing';
					}

				} else {
					$result  = 'error';
					$message = 'query error';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function print_invoice_procces()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$leftjoin = array(
					'preorder_customer ON preorder_customer.id_fk = invoice.id_fk',
					'company ON company.id = preorder_customer.id_company'
				);

				$select = $this->M_all->left_join('invoice', 'invoice.duration, company.logo, company.email', $leftjoin, ' WHERE invoice.id = "'.$this->input->post('id').'"');

				if($select->num_rows() > 0)
				{
					$no = 1;
					$no_delivery = '';
					$get = $select->result();
					$post = $this->input->post('data');
					$date = explode('/', $this->input->post('tgl'));
					$bank = explode('-', $this->input->post('pilihBANK'));
					$duration = explode('-', $get[0]->duration);
					foreach($post['price'] as $key => $value){
						$no_sj    = $post['no_sj'][$key];
				        $no_so    = $post['no_so'][$key];
				        $item     = $post['item'][$key];
				        $send_qty = $post['qty'][$key];
				        $unit     = $post['unit'][$key];
				        $price    = $post['price'][$key];

				        $this->mysqli_data[] = array(
				        	'no'        => $no++,
				          	'company'   => strtoupper($this->input->post('company')),
				          	'address'   => $this->input->post('address'),
				          	'phone'     => $this->input->post('phone'),
				          	'customer'  => $this->input->post('customer'),
				          	'no_invoice'=> $this->input->post('no_faktur'),
				          	'no_po'     => $this->input->post('no_po'),
				          	'no_sj'     => (strtolower($no_sj) === $no_delivery)? '' : $no_sj,
				          	'total'     => $this->input->post('bill'),
				          	'ongkoskir' => $this->input->post('biaya_kirim'),
				          	'item'      => $item,
				          	'unit'      => $unit,
				          	'qty'       => $send_qty,
				          	'price'     => $price,
				          	'tgl'       => date("d M Y", strtotime($date[2].'-'.$date[1].'-'.$date[0])),
				          	'telp'      => $this->input->post('telp'),
				          	'an'        => $bank[2],
				          	'rek'       => $bank[1],
				          	'bank'      => $bank[0],
				          	'ttd'       => $this->input->post('ttd'),
				          	'no_so'     => $no_so,
				          	'billto'    => $this->input->post('billto'),
				          	'shipto'    => $this->input->post('shipto'),
				          	'ship_name' => $this->input->post('ship_name'),
				          	'tenggat'   => date('d M Y', strtotime($duration[0].'-'.$duration[1].'-'.$duration[2])),
				          	'logo'      => $get[0]->logo,
				          	'email'     => empty($get[0]->email)? '' : 'Email: '.$get[0]->email
				        );

				        $array['ppn'][] = (($this->input->post('status_ppn') > 0)? $price/10 : 0) * $send_qty;
				        $array['subtotal'][] = $price * $send_qty;
				        $no_delivery = strtolower($no_sj);
					}

					$this->mysqli_data[] = array(
						'ppn'       => array_sum($array['ppn']),
						'subtotal'  => array_sum($array['subtotal'])
					);

					$result  = 'success';
					$message = 'Berhasil mencetak';

					$set = array( 'print' => 1, 'print_date' => $date[2].'-'.$date[1].'-'.$date[0]);

					$this->M_all->update('invoice', $set, 'id = "'. $this->input->post('id').'"');

				} else {
					$result  = 'error';
					$message = 'ID missing';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function invoice_procces_complete()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$date = explode('/', $this->input->post('date'));

			if(!empty($this->input->post('id')) && !empty($date) && !empty($this->input->post('ket')))
			{
				$set = array(
					'status' => 1,
					'complete_date' => $date[2].'-'.$date[1].'-'.$date[0],
					'note' => $this->input->post('ket')
				);

				$update = $this->M_all->update('invoice', $set, 'id = "'. $this->input->post('id').'"');
				if($update)
				{
					$result  = 'success';
					$message = 'Berhasil menyunting data';
				
				} else {
					$result  = 'error';
					$message = 'Gagal menyunting data';
				}

			} else {

				$result  = 'error';
				$message = 'Mohon untuk mengisi semua kolom yang disediakan';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function delete_invoice_procces()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$select = $this->M_all->select('invoice', 'no_invoice', 'id = "'.$this->input->post('id').'"');
				if($select->num_rows() > 0)
				{
					$invoice = $select->result();
					$delete = $this->M_all->delete('invoice', array( 'no_invoice = "'. $invoice[0]->no_invoice.'"'));
					if($delete)
					{
						$result  = 'success';
						$message = 'Berhasil menghapus data';
					
					} else {
						$result  = 'error';
						$message = 'Gagal menghapus data';
					}

				} else {
					$result  = 'error';
					$message = 'Invoice missing';
				}

			} else {

				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function invoice_duedate()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$this->load->model('M_invoice_duedate');
			$data = array();
			$data_invoice = array();
			$data_cost = array();
			$list = $this->M_invoice_duedate->get_datatables();
			foreach ($list as $k => $column)
			{
				$functions = "<div class='row text-center'>";
				if($this->session->userdata('role') == '5' || $this->session->userdata('id') !== $column->input_by)
				{
					$functions  .= 'Not Allowed';

				} else {

					if(!in_array($column->no_invoice, $data_invoice))
					{
						$functions .= '<button type="button" class="btn btn-flat btn-primary PrintView" data-id="'.$column->id.'"><i class="fa fa-print"></i></button>';

						$functions .= '<button type="button" class="btn btn-flat btn-success complete" data-id="'.$column->id.'" data-name="'.$column->no_invoice.'"><i class="fa fa-check-circle-o" aria-hidden="true"></i></button>';

						if($this->session->userdata('role') == '1')
						{
							$functions .= '<button type="button" class="btn btn-flat btn-danger HapusItem" data-id="'.$column->id.'" data-name="'.$column->no_invoice.'"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
						}
					}
				}
				$functions .= "</div'>";

				$invoice_date = empty($column->invoice_date)? explode('-', '---') : explode('-', $column->invoice_date);
				$duration = empty($column->duration)? explode('-', '---') : explode('-', $column->duration);
				$no_so = explode('/', $column->no_so);
				$biaya_kirim = !in_array($column->id_fk.'-'.$column->id_sj.'-'.$column->cost, $data_cost)? $column->cost : '';

				if(!in_array($column->no_invoice, $data_invoice))
				{
					if($column->print == '1'){ $print = 'SUDAH'; } else { $print = 'BELUM'; }
					$name = $column->name;
				} else {
					$print = ''; $name = '';
				}

				$row = array();
	            $row[] = empty(array_filter($invoice_date))? '' : $invoice_date[2].'/'.$invoice_date[1].'/'.$invoice_date[0];
	            $row[] = empty(array_filter($duration))? '' : $duration[2].'/'.$duration[1].'/'.$duration[0];
	            $row[] = $column->customer;
	            $row[] = $column->po_customer;
	            $row[] = $no_so[0].'/'.$no_so[1].$no_so[2];
	            $row[] = $column->no_delivery;
	            $row[] = $column->no_invoice;
	            $row[] = $column->send_qty;
	            $row[] = $column->unit;
	            $row[] = $column->price;
	            $row[] = $column->send_qty * $column->price;
	            $row[] = ($column->ppn > 0)? ($column->send_qty * $column->price)/10 : 0;
	            $row[] = ($column->ppn > 0)? ($column->send_qty * $column->price) + (($column->send_qty * $column->price)/10) : ($column->send_qty * $column->price);
	            $row[] = $biaya_kirim;
	            $row[] = $column->ekspedisi;
	            $row[] = $column->uom;
	            $row[] = $column->jml;
	            $row[] = $print;
	            $row[] = $name;
				$row[] = $functions;
				$data[] = $row;

				$data_cost[] = $column->id_fk.'-'.$column->id_sj.'-'.$column->cost;
				$data_invoice[] = $column->no_invoice;
        	}
 
		    $output = array(
		        "draw" => $_POST['draw'],
		        "recordsTotal" => $this->M_invoice_duedate->count_all(),
		        "recordsFiltered" => $this->M_invoice_duedate->count_filtered(),
		        "data" => $data,
		    );

		    header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function get_print_invoice_duedate()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$select_1 = $this->M_all->select('invoice', 'no_invoice', 'id = '.$this->input->post('id'));

				if($select_1->num_rows() > 0)
				{
					$data_cost = array();
					$array = array();
					$invoice = $select_1->result();

					$leftjoin = array(
						'delivery_orders_item ON delivery_orders_item.id_fk = invoice.id_fk AND delivery_orders_item.id_sj = invoice.id_sj',
						'preorder_item ON preorder_item.id_fk = invoice.id_fk AND preorder_item.item_to = delivery_orders_item.item_to',
						'preorder_customer ON preorder_customer.id_fk = invoice.id_fk',
						'preorder_price ON preorder_price.id_fk = invoice.id_fk',
						'delivery_orders_customer ON delivery_orders_customer.id_fk = delivery_orders_item.id_fk AND delivery_orders_customer.id_sj = delivery_orders_item.id_sj',
						'workorder_item ON workorder_item.id_fk = invoice.id_fk AND workorder_item.item_to = delivery_orders_item.item_to',
						'customer ON preorder_customer.id_customer = customer.id',
						'company ON company.id = preorder_customer.id_company',
						'status ON delivery_orders_item.id_fk = status.id_fk AND delivery_orders_item.item_to = status.item_to'
					);

					$select = $this->M_all->left_join('invoice', 'invoice.id_fk, invoice.no_invoice, invoice.input_by, invoice.invoice_date, delivery_orders_item.id_sj, delivery_orders_item.item_to, delivery_orders_item.no_delivery, delivery_orders_item.send_qty, preorder_item.item, preorder_item.unit, preorder_item.price, preorder_item.price, preorder_customer.customer, preorder_customer.po_customer, preorder_price.ppn, delivery_orders_customer.shipto, delivery_orders_customer.cost, workorder_item.no_so, workorder_item.ingredient, workorder_item.size, workorder_item.volume, customer.alamat, customer.kota, customer.negara, customer.provinsi, customer.kodepos, customer.telp, customer.s_nama, customer.s_alamat, customer.s_kota, customer.s_negara, customer.s_provinsi, customer.s_kodepos, company.company, company.address, company.phone', $leftjoin, ' WHERE status.hidden = 0 AND invoice.no_invoice = "'.$invoice[0]->no_invoice.'" ORDER BY delivery_orders_item.no_delivery ASC' );

					if($select->num_rows() > 0)
					{
						foreach($select->result() as $row)
						{
							$no_so = explode('/', $row->no_so);
							$alamat = empty($row->alamat)? '' : $row->alamat.'. ';
							$kota = empty($row->kota)? '' : $row->kota.'. ';
							$prov = empty($row->provinsi)? '' : $row->provinsi.'. ';
							$neg = empty($row->negara)? '' : $row->negara.'. ';
							$pos = empty($row->kodepos)? '' : $row->kodepos.'. ';
							$invoice_date = explode('-', $row->invoice_date);
							
							$biaya_kirim = !in_array($row->id_fk.'-'.$row->id_sj.'-'.$row->cost, $data_cost)? $row->cost : '';

							if($row->send_qty > 0)
							{
								$array['id_fk']        = $row->id_fk;
					            $array['company']      = $row->company;
					            $array['address']      = $row->address;
					            $array['phone']        = $row->phone;
					            $array['customer']     = $row->customer;
					            $array['billto']       = $alamat.$kota.$prov.$neg.$pos;
					            $array['shipto']       = $row->shipto;
					            $array['ship_name']    = empty($row->s_nama)? $row->customer : $row->s_nama;
					            $array['no_po']        = $row->po_customer;
					            $array['no_sj'][]      = $row->no_delivery;
					            $array['no_invoice']   = $row->no_invoice;
					            $array['item'][]       = strtoupper($row->item);
					            $array['send_qty'][]   = $row->send_qty;
					            $array['unit'][]       = $row->unit;
					            $array['tagihan'][]    = ($row->ppn > 0)? ($row->send_qty * $row->price) + (($row->send_qty * $row->price)/10) : ($row->send_qty * $row->price);
					            $array['biaya_kirim'][]= $biaya_kirim;
					            $array['telp']         = $row->telp;
					            $array['price'][]      = $row->price;
					            $array['ppn'][]        = $row->send_qty * $row->price;
					            $array['no_so'][]      = $no_so[0]."/".$no_so[1].$no_so[2];
					            $array['invoice_date'] = $invoice_date[2].'/'.$invoice_date[1].'/'.$invoice_date[0];
							}

							$data_cost[] = $row->id_fk.'-'.$row->id_sj.'-'.$row->cost;
				        }

				        $this->mysqli_data[] = array(
				        	'id_fk'         => $array['id_fk'],
				        	'company'       => $array['company'],
				        	'address'       => $array['address'],
				        	'phone'         => $array['phone'],
				        	'customer'      => $array['customer'],
				        	'billto'        => $array['billto'],
				        	'shipto'        => $array['shipto'],
				        	'ship_name'     => $array['ship_name'],
				        	'no_po'         => $array['no_po'],
				        	'no_sj'         => $array['no_sj'],
				        	'no_invoice'    => $array['no_invoice'],
				        	'item'          => $array['item'],
				        	'send_qty'      => $array['send_qty'],
				        	'unit'          => $array['unit'],
				        	'tagihan'       => array_sum($array['tagihan']),
				        	'biaya_kirim'   => array_sum($array['biaya_kirim']),
				        	'telp'          => $array['telp'],
				        	'price'         => $array['price'],
				        	'ppn'           => array_sum($array['ppn']),
				        	'no_so'         => $array['no_so'],
				        	'invoice_date'  => $array['invoice_date'],
				        );

				        $result  = 'success';
						$message = 'query success';

					} else {
						$result  = 'error';
						$message = 'ID missing';
					}

				} else {
					$result  = 'error';
					$message = 'query error';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function print_invoice_duedate()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$leftjoin = array(
					'preorder_customer ON preorder_customer.id_fk = invoice.id_fk',
					'company ON company.id = preorder_customer.id_company'
				);

				$select = $this->M_all->left_join('invoice', 'invoice.duration, company.logo, company.email', $leftjoin, ' WHERE invoice.id = "'.$this->input->post('id').'"');

				if($select->num_rows() > 0)
				{
					$no = 1;
					$no_delivery = '';
					$get = $select->result();
					$post = $this->input->post('data');
					$date = explode('/', $this->input->post('tgl'));
					$bank = explode('-', $this->input->post('pilihBANK'));
					$duration = explode('-', $get[0]->duration);
					foreach($post['price'] as $key => $value){
						$no_sj    = $post['no_sj'][$key];
				        $no_so    = $post['no_so'][$key];
				        $item     = $post['item'][$key];
				        $send_qty = $post['qty'][$key];
				        $unit     = $post['unit'][$key];
				        $price    = $post['price'][$key];

				        $this->mysqli_data[] = array(
				        	'no'        => $no++,
				          	'company'   => strtoupper($this->input->post('company')),
				          	'address'   => $this->input->post('address'),
				          	'phone'     => $this->input->post('phone'),
				          	'customer'  => $this->input->post('customer'),
				          	'no_invoice'=> $this->input->post('no_faktur'),
				          	'no_po'     => $this->input->post('no_po'),
				          	'no_sj'     => (strtolower($no_sj) === $no_delivery)? '' : $no_sj,
				          	'total'     => $this->input->post('bill'),
				          	'ongkoskir' => $this->input->post('biaya_kirim'),
				          	'item'      => $item,
				          	'unit'      => $unit,
				          	'qty'       => $send_qty,
				          	'price'     => $price,
				          	'tgl'       => date("d M Y", strtotime($date[2].'-'.$date[1].'-'.$date[0])),
				          	'telp'      => $this->input->post('telp'),
				          	'an'        => $bank[2],
				          	'rek'       => $bank[1],
				          	'bank'      => $bank[0],
				          	'ttd'       => $this->input->post('ttd'),
				          	'no_so'     => $no_so,
				          	'billto'    => $this->input->post('billto'),
				          	'shipto'    => $this->input->post('shipto'),
				          	'ship_name' => $this->input->post('ship_name'),
				          	'tenggat'   => date('d M Y', strtotime($duration[0].'-'.$duration[1].'-'.$duration[2])),
				          	'logo'      => $get[0]->logo,
				          	'email'     => empty($get[0]->email)? '' : 'Email: '.$get[0]->email
				        );

				        $array['ppn'][] = (($this->input->post('status_ppn') > 0)? $price/10 : 0) * $send_qty;
				        $array['subtotal'][] = $price * $send_qty;
				        $no_delivery = strtolower($no_sj);
					}

					$this->mysqli_data[] = array(
						'ppn'       => array_sum($array['ppn']),
						'subtotal'  => array_sum($array['subtotal'])
					);

					$result  = 'success';
					$message = 'Berhasil mencetak';

					$set = array( 'print' => 1, 'print_date' => $date[2].'-'.$date[1].'-'.$date[0]);

					$this->M_all->update('invoice', $set, 'id = "'. $this->input->post('id').'"');

				} else {
					$result  = 'error';
					$message = 'ID missing';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function invoice_duedate_complete()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$date = explode('/', $this->input->post('date'));

			if(!empty($this->input->post('id')) && !empty($date) && !empty($this->input->post('ket')))
			{
				$set = array(
					'status' => 1,
					'complete_date' => $date[2].'-'.$date[1].'-'.$date[0],
					'note' => $this->input->post('ket')
				);

				$update = $this->M_all->update('invoice', $set, 'id = "'. $this->input->post('id').'"');
				if($update)
				{
					$result  = 'success';
					$message = 'Berhasil menyunting data';
				
				} else {
					$result  = 'error';
					$message = 'Gagal menyunting data';
				}

			} else {

				$result  = 'error';
				$message = 'Mohon untuk mengisi semua kolom yang disediakan';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function delete_invoice_duedate()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$select = $this->M_all->select('invoice', 'no_invoice', 'id = "'.$this->input->post('id').'"');
				if($select->num_rows() > 0)
				{
					$invoice = $select->result();
					$delete = $this->M_all->delete('invoice', array( 'no_invoice = "'. $invoice[0]->no_invoice.'"'));
					if($delete)
					{
						$result  = 'success';
						$message = 'Berhasil menghapus data';
					
					} else {
						$result  = 'error';
						$message = 'Gagal menghapus data';
					}

				} else {
					$result  = 'error';
					$message = 'Invoice missing';
				}

			} else {

				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}


	public function invoice_done()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$this->load->model('M_invoice_done');
			$data = array();
			$data_invoice = array();
			$data_cost = array();
			$list = $this->M_invoice_done->get_datatables();
			foreach ($list as $k => $column)
			{
				$functions = "<div class='row text-center'>";
				if($this->session->userdata('role') == '5' || $this->session->userdata('id') !== $column->input_by)
				{
					$functions  .= 'Not Allowed';

				} else {

					if(!in_array($column->no_invoice, $data_invoice))
					{
						$functions .= '<button type="button" class="btn btn-flat btn-primary PrintView" data-id="'.$column->id.'"><i class="fa fa-print"></i></button>';

						if($this->session->userdata('role') == '1')
						{
							$functions .= '<button type="button" class="btn btn-flat btn-danger canceled" data-id="'.$column->id.'" data-name="'.$column->no_invoice.'"><i class="fa fa-times-circle-o" aria-hidden="true"></i></button>';
						}
					}
				}
				$functions .= "</div'>";

				$invoice_date = empty($column->invoice_date)? explode('-', '---') : explode('-', $column->invoice_date);
				$duration = empty($column->duration)? explode('-', '---') : explode('-', $column->duration);
				$no_so = explode('/', $column->no_so);
				$biaya_kirim = !in_array($column->id_fk.'-'.$column->id_sj.'-'.$column->cost, $data_cost)? $column->cost : '';

				if(!in_array($column->no_invoice, $data_invoice))
				{
					if($column->print == '1'){ $print = 'SUDAH'; } else { $print = 'BELUM'; }
					$name = $column->name;
				} else {
					$print = ''; $name = '';
				}

				if($column->send_qty > 0)
				{
					$row = array();
		            $row[] = empty(array_filter($invoice_date))? '' : $invoice_date[2].'/'.$invoice_date[1].'/'.$invoice_date[0];
		            $row[] = empty(array_filter($duration))? '' : $duration[2].'/'.$duration[1].'/'.$duration[0];
		            $row[] = $column->customer;
		            $row[] = $column->po_customer;
		            $row[] = $no_so[0].'/'.$no_so[1].$no_so[2];
		            $row[] = $column->no_delivery;
		            $row[] = $column->no_invoice;
		            $row[] = $column->send_qty;
		            $row[] = $column->unit;
		            $row[] = $column->price;
		            $row[] = $column->send_qty * $column->price;
		            $row[] = ($column->ppn > 0)? ($column->send_qty * $column->price)/10 : 0;
		            $row[] = ($column->ppn > 0)? ($column->send_qty * $column->price) + (($column->send_qty * $column->price)/10) : ($column->send_qty * $column->price);
		            $row[] = $biaya_kirim;
		            $row[] = $column->ekspedisi;
		            $row[] = $column->uom;
		            $row[] = $column->jml;
		            $row[] = $column->note;
		            $row[] = $print;
		            $row[] = $name;
					$row[] = $functions;
					$data[] = $row;

					$data_cost[] = $column->id_fk.'-'.$column->id_sj.'-'.$column->cost;
					$data_invoice[] = $column->no_invoice;
				}
        	}
 
		    $output = array(
		        "draw" => $_POST['draw'],
		        "recordsTotal" => $this->M_invoice_done->count_all(),
		        "recordsFiltered" => $this->M_invoice_done->count_filtered(),
		        "data" => $data,
		    );

		    header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function get_print_invoice_done()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$select_1 = $this->M_all->select('invoice', 'no_invoice', 'id = '.$this->input->post('id'));

				if($select_1->num_rows() > 0)
				{
					$data_cost = array();
					$array = array();
					$invoice = $select_1->result();

					$leftjoin = array(
						'delivery_orders_item ON delivery_orders_item.id_fk = invoice.id_fk AND delivery_orders_item.id_sj = invoice.id_sj',
						'preorder_item ON preorder_item.id_fk = invoice.id_fk AND preorder_item.item_to = delivery_orders_item.item_to',
						'preorder_customer ON preorder_customer.id_fk = invoice.id_fk',
						'preorder_price ON preorder_price.id_fk = invoice.id_fk',
						'delivery_orders_customer ON delivery_orders_customer.id_fk = delivery_orders_item.id_fk AND delivery_orders_customer.id_sj = delivery_orders_item.id_sj',
						'workorder_item ON workorder_item.id_fk = invoice.id_fk AND workorder_item.item_to = delivery_orders_item.item_to',
						'customer ON preorder_customer.id_customer = customer.id',
						'company ON company.id = preorder_customer.id_company',
						'status ON delivery_orders_item.id_fk = status.id_fk AND delivery_orders_item.item_to = status.item_to'
					);

					$select = $this->M_all->left_join('invoice', 'invoice.id_fk, invoice.no_invoice, invoice.input_by, invoice.invoice_date, delivery_orders_item.id_sj, delivery_orders_item.item_to, delivery_orders_item.no_delivery, delivery_orders_item.send_qty, preorder_item.item, preorder_item.unit, preorder_item.price, preorder_item.price, preorder_customer.customer, preorder_customer.po_customer, preorder_price.ppn, delivery_orders_customer.shipto, delivery_orders_customer.cost, workorder_item.no_so, workorder_item.ingredient, workorder_item.size, workorder_item.volume, customer.alamat, customer.kota, customer.negara, customer.provinsi, customer.kodepos, customer.telp, customer.s_nama, customer.s_alamat, customer.s_kota, customer.s_negara, customer.s_provinsi, customer.s_kodepos, company.company, company.address, company.phone', $leftjoin, ' WHERE status.hidden = 0 AND invoice.no_invoice = "'.$invoice[0]->no_invoice.'" ORDER BY delivery_orders_item.no_delivery ASC' );

					if($select->num_rows() > 0)
					{
						foreach($select->result() as $row)
						{
							$no_so = explode('/', $row->no_so);
							$alamat = empty($row->alamat)? '' : $row->alamat.'. ';
							$kota = empty($row->kota)? '' : $row->kota.'. ';
							$prov = empty($row->provinsi)? '' : $row->provinsi.'. ';
							$neg = empty($row->negara)? '' : $row->negara.'. ';
							$pos = empty($row->kodepos)? '' : $row->kodepos.'. ';
							$invoice_date = explode('-', $row->invoice_date);
							
							$biaya_kirim = !in_array($row->id_fk.'-'.$row->id_sj.'-'.$row->cost, $data_cost)? $row->cost : '';

							if($row->send_qty > 0)
							{
								$array['id_fk']        = $row->id_fk;
					            $array['company']      = $row->company;
					            $array['address']      = $row->address;
					            $array['phone']        = $row->phone;
					            $array['customer']     = $row->customer;
					            $array['billto']       = $alamat.$kota.$prov.$neg.$pos;
					            $array['shipto']       = $row->shipto;
					            $array['ship_name']    = empty($row->s_nama)? $row->customer : $row->s_nama;
					            $array['no_po']        = $row->po_customer;
					            $array['no_sj'][]      = $row->no_delivery;
					            $array['no_invoice']   = $row->no_invoice;
					            $array['item'][]       = strtoupper($row->item);
					            $array['send_qty'][]   = $row->send_qty;
					            $array['unit'][]       = $row->unit;
					            $array['tagihan'][]    = ($row->ppn > 0)? ($row->send_qty * $row->price) + (($row->send_qty * $row->price)/10) : ($row->send_qty * $row->price);
					            $array['biaya_kirim'][]= $biaya_kirim;
					            $array['telp']         = $row->telp;
					            $array['price'][]      = $row->price;
					            $array['ppn'][]        = $row->send_qty * $row->price;
					            $array['no_so'][]      = $no_so[0]."/".$no_so[1].$no_so[2];
					            $array['invoice_date'] = $invoice_date[2].'/'.$invoice_date[1].'/'.$invoice_date[0];
							}

							$data_cost[] = $row->id_fk.'-'.$row->id_sj.'-'.$row->cost;
				        }

				        $this->mysqli_data[] = array(
				        	'id_fk'         => $array['id_fk'],
				        	'company'       => $array['company'],
				        	'address'       => $array['address'],
				        	'phone'         => $array['phone'],
				        	'customer'      => $array['customer'],
				        	'billto'        => $array['billto'],
				        	'shipto'        => $array['shipto'],
				        	'ship_name'     => $array['ship_name'],
				        	'no_po'         => $array['no_po'],
				        	'no_sj'         => $array['no_sj'],
				        	'no_invoice'    => $array['no_invoice'],
				        	'item'          => $array['item'],
				        	'send_qty'      => $array['send_qty'],
				        	'unit'          => $array['unit'],
				        	'tagihan'       => array_sum($array['tagihan']),
				        	'biaya_kirim'   => array_sum($array['biaya_kirim']),
				        	'telp'          => $array['telp'],
				        	'price'         => $array['price'],
				        	'ppn'           => array_sum($array['ppn']),
				        	'no_so'         => $array['no_so'],
				        	'invoice_date'  => $array['invoice_date'],
				        );

				        $result  = 'success';
						$message = 'query success';

					} else {
						$result  = 'error';
						$message = 'ID missing';
					}

				} else {
					$result  = 'error';
					$message = 'query error';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function print_invoice_done()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$leftjoin = array(
					'preorder_customer ON preorder_customer.id_fk = invoice.id_fk',
					'company ON company.id = preorder_customer.id_company'
				);

				$select = $this->M_all->left_join('invoice', 'invoice.duration, company.logo, company.email', $leftjoin, ' WHERE invoice.id = "'.$this->input->post('id').'"');

				if($select->num_rows() > 0)
				{
					$no = 1;
					$no_delivery = '';
					$get = $select->result();
					$post = $this->input->post('data');
					$date = explode('/', $this->input->post('tgl'));
					$bank = explode('-', $this->input->post('pilihBANK'));
					$duration = explode('-', $get[0]->duration);
					foreach($post['price'] as $key => $value){
						$no_sj    = $post['no_sj'][$key];
				        $no_so    = $post['no_so'][$key];
				        $item     = $post['item'][$key];
				        $send_qty = $post['qty'][$key];
				        $unit     = $post['unit'][$key];
				        $price    = $post['price'][$key];

				        $this->mysqli_data[] = array(
				        	'no'        => $no++,
				          	'company'   => strtoupper($this->input->post('company')),
				          	'address'   => $this->input->post('address'),
				          	'phone'     => $this->input->post('phone'),
				          	'customer'  => $this->input->post('customer'),
				          	'no_invoice'=> $this->input->post('no_faktur'),
				          	'no_po'     => $this->input->post('no_po'),
				          	'no_sj'     => (strtolower($no_sj) === $no_delivery)? '' : $no_sj,
				          	'total'     => $this->input->post('bill'),
				          	'ongkoskir' => $this->input->post('biaya_kirim'),
				          	'item'      => $item,
				          	'unit'      => $unit,
				          	'qty'       => $send_qty,
				          	'price'     => $price,
				          	'tgl'       => date("d M Y", strtotime($date[2].'-'.$date[1].'-'.$date[0])),
				          	'telp'      => $this->input->post('telp'),
				          	'an'        => $bank[2],
				          	'rek'       => $bank[1],
				          	'bank'      => $bank[0],
				          	'ttd'       => $this->input->post('ttd'),
				          	'no_so'     => $no_so,
				          	'billto'    => $this->input->post('billto'),
				          	'shipto'    => $this->input->post('shipto'),
				          	'ship_name' => $this->input->post('ship_name'),
				          	'tenggat'   => date('d M Y', strtotime($duration[0].'-'.$duration[1].'-'.$duration[2])),
				          	'logo'      => $get[0]->logo,
				          	'email'     => empty($get[0]->email)? '' : 'Email: '.$get[0]->email
				        );

				        $array['ppn'][] = (($this->input->post('status_ppn') > 0)? $price/10 : 0) * $send_qty;
				        $array['subtotal'][] = $price * $send_qty;
				        $no_delivery = strtolower($no_sj);
					}

					$this->mysqli_data[] = array(
						'ppn'       => array_sum($array['ppn']),
						'subtotal'  => array_sum($array['subtotal'])
					);

					$result  = 'success';
					$message = 'Berhasil mencetak';

					$set = array( 'print' => 1, 'print_date' => $date[2].'-'.$date[1].'-'.$date[0]);

					$this->M_all->update('invoice', $set, 'id = "'. $this->input->post('id').'"');

				} else {
					$result  = 'error';
					$message = 'ID missing';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function invoice_done_canceled()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$set = array(
					'status' => 0,
					'complete_date' => '',
					'note' => ''
				);

				$update = $this->M_all->update('invoice', $set, 'id = "'. $this->input->post('id').'"');
				if($update)
				{
					$result  = 'success';
					$message = 'Berhasil menyunting data';
				
				} else {
					$result  = 'error';
					$message = 'Gagal menyunting data';
				}

			} else {

				$result  = 'error';
				$message = 'Mohon untuk mengisi semua kolom yang disediakan';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function aging()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$this->load->model('M_aging');
			$data = array();
			$list = $this->M_aging->get_datatables();
			foreach ($list as $k => $column)
			{
				$invoice_date = empty($column->invoice_date)? explode('-', '---') : explode('-', $column->invoice_date);
				$invoice_duedate = empty($column->invoice_duedate)? explode('-', '---') : explode('-', $column->invoice_duedate);
				$complete_date = empty($column->complete_date)? explode('-', '---') : explode('-', $column->complete_date);

				$row = array();
	            $row[] = $column->customer;
	            $row[] = $column->company;
	            $row[] = $column->no_invoice;
	            $row[] = $column->no_delivery;
	            $row[] = $column->no_so;
	            $row[] = $column->po_customer;
	            $row[] = empty(array_filter($invoice_date))? '' : $invoice_date[2].'/'.$invoice_date[1].'/'.$invoice_date[0];
	            $row[] = empty(array_filter($invoice_duedate))? '' : $invoice_duedate[2].'/'.$invoice_duedate[1].'/'.$invoice_duedate[0];
	            $row[] = ($column->ppn > 0)? ($column->subtotal / 10) + $column->subtotal : $column->subtotal;
	            $row[] = empty(array_filter($complete_date))? '' : $complete_date[2].'/'.$complete_date[1].'/'.$complete_date[0];
	            $row[] = $column->note;
	            $row[] = $column->cost;
				$data[] = $row;
        	}
 
		    $output = array(
		        "draw" => $_POST['draw'],
		        "recordsTotal" => $this->M_aging->count_all()->num_rows(),
		        "recordsFiltered" => $this->M_aging->count_filtered(),
		        "data" => $data,
		    );

		    header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function user()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$this->load->model('M_user');
			$data = array();
			$list = $this->M_user->get_datatables();
			foreach ($list as $k => $column)
			{
				$functions = "<div class='row text-center'>";
				if($this->session->userdata('role') == 1)
				{
					$functions .= '<button type="button" class="btn btn-flat btn-primary edit_user" data-id="'.$column->id.'" data-name="'.$column->name.'"><i class="fa fa-pencil-square-o"></i></button>';

					$functions .= '<button type="button" class="btn btn-flat btn-danger delete_user" data-id="'.$column->id.'" data-name="'.$column->name.'"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';

				} else {
					$functions .= "Not allowed";
				}
				$functions .= "</div>";

				if($column->role == 1){ $role = "Root"; }
				if($column->role == 2){ $role = "Administrator";}
				if($column->role == 3){ $role = "Sales Order";}
				if($column->role == 4){ $role = "Finance";}
				if($column->role == 5){ $role = "Guest";}
				if($column->role == 6){ $role = "Production";}
				if($column->status == 0){ $status = "Not Verified"; }
				if($column->status == 1){ $status = "Verified"; }
				if($column->account == 0){ $account = "Inactive";}
				if($column->account == 1){ $account = "Active";}
				
				$row = array();
				$row[] = $column->name;
				$row[] = $column->email;
				$row[] = $role;
				$row[] = $status;
				$row[] = $account;
				$row[] = $functions;
				$data[] = $row;
        	}
 
		    $output = array(
		        "draw" => $_POST['draw'],
		        "recordsTotal" => $this->M_user->count_all(),
		        "recordsFiltered" => $this->M_user->count_filtered(),
		        "data" => $data,
		    );

		    header('Content-Type: application/json');
			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function add_user()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(
				!empty($this->input->post('name')) &&
				!empty($this->input->post('email')) &&
				!empty($this->input->post('password')) && 
				!empty($this->input->post('role')) &&
				!empty($this->input->post('status')) &&
				!empty($this->input->post('account')))
			{
			    $data = array(
			    	'name' 	=> $this->input->post('name'),
			    	'email'	=> $this->input->post('email'),
			    	'password' => md5($this->input->post('password')),
			    	'role'	=> $this->input->post('role'),
			    	'status' => $this->input->post('status'),
			    	'account'=> $this->input->post('account')
			    );

			    $insert = $this->M_all->insert('user', $data);
			    if($insert)
			    {
			    	$result  = 'success';
			    	$message = 'Berhasil memasukan data';

			    } else {

			    	$result  = 'error';
					$message = 'Gagal memasukan data label';
			    }

			} else {

				$result  = 'error';
				$message = 'Mohon untuk mengisi semua kolom yang disediakan';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function get_user()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$select = $this->M_all->select('user', '*', 'id = '.$this->input->post('id'));
				if($select->num_rows() > 0)
				{
					foreach($select->result() as $row){
						$this->mysqli_data[] = array(
							'name' 		=> $row->name,
			                'email' 	=> $row->email,
			                'role' 		=> $row->role,
			                'status' 	=> $row->status,
			                'account' 	=> $row->account,
			        	);
			        }

			        $result  = 'success';
					$message = 'Berhasil mengambil data';

				} else {
					$result  = 'error';
					$message = 'Gagal mengambil data';
				}

			} else {
				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  	=> $result,
				"message" 	=> $message,
				"data"		=> $this->mysqli_data
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function edit_user()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(empty($this->input->post('id')))
			{
				$result  = 'error';
				$message = 'ID missing';

			} else {

				$data = array(
					'name'		=> $this->input->post('name'),
			    	'email'	=> $this->input->post('email'),
			    	'role'	=> $this->input->post('role'),
			    	'status'		=> $this->input->post('status'),
			    	'account'		=> $this->input->post('account'),
				);

				if($this->M_all->update('user', $data, 'id ='.$this->input->post('id')))
				{
					$result  = 'success';
					$message = 'Berhasil menyunting data';

				} else {
					$result  = 'error';
					$message = 'Gagal menyunting data';
				}
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function delete_user()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			if(!empty($this->input->post('id')))
			{
				$update = $this->M_all->update('user', array('hidden' => 1), 'id = '.$this->input->post('id'));
				if($update)
				{
					$result  = 'success';
					$message = 'Berhasil menghapus data';

				} else {
					$result  = 'error';
					$message = 'Gagal menghapus data';
				}
				
			} else {

				$result  = 'error';
				$message = 'ID missing';
			}

			$output = array(
				"result"  => $result,
				"message" => $message
			);

			echo json_encode($output);
			exit();

		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}






}