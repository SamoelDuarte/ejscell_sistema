<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Automattic\WooCommerce\Client;

require_once __DIR__ . '/../../vendor/autoload.php';

class WooCommerceLibrary
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

    // Função para obter os categorias
    public function get_products_categories()
    {
        return $this->client->get('products/categories',['per_page' => 99]);
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

    // Outra função de exemplo para criar um categoria
    public function create_product_category($data)
    {
        return $this->client->post('products/categories', $data);
    }
    // Função para excluir um produto
public function delete_product($product_id)
{
    try {
        // Chama a função de DELETE na API do WooCommerce
        return $this->client->delete('products/' . $product_id);
    } catch (Exception $e) {
        // Em caso de erro, log o erro e retorne false
        log_message('error', 'Erro ao excluir o produto no WooCommerce: ' . $e->getMessage());
        return false;
    }
}
}
