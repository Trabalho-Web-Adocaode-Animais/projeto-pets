<?php

declare(strict_types=1);

final class PetController
{
    private const VIEW_CADASTRO = __DIR__ . '/../Views/pets/cadastro.php';
    private const VIEW_EDITAR = __DIR__ . '/../Views/pets/editar.php';
    private const VIEW_MEUS = __DIR__ . '/../Views/pets/meus-pets.php';
    private const VIEW_HOME = __DIR__ . '/../Views/home.php';

    private const ESPECIES = ['Cachorro', 'Gato', 'Outro'];
    private const PORTES = ['Pequeno', 'Médio', 'Grande'];

    public function __construct(
        private readonly Pet $pet
    ) {
    }

    public function home(): void
    {
        $pets = $this->pet->all();
        require self::VIEW_HOME;
    }

    public function create(): void
    {
        $userId = $this->requireUserId();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require self::VIEW_CADASTRO;

            return;
        }

        $nome = isset($_POST['nome']) ? trim((string) $_POST['nome']) : '';
        $especie = isset($_POST['especie']) ? (string) $_POST['especie'] : '';
        $porte = isset($_POST['porte']) ? (string) $_POST['porte'] : '';
        $idadeRaw = isset($_POST['idade']) ? trim((string) $_POST['idade']) : '';
        $descricao = isset($_POST['descricao']) ? trim((string) $_POST['descricao']) : '';
        $imagemUrlRaw = isset($_POST['imagem_url']) ? (string) $_POST['imagem_url'] : '';

        if ($nome === '' || $especie === '' || $porte === '') {
            $_SESSION['error'] = 'Preencha Nome, Espécie e Porte.';
            header('Location: /pets/novo');
            exit;
        }

        if (!in_array($especie, self::ESPECIES, true) || !in_array($porte, self::PORTES, true)) {
            $_SESSION['error'] = 'Espécie ou porte inválidos.';
            header('Location: /pets/novo');
            exit;
        }

        if ($idadeRaw === '' || !ctype_digit($idadeRaw)) {
            $_SESSION['error'] = 'Informe a idade como número inteiro.';
            header('Location: /pets/novo');
            exit;
        }

        $idade = (int) $idadeRaw;
        if ($descricao === '') {
            $_SESSION['error'] = 'Preencha a descrição.';
            header('Location: /pets/novo');
            exit;
        }

        $imagemUrl = $this->parseOptionalImageUrl($imagemUrlRaw);
        if ($imagemUrl === false) {
            header('Location: /pets/novo');
            exit;
        }

        // Tenta salvar no banco e captura a exceção em caso de falha estrutural
        try {
            $this->pet->save([
                'usuario_id' => $userId,
                'nome' => $nome,
                'especie' => $especie,
                'porte' => $porte,
                'idade' => $idade,
                'descricao' => $descricao,
                'imagem_url' => $imagemUrl,
            ]);

            header('Location: /pets/meus');
            exit;

        } catch (\PDOException $e) {
            // Código 23000 representa falha de restrição de integridade (como FK inválida)
            if ($e->getCode() === '23000') {
                unset($_SESSION['user']); // Remove o login inválido
                $_SESSION['error'] = 'Sua sessão é inválida ou expirou. Por favor, faça login novamente.';
                header('Location: /login');
                exit;
            }

            // Para qualquer outro erro de banco de dados
            $_SESSION['error'] = 'Ocorreu um erro interno ao salvar o pet. Tente novamente.';
            header('Location: /pets/novo');
            exit;
        }
    }

    public function meus(): void
    {
        $userId = $this->requireUserId();
        $pets = $this->pet->findByUser($userId);
        require self::VIEW_MEUS;
    }

    public function edit(): void
    {
        $userId = $this->requireUserId();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir'])) {
            $this->handleDelete($userId);

            return;
        }

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id < 1 && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        }
        if ($id < 1) {
            $_SESSION['error'] = 'Pet não encontrado.';
            header('Location: /pets/meus');
            exit;
        }

        $row = $this->pet->find($id);
        if ($row === null || (int) $row['usuario_id'] !== $userId) {
            $_SESSION['error'] = 'Pet não encontrado ou você não tem permissão.';
            header('Location: /pets/meus');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $pet = $row;
            require self::VIEW_EDITAR;

            return;
        }

        $postId = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        if ($postId !== $id) {
            header('Location: /pets/meus');
            exit;
        }

        $nome = isset($_POST['nome']) ? trim((string) $_POST['nome']) : '';
        $especie = isset($_POST['especie']) ? (string) $_POST['especie'] : '';
        $porte = isset($_POST['porte']) ? (string) $_POST['porte'] : '';
        $idadeRaw = isset($_POST['idade']) ? trim((string) $_POST['idade']) : '';
        $descricao = isset($_POST['descricao']) ? trim((string) $_POST['descricao']) : '';
        $imagemUrlRaw = isset($_POST['imagem_url']) ? (string) $_POST['imagem_url'] : '';

        if ($nome === '' || $especie === '' || $porte === '') {
            $_SESSION['error'] = 'Preencha Nome, Espécie e Porte.';
            header('Location: /pets/editar?id=' . $id);
            exit;
        }

        if (!in_array($especie, self::ESPECIES, true) || !in_array($porte, self::PORTES, true)) {
            $_SESSION['error'] = 'Espécie ou porte inválidos.';
            header('Location: /pets/editar?id=' . $id);
            exit;
        }

        if ($idadeRaw === '' || !ctype_digit($idadeRaw)) {
            $_SESSION['error'] = 'Informe a idade como número inteiro.';
            header('Location: /pets/editar?id=' . $id);
            exit;
        }

        $idade = (int) $idadeRaw;
        if ($descricao === '') {
            $_SESSION['error'] = 'Preencha a descrição.';
            header('Location: /pets/editar?id=' . $id);
            exit;
        }

        $imagemUrl = $this->parseOptionalImageUrl($imagemUrlRaw);
        if ($imagemUrl === false) {
            header('Location: /pets/editar?id=' . $id);
            exit;
        }

        try {
            $this->pet->update($id, [
                'nome' => $nome,
                'especie' => $especie,
                'porte' => $porte,
                'idade' => $idade,
                'descricao' => $descricao,
                'imagem_url' => $imagemUrl,
            ]);

            header('Location: /pets/meus');
            exit;
        } catch (\PDOException $e) {
            $_SESSION['error'] = 'Ocorreu um erro interno ao atualizar o pet. Tente novamente.';
            header('Location: /pets/editar?id=' . $id);
            exit;
        }
    }

    public function toggleStatus(): void
    {
        $userId = $this->requireUserId();

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id < 1) {
            $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        }
        if ($id < 1) {
            $_SESSION['error'] = 'Pet não encontrado.';
            header('Location: /pets/meus');
            exit;
        }

        $row = $this->pet->find($id);
        if ($row === null) {
            $_SESSION['error'] = 'Pet não encontrado.';
            header('Location: /pets/meus');
            exit;
        }

        if ((int) $row['usuario_id'] !== $userId) {
            $_SESSION['error'] = 'Pet não encontrado ou você não tem permissão.';
            header('Location: /pets/meus');
            exit;
        }

        $current = (int) $row['status'];
        $newStatus = $current === 1 ? 0 : 1;

        if (!$this->pet->updateStatus($id, $newStatus)) {
            $_SESSION['error'] = 'Não foi possível atualizar o status do pet.';
            header('Location: /pets/meus');
            exit;
        }

        header('Location: /pets/meus');
        exit;
    }

    private function handleDelete(int $userId): void
    {
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        if ($id < 1) {
            header('Location: /pets/meus');
            exit;
        }

        $row = $this->pet->find($id);
        if ($row === null || (int) $row['usuario_id'] !== $userId) {
            $_SESSION['error'] = 'Pet não encontrado ou você não tem permissão.';
            header('Location: /pets/meus');
            exit;
        }

        $this->pet->delete($id);

        header('Location: /pets/meus');
        exit;
    }

    /**
     * @return string|null|false URL normalizada, null se vazio, false se inválido (mensagem em $_SESSION['error'])
     */
    private function parseOptionalImageUrl(string $raw): string|null|false
    {
        $trimmed = trim($raw);
        if ($trimmed === '') {
            return null;
        }

        if (strlen($trimmed) > 255) {
            $_SESSION['error'] = 'A URL da imagem deve ter no máximo 255 caracteres.';

            return false;
        }

        $valid = filter_var($trimmed, FILTER_VALIDATE_URL);
        if ($valid === false) {
            $_SESSION['error'] = 'A URL da imagem não é válida. Informe um link completo (por exemplo, começando com https://).';

            return false;
        }

        return $valid;
    }

    private function requireUserId(): int
    {
        if (!isset($_SESSION['user']['id'])) {
            header('Location: /login');
            exit;
        }

        return (int) $_SESSION['user']['id'];
    }
}

