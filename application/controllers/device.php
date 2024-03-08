<?php

class Device extends CI_Controller
{
    public function getStatus()
    {

        $url = "http://saboresdonordeste.com.br/sessions/" . $_GET['sessionId'] . "/status";

        $headers = array(
            'secret: $2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC'
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Erro na requisição cURL: ' . curl_error($ch);
        }

        curl_close($ch);

        // A variável $response contém a resposta da requisição
        // Você pode processar os dados recebidos conforme necessário
        echo $response;
    }

    public function updateStatus()
    {
        $this->load->model('Device_model');
    
      
                // Define o array com todos os dados
                $data = array(
                    'status' => $this->input->post('status'),
                    'name' => $this->input->post('name'),
                    'jid' => $this->input->post('jid'),
                    'picture' => $this->input->post('picture'),
                    
                );
    print_r($_POST);
                // Chama o método update do modelo Device_model
                $this->Device_model->update($data,$this->input->post('id'));
    
                // Retorna uma resposta JSON
            }
}
