<?php
// views/products/category.php
?>
<div class="container mx-auto px-4 py-10">
    <div class="max-w-5xl mx-auto bg-white/10 backdrop-blur rounded-3xl border border-white/10 p-6 text-slate-100 shadow-xl">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-2xl font-semibold mb-1">
                    Categoría: <?php echo htmlspecialchars($category['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                </h2>
                <p class="text-xs text-slate-300">
                    ID #<?php echo (int)$category['id']; ?>
                    <?php if ($category['parent_id']): ?>
                        · Subcategoría de ID #<?php echo (int)$category['parent_id']; ?>
                    <?php else: ?>
                        · Categoría padre
                    <?php endif; ?>
                </p>
            </div>
            <div class="text-right">
                <a href="index.php?controller=products&action=index"
                   class="btn btn-sm btn-outline-light text-xs mb-2">
                    &larr; Volver a productos
                </a>
                <div>
                    <button class="btn btn-sm btn-primary text-xs" disabled>
                        Editar categoría (futuro)
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="bg-slate-900/60 rounded-2xl p-4 border border-slate-700">
                <h3 class="text-lg font-semibold mb-2">Subcategorías</h3>
                <?php if (empty($children)): ?>
                    <p class="text-xs text-slate-300 mb-0">
                        Esta categoría no tiene subcategorías.
                    </p>
                <?php else: ?>
                    <div class="table-responsive text-xs">
                        <table class="table table-dark table-sm align-middle mb-0">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Productos</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($children as $child): ?>
                                <tr>
                                    <td><?php echo (int)$child['id']; ?></td>
                                    <td><?php echo htmlspecialchars($child['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-75 text-[10px]">
                                            <?php echo (int)($counts[$child['id']] ?? 0); ?> prod.
                                        </span>
                                    </td>
                                    <td>
                                        <a href="index.php?controller=products&action=showCategory&id=<?php echo (int)$child['id']; ?>"
                                           class="btn btn-sm btn-outline-light text-[10px]">
                                            Ver rama
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <div class="bg-slate-900/60 rounded-2xl p-4 border border-slate-700">
                <h3 class="text-lg font-semibold mb-2">Productos en esta categoría</h3>
                <?php if (empty($productos)): ?>
                    <p class="text-xs text-slate-300 mb-0">
                        No hay productos asociados directamente a esta categoría.
                    </p>
                <?php else: ?>
                    <div class="table-responsive text-xs">
                        <table class="table table-dark table-sm align-middle mb-0">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>SKU</th>
                                <th>Stock</th>
                                <th>Precio venta</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($productos as $p): ?>
                                <tr>
                                    <td><?php echo (int)$p['id']; ?></td>
                                    <td><?php echo htmlspecialchars($p['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($p['sku'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo (int)$p['stock_actual']; ?></td>
                                    <td>$<?php echo number_format($p['precio_venta'], 2, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
