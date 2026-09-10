<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Curso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AlunoValidacaoTest extends TestCase
{
    use RefreshDatabase;

    public static function dadosInvalidos(): array
    {
        return [
            'nome obrigatório' => ['nome', '', 'Informe o nome do aluno.'],
            'nome texto' => ['nome', ['indevido'], 'O nome deve ser um texto.'],
            'nome longo' => ['nome', str_repeat('a', 256), 'O nome deve ter no máximo 255 caracteres.'],
            'email obrigatório' => ['email', '', 'Informe o e-mail do aluno.'],
            'email inválido' => ['email', 'invalido', 'Informe um e-mail válido.'],
            'email longo' => ['email', str_repeat('a', 245).'@example.com', 'O e-mail deve ter no máximo 255 caracteres.'],
            'curso obrigatório' => ['curso_id', '', 'Selecione o curso do aluno.'],
            'curso inteiro' => ['curso_id', 'abc', 'Selecione um curso válido.'],
            'curso inexistente' => ['curso_id', 9999, 'O curso selecionado não existe.'],
            'professor inteiro' => ['user_id', 'abc', 'Selecione um professor válido.'],
            'professor inexistente' => ['user_id', 9999, 'O professor selecionado não existe.'],
        ];
    }

    #[DataProvider('dadosInvalidos')]
    public function test_rejeita_dados_invalidos_com_mensagem_personalizada(string $campo, mixed $valor, string $mensagem): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $dados = ['nome' => 'Ana', 'email' => 'ana@example.com', 'curso_id' => Curso::factory()->create()->id];
        $dados[$campo] = $valor;

        $this->actingAs($admin)->post(route('alunos.store'), $dados)->assertSessionHasErrors([$campo => $mensagem]);

        $this->assertDatabaseCount('alunos', 0);
    }

    public function test_rejeita_email_duplicado_ao_cadastrar(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $aluno = Aluno::factory()->create();

        $this->actingAs($admin)->post(route('alunos.store'), [
            'nome' => 'Outro', 'email' => $aluno->email, 'curso_id' => $aluno->curso_id,
        ])->assertSessionHasErrors(['email' => 'Este e-mail já está cadastrado.']);

        $this->assertDatabaseCount('alunos', 1);
    }

    public function test_rejeita_email_de_outro_aluno_ao_editar(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $aluno = Aluno::factory()->create();
        $outro = Aluno::factory()->create();

        $this->actingAs($admin)->put(route('alunos.update', $aluno), [
            'nome' => $aluno->nome, 'email' => $outro->email, 'curso_id' => $aluno->curso_id,
        ])->assertSessionHasErrors(['email' => 'Este e-mail já está cadastrado.']);

        $this->assertDatabaseHas('alunos', ['id' => $aluno->id, 'email' => $aluno->email]);
    }

    public function test_administrador_nao_pode_ser_designado_como_professor(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('alunos.store'), [
            'nome' => 'Ana', 'email' => 'ana@example.com',
            'curso_id' => Curso::factory()->create()->id, 'user_id' => $admin->id,
        ])->assertSessionHasErrors(['user_id' => 'O professor selecionado não existe.']);

        $this->assertDatabaseCount('alunos', 0);
    }

    public function test_campos_extras_nao_alteram_id_ou_data_de_cadastro(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->freezeTime();

        $this->actingAs($admin)->post(route('alunos.store'), [
            'nome' => 'Ana', 'email' => 'ana@example.com', 'curso_id' => Curso::factory()->create()->id,
            'id' => 999, 'created_at' => '2000-01-01 00:00:00',
        ])->assertRedirectToRoute('alunos.index');

        $this->assertDatabaseHas('alunos', ['id' => 1, 'created_at' => now()->format('Y-m-d H:i:s')]);
    }
}
