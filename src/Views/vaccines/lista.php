<?php

declare(strict_types=1);

$pageTitle = 'Vacinas — PetMatch';
$error = null;
if (isset($_SESSION['error'])) {
    $error = (string) $_SESSION['error'];
    unset($_SESSION['error']);
}

// As variáveis $vacinas, $petId e $petNome vêm do VaccineController
if (!isset($vacinas)) {
    $vacinas = [];
}

require __DIR__ . '/../header.php';
?>
<main class="container py-5 flex-grow-1">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Histórico de Vacinas</h1>
            <p class="text-muted mb-0">Gerenciando as vacinas de <strong><?= htmlspecialchars((string) $petNome, ENT_QUOTES, 'UTF-8') ?></strong></p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="/vacinas/nova?pet_id=<?= (int) $petId ?>" class="btn btn-primary shadow-sm">
                + Nova Vacina
            </a>
            <a href="/pets/meus" class="btn btn-outline-secondary ms-2 shadow-sm">Voltar</a>
        </div>
    </div>

    <?php if ($error !== null) : ?>
        <div class="alert alert-danger shadow-sm border-0" role="alert">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-body p-0">
            <?php if ($vacinas === []) : ?>
                <div class="p-5 text-center text-muted">
                    <p class="mb-0">Nenhuma vacina registrada para este pet ainda.</p>
                </div>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="ps-4">Vacina</th>
                                <th scope="col">Data de Aplicação</th>
                                <th scope="col">Próxima Dose</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vacinas as $vacina) : ?>
                                <tr>
                                    <td class="ps-4 fw-medium text-dark">
                                        <?= htmlspecialchars((string) $vacina['nome'], ENT_QUOTES, 'UTF-8') ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars(date('d/m/Y', strtotime((string) $vacina['data_aplicacao'])), ENT_QUOTES, 'UTF-8') ?>
                                    </td>
                                    <td>
                                        <?php if ($vacina['proxima_dose'] !== null) : ?>
                                            <span class="badge bg-info text-dark">
                                                <?= htmlspecialchars(date('d/m/Y', strtotime((string) $vacina['proxima_dose'])), ENT_QUOTES, 'UTF-8') ?>
                                            </span>
                                        <?php else : ?>
                                            <span class="text-muted small">Dose única</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php require __DIR__ . '/../footer.php'; ?>