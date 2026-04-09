<?php

declare(strict_types=1);

$pageTitle = 'Nova Vacina — PetMatch';
$error = null;
if (isset($_SESSION['error'])) {
    $error = (string) $_SESSION['error'];
    unset($_SESSION['error']);
}

require __DIR__ . '/../header.php';
?>
<main class="container py-5 flex-grow-1">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <h1 class="h3 text-center mb-2">Registrar Vacina</h1>
                    <p class="text-center text-muted mb-4">Pet: <strong><?= htmlspecialchars((string) $petNome, ENT_QUOTES, 'UTF-8') ?></strong></p>

                    <?php if ($error !== null) : ?>
                        <div class="alert alert-danger shadow-sm border-0" role="alert">
                            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="/vacinas/nova?pet_id=<?= (int) $petId ?>" novalidate>
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome da Vacina (Ex: V8, Raiva)</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="data_aplicacao" class="form-label">Data de Aplicação</label>
                                <input type="date" class="form-control" id="data_aplicacao" name="data_aplicacao" required>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label for="proxima_dose" class="form-label">Próxima Dose (Opcional)</label>
                                <input type="date" class="form-control" id="proxima_dose" name="proxima_dose">
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Salvar Vacina</button>
                            <a href="/vacinas?pet_id=<?= (int) $petId ?>" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require __DIR__ . '/../footer.php'; ?>