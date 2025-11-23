<?php
// views/products/history.php

$role = $_SESSION['role_name'] ?? null;
?>
<div class="container mx-auto px-4 py-10">
    <div class="max-w-5xl mx-auto bg-white/10 backdrop-blur rounded-3xl border border-white/10 p-6 text-slate-100 shadow-xl">
        
        <!-- Encabezado -->
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-2xl font-semibold mb-1">
                    Historial de stock
                </h2>
                <p class="text-xs text-slate-300 mb-0">
                    Producto: <span class="font-semibold">
                        <?php echo htmlspecialchars($product['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                    <?php if (!empty($product['sku'])): ?>
                        · SKU: <span class="text-slate-200">
                            <?php echo htmlspecialchars($product['sku'], ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    <?php endif; ?>
                </p>
                <p class="text-[11px] text-slate-400">
                    Stock actual: <span class="text-slate-100 font-semibold">
                        <?php echo (int)$product['stock_actual']; ?>
                    </span>
                    · Stock mínimo: <span class="text-slate-100 font-semibold">
                        <?php echo (int)$product['stock_minimo']; ?>
                    </span>
                </p>
            </div>

            <div class="text-right space-y-2">
                <a href="index.php?controller=products&action=index"
                   class="btn btn-sm btn-outline-light text-xs">
                    &larr; Volver a productos
                </a>
                <?php if (in_array($role, ['admin', 'compras'], true)): ?>
                    <div>
                        <a href="index.php?controller=products&action=edit&id=<?php echo (int)$product['id']; ?>"
                           class="btn btn-sm btn-primary text-[11px]">
                            Editar producto
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Info -->
        <div class="mb-3">
            <p class="text-[11px] text-slate-300">
                Cada movimiento refleja quién modificó el stock, en qué dirección, cuánto cambió y el stock antes / después.
            </p>
        </div>

        <!-- Tabla de historial -->
        <div class="table-responsive text-xs">
            <table class="table table-dark table-sm align-middle mb-0">
                <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Tipo</th>
                    <th>Cambio</th>
                    <th>Stock (antes → después)</th>
                    <th>Motivo</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($movimientos)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-slate-300 py-3">
                            Aún no hay movimientos registrados para este producto.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($movimientos as $m): ?>
                        <?php
                        $signo = (int)$m['cantidad'] >= 0 ? '+' : '-';
                        $absCantidad = abs((int)$m['cantidad']);

                        // badge para tipo
                        $badgeClass = 'secondary';
                        switch ($m['tipo']) {
                            case 'compra':
                                $badgeClass = 'success';
                                break;
                            case 'venta':
                                $badgeClass = 'danger';
                                break;
                            case 'ajuste_manual':
                                $badgeClass = 'warning';
                                break;
                            case 'correccion':
                                $badgeClass = 'info';
                                break;
                        }
                        ?>
                        <tr>
                            <td class="text-[11px]">
                                <?php echo htmlspecialchars($m['created_at'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td class="text-[11px]">
                                <?php echo htmlspecialchars($m['username'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo $badgeClass; ?> bg-opacity-75 text-[10px]">
                                    <?php echo htmlspecialchars($m['tipo'], ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                            <td class="<?php echo $signo === '+' ? 'text-emerald-300' : 'text-rose-300'; ?>">
                                <?php echo $signo . $absCantidad; ?>
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
                                    <span class="text-slate-400">—</span>
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
