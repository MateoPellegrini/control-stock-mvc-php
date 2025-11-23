<?php
// views/products/form.php
$mode    = $mode ?? 'create';
$product = $product ?? null;

$isEdit = $mode === 'edit';

$values = [
    'id'            => $product['id']            ?? '',
    'nombre'        => $product['nombre']        ?? '',
    'sku'           => $product['sku']           ?? '',
    'codigo_barras' => $product['codigo_barras'] ?? '',
    'categoria_id'  => $product['categoria_id']  ?? '',
    'precio_compra' => $product['precio_compra'] ?? '0',
    'precio_venta'  => $product['precio_venta']  ?? '0',
    'stock_actual'  => $product['stock_actual']  ?? '0',
    'stock_minimo'  => $product['stock_minimo']  ?? '0',
    'estado'        => $product['estado']        ?? 'activo',
];

$error   = $_SESSION['error']   ?? null;
$success = $_SESSION['success'] ?? null;
unset($_SESSION['error'], $_SESSION['success']);

function renderCategoryOptions($nodes, $selectedId = null, $level = 0) {
    foreach ($nodes as $cat) {
        $prefix = str_repeat('— ', $level);
        $selected = ($selectedId == $cat['id']) ? 'selected' : '';
        echo '<option value="' . (int)$cat['id'] . '" ' . $selected . '>';
        echo htmlspecialchars($prefix . $cat['nombre'], ENT_QUOTES, 'UTF-8');
        echo '</option>';
        if (!empty($cat['children'])) {
            renderCategoryOptions($cat['children'], $selectedId, $level + 1);
        }
    }
}
// Verificar si el usuario tiene permiso para editar el stock
$role = $_SESSION['role_name'] ?? null;
$canEditStock = in_array($role, ['admin', 'compras'], true);

?>
<div class="container mx-auto px-4 py-10">
    <div class="max-w-3xl mx-auto bg-white/10 backdrop-blur rounded-3xl border border-white/10 p-6 text-slate-100 shadow-xl">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-semibold mb-0">
                <?php echo $isEdit ? 'Editar producto' : 'Nuevo producto'; ?>
            </h2>
            <div class="space-x-2">
                <?php if ($isEdit): ?>
                    <a href="index.php?controller=products&action=history&id=<?php echo (int)$values['id']; ?>"
                    class="btn btn-sm btn-outline-info text-xs">
                        Historial
                    </a>
                <?php endif; ?>
                <a href="index.php?controller=products&action=index"
                class="btn btn-sm btn-outline-light text-xs">
                    &larr; Volver al listado
                </a>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger text-sm">
                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success text-sm">
                <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <form action="index.php?controller=products&action=<?php echo $isEdit ? 'update' : 'store'; ?>"
              method="POST"
              class="space-y-3">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?php echo (int)$values['id']; ?>">
            <?php endif; ?>

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label text-xs text-slate-200">Nombre</label>
                    <input type="text" name="nombre" class="form-control form-control-sm bg-slate-900/70 border-slate-600 text-slate-100"
                           value="<?php echo htmlspecialchars($values['nombre'], ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                <?php if ($isEdit): ?>
                <div class="col-md-4">
                    <label class="form-label text-xs text-slate-200">SKU (automático)</label>
                    <input type="text"
                        class="form-control form-control-sm bg-slate-900/70 border-slate-600 text-slate-400"
                        value="<?php echo htmlspecialchars($values['sku'], ENT_QUOTES, 'UTF-8'); ?>"
                        readonly>
                </div>
                <?php endif; ?>

                <div class="col-md-4">
                    <label class="form-label text-xs text-slate-200">Código de barras (opcional)</label>
                    <input type="text" name="codigo_barras"
                        class="form-control form-control-sm bg-slate-900/70 border-slate-600 text-slate-100"
                        value="<?php echo htmlspecialchars($values['codigo_barras'], ENT_QUOTES, 'UTF-8'); ?>">
                </div>

                <div class="col-md-8">
                    <label class="form-label text-xs text-slate-200">Categoría</label>
                    <select name="categoria_id" class="form-select form-select-sm bg-slate-900/70 border-slate-600 text-slate-100">
                        <option value="">Sin categoría</option>
                        <?php renderCategoryOptions($tree, $values['categoria_id']); ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label text-xs text-slate-200">Precio compra</label>
                    <input type="number" step="0.01" min="0" name="precio_compra"
                           class="form-control form-control-sm bg-slate-900/70 border-slate-600 text-slate-100"
                           value="<?php echo htmlspecialchars($values['precio_compra'], ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-xs text-slate-200">Precio venta</label>
                    <input type="number" step="0.01" min="0" name="precio_venta"
                           class="form-control form-control-sm bg-slate-900/70 border-slate-600 text-slate-100"
                           value="<?php echo htmlspecialchars($values['precio_venta'], ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-xs text-slate-200">Stock actual</label>
                    <input type="number" min="0" name="stock_actual"
                        class="form-control form-control-sm bg-slate-900/70 border-slate-600 text-slate-100"
                        value="<?php echo htmlspecialchars($values['stock_actual'], ENT_QUOTES, 'UTF-8'); ?>"
                        <?php echo $canEditStock ? '' : 'readonly'; ?>>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-xs text-slate-200">Stock mínimo</label>
                    <input type="number" min="0" name="stock_minimo"
                        class="form-control form-control-sm bg-slate-900/70 border-slate-600 text-slate-100"
                        value="<?php echo htmlspecialchars($values['stock_minimo'], ENT_QUOTES, 'UTF-8'); ?>"
                        <?php echo $canEditStock ? '' : 'readonly'; ?>>
                </div>

                <div class="col-md-4">
                    <label class="form-label text-xs text-slate-200">Estado</label>
                    <select name="estado" class="form-select form-select-sm bg-slate-900/70 border-slate-600 text-slate-100">
                        <option value="activo"   <?php echo $values['estado']==='activo'   ? 'selected' : ''; ?>>Activo</option>
                        <option value="inactivo" <?php echo $values['estado']==='inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 flex justify-end gap-2">
                <button type="submit" class="btn btn-sm btn-primary">
                    <?php echo $isEdit ? 'Guardar cambios' : 'Crear producto'; ?>
                </button>
            </div>
        </form>
    </div>
</div>
