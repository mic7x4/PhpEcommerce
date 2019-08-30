<?php 
	$db=mysqli_connect('localhost','root','','ecommerce');
	if(mysqli_connect_errno()){
		echo 'Database Connection Failed.. with the Following Errors'.mysqli_connect_error();
		die();
	}
require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/config.php';
require_once(BASEURL.'helpers/helpers.php');