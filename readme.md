Instalação e Setup
Clonar o projeto:

Bash
git clone https://github.com/seu-usuario/album_api.git
cd album_api
Instalar dependências (Obrigatório):
Como o projeto utiliza bibliotecas externas, rode:

Bash
composer install --no-security-blocking
Configurar Variáveis de Ambiente:
Crie um arquivo chamado .env na raiz do projeto e cole as seguintes configurações:

Snippet de código
# Database Configuration
DB_HOST=localhost
DB_NAME=banco_memorias
DB_USER=root
DB_PASS=

# JWT Security (Opcional se usar login)
JWT_SECRET=sua_chave_secreta_super_foda_123
Banco de Dados:
Certifique-se de que o seu MySQL está rodando e crie o banco:

SQL
CREATE DATABASE banco_memorias;
(Execute os scripts SQL da pasta /database se houver).

Rodar o Servidor:
Para subir a API localmente:

Bash
php -S localhost:8000 -t public
📖 Guia Definitivo do Composer
O Composer é o que faz a mágica de baixar as pastas que estavam faltando no seu projeto. Aqui estão os comandos que usamos e para que servem:

1. composer install
Quando usar: Quando você acaba de baixar o projeto do GitHub.

Ele olha o arquivo composer.json e baixa exatamente as versões que o criador do projeto usou.

Dica: Se der erro de segurança (como o do JWT), use composer install --no-security-blocking.

2. composer update
Quando usar: Quando você quer atualizar as bibliotecas para versões mais novas ou quando mudou algo manualmente no composer.json.

Ele tenta baixar a versão mais recente permitida.

3. composer require <nome-do-pacote>
Quando usar: Quando você quer instalar uma biblioteca nova.

Exemplo: composer require vlucas/phpdotenv (Para ler o arquivo .env).

Exemplo: composer require nikic/fast-route (Para gerenciar rotas).

4. composer dump-autoload (ou composer dump)
Quando usar: Quando você cria uma classe nova, muda um Namespace ou o PHP diz que não está encontrando um arquivo que você sabe que existe.

Ele regenera o arquivo vendor/autoload.php, que é o "mapa" do seu projeto.

5. composer clear-cache
Quando usar: Quando o download de alguma biblioteca trava ou dá erro estranho de "arquivo corrompido". Limpa a memória temporária do Composer.

🛠️ Estrutura de Pastas
/public: Ponto de entrada (index.php).

/src: Coração da aplicação (Controllers, Models, Routes).

/vendor: Bibliotecas instaladas pelo Composer (não mexer aqui!).

.env: Configurações sensíveis (nunca envie para o GitHub).