<nav aria-label="Navegação principal">
    <a href="{{ route('home') }}">Início</a>
    <a href="{{ route('alunos.index') }}">Alunos</a>
    <a href="{{ route('alunos.create') }}">Cadastrar aluno</a>
    <a href="{{ route('cursos.index') }}">Cursos</a>
    @auth
        <a href="{{ route('dashboard') }}">Painel</a>
        <a href="{{ route('profile.edit') }}">Perfil</a>
        <form method="POST" action="{{ route('logout') }}">@csrf<button>Sair</button></form>
    @else
        <a href="{{ route('login') }}">Entrar</a>
        <a href="{{ route('register') }}">Criar conta</a>
    @endauth
</nav>
