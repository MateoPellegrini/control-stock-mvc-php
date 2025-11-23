<?php
// views/auth/dashboard.php
?>
<div class="container mx-auto px-4 py-10">
    <div class="max-w-xl mx-auto bg-white/10 backdrop-blur shadow-xl rounded-3xl overflow-hidden border border-white/10 p-6 text-center text-slate-100">
        <h2 class="text-2xl font-semibold mb-2">Bienvenido, <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>!</h2>
        <p class="text-sm mb-4">Este es un dashboard genérico. Tu rol es: <?php echo htmlspecialchars($roleName ?? 'desconocido', ENT_QUOTES, 'UTF-8'); ?>.</p>
        <a href="index.php?controller=auth&action=logout"
           class="inline-block btn btn-outline-light text-sm">
            Cerrar sesión
        </a>
    </div>
</div>
