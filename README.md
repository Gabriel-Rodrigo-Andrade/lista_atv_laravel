# Lista de atividades Laravel

Projeto individual de cadastro de alunos, desenvolvido com Laravel 13, Blade, Eloquent, SQLite e Laravel Breeze.

Repositório para entrega: https://github.com/Gabriel-Rodrigo-Andrade/lista_atv_laravel

## Executar

Requisitos: PHP 8.4 ou superior, Composer 2 e Node.js 22.12 ou superior.

```bash
git clone git@github.com:Gabriel-Rodrigo-Andrade/lista_atv_laravel.git
cd lista_atv_laravel
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm ci
npm run build
php artisan serve
```

Acesse http://127.0.0.1:8000. O SQLite dispensa configuração de um servidor de banco.

O seeder cadastra três cursos, dez alunos e duas contas de demonstração. As contas são criadas somente nos ambientes `local` e `testing`:

| Conta | E-mail | Senha |
| --- | --- | --- |
| Administrador | admin@escola.test | senha123 |
| Professor | professor@escola.test | senha123 |

Os dez alunos ficam vinculados ao professor de demonstração. Cada execução do `AlunoSeeder` acrescenta dez alunos; execute a carga inicial uma vez. O cadastro público do Breeze cria professores, sem permitir escolher o papel de administrador.

## Organização das atividades

Cada ATV tem um commit identificado por número. Cada tema tem uma branch. As branches são cumulativas: o tema seguinte parte do anterior. A `main` e a branch `tema-12-policies` contêm o projeto completo. Os commits iniciais `chore` apenas preparam o Laravel e as orientações de desenvolvimento.

| Tema / branch | Atividades |
| --- | --- |
| `tema-01-rotas` | 1: rotas de texto; 2: parâmetros |
| `tema-02-controllers` | 3: AlunoController; 4: sete ações e rotas resource |
| `tema-03-views` | 5: pasta alunos; 6: views principais |
| `tema-04-blade` | 7: layout; 8: páginas; 9: diretivas e menu compartilhado |
| `tema-05-models-eloquent` | 10: model e migration; 11: consultas |
| `tema-06-seeders` | 12: seeder de dez alunos |
| `tema-07-crud` | 13: persistência do CRUD |
| `tema-08-forms-requests` | 14: formulários; 15: Request e mensagens personalizadas |
| `tema-09-relacionamentos` | 16: Curso; 17: chave estrangeira e view dos alunos do curso |
| `tema-10-autenticacao` | 18: Breeze; 19: User e Aluno; 20: roles |
| `tema-11-middleware` | 21: proteção de `/admin` e `/professor` |
| `tema-12-policies` | 22: policy por registro; 23: autorização das ações e testes |

Os desafios fazem parte dos commits das atividades 9, 15 e 17. Para conferir a evolução:

```bash
git log --oneline --all --decorate
git switch tema-01-rotas
```

As rotas `/sobre` e `/contato` retornam texto. `/produto/{id}`, `/categoria/{id}` e `/usuario/{id}` incluem o parâmetro na resposta. `/alunos` retorna texto na primeira branch e evolui para a listagem do CRUD nos temas seguintes.

## Funcionalidades

- Cadastro, listagem, detalhes, edição e exclusão de alunos.
- Campos nome, e-mail único, curso e professor responsável opcional.
- Filtros combináveis por nome do curso, palavra no nome e cadastro nos últimos sete dias, com quantidade total de alunos.
- Relações `Curso hasMany Aluno`, `Aluno belongsTo Curso`, `User hasMany Aluno` e `Aluno belongsTo User`.
- Lista de cursos em `/cursos` e alunos de um curso em `/cursos/{curso}`.
- Login, cadastro, recuperação de senha e perfil fornecidos pelo Breeze. Com `MAIL_MAILER=log`, links de recuperação ficam no log local.
- Layout em `resources/views/layouts/app.blade.php` e menu compartilhado em `resources/views/partials/menu.blade.php`, incluído também no layout de autenticação.

## Permissões

| Ação | Administrador | Professor |
| --- | --- | --- |
| Consultar alunos e cursos | Sim | Sim |
| Cadastrar aluno | Sim | Não |
| Editar aluno | Qualquer aluno | Apenas alunos vinculados a ele |
| Excluir aluno | Sim | Não |
| Designar professor responsável | Sim | Não |
| Acessar `/admin` | Sim | Não |
| Acessar `/professor` | Sim | Sim |

A policy é aplicada no servidor e nas views. O Request autoriza antes de validar e usa somente dados validados. Um professor não consegue mudar o responsável enviando `user_id` manualmente. O campo `role` não permite atribuição em massa pelo cadastro público.

## Verificação

```bash
php artisan test --compact
npm run build
php artisan route:list --except-vendor
```

Os testes usam SQLite em memória, sem alterar o banco de desenvolvimento. Cobrem CRUD, filtros, seeders, relacionamentos, mensagens de validação, autenticação, middleware, policy e tentativas de acesso indevido.
