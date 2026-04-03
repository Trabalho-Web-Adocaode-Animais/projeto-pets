
projeto-pets - Sistema de Doação de Animais
O projeto-pets é uma aplicação web desenvolvida em PHP para a disciplina de Web Servidor. O objetivo é conectar doadores e adotantes de animais de estimação em uma interface simples e funcional, no estilo de anúncios classificados.

----------------------------------------------------------------

🛠️ Tecnologias e Versões
Linguagem: PHP 8.1 ou superior (obrigatório PHP 8+)

Banco de Dados: MariaDB / MySQL

Padrão de Arquitetura: MVC Manual (Model-View-Controller)

Controle de Sessão: PHP Native Sessions

Ambiente: Linux (Ubuntu/Debian)

------------------------------------------------------------

📋 Requisitos de Instalação (Linux)

1. Instalação dos Pacotes Necessários
Abra o terminal e instale o PHP e as extensões de banco de dados e strings:


sudo apt update
sudo apt install php-cli php-mysql php-mbstring php-xml php-curl -y
2. Configuração do Banco de Dados
Instale o servidor de banco de dados:


sudo apt install mariadb-server -y
sudo systemctl start mariadb
Acesse o console do banco:


sudo mariadb
Execute os comandos SQL abaixo para preparar o ambiente:

SQL
CREATE DATABASE IF NOT EXISTS petmatch;
CREATE USER IF NOT EXISTS 'vitor'@'localhost' IDENTIFIED BY 'suasenha';
GRANT ALL PRIVILEGES ON petmatch.* TO 'seuusuario'@'localhost';
FLUSH PRIVILEGES;
EXIT;


3. Importação da Estrutura
Com o banco criado, importe o script de tabelas localizado na raiz do projeto:

Bash
mariadb -u seuusuario -p petmatch < database.sql

⚙️ Configuração do Sistema
Localize o arquivo config/db.php.

Certifique-se de que as constantes de conexão (host, dbname, user, password) coincidem com as configurações feitas no passo anterior.

🚀 Como Executar
Para rodar o projeto utilizando o servidor embutido do PHP, execute o comando abaixo na pasta raiz do projeto (projeto-pets):

Bash
php -S localhost:8000 -t public
Após iniciar, abra o navegador e acesse:

http://localhost:8000

📁 Estrutura do Projeto (MVC)

/config: Arquivos de configuração (banco de dados).

/public: Ponto de entrada da aplicação (index.php) e assets.

/src/Controllers: Lógica de controle e processamento de requisições.

/src/Models: Abstração de dados e regras de negócio.

/src/Views: Arquivos de apresentação (HTML/PHP).

database.sql: Script de criação das tabelas.