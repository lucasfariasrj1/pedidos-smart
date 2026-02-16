<?php
// dist/dashboard_home.php
require_once __DIR__ . '/includes/api_helper.php';

// Pegamos os dados do usuário da sessão para decidir o que exibir
$userRole = $_SESSION['role'] ?? 'user';
$lojaId = $_SESSION['loja_id'] ?? null;

// Buscamos os dados reais da API
$ordersRes = callApi('GET', '/auth/orders');
$pedidos = $ordersRes['ok'] ? $ordersRes['data'] : [];

// Filtramos os pedidos se o usuário não for admin (Segurança adicional no Front)
if ($userRole !== 'admin') {
    $pedidos = array_filter($pedidos, function($p) use ($lojaId) {
        return $p['loja_id'] == $lojaId;
    });
}

// Estatísticas Simples
$totalPedidos = count($pedidos);
$pedidosPendentes = count(array_filter($pedidos, function($p) { 
    return ($p['status'] ?? '') === 'Pendente'; 
}));
?>

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Painel de Controle</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box shadow-sm">
                    <span class="info-box-icon bg-primary shadow-sm"><i class="bi bi-cart-fill"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total de Pedidos</span>
                        <span class="info-box-number"><?= $totalPedidos ?></span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box shadow-sm">
                    <span class="info-box-icon bg-warning shadow-sm"><i class="bi bi-clock-history"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pendentes</span>
                        <span class="info-box-number"><?= $pedidosPendentes ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header border-0">
                        <h3 class="card-title">Pedidos Recentes</h3>
                        <div class="card-tools">
                            <a href="index.php?url=pedidos" class="btn btn-tool btn-sm"> 
                                <i class="bi bi-list"></i> Ver todos 
                            </a>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Peça</th>
                                    <th>Loja</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($pedidos)): ?>
                                    <tr><td colspan="5" class="text-center">Nenhum pedido encontrado.</td></tr>
                                <?php else: ?>
                                    <?php 
                                    // Mostra apenas os 5 últimos
                                    $recentes = array_slice(array_reverse($pedidos), 0, 5);
                                    foreach ($recentes as $p): 
                                    ?>
                                        <tr>
                                            <td>#<?= $p['id'] ?></td>
                                            <td><?= htmlspecialchars($p['peca']) ?></td>
                                            <td>Loja <?= $p['loja_id'] ?></td>
                                            <td>
                                                <span class="badge <?= ($p['status'] == 'Pendente') ? 'text-bg-warning' : 'text-bg-success' ?>">
                                                    <?= $p['status'] ?? 'Pendente' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="index.php?url=pedidos" class="text-secondary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>