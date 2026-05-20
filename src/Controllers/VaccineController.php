<?php

declare(strict_types=1);

final class VaccineController
{
    private const VIEW_CADASTRO = __DIR__ . '/../Views/vaccines/cadastro.php';
    private const VIEW_LISTA = __DIR__ . '/../Views/vaccines/lista.php';

    public function __construct(
        private readonly Vaccine $vaccine,
        private readonly Pet $pet
    ) {
    }

    
    //Exibe a lista de vacinas de um pet específico.
    
    public function index(): void
    {
        $userId = $this->requireUserId();
        
        // Pega o ID do pet via URL (ex: /vacinas?pet_id=5)
        $petId = isset($_GET['pet_id']) ? (int) $_GET['pet_id'] : 0;
        
        if ($petId < 1) {
            $_SESSION['error'] = 'Selecione um pet para ver as vacinas.';
            header('Location: /pets/meus');
            exit;
        }

        // Validação de Segurança (IDOR): O pet existe e pertence ao usuário logado?
        $petRow = $this->pet->find($petId);
        if ($petRow === null || (int) $petRow['usuario_id'] !== $userId) {
            $_SESSION['error'] = 'Pet não encontrado ou acesso negado.';
            header('Location: /pets/meus');
            exit;
        }

        // Busca as vacinas e carrega a View de listagem
        $vacinas = $this->vaccine->findByPet($petId);
        $petNome = $petRow['nome']; 
        
        require self::VIEW_LISTA;
    }

    
    //Gerencia a exibição do formulário e o salvamento de uma nova vacina.
    
    public function create(): void
    {
        $userId = $this->requireUserId();
        $petId = isset($_GET['pet_id']) ? (int) $_GET['pet_id'] : 0;

        if ($petId < 1) {
            header('Location: /pets/meus');
            exit;
        }

        $petRow = $this->pet->find($petId);
        if ($petRow === null || (int) $petRow['usuario_id'] !== $userId) {
            $_SESSION['error'] = 'Pet não encontrado ou acesso negado.';
            header('Location: /pets/meus');
            exit;
        }

        // Se for requisição GET, apenas exibe a tela de cadastro
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $petNome = $petRow['nome'];
            require self::VIEW_CADASTRO;
            return;
        }

        // --- Processamento do Formulário (POST) ---
        $nome = isset($_POST['nome']) ? trim((string) $_POST['nome']) : '';
        $dataAplicacao = isset($_POST['data_aplicacao']) ? trim((string) $_POST['data_aplicacao']) : '';
        $proximaDose = isset($_POST['proxima_dose']) ? trim((string) $_POST['proxima_dose']) : '';

        if ($nome === '' || $dataAplicacao === '') {
            $_SESSION['error'] = 'Preencha o nome da vacina e a data de aplicação.';
            header('Location: /vacinas/nova?pet_id=' . $petId);
            exit;
        }

        try {
            $this->vaccine->save([
                'pet_id' => $petId,
                'nome' => $nome,
                'data_aplicacao' => $dataAplicacao,
                'proxima_dose' => $proximaDose
            ]);

            header('Location: /vacinas?pet_id=' . $petId);
            exit;

        } catch (\PDOException $e) {
            $_SESSION['error'] = 'Ocorreu um erro interno ao salvar a vacina. Tente novamente.';
            header('Location: /vacinas/nova?pet_id=' . $petId);
            exit;
        }
    }

    //Garante que apenas usuários logados acessem essas funções.
    
    private function requireUserId(): int
    {
        if (!isset($_SESSION['user']['id'])) {
            header('Location: /login');
            exit;
        }

        return (int) $_SESSION['user']['id'];
    }
}