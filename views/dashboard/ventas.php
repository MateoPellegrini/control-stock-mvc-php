<?php
// views/dashboard/ventas.php
?>
<div class="container mx-auto px-4 py-10">
    <div class="max-w-3xl mx-auto bg-white/10 backdrop-blur shadow-xl rounded-3xl border border-white/10 p-6 text-slate-100">
        <h2 class="text-2xl font-semibold mb-2">
            Panel de Ventas
        </h2>
        <p class="text-sm mb-4">
            Hola, <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?> (rol: ventas).
        </p>

        <div class="space-y-4 mt-4">
            <div class="bg-slate-900/60 rounded-2xl p-4 border border-slate-700">
                <h3 class="text-lg font-semibold mb-2">Nueva venta</h3>
                <p class="text-xs text-slate-300">
                    Registrar una nueva venta, seleccionar cliente y productos.
                </p>
                <a href="index.php?controller=sales&action=create"
                class="btn btn-sm btn-primary mt-3">
                    Registrar venta
                </a>
            </div>

            <div class="bg-slate-900/60 rounded-2xl p-4 border border-slate-700">
                <h3 class="text-lg font-semibold mb-2">Ventas del día</h3>
                <p class="text-xs text-slate-300">
                    Resumen rápido de las ventas realizadas hoy.
                </p>
                <button class="btn btn-sm btn-outline-light mt-3">
                    Ver detalle
                </button>
            </div>

            <div class="bg-slate-900/60 rounded-2xl p-4 border border-slate-700">
                <h3 class="text-lg font-semibold mb-2">Consulta de stock</h3>
                <p class="text-xs text-slate-300">
                    Ver disponibilidad de productos para informar al cliente.
                </p>
                <a href="index.php?controller=products&action=index" class="btn btn-sm btn-outline-light mt-3">
                    Consultar productos
                </a>
            </div>
        </div>

        <div class="mt-6 text-right">
            <a href="index.php?controller=auth&action=logout"
               class="inline-block btn btn-outline-light text-xs">
                Cerrar sesión
            </a>
        </div>
    </div>
</div>
