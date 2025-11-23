<?php
// views/dashboard/ventas.php

$productCounts = $productCounts ?? ['activo' => 0, 'inactivo' => 0];
$salesToday    = $salesToday    ?? ['cantidad_ventas' => 0, 'total_vendido' => 0, 'unidades' => 0];
$topSelling    = $topSelling    ?? [];
?>
<div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-semibold text-slate-100 mb-1">
                Dashboard ventas
            </h1>
            <p class="text-xs md:text-sm text-slate-300 mb-0">
                Hola, <span class="font-semibold"><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></span>.
                Rol: <span class="uppercase tracking-wide text-emerald-300 text-[11px]"><?php echo htmlspecialchars($roleName, ENT_QUOTES, 'UTF-8'); ?></span>
            </p>
        </div>
        <div class="text-right space-y-2">
            <a href="index.php?controller=sales&action=create"
               class="btn btn-sm btn-primary text-xs w-full md:w-auto">
                + Registrar venta
            </a>
            <a href="index.php?controller=auth&action=logout"
               class="btn btn-sm btn-outline-light text-xs w-full md:w-auto">
                Cerrar sesión
            </a>
        </div>
    </div>

    <!-- Cards resumen -->
    <div class="row g-3 mb-6">
        <!-- Ventas de hoy -->
        <div class="col-12 col-md-4">
            <div class="bg-slate-900/70 border border-rose-500/40 rounded-3xl p-3 h-100 shadow-lg">
                <p class="text-[11px] text-slate-300 mb-1">Ventas de hoy</p>
                <p class="text-2xl font-semibold text-rose-300 mb-0">
                    $<?php echo number_format($salesToday['total_vendido'] ?? 0, 2, ',', '.'); ?>
                </p>
                <p class="text-[11px] text-slate-400 mb-0">
                    <?php echo (int)($salesToday['cantidad_ventas'] ?? 0); ?> ventas ·
                    <?php echo (int)($salesToday['unidades'] ?? 0); ?> unidades
                </p>
            </div>
        </div>

        <!-- Productos activos -->
        <div class="col-6 col-md-4">
            <div class="bg-slate-900/70 border border-emerald-500/40 rounded-3xl p-3 h-100 shadow-lg">
                <p class="text-[11px] text-slate-300 mb-1">Productos activos</p>
                <p class="text-2xl font-semibold text-emerald-300 mb-0">
                    <?php echo (int)$productCounts['activo']; ?>
                </p>
                <p class="text-[11px] text-slate-400 mb-0">
                    Catálogo disponible para vender
                </p>
            </div>
        </div>

        <!-- Acceso rápido -->
        <div class="col-6 col-md-4">
            <div class="bg-slate-900/70 border border-sky-500/40 rounded-3xl p-3 h-100 shadow-lg flex flex-col justify-between">
                <div>
                    <p class="text-[11px] text-slate-300 mb-1">Accesos rápidos</p>
                    <p class="text-[11px] text-slate-400 mb-2">
                        Operaciones frecuentes del sector ventas.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="index.php?controller=sales&action=create"
                       class="btn btn-sm btn-primary text-[11px]">
                        + Nueva venta
                    </a>
                    <a href="index.php?controller=products&action=index"
                       class="btn btn-sm btn-outline-light text-[11px]">
                        Ver productos
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Top productos vendidos -->
    <div class="row g-3">
        <div class="col-12">
            <div class="bg-slate-900/70 border border-white/10 rounded-3xl p-3 h-100 shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-sm font-semibold text-slate-100 mb-0">
                        Top productos vendidos (últimos 30 días)
                    </h2>
                </div>
                <div class="table-responsive text-xs">
                    <table class="table table-dark table-sm align-middle mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th>Unid. vendidas</th>
                            <th>Total vendido</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($topSelling)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-slate-400 py-2">
                                    Todavía no hay ventas registradas en el período.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $pos = 1; ?>
                            <?php foreach ($topSelling as $row): ?>
                                <tr>
                                    <td class="text-[11px] text-slate-400">
                                        <?php echo $pos++; ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($row['producto_nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                    </td>
                                    <td>
                                        <?php echo (int)$row['unidades_vendidas']; ?>
                                    </td>
                                    <td>
                                        $<?php echo number_format($row['total_vendido'], 2, ',', '.'); ?>
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
