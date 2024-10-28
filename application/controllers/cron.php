<?php

class Cron extends CI_Controller
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

    public function send_products_to_woocommerce()
    {
        // Carregar a biblioteca WooCommerce
        $this->load->library('woocommercelibrary');

        // Selecionar os 5 primeiros produtos sem id_wc
        $products = $this->Item->get_items_without_wc_id(5);

        // Verifica se existem produtos a serem enviados
        if ($products->num_rows() > 0) {
            foreach ($products->result() as $product) {

                $this->load->model('categorie');
                $category = $this->categorie->get_wc_id($product->item_id);

                // print_r($category);
                // exit;
                // Obter imagens do item
                $images = $this->get_item_images($product->item_id);

                // Preparar os dados do produto para envio ao WooCommerce
                $data = [
                    'name' => $product->name,
                    'type' => 'simple',
                    'regular_price' => (string) $product->unit_price, // WooCommerce espera string
                    'description' => $product->description,
                    'short_description' => $product->description,
                    'sku' => $product->item_number,
                    'manage_stock' => true,
                    'stock_quantity' => (int) $product->quantity,
                    'categories' => [
                        ['id' => (int) $category],  // ID da categoria no WooCommerce
                    ],
                    'images' => $images, // Adiciona as imagens ao array de dados
                ];

                // Enviar o produto para o WooCommerce
                try {
                    $response = $this->wooCommerceLibrary->create_product($data);

                    // Se o produto foi criado com sucesso, obter o id_wc retornado
                    if (!empty($response->id)) {
                        // Atualizar o campo id_wc no banco de dados
                        $this->Item->update_wc_id($product->item_id, $response->id);
                        echo "Produto '{$product->name}' enviado com sucesso! ID do WooCommerce: {$response->id} <br>";
                    }
                } catch (Exception $e) {
                    // Em caso de erro, logar ou manipular a exceção aqui
                    log_message('error', "Erro ao enviar produto '{$product->name}': " . $e->getMessage());
                    echo "Erro ao enviar produto '{$product->name}': " . $e->getMessage() . "\n";
                }
            }
        } else {
            echo "Nenhum produto sem id_wc encontrado.\n";
        }
    }

    // Função para obter as imagens do item
    private function get_item_images($item_id)
    {
        // Seleciona as imagens do banco de dados
        $this->db->select('image_path');
        $this->db->from('item_images');
        $this->db->where('item_id', $item_id);
        $query = $this->db->get();

        $images = [];

        // Organiza as imagens
        foreach ($query->result() as $row) {
            $images[] = [
                'src' => base_url($row->image_path), // Converte o caminho relativo para um URL absoluto
            ];
        }

        return $images;
    }
}
