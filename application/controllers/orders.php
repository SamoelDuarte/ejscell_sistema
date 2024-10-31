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
		$this->load->model('order'); // Modelo para interagir com os itens
		// Busca todos os pedidos no banco de dados usando o modelo
		$data['orders'] = $this->order->get_all_orders();

		// Carrega a view de listagem de pedidos e passa os dados
		$this->load->view('orders/manage', $data);
	}
}
