<?php

class WH extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Carregar o modelo de item
        $this->load->model('Item');
        $this->load->library('woocommercelibrary');
        // Atribua a instância à propriedade
        $this->wooCommerceLibrary = new WooCommerceLibrary();
    }

  public function woocommerceOrders(){

  }
}
