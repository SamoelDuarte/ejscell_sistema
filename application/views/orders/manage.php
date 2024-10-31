<?php $this->load->view("partial/header"); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>
    /* Estilos para os status */
    .status-concluido {
        color: green;
        font-weight: bold;
    }

    .status-pendente {
        color: orange;
        font-weight: bold;
    }

    .status-processando {
        color: blue;
        font-weight: bold;
    }

    .status-em-espera {
        color: gray;
        font-weight: bold;
    }

    .status-cancelado {
        color: red;
        font-weight: bold;
    }

    .status-reembolsado {
        color: purple;
        font-weight: bold;
    }

    .status-falhou {
        color: darkred;
        font-weight: bold;
    }
</style>

<div class="container mt-5">
<table id="ordersTable" class="display" style="width:100%">
    <thead>
        <tr>
            <th>ID do Pedido</th>
            <th>Status</th>
            <th>Data</th>
            <th>Valor Total</th>
            <th>Desconto</th>
            <th>Frete</th>
            <th>Método de Pagamento</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?php echo $order['order_id']; ?></td>
                <td class="status-<?php echo strtolower(str_replace(' ', '-', $order['status'])); ?>">
                    <?php echo $order['status']; ?>
                </td>
                <td><?php echo $order['created_at']; ?></td>
                <td><?php echo $order['total_amount']; ?></td>
                <td><?php echo $order['discount_total']; ?></td>
                <td><?php echo $order['shipping_cost']; ?></td>
                <td><?php echo $order['payment_method']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<!-- Script para inicializar o DataTable -->
<script>
    $(document).ready(function() {
        $('#ordersTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Portuguese-Brasil.json"
            }
        });
    });
</script>


<?php $this->load->view("partial/footer"); ?>