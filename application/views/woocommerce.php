<?php $this->load->view("partial/header"); ?>
<div id="page_title">WooCommerce</div>

<fieldset>
	<legend>Sincronização de Categorias</legend>

	<div>
		<button class="submit_button" style="margin-top: 20px;" onclick="window.location.href='<?php echo site_url('woocommerce/sync_categories'); ?>'">
			Sincronizar Categorias do Produtos
		</button>
	</div>

	<div style="margin-top: 20px;">
		<h1>Sincronização de Categorias</h1>
		<p>Total de categorias: <?= $total_categories ?></p>
		<p>Total de categorias sincronizadas: <?= $sync_count ?></p>
		<p>Total de categorias não sincronizadas: <?= $unsync_count ?></p>

	</div>
</fieldset>

<?php $this->load->view("partial/footer"); ?>