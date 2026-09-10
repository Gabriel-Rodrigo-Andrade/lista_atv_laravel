<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AlunoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('alunos', 'email')->ignore($this->route('aluno'))],
            'curso_id' => ['required', 'integer', 'exists:cursos,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'Informe o nome do aluno.',
            'nome.string' => 'O nome deve ser um texto.',
            'nome.max' => 'O nome deve ter no máximo 255 caracteres.',
            'email.required' => 'Informe o e-mail do aluno.',
            'email.email' => 'Informe um e-mail válido.',
            'email.max' => 'O e-mail deve ter no máximo 255 caracteres.',
            'email.unique' => 'Este e-mail já está cadastrado.',
            'curso_id.required' => 'Selecione o curso do aluno.',
            'curso_id.integer' => 'Selecione um curso válido.',
            'curso_id.exists' => 'O curso selecionado não existe.',
        ];
    }
}
