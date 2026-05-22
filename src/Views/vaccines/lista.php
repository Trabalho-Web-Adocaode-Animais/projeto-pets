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
                                <th scope="col" class="text-end pe-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vacinas as $vacina) : ?>
                                <tr>
                                    <td class="ps-4 fw-medium text-dark">
                                        <?= htmlspecialchars((string) $vacina['nome'], ENT_QUOTES, 'UTF-8') ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars(\Carbon\Carbon::parse((string) $vacina['data_aplicacao'])->format('d/m/Y'), ENT_QUOTES, 'UTF-8') ?>
                                        <br>
                                        <small class="text-muted">
                                            <?= htmlspecialchars(\Carbon\Carbon::parse((string) $vacina['data_aplicacao'])->diffForHumans(), ENT_QUOTES, 'UTF-8') ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php if ($vacina['proxima_dose'] !== null) : ?>
                                            <span class="badge bg-info text-dark mb-1">
                                                <?= htmlspecialchars(\Carbon\Carbon::parse((string) $vacina['proxima_dose'])->format('d/m/Y'), ENT_QUOTES, 'UTF-8') ?>
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                <?= htmlspecialchars(\Carbon\Carbon::parse((string) $vacina['proxima_dose'])->diffForHumans(), ENT_QUOTES, 'UTF-8') ?>
                                            </small>
                                        <?php else : ?>
                                            <span class="text-muted small">Dose única</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <form action="/vacinas/remover" method="POST" onsubmit="return confirm('Tem certeza que deseja remover esta vacina?');" class="m-0">
                                            <input type="hidden" name="id" value="<?= (int) $vacina['id'] ?>">
                                            <input type="hidden" name="pet_id" value="<?= (int) $petId ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm">
                                                Remover
                                            </button>
                                        </form>
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