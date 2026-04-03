-- Banco: projeto_pets 
-- Charset: utf8mb4

CREATE DATABASE IF NOT EXISTS projeto_pets
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE projeto_pets;

-- usuarios: cadastro de quem publica/doa pets
CREATE TABLE usuarios (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nome VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  senha VARCHAR(255) NOT NULL,
  whatsapp VARCHAR(32) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uk_usuarios_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- pets: FK usuario_id -> usuarios(id)
CREATE TABLE pets (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario_id INT UNSIGNED NOT NULL,
  nome VARCHAR(255) NOT NULL,
  especie ENUM('Cachorro', 'Gato', 'Outro') NOT NULL,
  porte ENUM('Pequeno', 'Médio', 'Grande') NOT NULL,
  idade INT UNSIGNED NOT NULL,
  descricao TEXT NOT NULL,
  imagem_url VARCHAR(255) NULL DEFAULT NULL,
  status TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '1 = disponível',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_pets_usuario_id (usuario_id),
  KEY idx_pets_status (status),
  CONSTRAINT fk_pets_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Banco já existente: ALTER TABLE pets ADD COLUMN imagem_url VARCHAR(255) NULL DEFAULT NULL AFTER descricao;

-- vacinas: histórico de vacinação do pet. FK pet_id -> pets(id) com exclusão em cascata
CREATE TABLE vacinas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pet_id INT UNSIGNED NOT NULL,
    nome VARCHAR(100) NOT NULL,
    data_aplicacao DATE NOT NULL,
    proxima_dose DATE DEFAULT NULL,
    FOREIGN KEY (pet_id) REFERENCES pets(id) ON DELETE CASCADE
);