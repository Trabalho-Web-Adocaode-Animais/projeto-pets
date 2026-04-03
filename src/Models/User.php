<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';

final class User
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /**
     * @param array{nome: string, email: string, senha: string, whatsapp: string} $data
     */
    public function save(array $data): bool
    {
        $hash = password_hash($data['senha'], PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare(
            'INSERT INTO usuarios (nome, email, senha, whatsapp) VALUES (:nome, :email, :senha, :whatsapp)'
        );

        $stmt->execute([
            'nome' => $data['nome'],
            'email' => $data['email'],
            'senha' => $hash,
            'whatsapp' => $data['whatsapp'],
        ]);

        return $stmt->rowCount() === 1;
    }

    public function getByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, nome, email, senha, whatsapp, created_at FROM usuarios WHERE email = :email LIMIT 1'
        );
        $stmt->execute(['email' => $email]);

        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    public function exists(string $email): bool
    {
        $stmt = $this->pdo->prepare('SELECT 1 FROM usuarios WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);

        return $stmt->fetchColumn() !== false;
    }
}
