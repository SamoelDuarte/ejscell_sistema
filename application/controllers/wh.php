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

        // Converte o array para um formato de texto legível
        $log_data = print_r($decoded_data, true);
        log_message('error', $decoded_data);
        // Define o caminho e o nome do arquivo onde o log será salvo
        $file_path = APPPATH . 'logs/woocommerce_orders_log.txt';

        // Escreve os dados no arquivo de log (apendando caso o arquivo já exista)
        file_put_contents($file_path, $log_data . "\n\n", FILE_APPEND);

        // Retorna uma resposta para o WooCommerce (geralmente 200 OK para confirmar recebimento)
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Webhook recebido e logado com sucesso.']);
    }
}
