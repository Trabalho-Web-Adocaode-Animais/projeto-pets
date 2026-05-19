<?php

declare(strict_types=1);

final class AuthController
{
    private const VIEW_REGISTER = __DIR__ . '/../Views/register.php';
    private const VIEW_LOGIN = __DIR__ . '/../Views/login.php';

    public function __construct(
        private readonly User $user
    ) {
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require self::VIEW_REGISTER;

            return;
        }

        $nome = isset($_POST['nome']) ? trim((string) $_POST['nome']) : '';
        $email = isset($_POST['email']) ? trim((string) $_POST['email']) : '';
        $senha = isset($_POST['senha']) ? (string) $_POST['senha'] : '';
        $whatsapp = isset($_POST['whatsapp']) ? trim((string) $_POST['whatsapp']) : '';

        if ($nome === '' || $email === '' || $senha === '' || $whatsapp === '') {
            $_SESSION['error'] = 'Preencha todos os campos.';
            header('Location: /cadastro');
            exit;
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $_SESSION['error'] = 'Informe um e-mail válido.';
            header('Location: /cadastro');
            exit;
        }

        if (strlen($senha) < 6) {
            $_SESSION['error'] = 'A senha deve ter no mínimo 6 caracteres.';
            header('Location: /cadastro');
            exit;
        }

        if ($this->user->exists($email)) {
            $_SESSION['error'] = 'Este e-mail já está cadastrado.';
            header('Location: /cadastro');
            exit;
        }

        $this->user->save([
            'nome' => $nome,
            'email' => $email,
            'senha' => $senha,
            'whatsapp' => $whatsapp,
        ]);

        header('Location: /login');
        exit;
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require self::VIEW_LOGIN;

            return;
        }

        $email = isset($_POST['email']) ? trim((string) $_POST['email']) : '';
        $senha = isset($_POST['senha']) ? (string) $_POST['senha'] : '';

        if ($email === '' || $senha === '') {
            $_SESSION['error'] = 'Preencha e-mail e senha.';
            header('Location: /login');
            exit;
        }

        $row = $this->user->getByEmail($email);

        if ($row === null || !password_verify($senha, $row['senha'])) {
            $_SESSION['error'] = 'E-mail ou senha incorretos.';
            header('Location: /login');
            exit;
        }

        $_SESSION['user'] = [
            'id' => (int) $row['id'],
            'nome' => $row['nome'],
        ];

        header('Location: /');
        exit;
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        header('Location: /');
        exit;
    }
}
