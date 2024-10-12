<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Automattic\WooCommerce\Client;

require_once __DIR__ . '/../../vendor/autoload.php';

class WooCommerce
{

    protected $client;

    public function __construct()
    {
        // Defina as credenciais da API do WooCommerce
        $this->client = new Client(
            'https://ejscell.com.br',
            CONSUMER_KEY_WC,  // Defina suas constantes ou variáveis
            CONSUMER_SECRET_WC,
            [
                'version' => 'wc/v3',
            ]
        );
    }

    // Função para obter os produtos
    public function get_products()
    {
        return $this->client->get('products');
    }

    // Adicione mais funções conforme necessário
    public function get_orders()
    {
        return $this->client->get('orders');
    }

    // Outra função de exemplo para criar um produto
    public function create_product($data)
    {
        return $this->client->post('products', $data);
    }

    // Função para atualizar um produto
    public function update_product($product_id, $data)
    {
        return $this->client->put('products/' . $product_id, $data);
    }
}
