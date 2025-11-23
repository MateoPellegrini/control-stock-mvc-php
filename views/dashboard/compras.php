<?php
// views/dashboard/compras.php
?>
<div class="container mx-auto px-4 py-10">
    <div class="max-w-3xl mx-auto bg-white/10 backdrop-blur shadow-xl rounded-3xl border border-white/10 p-6 text-slate-100">
        <h2 class="text-2xl font-semibold mb-2">
            Panel de Compras
        </h2>
        <p class="text-sm mb-4">
            Hola, <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?> (rol: compras).
        </p>

        <div class="space-y-4 mt-4">
            <div class="bg-slate-900/60 rounded-2xl p-4 border border-slate-700">
                <h3 class="text-lg font-semibold mb-2">Nueva orden de compra</h3>
                <p class="text-xs text-slate-300">
                    Generar una orden de compra para un proveedor.
                </p>
                <a href="index.php?controller=purchases&action=create"
                class="btn btn-sm btn-primary mt-3">
                    Registrar compra
                </a>

            </div>

            <div class="bg-slate-900/60 rounded-2xl p-4 border border-slate-700">
                <h3 class="text-lg font-semibold mb-2">Proveedores</h3>
                <p class="text-xs text-slate-300">
                    Listado de proveedores, datos de contacto y productos asociados.
                </p>
                <button class="btn btn-sm btn-outline-light mt-3">
                    Ver proveedores
                </button>
            </div>

            <div class="bg-slate-900/60 rounded-2xl p-4 border border-slate-700">
                <h3 class="text-lg font-semibold mb-2">Órdenes pendientes</h3>
                <p class="text-xs text-slate-300">
                    Órdenes de compra que aún no se han recibido o completado.
                </p>
                <button class="btn btn-sm btn-outline-light mt-3">
                    Ver pendientes
                </button>
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
