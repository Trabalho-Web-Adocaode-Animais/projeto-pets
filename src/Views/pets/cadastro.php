<?php

declare(strict_types=1);

$pageTitle = 'Novo pet — PetMatch';
$error = null;
if (isset($_SESSION['error'])) {
    $error = (string) $_SESSION['error'];
    unset($_SESSION['error']);
}

require __DIR__ . '/../header.php';
?>
<main class="container py-5 flex-grow-1">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <h1 class="h3 text-center mb-4">Cadastrar pet</h1>

                    <?php if ($error !== null) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="/pets/novo" novalidate>
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" required maxlength="255">
                        </div>
                        <div class="mb-3">
                            <label for="especie" class="form-label">Espécie</label>
                            <select class="form-select" id="especie" name="especie" required>
                                <option value="" selected disabled>Selecione</option>
                                <option value="Cachorro">Cachorro</option>
                                <option value="Gato">Gato</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="porte" class="form-label">Porte</label>
                            <select class="form-select" id="porte" name="porte" required>
                                <option value="" selected disabled>Selecione</option>
                                <option value="Pequeno">Pequeno</option>
                                <option value="Médio">Médio</option>
                                <option value="Grande">Grande</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="idade" class="form-label">Idade (anos)</label>
                            <input type="number" class="form-control" id="idade" name="idade" required min="0" step="1">
                        </div>
                        <div class="mb-3">
                            <label for="imagem_url" class="form-label">URL da Imagem</label>
                            <input type="text" class="form-control" id="imagem_url" name="imagem_url" maxlength="255" autocomplete="off" placeholder="https://...">
                            <div class="form-text">Dica: Cole um link de imagem do Google ou Unsplash.</div>
                        </div>
                        <div class="mb-4">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="4" required></textarea>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Salvar</button>
                            <a href="/pets/meus" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require __DIR__ . '/../footer.php'; ?>

