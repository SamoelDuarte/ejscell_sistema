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
    
        // Decodifica o JSON para um array associativo
        $decoded_data = json_decode($data, true);
    
        // Verifica se o pedido existe pelo 'order_id' (substitua 'order_id' pelo campo correto caso necessário)
        $order_id = $decoded_data['id'];
    
        // Conexão com o banco de dados (garanta que você possui uma conexão configurada corretamente)
        $this->load->database();
    
        // Verifica se o pedido já existe
        $existing_order = $this->db->get_where('orders', ['order_id' => $order_id])->row();
    
        if ($existing_order) {
            // Atualiza o pedido se ele já existe
            $this->db->where('order_id', $order_id);
            $this->db->update('orders', [
                'status' => $decoded_data['status'],
                'total_amount' => $decoded_data['total'],
                'shipping_cost' => $decoded_data['shipping_total'],
                'created_at' => $decoded_data['date_created'],
                'updated_at' => $decoded_data['date_modified']
            ]);
        } else {
            // Insere o pedido se ele não existe
            $this->db->insert('orders', [
                'order_id' => $order_id,
                'customer_name' => $decoded_data['billing']['first_name'] . ' ' . $decoded_data['billing']['last_name'],
                'status' => $decoded_data['status'],
                'total_amount' => $decoded_data['total'],
                'shipping_cost' => $decoded_data['shipping_total'],
                'created_at' => $decoded_data['date_created'],
                'updated_at' => $decoded_data['date_modified']
            ]);
        }
    
        // Processa os itens do pedido
        foreach ($decoded_data['line_items'] as $item) {
            $item_id = $item['id'];
    
            // Verifica se o item já existe na tabela order_items
            $existing_item = $this->db->get_where('order_items', ['item_id' => $item_id, 'order_id' => $order_id])->row();
    
            if ($existing_item) {
                // Atualiza o item se ele já existe
                $this->db->where(['item_id' => $item_id, 'order_id' => $order_id]);
                $this->db->update('order_items', [
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'total' => $item['total']
                ]);
            } else {
                // Insere o item se ele não existe
                $this->db->insert('order_items', [
                    'order_id' => $order_id,
                    'item_id' => $item_id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'total' => $item['total']
                ]);
            }
        }
    
        // Retorna uma resposta de sucesso para o WooCommerce
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Pedido recebido e processado com sucesso.']);
    }
    
}
