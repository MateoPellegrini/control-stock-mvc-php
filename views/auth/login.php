<?php
// views/auth/login.php
$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>
<div class="container mx-auto px-4 py-10">
    <div class="max-w-md mx-auto bg-white/10 backdrop-blur shadow-xl rounded-3xl overflow-hidden border border-white/10">
        <div class="px-6 py-5 border-b border-white/10 bg-gradient-to-r from-indigo-500/80 to-sky-500/80">
            <h1 class="text-xl font-semibold text-white text-center">
                Sistema de Control de Stock
            </h1>
            <p class="text-slate-100 text-center text-sm mt-1">
                Inicia sesión para continuar
            </p>
        </div>

        <div class="p-6">
            <?php if ($error): ?>
                <div class="alert alert-danger text-sm mb-4" role="alert">
                    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?controller=auth&action=authenticate" method="POST" class="space-y-4">
                <div class="mb-3">
                    <label for="identifier" class="form-label text-sm text-slate-100">
                        Usuario o Email
                    </label>
                    <input
                        type="text"
                        class="form-control bg-slate-900/50 border-slate-600 text-slate-100 text-sm"
                        id="identifier"
                        name="identifier"
                        placeholder="usuario o correo"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label text-sm text-slate-100">
                        Contraseña
                    </label>
                    <input
                        type="password"
                        class="form-control bg-slate-900/50 border-slate-600 text-slate-100 text-sm"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <div class="flex items-center justify-between mb-3 text-xs text-slate-300">
                    <span>Rol según tu usuario configurado.</span>
                </div>

                <button
                    type="submit"
                    class="w-full btn btn-primary bg-gradient-to-r from-indigo-500 to-sky-500 border-0 text-sm py-2.5 rounded-2xl shadow-lg hover:shadow-indigo-500/40 transition-all duration-200"
                >
                    Iniciar sesión
                </button>
            </form>
        </div>

        <div class="px-6 py-3 border-t border-white/10 text-center text-[11px] text-slate-300 bg-slate-900/60">
            &copy; <?php echo date('Y'); ?> Sistema de Stock - Login inicial (MVC + PHP)
        </div>
    </div>
</div>
