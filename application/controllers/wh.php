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
        $decoded_data = json_decode($data, true);

        try {
            // Inicia a transação
            $this->db->trans_begin();

            // Extrai as informações do pedido
            $order_id = $decoded_data['id'];
            $status = $decoded_data['status'];
            $created_at = date('Y-m-d H:i:s', strtotime($decoded_data['date_created']));
            $updated_at = date('Y-m-d H:i:s', strtotime($decoded_data['date_modified']));
            $total_amount = $decoded_data['total'];
            $discount_total = $decoded_data['discount_total'];
            $shipping_cost = $decoded_data['shipping_total'];
            $customer_id = $decoded_data['customer_id'];
            $payment_method = $decoded_data['payment_method'];
            $customer_ip = $decoded_data['customer_ip_address'];
            $customer_user_agent = $decoded_data['customer_user_agent'];
            $order_key = $decoded_data['order_key'];
            $order_number = $decoded_data['number'];

            // Verifica se o pedido já existe no banco de dados
            $this->db->where('order_id', $order_id);
            $query = $this->db->get('ejs_orders');

            if ($query->num_rows() > 0) {
                // Atualiza o pedido
                $data = array(
                    'status' => $status,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                    'total_amount' => $total_amount,
                    'discount_total' => $discount_total,
                    'shipping_cost' => $shipping_cost,
                    'customer_id' => $customer_id,
                    'payment_method' => $payment_method,
                    'customer_ip' => $customer_ip,
                    'customer_user_agent' => $customer_user_agent,
                    'order_key' => $order_key,
                    'order_number' => $order_number
                );
                $this->db->where('order_id', $order_id);
                $this->db->update('ejs_orders', $data);
            } else {
                // Insere um novo pedido
                $data = array(
                    'order_id' => $order_id,
                    'status' => $status,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                    'total_amount' => $total_amount,
                    'discount_total' => $discount_total,
                    'shipping_cost' => $shipping_cost,
                    'customer_id' => $customer_id,
                    'payment_method' => $payment_method,
                    'customer_ip' => $customer_ip,
                    'customer_user_agent' => $customer_user_agent,
                    'order_key' => $order_key,
                    'order_number' => $order_number
                );
                $this->db->insert('ejs_orders', $data);
            }

            // Processa os itens do pedido
            foreach ($decoded_data['line_items'] as $item) {
                $item_id = $item['id'];
                $product_id = $item['product_id'];
                $product_name = $item['name'];
                $quantity = $item['quantity'];
                $price = $item['price'];
                $subtotal = $item['subtotal'];
                $total = $item['total'];

                // Verifica se o item já existe
                $this->db->where('item_id', $item_id);
                $this->db->where('order_id', $order_id);
                $query = $this->db->get('ejs_order_items');

                if ($query->num_rows() > 0) {
                    // Atualiza o item
                    $data = array(
                        'product_id' => $product_id,
                        'product_name' => $product_name,
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $subtotal,
                        'total' => $total
                    );
                    $this->db->where('item_id', $item_id);
                    $this->db->where('order_id', $order_id);
                    $this->db->update('ejs_order_items', $data);
                } else {
                    // Insere um novo item
                    $data = array(
                        'item_id' => $item_id,
                        'order_id' => $order_id,
                        'product_id' => $product_id,
                        'product_name' => $product_name,
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $subtotal,
                        'total' => $total
                    );
                    $this->db->insert('ejs_order_items', $data);
                }
            }

            // Confirma a transação
            if ($this->db->trans_status() === FALSE) {
                // Reverte a transação caso ocorra algum erro
                $this->db->trans_rollback();
                throw new Exception('Erro ao salvar o pedido e seus itens no banco de dados.');
            } else {
                // Confirma a transação
                $this->db->trans_commit();
            }
        } catch (Exception $e) {
            // Log de erro e retorno de mensagem
            log_message('error', 'Erro no processamento do pedido: ' . $e->getMessage());
            echo json_encode(array("error" => "Erro ao processar o pedido: " . $e->getMessage()));
        }
    }
}
