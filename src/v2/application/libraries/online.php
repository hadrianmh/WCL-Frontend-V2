<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class online {

	public function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->model('M_all');
	}

	public function check( $email, $password, $action )
	{
		$check = $this->ci->M_all->select( 'user', 'status', array( 'email' => $email, 'password' => $password));

		if( $check->num_rows() > 0 )
		{
			$data = $check->row_array();

			if($data['status'] > 0)
			{
		        $expireAfter = 60;

		        if(isset($action))
		        {
		        	$secondsInactive = time() - $action;
		        	$expireAfterSeconds = $expireAfter * 60;
		        	if($secondsInactive >= $expireAfterSeconds)
		        	{
		        		redirect(base_url('index.php/action/auth/signout'));

		        	} else {

		        		$this->ci->session->set_userdata('last_action', time());
		        	}
		        }

			} else {

				redirect(base_url('index.php/action/auth/signout'));

			}

		} else {

			redirect(base_url('index.php/action/auth/signout'));
		}
	}
}

?>