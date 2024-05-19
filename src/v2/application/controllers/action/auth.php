<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class auth extends CI_Controller {

	private $email;
	private $password;
	private $code;
	private $error;
	private $direct;
	private $column_user 	= 'id, name, email, role, status, account, picture';
	private $table_user		= 'user';


	public function __construct()
	{
		date_default_timezone_set('Asia/Jakarta');
		parent::__construct();
		$this->load->model('M_all');
		$this->load->helper('cookie');
	}

	public function index()
	{
		redirect(base_url('index.php/procces/auth/signout'));
	}

	public function signin()
	{
		$this->email 		= $this->input->post('email');
		$this->password 	= $this->input->post('password');
		$this->code 		= $this->input->post('captcha');

		if(isset($this->email) && isset($this->password) && isset($this->code))
		{
			if(!empty($this->email) && !empty($this->password) && !empty($this->code))
			{
				if($this->code == $_SESSION['digit'])
				{
					$where = array( 'email' => $this->email, 'password' => md5($this->password));
					$user = $this->M_all->select( $this->table_user, $this->column_user, $where )->row_array();

					if(!empty(array_filter($user)))
					{ 
						if($user['status'] > 0)
						{
							$data = array(
								'name' 		=> $user['name'],
								'email' 	=> $this->email,
								'password' 	=> md5($this->password),
								'role' 		=> $user['role'],
								'status' 	=> $user['status'],
								'account' 	=> $user['account'],
								'signin'	=> 'is_signin',
								'id'		=> $user['id'],
								'picture'	=> $user['picture'],
								'last_action' => time()
							);

							$log = array(
								'data' 		=> 'URL: '.current_url().' - Browser: '.$_SERVER['HTTP_USER_AGENT'].' - IP: '.$this->input->ip_address(),
								'query'		=> 'Sign in',
								'date'		=> date('Y-m-d h:i:s'),
								'user'		=> $user['name'],
							);

							$this->M_all->insert( 'log', $log );
							$this->session->set_userdata($data);
							$this->direct 	= base_url('index.php/dashboard/index');

						} else {
							
							$this->error 	= "Please verify email to continue or contact the administrator";
							$this->direct 	= base_url();
						}

					} else {

						$this->error 	= "Please check your detail login";
						$this->direct 	= base_url();
					}

				} else {
					
					$this->error 	= "Wrong security code";
					$this->direct 	= base_url();
				}

			} else {

				$this->error 	= "Please fill in all fields";
				$this->direct 	= base_url();
			}

		} else {

			$this->error 	= "Not allowed";
			$this->direct 	= "";
		}

		
		$this->session->set_flashdata("error", $this->error);
		redirect($this->direct);
	}

	public function signout()
	{
		$this->session->sess_destroy();
		delete_cookie('notification'); 
        redirect(base_url());
	}

	public function captcha()
	{
		$image = @imagecreatetruecolor(120, 30) or die("Cannot Initialize new GD image stream");
		$background = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
		imagefill($image, 0, 0, $background);
		$linecolor = imagecolorallocate($image, 0xCC, 0xCC, 0xCC);
		$textcolor = imagecolorallocate($image, 0x33, 0x33, 0x33);

		for($i=0; $i < 6; $i++) {
			imagesetthickness($image, rand(1,3));
			imageline($image, 0, rand(0,30), 120, rand(0,30), $linecolor);
		}

		session_start();
		$digit = '';
		for($x = 15; $x <= 95; $x += 20) {
			$digit .= ($num = rand(0, 9));
			imagechar($image, rand(3, 5), $x, rand(2, 14), $num, $textcolor);
		}

		ob_clean();
		$_SESSION['digit'] = $digit;
		$this->output->set_content_type('image/png');
		return imagepng($image);
		imagedestroy($image);
	}
}