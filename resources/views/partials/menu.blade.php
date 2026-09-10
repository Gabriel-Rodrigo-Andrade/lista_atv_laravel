<nav aria-label="Navegação principal">
    <a href="{{ route('home') }}">Início</a>
    <a href="{{ route('alunos.index') }}">Alunos</a>
    @can('create', \App\Models\Aluno::class)
        <a href="{{ route('alunos.create') }}">Cadastrar aluno</a>
    @endcan
    <a href="{{ route('cursos.index') }}">Cursos</a>
    @auth
        @if (auth()->user()->isAdmin())<a href="{{ route('admin') }}">Administração</a>@endif
        @if (auth()->user()->isAdmin() || auth()->user()->isProfessor())<a href="{{ route('professor') }}">Área do professor</a>@endif
        <a href="{{ route('dashboard') }}">Painel</a>
        <a href="{{ route('profile.edit') }}">Perfil</a>
        <form method="POST" action="{{ route('logout') }}">@csrf<button>Sair</button></form>
    @else
        <a href="{{ route('login') }}">Entrar</a>
        <a href="{{ route('register') }}">Criar conta</a>
    @endauth
</nav>
