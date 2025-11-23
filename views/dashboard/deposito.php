<?php
// views/dashboard/deposito.php
?>
<div class="container mx-auto px-4 py-10">
    <div class="max-w-3xl mx-auto bg-white/10 backdrop-blur shadow-xl rounded-3xl border border-white/10 p-6 text-slate-100">
        <h2 class="text-2xl font-semibold mb-2">
            Panel de Depósito
        </h2>
        <p class="text-sm mb-4">
            Hola, <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?> (rol: depósito).
        </p>

        <div class="space-y-4 mt-4">
            <div class="bg-slate-900/60 rounded-2xl p-4 border border-slate-700">
                <h3 class="text-lg font-semibold mb-2">Control de stock</h3>
                <p class="text-xs text-slate-300">
                    Ajustes de stock físico, roturas, pérdidas, etc.
                </p>
                <a href="index.php?controller=products&action=index" class="btn btn-sm btn-primary mt-3">
                    Ver productos
                </a>
            </div>

            <div class="bg-slate-900/60 rounded-2xl p-4 border border-slate-700">
                <h3 class="text-lg font-semibold mb-2">Movimientos recientes</h3>
                <p class="text-xs text-slate-300">
                    Entradas y salidas recientes de mercadería.
                </p>
                <button class="btn btn-sm btn-outline-light mt-3">
                    Ver movimientos
                </button>
            </div>

            <div class="bg-slate-900/60 rounded-2xl p-4 border border-slate-700">
                <h3 class="text-lg font-semibold mb-2">Alertas de stock bajo</h3>
                <p class="text-xs text-slate-300">
                    Productos que están por debajo del mínimo establecido.
                </p>
                <button class="btn btn-sm btn-outline-light mt-3">
                    Ver alertas
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
