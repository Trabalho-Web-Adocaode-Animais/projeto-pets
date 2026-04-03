<?php

declare(strict_types=1);

/** @var list<array<string, mixed>> $pets */
$pageTitle = 'Meus pets — PetMatch';
$error = null;
if (isset($_SESSION['error'])) {
    $error = (string) $_SESSION['error'];
    unset($_SESSION['error']);
}

require __DIR__ . '/../header.php';
?>
<main class="container py-4 flex-grow-1">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <h1 class="h3 mb-0">Meus pets</h1>
        <a href="/pets/novo" class="btn btn-primary">Novo pet</a>
    </div>

    <?php if ($error !== null) : ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if ($pets === []) : ?>
        <div class="alert alert-info border-0 shadow-sm">Você ainda não cadastrou nenhum pet.</div>
    <?php else : ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($pets as $p) : ?>
                <?php
                $disponivel = (int) $p['status'] === 1;
                $petId = (int) $p['id'];
                $nomePet = (string) $p['nome'];
                $rawImg = isset($p['imagem_url']) && $p['imagem_url'] !== null ? trim((string) $p['imagem_url']) : '';
                $temImagem = $rawImg !== '';
                ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 overflow-hidden">
                        <div class="ratio ratio-1x1 bg-secondary bg-opacity-10">
                            <?php if ($temImagem) : ?>
                                <img src="<?= htmlspecialchars($rawImg, ENT_QUOTES, 'UTF-8') ?>"
                                     class="pet-card-image"
                                     alt="<?= htmlspecialchars($nomePet, ENT_QUOTES, 'UTF-8') ?>"
                                     onerror="this.classList.add('d-none'); document.getElementById('meus-pet-fb-<?= $petId ?>').classList.remove('d-none')">
                                <div id="meus-pet-fb-<?= $petId ?>" class="pet-card-fallback d-none" role="img" aria-label="Pet sem foto">
                                    <span class="fs-4 lh-1 mb-1" aria-hidden="true">&#128247;</span>
                                    <span>Pet sem foto</span>
                                </div>
                            <?php else : ?>
                                <div class="pet-card-fallback" role="img" aria-label="Pet sem foto">
                                    <span class="fs-4 lh-1 mb-1" aria-hidden="true">&#128247;</span>
                                    <span>Pet sem foto</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h2 class="h5 card-title"><?= htmlspecialchars($nomePet, ENT_QUOTES, 'UTF-8') ?></h2>
                            <p class="card-text text-muted small mb-1">
                                <strong>Espécie:</strong> <?= htmlspecialchars((string) $p['especie'], ENT_QUOTES, 'UTF-8') ?>
                            </p>
                            <p class="card-text text-muted small mb-1">
                                <strong>Porte:</strong> <?= htmlspecialchars((string) $p['porte'], ENT_QUOTES, 'UTF-8') ?>
                            </p>
                            <p class="card-text text-muted small mb-3">
                                <strong>Idade:</strong> <?= (int) $p['idade'] ?> ano(s)
                            </p>
                            <p class="mb-3">
                                <?php if ($disponivel) : ?>
                                    <span class="badge text-bg-success">Disponível</span>
                                <?php else : ?>
                                    <span class="badge text-bg-secondary">Adotado</span>
                                <?php endif; ?>
                            </p>
                            <div class="mt-auto d-grid gap-2">
                                <a href="/pets/editar?id=<?= $petId ?>" class="btn btn-outline-primary">Editar</a>
                                <?php if ($disponivel) : ?>
                                    <form method="post" action="/pets/status" onsubmit="return confirm('Marcar este pet como adotado?');">
                                        <input type="hidden" name="id" value="<?= $petId ?>">
                                        <button type="submit" class="btn btn-success w-100">Marcar como Adotado</button>
                                    </form>
                                <?php else : ?>
                                    <form method="post" action="/pets/status" onsubmit="return confirm('Reativar o anúncio deste pet na listagem?');">
                                        <input type="hidden" name="id" value="<?= $petId ?>">
                                        <button type="submit" class="btn btn-outline-secondary w-100">Reativar anúncio</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
<?php require __DIR__ . '/../footer.php'; ?>
