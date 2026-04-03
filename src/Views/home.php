<?php

declare(strict_types=1);

$pageTitle = 'Início — PetMatch';
require __DIR__ . '/header.php';
?>
<main class="container py-4 flex-grow-1">
    <div class="p-5 mb-4 bg-white rounded-3 shadow-sm border">
        <div class="container-fluid py-3">
            <h1 class="display-5 fw-bold text-primary">Bem-vindo ao PetMatch</h1>
            <p class="col-md-10 fs-5 text-secondary mt-3 mb-0">
                Conectamos quem deseja doar com quem procura um novo amigo. Cadastre-se, anuncie ou encontre seu pet.
            </p>
        </div>
    </div>

    <?php if (isset($_SESSION['user']['nome'])) : ?>
        <p class="lead mb-4">
            Olá, <strong><?= htmlspecialchars((string) $_SESSION['user']['nome'], ENT_QUOTES, 'UTF-8') ?></strong>!
        </p>
    <?php endif; ?>

    <div class="alert alert-info border-0 shadow-sm" role="status">
        <p class="mb-0">Em breve, a lista de pets para adoção aparecerá aqui!</p>
    </div>
</main>
<?php require __DIR__ . '/footer.php'; ?>

