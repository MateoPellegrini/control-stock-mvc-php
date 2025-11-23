<?php
// views/dashboard/admin.php
?>
<div class="container mx-auto px-4 py-10">
    <div class="max-w-4xl mx-auto bg-white/10 backdrop-blur shadow-xl rounded-3xl border border-white/10 p-6 text-slate-100">
        <h2 class="text-2xl font-semibold mb-2">
            Panel de Administración
        </h2>
        <p class="text-sm mb-4">
            Hola, <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?> (rol: admin).
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div class="bg-slate-900/60 rounded-2xl p-4 border border-slate-700">
                <h3 class="text-lg font-semibold mb-2">Gestión de usuarios</h3>
                <p class="text-xs text-slate-300">
                    Crear, editar y desactivar usuarios. Asignar roles y permisos.
                </p>
                <button class="btn btn-sm btn-primary mt-3">
                    Ir a usuarios
                </button>
            </div>

            <div class="bg-slate-900/60 rounded-2xl p-4 border border-slate-700">
                <h3 class="text-lg font-semibold mb-2">Configuración general</h3>
                <p class="text-xs text-slate-300">
                    Parámetros del sistema, categorías de productos, etc.
                </p>
                <a href="index.php?controller=products&action=index" class="btn btn-sm btn-outline-light mt-3">
                    Gestionar productos
                </a>
            </div>
            

            <div class="bg-slate-900/60 rounded-2xl p-4 border border-slate-700">
                <h3 class="text-lg font-semibold mb-2">Reportes</h3>
                <p class="text-xs text-slate-300">
                    Reportes globales de ventas, compras y stock.
                </p>
                <button class="btn btn-sm btn-primary mt-3">
                    Ver reportes
                </button>
                <a href="index.php?controller=purchases&action=create"
                class="btn btn-sm btn-primary mt-3">
                    Registrar compra
                </a>
                <a href="index.php?controller=sales&action=create"
                class="btn btn-sm btn-primary mt-3">
                    Registrar venta
                </a>

            </div>

            <div class="bg-slate-900/60 rounded-2xl p-4 border border-slate-700">
                <h3 class="text-lg font-semibold mb-2">Seguridad</h3>
                <p class="text-xs text-slate-300">
                    Auditoría, cambios de contraseña, logs de acceso (futuro).
                </p>
                <button class="btn btn-sm btn-outline-light mt-3">
                    Ver opciones
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
