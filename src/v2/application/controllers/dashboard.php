<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class dashboard extends CI_controller {

	public function __contstruct()
	{
		parent::__contstruct();
		$this->load->library('online');
		$this->online->check(
			$this->session->userdata('email'),
			$this->session->userdata('password'),
			$this->session->userdata('last_action')
		);

	}

	public function index()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$array['data'] = array(
				'name'		=> $this->session->userdata('name'),
				'role'		=> $this->session->userdata('role'),
				'account'	=> $this->session->userdata('account'),
				'picture'	=> $this->session->userdata('picture')
			);

			$this->load->view('dashboard/header');
			$this->load->view('dashboard/nav', $array);
			$this->load->view('dashboard/index');
			$this->load->view('dashboard/footer');
			
		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function company()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$array['data'] = array(
				'name'		=> $this->session->userdata('name'),
				'role'		=> $this->session->userdata('role'),
				'account'	=> $this->session->userdata('account'),
				'picture'	=> $this->session->userdata('picture')
			);

			$this->load->view('dashboard/header');
			$this->load->view('dashboard/nav', $array);
			$this->load->view('dashboard/company');
			$this->load->view('dashboard/footer');
			
		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function vendor()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$array['data'] = array(
				'name'		=> $this->session->userdata('name'),
				'role'		=> $this->session->userdata('role'),
				'account'	=> $this->session->userdata('account'),
				'picture'	=> $this->session->userdata('picture')
			);

			$this->load->view('dashboard/header');
			$this->load->view('dashboard/nav', $array);
			$this->load->view('dashboard/vendor');
			$this->load->view('dashboard/footer');
			
		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function customer()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$array['data'] = array(
				'name'		=> $this->session->userdata('name'),
				'role'		=> $this->session->userdata('role'),
				'account'	=> $this->session->userdata('account'),
				'picture'	=> $this->session->userdata('picture')
			);

			$this->load->view('dashboard/header');
			$this->load->view('dashboard/nav', $array);
			$this->load->view('dashboard/customer');
			$this->load->view('dashboard/footer');
			
		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function label()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$array['data'] = array(
				'name'		=> $this->session->userdata('name'),
				'role'		=> $this->session->userdata('role'),
				'account'	=> $this->session->userdata('account'),
				'picture'	=> $this->session->userdata('picture')
			);

			$this->load->view('dashboard/header');
			$this->load->view('dashboard/nav', $array);
			$this->load->view('dashboard/label');
			$this->load->view('dashboard/footer');
			
		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function ribbon()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$array['data'] = array(
				'name'		=> $this->session->userdata('name'),
				'role'		=> $this->session->userdata('role'),
				'account'	=> $this->session->userdata('account'),
				'picture'	=> $this->session->userdata('picture')
			);

			$this->load->view('dashboard/header');
			$this->load->view('dashboard/nav', $array);
			$this->load->view('dashboard/ribbon');
			$this->load->view('dashboard/footer');
			
		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function material()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$array['data'] = array(
				'name'		=> $this->session->userdata('name'),
				'role'		=> $this->session->userdata('role'),
				'account'	=> $this->session->userdata('account'),
				'picture'	=> $this->session->userdata('picture')
			);

			$this->load->view('dashboard/header');
			$this->load->view('dashboard/nav', $array);
			$this->load->view('dashboard/material');
			$this->load->view('dashboard/footer');
			
		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function purchase_order()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$array['data'] = array(
				'name'		=> $this->session->userdata('name'),
				'role'		=> $this->session->userdata('role'),
				'account'	=> $this->session->userdata('account'),
				'picture'	=> $this->session->userdata('picture')
			);

			$this->load->view('dashboard/header');
			$this->load->view('dashboard/nav', $array);
			$this->load->view('dashboard/purchase_order');
			$this->load->view('dashboard/footer');
			
		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function sales_order()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$array['data'] = array(
				'name'		=> $this->session->userdata('name'),
				'role'		=> $this->session->userdata('role'),
				'account'	=> $this->session->userdata('account'),
				'picture'	=> $this->session->userdata('picture')
			);

			$this->load->view('dashboard/header');
			$this->load->view('dashboard/nav', $array);
			$this->load->view('dashboard/sales_order');
			$this->load->view('dashboard/footer');
			
		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function workorder()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$array['data'] = array(
				'name'		=> $this->session->userdata('name'),
				'role'		=> $this->session->userdata('role'),
				'account'	=> $this->session->userdata('account'),
				'picture'	=> $this->session->userdata('picture')
			);

			$this->load->view('dashboard/header');
			$this->load->view('dashboard/nav', $array);
			$this->load->view('dashboard/workorder');
			$this->load->view('dashboard/footer');
			
		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function do_waiting()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$array['data'] = array(
				'name'		=> $this->session->userdata('name'),
				'role'		=> $this->session->userdata('role'),
				'account'	=> $this->session->userdata('account'),
				'picture'	=> $this->session->userdata('picture')
			);

			$this->load->view('dashboard/header');
			$this->load->view('dashboard/nav', $array);
			$this->load->view('dashboard/do_waiting');
			$this->load->view('dashboard/footer');
			
		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function do_delivery()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$array['data'] = array(
				'name'		=> $this->session->userdata('name'),
				'role'		=> $this->session->userdata('role'),
				'account'	=> $this->session->userdata('account'),
				'picture'	=> $this->session->userdata('picture')
			);

			$this->load->view('dashboard/header');
			$this->load->view('dashboard/nav', $array);
			$this->load->view('dashboard/do_delivery');
			$this->load->view('dashboard/footer');
			
		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function invoice_waiting()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$array['data'] = array(
				'name'		=> $this->session->userdata('name'),
				'role'		=> $this->session->userdata('role'),
				'account'	=> $this->session->userdata('account'),
				'picture'	=> $this->session->userdata('picture')
			);

			$this->load->view('dashboard/header');
			$this->load->view('dashboard/nav', $array);
			$this->load->view('dashboard/invoice_waiting');
			$this->load->view('dashboard/footer');
			
		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function invoice_procces()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$array['data'] = array(
				'name'		=> $this->session->userdata('name'),
				'role'		=> $this->session->userdata('role'),
				'account'	=> $this->session->userdata('account'),
				'picture'	=> $this->session->userdata('picture')
			);

			$this->load->view('dashboard/header');
			$this->load->view('dashboard/nav', $array);
			$this->load->view('dashboard/invoice_procces');
			$this->load->view('dashboard/footer');
			
		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function invoice_duedate()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$array['data'] = array(
				'name'		=> $this->session->userdata('name'),
				'role'		=> $this->session->userdata('role'),
				'account'	=> $this->session->userdata('account'),
				'picture'	=> $this->session->userdata('picture')
			);

			$this->load->view('dashboard/header');
			$this->load->view('dashboard/nav', $array);
			$this->load->view('dashboard/invoice_duedate');
			$this->load->view('dashboard/footer');
			
		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function invoice_done()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$array['data'] = array(
				'name'		=> $this->session->userdata('name'),
				'role'		=> $this->session->userdata('role'),
				'account'	=> $this->session->userdata('account'),
				'picture'	=> $this->session->userdata('picture')
			);

			$this->load->view('dashboard/header');
			$this->load->view('dashboard/nav', $array);
			$this->load->view('dashboard/invoice_done');
			$this->load->view('dashboard/footer');
			
		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function aging()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$array['data'] = array(
				'name'		=> $this->session->userdata('name'),
				'role'		=> $this->session->userdata('role'),
				'account'	=> $this->session->userdata('account'),
				'picture'	=> $this->session->userdata('picture')
			);

			$this->load->view('dashboard/header');
			$this->load->view('dashboard/nav', $array);
			$this->load->view('dashboard/aging');
			$this->load->view('dashboard/footer');
			
		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}

	public function user()
	{
		if($this->session->userdata('signin') == 'is_signin')
		{
			$array['data'] = array(
				'name'		=> $this->session->userdata('name'),
				'role'		=> $this->session->userdata('role'),
				'account'	=> $this->session->userdata('account'),
				'picture'	=> $this->session->userdata('picture')
			);

			$this->load->view('dashboard/header');
			$this->load->view('dashboard/nav', $array);
			$this->load->view('dashboard/user');
			$this->load->view('dashboard/footer');
			
		} else {
			redirect(base_url('index.php/action/auth/signout'));
		}
	}
}