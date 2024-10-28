<?php
require_once("secure_area.php");
class Orders extends Secure_area
{
	function __construct()
	{
		parent::__construct('orders');
	}

	function index()
	{
		
	}

}
