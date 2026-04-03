<?php

declare(strict_types=1);

/** @var list<array<string, mixed>> $pets */
if (!isset($pets)) {
    $pets = [];
}

$pageTitle = 'Início — PetMatch';

/**
 * Monta URL wa.me a partir do número salvo no cadastro.
 */
function pet_whatsapp_url(string $raw): string
{
    $digits = preg_replace('/\D+/', '', $raw);
    if ($digits === '') {
        return '#';
    }
    if (strlen($digits) >= 10 && strlen($digits) <= 11 && !str_starts_with($digits, '55')) {
        $digits = '55' . $digits;
    }

    return 'https://wa.me/' . $digits;
}

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

    <h2 class="h4 mb-3">Pets disponíveis para adoção</h2>

    <?php if ($pets === []) : ?>
        <div class="alert alert-info border-0 shadow-sm" role="status">
            <p class="mb-0">Nenhum pet disponível no momento. Volte em breve!</p>
        </div>
    <?php else : ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($pets as $p) : ?>
                <?php
                $wa = pet_whatsapp_url((string) ($p['dono_whatsapp'] ?? ''));
                $msg = rawurlencode('Olá! Tenho interesse no pet ' . (string) $p['nome'] . ' via PetMatch.');
                $waHref = $wa !== '#' ? $wa . '?text=' . $msg : '#';
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
                                     onerror="this.classList.add('d-none'); document.getElementById('pet-fb-<?= $petId ?>').classList.remove('d-none')">
                                <div id="pet-fb-<?= $petId ?>" class="pet-card-fallback d-none" role="img" aria-label="Pet sem foto">
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
                            <h3 class="card-title h5"><?= htmlspecialchars($nomePet, ENT_QUOTES, 'UTF-8') ?></h3>
                            <p class="card-text text-muted small mb-1">
                                <strong>Espécie:</strong> <?= htmlspecialchars((string) $p['especie'], ENT_QUOTES, 'UTF-8') ?>
                            </p>
                            <p class="card-text text-muted small mb-3">
                                <strong>Porte:</strong> <?= htmlspecialchars((string) $p['porte'], ENT_QUOTES, 'UTF-8') ?>
                                · <strong>Idade:</strong> <?= (int) $p['idade'] ?> ano(s)
                            </p>
                            <div class="mt-auto d-grid gap-2">
                                <?php if ($waHref !== '#') : ?>
                                    <a class="btn btn-success" href="<?= htmlspecialchars($waHref, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">Tenho interesse</a>
                                <?php else : ?>
                                    <button type="button" class="btn btn-secondary" disabled>WhatsApp indisponível</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
<?php require __DIR__ . '/footer.php'; ?>
