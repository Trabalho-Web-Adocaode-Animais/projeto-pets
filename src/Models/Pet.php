<?php

declare(strict_types=1);

final class Pet
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    /**
     * Pets disponíveis (status = 1), com WhatsApp do dono para contato.
     *
     * @return list<array<string, mixed>>
     */
    public function all(): array
    {
        $stmt = $this->pdo->query(
            'SELECT p.id, p.nome, p.especie, p.porte, p.idade, p.descricao, p.imagem_url, p.status, p.created_at,
                    u.whatsapp AS dono_whatsapp, u.nome AS dono_nome
             FROM pets p
             INNER JOIN usuarios u ON u.id = p.usuario_id
             WHERE p.status = 1
             ORDER BY p.created_at DESC'
        );

        return $stmt->fetchAll();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function findByUser(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, usuario_id, nome, especie, porte, idade, descricao, imagem_url, status, created_at
             FROM pets
             WHERE usuario_id = :uid
             ORDER BY created_at DESC'
        );
        $stmt->execute(['uid' => $userId]);

        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, usuario_id, nome, especie, porte, idade, descricao, imagem_url, status, created_at
             FROM pets WHERE id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    /**
     * @param array{usuario_id: int, nome: string, especie: string, porte: string, idade: int, descricao: string, imagem_url: ?string} $data
     */
    public function save(array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO pets (usuario_id, nome, especie, porte, idade, descricao, imagem_url, status)
             VALUES (:usuario_id, :nome, :especie, :porte, :idade, :descricao, :imagem_url, 1)'
        );

        $stmt->execute([
            'usuario_id' => $data['usuario_id'],
            'nome' => $data['nome'],
            'especie' => $data['especie'],
            'porte' => $data['porte'],
            'idade' => $data['idade'],
            'descricao' => $data['descricao'],
            'imagem_url' => $data['imagem_url'],
        ]);

        return $stmt->rowCount() === 1;
    }

    /**
     * @param array{nome: string, especie: string, porte: string, idade: int, descricao: string, imagem_url: ?string} $data
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE pets SET nome = :nome, especie = :especie, porte = :porte, idade = :idade, descricao = :descricao, imagem_url = :imagem_url
             WHERE id = :id'
        );

        $stmt->execute([
            'id' => $id,
            'nome' => $data['nome'],
            'especie' => $data['especie'],
            'porte' => $data['porte'],
            'idade' => $data['idade'],
            'descricao' => $data['descricao'],
            'imagem_url' => $data['imagem_url'],
        ]);

        return $stmt->rowCount() >= 0;
    }

    public function updateStatus(int $id, int $status): bool
    {
        if ($status !== 0 && $status !== 1) {
            return false;
        }

        $stmt = $this->pdo->prepare('UPDATE pets SET status = :status WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'status' => $status,
        ]);

        return $stmt->rowCount() === 1;
    }

    public function markAsAdopted(int $id): bool
    {
        return $this->updateStatus($id, 0);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM pets WHERE id = :id');
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() === 1;
    }
}

