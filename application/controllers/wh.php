<?php

class WH extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function woocommerceOrders()
    {
        // Lê o conteúdo da requisição (JSON enviado pelo WooCommerce)
        $data = file_get_contents('php://input');

        // Log do JSON recebido
        log_message('error', 'WooCommerce Order JSON recebido: ' . $data);

        // Decodifica o JSON para um array associativo
        $decoded_data = json_decode($data, true);

        // Verificação de erro na decodificação JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            log_message('error', 'Erro ao decodificar JSON: ' . json_last_error_msg());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Erro ao processar JSON.']);
            return;
        }

        // Verifica se o pedido existe pelo 'order_id'
        $order_id = $decoded_data['id'];

        // Conexão com o banco de dados
        $this->load->database();

        // Verifica se o pedido já existe na tabela 'ejs_orders'
        $existing_order = $this->db->get_where('ejs_orders', ['order_id' => $order_id])->row();

        if ($existing_order) {
            // Atualiza o pedido se ele já existe
            $this->db->where('order_id', $order_id);
            $this->db->update('ejs_orders', [
                'status' => $decoded_data['status'],
                'total_amount' => $decoded_data['total'],
                'shipping_cost' => $decoded_data['shipping_total'],
                'created_at' => $decoded_data['date_created'],
                'updated_at' => $decoded_data['date_modified']
            ]);

            log_message('error', 'Pedido atualizado com sucesso: ID ' . $order_id);
        } else {
            // Insere o pedido se ele não existe
            $this->db->insert('ejs_orders', [
                'order_id' => $order_id,
                'customer_name' => $decoded_data['billing']['first_name'] . ' ' . $decoded_data['billing']['last_name'],
                'status' => $decoded_data['status'],
                'total_amount' => $decoded_data['total'],
                'shipping_cost' => $decoded_data['shipping_total'],
                'created_at' => $decoded_data['date_created'],
                'updated_at' => $decoded_data['date_modified']
            ]);

            log_message('error', 'Pedido inserido com sucesso: ID ' . $order_id);
        }

        // Processa os itens do pedido
        foreach ($decoded_data['line_items'] as $item) {
            $item_id = $item['id'];

            // Verifica se o item já existe na tabela 'ejs_order_items'
            $existing_item = $this->db->get_where('ejs_order_items', ['item_id' => $item_id, 'order_id' => $order_id])->row();

            if ($existing_item) {
                // Atualiza o item se ele já existe
                $this->db->where(['item_id' => $item_id, 'order_id' => $order_id]);
                $this->db->update('ejs_order_items', [
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'total' => $item['total']
                ]);

                log_message('error', 'Item do pedido atualizado com sucesso: Item ID ' . $item_id . ' para Pedido ID ' . $order_id);
            } else {
                // Insere o item se ele não existe
                $this->db->insert('ejs_order_items', [
                    'order_id' => $order_id,
                    'item_id' => $item_id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'total' => $item['total']
                ]);

                log_message('error', 'Item do pedido inserido com sucesso: Item ID ' . $item_id . ' para Pedido ID ' . $order_id);
            }
        }

        // Retorna uma resposta de sucesso para o WooCommerce
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Pedido recebido e processado com sucesso.']);
    }
}
