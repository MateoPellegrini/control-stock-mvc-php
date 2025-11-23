<?php
// views/products/index.php
function renderCategoryTree($nodes, $counts, $level = 0) {
    if (!$nodes) return;
    echo '<ul class="list-unstyled ms-' . ($level * 3) . '">';
    foreach ($nodes as $cat) {
        $count = $counts[$cat['id']] ?? 0;
        echo '<li class="mb-1">';
        echo '<span class="text-slate-100 text-sm">';
        echo str_repeat('&raquo; ', max(0, $level));
        echo htmlspecialchars($cat['nombre'], ENT_QUOTES, 'UTF-8');
        echo ' <span class="badge bg-primary bg-opacity-75 text-[10px]">' . $count . ' prod.</span>';
        echo '</span> ';
        echo '<a href="index.php?controller=products&action=showCategory&id=' . (int)$cat['id'] . '" class="text-xs text-sky-300 ms-2">ver rama</a>';
        if (!empty($cat['children'])) {
            renderCategoryTree($cat['children'], $counts, $level + 1);
        }
        echo '</li>';
    }
    echo '</ul>';
}
?>
<?php $role = $_SESSION['role_name'] ?? null; ?>
<div class="container mx-auto px-4 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-white/10 backdrop-blur rounded-3xl border border-white/10 p-4 text-slate-100 shadow-xl">
            <h2 class="text-lg font-semibold mb-2">
                Categorías de productos
            </h2>
            <p class="text-xs text-slate-300 mb-3">
                Estructura padre / subcategoría. El badge indica la cantidad de productos en cada categoría (solo directos).
            </p>
            <?php renderCategoryTree($tree, $productCounts); ?>
        </div>

        <div class="lg:col-span-2 bg-white/10 backdrop-blur rounded-3xl border border-white/10 p-4 text-slate-100 shadow-xl">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold">
                    Productos
                </h2>
                <div class="space-x-2">
                    <?php if (in_array($role, ['admin', 'compras'], true)): ?>
                        <a href="index.php?controller=purchases&action=create"
                        class="btn btn-sm btn-outline-success text-xs">
                            + Compra
                        </a>
                    <?php endif; ?>
                    <?php if (in_array($role, ['admin', 'ventas'], true)): ?>
                        <a href="index.php?controller=sales&action=create"
                        class="btn btn-sm btn-outline-danger text-xs">
                            + Venta
                        </a>
                    <?php endif; ?>
                    <?php if (in_array($role, ['admin', 'compras'], true)): ?>
                        <a href="index.php?controller=products&action=create"
                        class="btn btn-sm btn-primary text-xs">
                            + Nuevo producto
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="table-responsive text-xs">
                <table class="table table-dark table-hover table-sm align-middle mb-0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>SKU</th>
                        <th>Stock</th>
                        <th>Precio venta</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($productos as $p): ?>
                        <tr>
                            <td><?php echo (int)$p['id']; ?></td>
                            <td><?php echo htmlspecialchars($p['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($p['categoria_nombre'] ?? 'Sin categoría', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($p['sku'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo (int)$p['stock_actual']; ?></td>
                            <td>$<?php echo number_format($p['precio_venta'], 2, ',', '.'); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $p['estado'] === 'activo' ? 'success' : 'secondary'; ?> bg-opacity-75 text-[10px]">
                                    <?php echo htmlspecialchars($p['estado'], ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">         
                                    <a href="index.php?controller=products&action=history&id=<?php echo (int)$p['id']; ?>"
                                    class="btn btn-sm btn-outline-info text-[10px]">
                                        Historial
                                    </a>
                                    <?php if (in_array($role, ['admin', 'compras'], true)): ?>
                                        <a href="index.php?controller=products&action=edit&id=<?php echo (int)$p['id']; ?>"
                                        class="btn btn-sm btn-outline-light text-[10px] mb-1">
                                            Editar
                                        </a>
                                        <a href="index.php?controller=products&action=delete&id=<?php echo (int)$p['id']; ?>"
                                        class="btn btn-sm btn-outline-danger text-[10px]"
                                        onclick="return confirm('¿Seguro que querés eliminar este producto?');">
                                            Eliminar
                                        </a>
                                    <?php else: ?>
                                        <span class="text-[10px] text-slate-400">Solo lectura</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
