<?php
// views/users/form.php
$error   = $_SESSION['error']   ?? null;
$success = $_SESSION['success'] ?? null;
unset($_SESSION['error'], $_SESSION['success']);
?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-xl mx-auto bg-slate-900/70 border border-white/10 rounded-3xl p-6 text-slate-100 shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl md:text-2xl font-semibold mb-0">
                Crear nuevo usuario
            </h1>
            <a href="index.php?controller=auth&action=dashboard"
               class="btn btn-sm btn-outline-light text-xs">
                &larr; Volver al dashboard
            </a>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger text-xs mb-3">
                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success text-xs mb-3">
                <?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <form action="index.php?controller=auth&action=storeUser" method="POST" class="space-y-3">
            <div class="mb-2">
                <label class="form-label text-xs text-slate-200">Nombre de usuario</label>
                <input type="text" name="username"
                       class="form-control form-control-sm bg-slate-900/80 border-slate-600 text-slate-100"
                       required>
            </div>

            <div class="mb-2">
                <label class="form-label text-xs text-slate-200">Email</label>
                <input type="email" name="email"
                       class="form-control form-control-sm bg-slate-900/80 border-slate-600 text-slate-100"
                       required>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-md-6">
                    <label class="form-label text-xs text-slate-200">Contraseña</label>
                    <input type="password" name="password"
                           class="form-control form-control-sm bg-slate-900/80 border-slate-600 text-slate-100"
                           required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-xs text-slate-200">Repetir contraseña</label>
                    <input type="password" name="password_confirmation"
                           class="form-control form-control-sm bg-slate-900/80 border-slate-600 text-slate-100"
                           required>
                </div>
            </div>

            <div class="mb-2">
                <label class="form-label text-xs text-slate-200">Rol</label>
                <select name="role_id"
                        class="form-select form-select-sm bg-slate-900/80 border-slate-600 text-slate-100"
                        required>
                    <option value="">Seleccionar rol...</option>
                    <?php if (!empty($roles)): ?>
                        <?php foreach ($roles as $r): ?>
                            <option value="<?php echo (int)$r['id']; ?>">
                                <?php echo htmlspecialchars($r['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" checked>
                <label class="form-check-label text-xs text-slate-200" for="is_active">
                    Usuario activo
                </label>
            </div>

            <div class="mt-4 flex justify-end">
                <button type="submit" class="btn btn-sm btn-primary">
                    Crear usuario
                </button>
            </div>
        </form>
    </div>
</div>
