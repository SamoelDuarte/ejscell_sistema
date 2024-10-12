<?php
class Item extends CI_Model
{
	/*
	Determines if a given item_id is an item
	*/
	function exists($item_id)
	{
		$this->db->from('items');
		$this->db->where('item_id', $item_id);
		$query = $this->db->get();

		return ($query->num_rows() == 1);
	}

	/*
	Returns all the items
	*/
	function get_all($limit = 10000, $offset = 0)
	{
		$this->db->from('items');
		$this->db->where('deleted', 0);
		$this->db->order_by("name", "asc");
		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();
	}

	// Busca as imagens de um item
	public function get_item_images($item_id)
	{
		$this->db->from('item_images');
		$this->db->where('item_id', $item_id);
		$query = $this->db->get();
		return $query->result_array(); // Retorna um array com as URLs das imagens
	}

	function count_all()
	{
		$this->db->from('items');
		$this->db->where('deleted', 0);
		return $this->db->count_all_results();
	}

	function get_all_filtered($no_description, $low_inventory = 0, $is_serialized = 0)
	{
		$this->db->from('items');

		if ($low_inventory != 0) {
			$this->db->where('quantity <=', 'reorder_level', false);
		}

		if ($is_serialized != 0) {
			$this->db->where('is_serialized', 1);
		}

		if ($no_description != 0) {
			$this->db->where('description', '');
		}

		$this->db->where('deleted', 0);
		$this->db->order_by("name", "asc");
		return $this->db->get();
	}


	/*
	Gets information about a particular item
	*/
	function get_info($item_id)
	{
		$this->db->from('items');
		$this->db->where('item_id', $item_id);

		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->row();
		} else {
			//Get empty base parent object, as $item_id is NOT an item
			$item_obj = new stdClass();

			//Get all the fields from items table
			$fields = $this->db->list_fields('items');

			foreach ($fields as $field) {
				$item_obj->$field = '';
			}

			return $item_obj;
		}
	}

	/*
	Get an item id given an item number
	*/
	function get_item_id($item_number)
	{
		$this->db->from('items');
		$this->db->where('item_number', $item_number);

		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->row()->item_id;
		}

		return false;
	}

	/*
	Gets information about multiple items
	*/
	function get_multiple_info($item_ids)
	{
		$this->db->from('items');
		$this->db->where_in('item_id', $item_ids);
		$this->db->order_by("item", "asc");
		return $this->db->get();
	}

	/*
	Inserts or updates a item
	*/
	function save(&$item_data, $item_id = false, $images = array(), $cover_image_index = null)
	{
		// Verifica se é um novo item ou um item existente
		if (!$item_id || !$this->exists($item_id)) {
			if ($this->db->insert('items', $item_data)) {
				$item_id = $this->db->insert_id();  // Obtém o novo item_id
			} else {
				return false;
			}
		} else {
			$this->db->where('item_id', $item_id);
			$this->db->update('items', $item_data);
		}

		// Se houver imagens para serem salvas
		if (!empty($images)) {
			$this->save_item_images($item_id, $images, $cover_image_index);  // Chama a função para salvar as imagens
		}

		return true;
	}
	function save_item_images($item_id, $images, $cover_image_index = null)
	{

		print_r($images);
		exit;
		$this->load->library('upload');

		// Itera sobre as imagens para salvá-las no banco de dados
		foreach ($images['tmp_name'] as $key => $image) {
			if (is_uploaded_file($image)) {
				$image_name = $images['name'][$key];
				$upload_path = 'uploads/items/' . $image_name;

				// Mova a imagem para o diretório correto
				move_uploaded_file($image, $upload_path);

				// Dados da imagem para inserir no banco
				$image_data = array(
					'item_id' => $item_id,
					'file_name' => $image_name,
					'file_path' => $upload_path,
					'is_cover' => ($key == $cover_image_index) ? 1 : 0,  // Define se é capa
				);

				// Insere a imagem no banco de dados
				$this->db->insert('ejs_item_images', $image_data);
			}
		}

		// Atualiza outras imagens para não serem capa (caso já exista capa)
		if ($cover_image_index !== null) {
			$this->db->where('item_id', $item_id);
			$this->db->where('id !=', $this->db->insert_id());  // Exclui a nova imagem de capa
			$this->db->update('ejs_item_images', array('is_cover' => 0));
		}
	}



	/*
	Updates multiple items at once
	*/
	function update_multiple($item_data, $item_ids)
	{
		$this->db->where_in('item_id', $item_ids);
		return $this->db->update('items', $item_data);
	}

	/*
	Deletes one item
	*/
	function delete($item_id)
	{
		$this->db->where('item_id', $item_id);
		return $this->db->update('items', array('deleted' => 1));
	}

	/*
	Deletes a list of items
	*/
	function delete_list($item_ids)
	{
		$this->db->where_in('item_id', $item_ids);
		return $this->db->update('items', array('deleted' => 1));
	}

	/*
	Get search suggestions to find items
	*/
	function get_search_suggestions($search, $limit = 25)
	{
		$suggestions = array();

		// Busca por nome
		$this->db->from('items');
		$this->db->like('name', $search);
		$this->db->where('deleted', 0);
		$this->db->order_by("name", "asc");
		$by_name = $this->db->get();
		foreach ($by_name->result() as $row) {
			$suggestions[] = $row->name;
		}

		// Busca por categoria
		$this->db->select('category');
		$this->db->from('items');
		$this->db->where('deleted', 0);
		$this->db->distinct();
		$this->db->like('category', $search);
		$this->db->order_by("category", "asc");
		$by_category = $this->db->get();
		foreach ($by_category->result() as $row) {
			$suggestions[] = $row->category;
		}

		// Busca por número do item
		$this->db->from('items');
		$this->db->like('item_number', $search);
		$this->db->where('deleted', 0);
		$this->db->order_by("item_number", "asc");
		$by_item_number = $this->db->get();
		foreach ($by_item_number->result() as $row) {
			$suggestions[] = $row->item_number;
		}

		// Limitar sugestões ao valor de $limit
		if (count($suggestions) > $limit) {
			$suggestions = array_slice($suggestions, 0, $limit);
		}

		return $suggestions;
	}


	function get_item_search_suggestions($search, $limit = 25)
	{
		$suggestions = array();

		$this->db->from('items');
		$this->db->where('deleted', 0);
		$this->db->like('name', $search);
		$this->db->order_by("name", "asc");
		$by_name = $this->db->get();
		foreach ($by_name->result() as $row) {
			$suggestions[] = $row->item_id . '|' . $row->name;
		}

		$this->db->from('items');
		$this->db->where('deleted', 0);
		$this->db->like('item_number', $search);
		$this->db->order_by("item_number", "asc");
		$by_item_number = $this->db->get();
		foreach ($by_item_number->result() as $row) {
			$suggestions[] = $row->item_id . '|' . $row->item_number;
		}

		//only return $limit suggestions
		if (count($suggestions > $limit)) {
			$suggestions = array_slice($suggestions, 0, $limit);
		}
		return $suggestions;
	}

	function get_category_suggestions($search)
	{
		$suggestions = array();
		$this->db->distinct();
		$this->db->select('category');
		$this->db->from('items');
		$this->db->like('category', $search);
		$this->db->where('deleted', 0);
		$this->db->order_by("category", "asc");
		$by_category = $this->db->get();
		foreach ($by_category->result() as $row) {
			$suggestions[] = $row->category;
		}

		return $suggestions;
	}

	/*
	Preform a search on items
	*/
	function search($search)
	{
		$this->db->from('items');
		$this->db->where("(name LIKE '%" . $this->db->escape_like_str($search) . "%' or 
		item_number LIKE '%" . $this->db->escape_like_str($search) . "%' or 
		category LIKE '%" . $this->db->escape_like_str($search) . "%') and deleted=0");
		$this->db->order_by("name", "asc");
		return $this->db->get();
	}

	function get_categories()
	{
		$this->db->select('category');
		$this->db->from('items');
		$this->db->where('deleted', 0);
		$this->db->distinct();
		$this->db->order_by("category", "asc");

		return $this->db->get();
	}
}
