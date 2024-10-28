<?php
require_once("secure_area.php");
require_once("Utils.php");

class Woocommerce extends Secure_area
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Item'); // Modelo para interagir com os itens
		$this->load->model('categorie'); // Modelo para interagir com os itens
	}

	public function index()
	{
		// Busca todas as categorias
		$categories = $this->categorie->get_all(); // Método que busca todas as categorias

		// Inicializa as contagens
		$sync_count = 0; // Contador de categorias sincronizadas
		$unsync_count = 0; // Contador de categorias não sincronizadas

		// Verifica se a consulta retornou resultados
		if (!empty($categories)) {
			foreach ($categories as $category) {
				// Verifica se wc_id é NULL
				if ($category->wc_id) {
					$sync_count++; // Se wc_id não for NULL, conta como sincronizado
				} else {
					$unsync_count++; // Se wc_id for NULL, conta como não sincronizado
				}
			}
		}

		// Dados para a view
		$data['total_categories'] = count($categories);
		$data['sync_count'] = $sync_count; // Total de categorias sincronizadas
		$data['unsync_count'] = $unsync_count; // Total de categorias não sincronizadas

		// Carregar a view com os dados
		$this->load->view("woocommerce", $data);
	}


	private function get_woocommerce_categories()
	{
		$this->load->library('woocommercelibrary');
		// Aqui você deve usar a biblioteca da API WooCommerce para obter as categorias
		// O código pode variar dependendo de como você configurou a API
		try {
			$response = $this->woocommercelibrary->get_products_categories(); // Chamada à API para obter categorias
			// print_r($response);
			return $response; // Supondo que a resposta é um array de categorias
		} catch (Exception $e) {
			// Tratamento de erros
			log_message('error', 'Erro ao obter categorias do WooCommerce: ' . $e->getMessage());
			return []; // Retorna um array vazio em caso de erro
		}
	}

	public function sync_categories()
	{
		// Busca as categorias que não possuem wc_id
		$categories = $this->categorie->get_without_wc_id();

		// Sincroniza categorias
		foreach ($categories as $category) {
			// Categoria não existe no WooCommerce, então cria
			$result = $this->create_woocommerce_category($category);

			// Se a categoria foi criada com sucesso, atualiza o wc_id na tabela local
			if ($result && isset($result->id)) { // Verifica se o resultado é válido e contém o id
				$update_data = [
					'wc_id' => $result->id, // ID retornado do WooCommerce
					'category_id' => $category->category_id, // Outras informações que você deseja atualizar
				];
				$this->categorie->update($update_data,$category->category_id); // Atualiza o wc_id na tabela local
				log_message('info', 'Categoria sincronizada: ' . $category->category_name . ' com wc_id: ' . $result->id);
			} else {
				log_message('error', 'Falha ao criar categoria: ' . $category->category_name);
			}
		}

		// Redireciona para o index após a sincronização
		redirect('woocommerce'); // Ajuste a URL conforme sua configuração
	}


	private function create_woocommerce_category($category)
	{
		$this->load->library('woocommercelibrary');
		try {
			$data = [
				'name' => $category->category_name,
			];
			return $this->woocommercelibrary->create_product_category($data); // Chama a função da biblioteca para criar a categoria
		} catch (Exception $e) {
			log_message('error', 'Erro ao criar categoria no WooCommerce: ' . $e->getMessage());
		}
	}
}
