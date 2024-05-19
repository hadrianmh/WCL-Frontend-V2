<?php
require '../dashboard/session.php';
require 'connect.php';

$tabel = 'customer';

$action = '';
$id  = '';

if (isset($_GET['req'])){
	$action = $_GET['req'];
	if(
		$action !== 'customer' ||
		$action !== 'company' ||
		$action !== 'so_item' ||
		$action !== 'vendor' ||
		$action !== 'po_item' ||
		$action !== 'so_attribute' ||
		$action !== 'po_attribute'
	){
    	$action == '';
  	}
}

if($action != ''){

	if($action == 'customer')
	{
		if($_GET['keyword'] != '')
		{
			$keyword = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['keyword']))));
			$query = "SELECT a.id AS id_customer, a.nama, b.po_customer, b.id AS id_po, GROUP_CONCAT(c.item SEPARATOR ' - ') AS item FROM customer AS a LEFT JOIN preorder_customer AS b ON a.id = b.id_customer LEFT JOIN preorder_item AS c ON c.id_fk = b.id_fk WHERE a.nama LIKE '%$keyword%' GROUP BY b.id_fk ORDER BY b.id DESC LIMIT 20";
			$sql = $connect->query($query);

			if(!$sql){
				$mysqli_data[] = 'cannot to connect database';
			} else {
				if($sql->num_rows > 0)
				{
					$prevVal = "";
					$first = TRUE;
					while($row = $sql->fetch_array())
					{
						$customer = str_replace(' ', '_', strtolower($row['nama']));
						$nopo = str_replace(' ', '_', $row['po_customer']);


						if(empty($nopo))
						{
							$mysqli_data[] = array(
			  					'id_customer'	=> $row['id_customer'],
								'id_po'			=> null,
				  				'label'			=> 'Buat preorder baru',
					  			'value'			=> $row['nama'],
					  			'category' 		=> $row['nama'],
				  			);

						} else {

							if($prevVal !== $customer)
							{
								$prevVal = $customer;
									$mysqli_data[] = array(
				  					'id_customer'	=> $row['id_customer'],
									'id_po'			=> null,
					  				'label'			=> 'Buat preorder baru',
						  			'value'			=> $row['nama'],
						  			'category' 		=> $row['nama'],
					  			);
							}

							$mysqli_data[] = array(
								'id_customer'	=> $row['id_customer'],
								'id_po'			=> $row['id_po'],
								'label' 		=> $row['item'],
								'value' 		=> $row['nama'],
								'category'		=> $row['nama']
							);

						}
					}

				} else {
					$mysqli_data[] = array(
						'id_customer'	=> null,
						'id_po'			=> null,
		  				'label'			=> 'Tidak terdaftar, silakan daftar sebagai pelanggan baru.',
			  			'value'			=> $_GET['keyword'],
			  			'category' 		=> '',
					);
				}
			}

		} else {
			$mysqli_data[] = 'not found';
		}

	} else if($action == 'so_item') {

		$customer = mysqli_real_escape_string($connect, $_GET['id']);
		$po = mysqli_real_escape_string($connect, $_GET['no']);

		if(empty($po))
		{
			$mysqli_data[] = array(
				'id_customer'	=> $customer,
				'id_po'			=> '0'
		);

		} else {

			$QUERY = 'SELECT a.id AS id_customer, b.id AS id_po, c.item_to, c.price, d.item, d.size, d.unit, d.qore, d.lin, d.roll, d.ingredient, d.qty, d.volume, d.annotation, d.porporasi, d.uk_bahan_baku, d.qty_bahan_baku, d.roll, d.detail, d.merk, d.type FROM customer AS a LEFT JOIN preorder_customer AS b ON a.id = b.id_customer LEFT JOIN preorder_item AS c ON c.id_fk = b.id_fk LEFT JOIN workorder_item AS d ON d.id_fk = c.id_fk AND d.item_to = c.item_to WHERE a.id = "'.$customer.'" AND b.id = "'.$po.'"';
			$SQL = $connect->query($QUERY);

			if(!$SQL)
			{
				$mysqli_data[] = 'cannot to connect database';

			} else {

				while($row = $SQL->fetch_array())
				{
					$mysqli_data[] = array(
						'id_customer'	=> $row['id_customer'],
						'id_po'			=> $row['id_po'],
			  			'item_to'		=> $row['item_to'],
			  			'price'			=> $row['price'],
			  			'item'			=> $row['item'],
			  			'size'			=> $row['size'],
			  			'unit'			=> $row['unit'],
			  			'qore'			=> $row['qore'],
			  			'roll'			=> $row['roll'],
			  			'lin'			=> $row['lin'],
			  			'ingredient'	=> $row['ingredient'],
			  			'qty'			=> $row['qty'],
			  			'volume'		=> $row['volume'],
			  			'annotation'	=> $row['annotation'],
			  			'porporasi'		=> $row['porporasi'],
			  			'uk_bahan_baku'	=> $row['uk_bahan_baku'],
			  			'qty_bahan_baku'=> $row['qty_bahan_baku'],
			  			'detail'		=> $row['detail'],
			  			'merk'			=> $row['merk'],
			  			'type'			=> $row['type'],
					);
				}
			}
		}

	} else if($action == 'vendor') {
		if($_GET['keyword'] != '')
		{
			$keyword = mysqli_real_escape_string($connect, trim(preg_replace('!\s+!',' ', strip_tags($_GET['keyword']))));
			$query = "SELECT a.id AS id_vendor, a.vendor, b.id AS id_po, b.nopo, b.type, GROUP_CONCAT(c.detail SEPARATOR ' - ') AS detail, d.isi FROM vendor AS a LEFT JOIN po_customer AS b ON a.id = b.id_vendor LEFT JOIN po_item AS c ON c.id_fk = b.id LEFT JOIN setting AS d ON d.id = b.type WHERE a.vendor LIKE '%$keyword%' GROUP BY b.id ORDER BY a.id DESC LIMIT 20";
			$sql = $connect->query($query);

			if(!$sql){
				$mysqli_data[] = 'cannot to connect database';
			} else {
				if($sql->num_rows > 0)
				{
					$prevVal = "";
					while($row = $sql->fetch_array())
					{
						$vendor = str_replace(' ', '_', strtolower($row['vendor']));
						$nopo = str_replace(' ', '_', $row['nopo']);

						if(empty($nopo))
						{
							$mysqli_data[] = array(
			  					'id_vendor'		=> $row['id_vendor'],
								'id_po'			=> null,
				  				'label'			=> 'Buat preorder baru',
					  			'value'			=> $row['vendor'],
					  			'category' 		=> $row['vendor'],
				  			);

						} else {

							if($prevVal != $vendor)
							{
									$mysqli_data[] = array(
				  					'id_vendor'		=> $row['id_vendor'],
									'id_po'			=> null,
					  				'label'			=> 'Buat preorder baru',
						  			'value'			=> $row['vendor'],
						  			'category' 		=> $row['vendor'],
					  			);
							}

							$mysqli_data[] = array(
								'id_vendor'		=> $row['id_vendor'],
								'id_po'			=> $row['id_po'],
								'label' 		=> '['.$row['isi'].'] - '.$row['detail'],
								'value' 		=> $row['vendor'],
								'category'		=> $row['vendor']
							);
						}

						$prevVal = $vendor;
					}

				} else {
					$mysqli_data[] = array(
						'id_customer'	=> null,
						'id_po'			=> null,
		  				'label'			=> 'Tidak terdaftar, silakan daftar sebagai vendor baru.',
			  			'value'			=> $_GET['keyword'],
			  			'category' 		=> '',
					);
				}
			}

		} else {
			$mysqli_data[] = 'not found';
		}

	} else if($action == 'po_item') {

		$vendor = mysqli_real_escape_string($connect, $_GET['vendor']);
		$id_po = mysqli_real_escape_string($connect, $_GET['id']);

		if(empty($id_po))
		{
			$mysqli_data['value'][] = array(
				'id_vendor'		=> $vendor,
				'id_po'			=> '0'
			);

		} else {

			$select1 = 'SELECT * FROM po_item WHERE id_fk = "'.$id_po.'" ORDER BY id ASC';
			$sql1 = $connect->query($select1);

			$select2 = "SELECT a.type, b.value FROM po_customer AS a LEFT JOIN setting AS b ON b.id = a.type WHERE a.id = '".$id_po."'";
			$sql2 = $connect->query($select2);

			if(!$sql1 || !$sql2)
			{
				$mysqli_data[] = 'cannot to connect database';

			} else {

				while($row = $sql1->fetch_array())
				{
					$mysqli_data['value'][] = array(
						'id_vendor'		=> $vendor,
						'id_po'			=> $id_po,
			  			'item_to'		=> $row['item_to'],
			  			'detail'		=> $row['detail'],
			  			'size'			=> $row['size'],
			  			'price_1'		=> $row['price_1'],
			  			'price_2'		=> $row['price_2'],
			  			'qty'			=> $row['qty'],
			  			'unit'			=> $row['unit'],
			  			'merk'			=> $row['merk'],
			  			'type'			=> $row['type'],
			  			'core'			=> $row['core'],
			  			'gulungan'		=> $row['gulungan'],
			  			'bahan'			=> $row['bahan'],
					);
				}

				while ($baris = $sql2->fetch_array())
				{
					$obj = json_decode($baris['value']);
					$mysqli_data['input'][] = array(
						'type' 		=> $baris['type'],
						'attribute' => $obj->{'input'},
					);
				}
			}
		}

	} else if($action == 'company') {
		$query = "SELECT id, company FROM company ORDER BY id DESC";
		$sql = $connect->query($query);
		if(!$sql)
		{
			$mysqli_data[] = 'cannot to connect database';

		} else {
			while ($row = $sql->fetch_array()) {
				$mysqli_data[] = array(
					'id'		=> $row['id'],
					'company'	=> $row['company'],
				);
			}
		}

	} elseif($action == 'so_attribute'){
		$id = mysqli_real_escape_string($connect, $_GET['id']);
		if(!is_numeric($id)){
			$id = '';
			$mysqli_data[] = 'ID Missing';
		} else {
			$query = "SELECT value FROM setting WHERE id = '".$id."'";
			$sql = $connect->query($query);
			if(!$sql)
			{
				$mysqli_data[] = 'cannot to connect database';

			} else {
				while($row = $sql->fetch_array()) {
					$obj = json_decode($row['value']);
					$mysqli_data[] = array(
						'field'		=> $obj->{'input'}
					);
				}
			}
		}

	} elseif($action == 'po_attribute'){
		$id = mysqli_real_escape_string($connect, $_GET['id']);
		if(!is_numeric($id)){
			$id = '';
			$mysqli_data[] = 'ID Missing';
		} else {
			$query = "SELECT value FROM setting WHERE id = '".$id."'";
			$sql = $connect->query($query);
			if(!$sql)
			{
				$mysqli_data[] = 'cannot to connect database';

			} else {
				while($row = $sql->fetch_array()) {
					$obj = json_decode($row['value']);
					$mysqli_data[] = array(
						'field'		=> $obj->{'input'}
					);
				}
			}
		}

	} else {
		$mysqli_data[] = 'not allowed';
	}

	mysqli_close($connect);
	$data = array(
		"data"	=> $mysqli_data
	);

  	///////////////////////////
  	// Convert PHP array to JSON array
	//////////////////////////

	foreach ($data as $value) {
		$json_data = json_encode($value);
	}
		print_r($json_data);
	
} else {
	echo "Not allowed";
}

?>