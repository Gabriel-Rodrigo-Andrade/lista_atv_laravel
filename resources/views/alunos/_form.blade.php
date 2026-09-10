@csrf
<label for="nome">Nome</label>
<input id="nome" name="nome" value="{{ old('nome', $aluno->nome ?? '') }}" required maxlength="255">
@error('nome')<p class="erro">{{ $message }}</p>@enderror
<label for="email">E-mail</label>
<input id="email" name="email" type="email" value="{{ old('email', $aluno->email ?? '') }}" required maxlength="255">
@error('email')<p class="erro">{{ $message }}</p>@enderror
<label for="curso">Curso</label>
<input id="curso" name="curso" value="{{ old('curso', $aluno->curso ?? '') }}" required maxlength="255">
@error('curso')<p class="erro">{{ $message }}</p>@enderror
<p><button type="submit">Salvar</button> <a href="{{ route('alunos.index') }}">Cancelar</a></p>
