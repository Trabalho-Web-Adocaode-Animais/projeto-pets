<?php

declare(strict_types=1);

$pageTitle = 'Login — PetMatch';
$error = null;
if (isset($_SESSION['error'])) {
    $error = (string) $_SESSION['error'];
    unset($_SESSION['error']);
}

require __DIR__ . '/header.php';
?>
<main class="container py-5 flex-grow-1">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <h1 class="h3 text-center mb-4">Login</h1>

                    <?php if ($error !== null) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="/login" novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" required autocomplete="email">
                        </div>
                        <div class="mb-4">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="senha" name="senha" required autocomplete="current-password">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Entrar</button>
                        </div>
                    </form>
                </div>
            </div>
            <p class="text-center text-muted mt-4 mb-0">
                <a href="/cadastro" class="text-decoration-none">Criar conta</a>
                <span class="mx-2">·</span>
                <a href="/" class="text-decoration-none">Início</a>
            </p>
        </div>
    </div>
</main>
<?php require __DIR__ . '/footer.php'; ?>

