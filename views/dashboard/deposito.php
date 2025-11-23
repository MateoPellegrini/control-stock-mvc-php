<?php
// views/dashboard/deposito.php

$productCounts = $productCounts ?? ['activo' => 0, 'inactivo' => 0];
$lowStock      = $lowStock      ?? [];
$lastMovements = $lastMovements ?? [];
?>
<div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-semibold text-slate-100 mb-1">
                Dashboard depósito
            </h1>
            <p class="text-xs md:text-sm text-slate-300 mb-0">
                Hola, <span class="font-semibold"><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></span>.
                Rol: <span class="uppercase tracking-wide text-emerald-300 text-[11px]"><?php echo htmlspecialchars($roleName, ENT_QUOTES, 'UTF-8'); ?></span>
            </p>
        </div>
        <div class="text-right">
            <a href="index.php?controller=auth&action=logout"
               class="btn btn-sm btn-outline-light text-xs w-full md:w-auto">
                Cerrar sesión
            </a>
        </div>
    </div>

    <!-- Cards resumen -->
    <div class="row g-3 mb-6">
        <!-- Productos activos -->
        <div class="col-6 col-md-4">
            <div class="bg-slate-900/70 border border-emerald-500/40 rounded-3xl p-3 h-100 shadow-lg">
                <p class="text-[11px] text-slate-300 mb-1">Productos activos</p>
                <p class="text-2xl font-semibold text-emerald-300 mb-0">
                    <?php echo (int)$productCounts['activo']; ?>
                </p>
                <p class="text-[11px] text-slate-400 mb-0">
                    Ítems que se gestionan en depósito
                </p>
            </div>
        </div>

        <!-- Productos inactivos -->
        <div class="col-6 col-md-4">
            <div class="bg-slate-900/70 border border-slate-500/40 rounded-3xl p-3 h-100 shadow-lg">
                <p class="text-[11px] text-slate-300 mb-1">Productos inactivos</p>
                <p class="text-2xl font-semibold text-slate-200 mb-0">
                    <?php echo (int)$productCounts['inactivo']; ?>
                </p>
                <p class="text-[11px] text-slate-400 mb-0">
                    Ítems que ya no deberían moverse
                </p>
            </div>
        </div>

        <!-- Productos con stock bajo (cantidad) -->
        <div class="col-12 col-md-4">
            <div class="bg-slate-900/70 border border-amber-500/40 rounded-3xl p-3 h-100 shadow-lg">
                <p class="text-[11px] text-slate-300 mb-1">Productos con stock bajo</p>
                <p class="text-2xl font-semibold text-amber-300 mb-0">
                    <?php echo is_array($lowStock) ? count($lowStock) : 0; ?>
                </p>
                <p class="text-[11px] text-slate-400 mb-0">
                    Avisar a compras para reposición
                </p>
            </div>
        </div>
    </div>

    <!-- Productos con stock bajo -->
    <div class="row g-3 mb-6">
        <div class="col-12">
            <div class="bg-slate-900/70 border border-white/10 rounded-3xl p-3 h-100 shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-sm font-semibold text-slate-100 mb-0">
                        Detalle de productos con stock bajo
                    </h2>
                    <a href="index.php?controller=products&action=index"
                       class="text-[11px] text-sky-300 hover:underline">
                        Ver listado completo
                    </a>
                </div>
                <div class="table-responsive text-xs">
                    <table class="table table-dark table-sm align-middle mb-0">
                        <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Stock</th>
                            <th>Mínimo</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($lowStock)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-slate-400 py-2">
                                    No hay productos por debajo del stock mínimo.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($lowStock as $p): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($p['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($p['categoria_nombre'] ?? '—', ENT_QUOTES, 'UTF-8'); ?>
                                    </td>
                                    <td class="text-rose-300">
                                        <?php echo (int)$p['stock_actual']; ?>
                                    </td>
                                    <td><?php echo (int)$p['stock_minimo']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos movimientos de stock -->
    <div class="bg-slate-900/70 border border-white/10 rounded-3xl p-3 shadow-lg">
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-sm font-semibold text-slate-100 mb-0">
                Últimos movimientos de stock
            </h2>
            <span class="text-[11px] text-slate-400">
                Últimos <?php echo isset($lastMovements) ? count($lastMovements) : 0; ?> registros
            </span>
        </div>
        <div class="table-responsive text-xs">
            <table class="table table-dark table-sm align-middle mb-0">
                <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>Usuario</th>
                    <th>Tipo</th>
                    <th>Cant.</th>
                    <th>Stock (antes → desp.)</th>
                    <th>Motivo</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($lastMovements)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-slate-400 py-2">
                            Aún no hay movimientos registrados.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($lastMovements as $m): ?>
                        <?php
                        $badgeClass = 'secondary';
                        switch ($m['tipo']) {
                            case 'compra':        $badgeClass = 'success'; break;
                            case 'venta':         $badgeClass = 'danger';  break;
                            case 'ajuste_manual': $badgeClass = 'warning'; break;
                            case 'correccion':    $badgeClass = 'info';    break;
                        }
                        $cantidad = (int)$m['cantidad'];
                        $signo    = $cantidad >= 0 ? '+' : '-';
                        $absCant  = abs($cantidad);
                        ?>
                        <tr>
                            <td class="text-[11px]">
                                <?php echo htmlspecialchars($m['created_at'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($m['producto_nombre'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($m['username'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo $badgeClass; ?> bg-opacity-75 text-[10px]">
                                    <?php echo htmlspecialchars($m['tipo'], ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                            <td class="<?php echo $signo === '+' ? 'text-emerald-300' : 'text-rose-300'; ?>">
                                <?php echo $signo . $absCant; ?>
                            </td>
                            <td>
                                <?php echo (int)$m['stock_anterior']; ?>
                                &rarr;
                                <?php echo (int)$m['stock_nuevo']; ?>
                            </td>
                            <td class="text-[11px]">
                                <?php if (!empty($m['motivo'])): ?>
                                    <?php echo htmlspecialchars($m['motivo'], ENT_QUOTES, 'UTF-8'); ?>
                                <?php else: ?>
                                    <span class="text-slate-500">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
