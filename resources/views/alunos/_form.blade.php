@csrf
<label for="nome">Nome</label>
<input id="nome" name="nome" value="{{ old('nome', $aluno->nome ?? '') }}" required maxlength="255">
@error('nome')<p class="erro">{{ $message }}</p>@enderror
<label for="email">E-mail</label>
<input id="email" name="email" type="email" value="{{ old('email', $aluno->email ?? '') }}" required maxlength="255">
@error('email')<p class="erro">{{ $message }}</p>@enderror
<label for="curso_id">Curso</label>
<select id="curso_id" name="curso_id" required>
    <option value="">Selecione um curso</option>
    @foreach ($cursos as $curso)
        <option value="{{ $curso->id }}" @selected(old('curso_id', $aluno->curso_id ?? '') == $curso->id)>{{ $curso->nome }}</option>
    @endforeach
</select>
@error('curso_id')<p class="erro">{{ $message }}</p>@enderror
<p><button type="submit">Salvar</button> <a href="{{ route('alunos.index') }}">Cancelar</a></p>
