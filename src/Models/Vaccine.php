<?php

declare(strict_types=1);

final class Vaccine
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    
    //Salva uma nova vacina no banco de dados.
    
    public function save(array $dados): bool
    {
        $sql = "INSERT INTO vacinas (pet_id, nome, data_aplicacao, proxima_dose) 
                VALUES (:pet_id, :nome, :data_aplicacao, :proxima_dose)";
        
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':pet_id'         => (int) $dados['pet_id'],
            ':nome'           => $dados['nome'],
            ':data_aplicacao' => $dados['data_aplicacao'],
            // Se a proxima_dose vier vazia, gravamos como NULL no banco
            ':proxima_dose'   => $dados['proxima_dose'] !== '' ? $dados['proxima_dose'] : null
        ]);
    }

    
    //Busca todas as vacinas de um pet, ordenadas da mais recente para a mais antiga.
    
    public function findByPet(int $petId): array
    {
        $sql = "SELECT * FROM vacinas WHERE pet_id = :pet_id ORDER BY data_aplicacao DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':pet_id' => $petId]);
        
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $resultados === false ? [] : $resultados;
    }
    // Remove uma vacina pelo ID.
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM vacinas WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([':id' => $id]);
    }
}