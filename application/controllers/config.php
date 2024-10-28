<?php
require_once("secure_area.php");
require_once("Utils.php");


use Automattic\WooCommerce\Client;

class Config extends Secure_area
{
	function __construct()
	{
		parent::__construct('config');
		$this->load->model('device_model');
		$this->load->model('categorie');
	}

	public function sync_categories()
	{
		// Passo 1: Buscar todos os produtos
		$products = $this->Item->get_all(); // Presume que você tenha um método para obter todos os produtos

		foreach ($products->result() as $product) {


			// Pega a categoria do produto como texto
			$category_name = $product->categorie; // Ajuste para o nome correto da coluna

			// Passo 2: Verificar se a categoria existe na tabela ejs_categories_products
			$category = $this->categorie->get_by_name($category_name); // Presume que você tenha um método para buscar categorias pelo nome

			// Passo 3: Se a categoria não existir, insira-a
			if (!$category) {
				$category_name = ucwords(strtolower($category_name)); // Torna a primeira letra de cada palavra maiúscula
				$category_id = $this->categorie->insert([
					'category_name' => $category_name
				]); // Insere a nova categoria e captura o ID
			} else {
				$category_id = $category->categorie_id; // Categoria já existe, captura o ID existente
			}

			// Passo 4: Atualizar o produto com o category_id
			$this->Item->update_category_id($product->item_id, $category_id); // Atualiza o produto com o novo category_id
		}
	}

	public function index()
	{


		$issession = $this->device_model->getSession();


		if (!$issession) {
			$this->device_model->deleteRowsWithStatusNotOne();
			// Cria uma nova sessão e salva no banco de dados
			$session = Utils::createCode();
			$data = array(
				"session" => $session
			);
			$id = $this->device_model->save($data);

			// Obtém o caminho da imagem do QR code
			$qrcodeImgSrc = $this->getQrCode($session);

			// Carrega a view com os dados necessários
			$send = array(
				"qrcodeImgSrc" => $qrcodeImgSrc,
				"session" => $session,
				"device" => $id
			);
		} else {
			$send = array(
				"qrcodeImgSrc" => '',
				"session" => '',
				"device" => ''
			);
		}




		$this->load->view("config", $send);
	}

	function getQrCode($session)
	{


		// URL da requisição
		$url =  'http://aluguelmetaverso.com.br/sessions/add';

		// Dados da requisição
		$data = array(
			'sessionId' => $session // Substitua $session pela sua variável contendo os dados
		);

		// Configuração da requisição
		$options = array(
			CURLOPT_URL => $url,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => json_encode($data),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => array(
				'secret: $2a$12$VruN7Mf0FsXW2mR8WV0gTO134CQ54AmeCR.ml3wgc9guPSyKtHMgC',
				'Content-Type: application/json'
			)
		);

		// Inicializar a sessão curl
		$ch = curl_init();

		// Configurar as opções do curl
		curl_setopt_array($ch, $options);

		// Executar a requisição e obter a resposta
		$response = curl_exec($ch);

		// Verificar se ocorreu algum erro
		if (curl_errno($ch)) {
			echo 'Erro na requisição: ' . curl_error($ch);
		}

		// Fechar a sessão curl
		curl_close($ch);

		// Tratar a resposta (no caso de JSON, decodificar o JSON)
		$result = json_decode($response, true);

		// Exemplo de utilização dos dados da resposta
		if (isset($result['qr'])) {
			return   $result['qr'];
			// Faça o que for necessário com a imagem do QR code
		}

		return false;
	}
	function save()
	{
		$batch_save_data = array(
			'company' => $this->input->post('company'),
			'address' => $this->input->post('address'),
			'phone' => $this->input->post('phone'),
			'email' => $this->input->post('email'),
			'fax' => $this->input->post('fax'),
			'website' => $this->input->post('website'),
			'default_tax_1_rate' => $this->input->post('default_tax_1_rate'),
			'default_tax_1_name' => $this->input->post('default_tax_1_name'),
			'default_tax_2_rate' => $this->input->post('default_tax_2_rate'),
			'default_tax_2_name' => $this->input->post('default_tax_2_name'),
			'currency_symbol' => $this->input->post('currency_symbol'),
			'return_policy' => $this->input->post('return_policy'),
			'language' => $this->input->post('language'),
			'timezone' => $this->input->post('timezone'),
			'print_after_sale' => $this->input->post('print_after_sale')
		);

		if ($this->Appconfig->batch_save($batch_save_data)) {
			echo json_encode(array('success' => true, 'message' => $this->lang->line('config_saved_successfully')));
		}
	}
}
