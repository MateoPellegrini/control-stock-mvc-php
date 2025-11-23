<?php
// views/purchases/form.php
$error   = $_SESSION['error']   ?? null;
$success = $_SESSION['success'] ?? null;
unset($_SESSION['error'], $_SESSION['success']);
?>
<div class="container mx-auto px-4 py-10">
    <div class="max-w-3xl mx-auto bg-white/10 backdrop-blur rounded-3xl border border-white/10 p-6 text-slate-100 shadow-xl">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-semibold mb-0">
                Registrar compra
            </h2>
            <a href="index.php?controller=products&action=index"
               class="btn btn-sm btn-outline-light text-xs">
                &larr; Volver a productos
            </a>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger text-sm"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success text-sm"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <form id="purchase-form"
            action="index.php?controller=purchases&action=store"
            method="POST"
            class="space-y-3">

            <div class="mb-3">
                <label class="form-label text-xs text-slate-200">Producto</label>
                <select name="producto_id"
                        class="form-select form-select-sm bg-slate-900/70 border-slate-600 text-slate-100"
                        required>
                    <option value="">Seleccionar...</option>
                    <?php foreach ($productos as $p): ?>
                        <option
                            value="<?php echo (int)$p['id']; ?>"
                            data-preciocompra="<?php echo (float)$p['precio_compra']; ?>"
                        >
                            <?php
                            echo htmlspecialchars($p['nombre'], ENT_QUOTES, 'UTF-8');
                            if (!empty($p['categoria_nombre'])) {
                                echo ' [' . htmlspecialchars($p['categoria_nombre'], ENT_QUOTES, 'UTF-8') . ']';
                            }
                            ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label text-xs text-slate-200">Cantidad</label>
                    <input type="number" name="cantidad" min="1"
                        class="form-control form-control-sm bg-slate-900/70 border-slate-600 text-slate-100"
                        required>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-xs text-slate-200">Precio unitario</label>
                    <input type="number" step="0.01" min="0" name="precio_unitario"
                        class="form-control form-control-sm bg-slate-900/70 border-slate-600 text-slate-100"
                        readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-xs text-slate-200">Total</label>
                    <input type="text" name="total"
                        class="form-control form-control-sm bg-slate-900/70 border-slate-600 text-slate-100"
                        readonly>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label text-xs text-slate-200">Motivo / Nota (opcional)</label>
                <textarea name="motivo" rows="2"
                        class="form-control form-control-sm bg-slate-900/70 border-slate-600 text-slate-100"
                        placeholder="Compra a proveedor X, remito Nro..."></textarea>
            </div>

            <div class="mt-4 flex justify-end">
                <button type="submit" class="btn btn-sm btn-primary">
                    Guardar compra
                </button>
            </div>
        </form>
    </div>
</div>

<script src="assets/js/setupTotalCalculator.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.querySelector('#purchase-form select[name="producto_id"]');
    const precioInput = document.querySelector('#purchase-form input[name="precio_unitario"]');

    function recalcularTotalCompras() {
        setupTotalCalculator('#purchase-form');
    }

    if (select && precioInput) {
        select.addEventListener('change', function () {
            const option = this.selectedOptions[0];
            if (option) {
                const precioCompra = option.dataset.preciocompra;
                precioInput.value = precioCompra ? precioCompra : '';
                // recalcular total luego de setear precio
                const evt = new Event('input');
                precioInput.dispatchEvent(evt);
            }
        });
    }

    // inicializar calculadora
    setupTotalCalculator('#purchase-form');
});
</script>

