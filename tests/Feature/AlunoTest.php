<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\Curso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlunoTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cadastra_aluno_com_curso_e_professor(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $professor = User::factory()->create(['role' => 'professor']);
        $dados = Aluno::factory()->make(['curso_id' => Curso::factory()->create()->id, 'user_id' => $professor->id])->toArray();

        $this->actingAs($admin)->post(route('alunos.store'), $dados)->assertRedirectToRoute('alunos.index');

        $this->assertDatabaseHas('alunos', $dados);
    }

    public function test_professor_edita_aluno_proprio_sem_transferir_responsabilidade(): void
    {
        $professor = User::factory()->create(['role' => 'professor']);
        $outro = User::factory()->create(['role' => 'professor']);
        $aluno = Aluno::factory()->for($professor, 'user')->create();

        $this->actingAs($professor)->put(route('alunos.update', $aluno), [
            'nome' => 'Nome atualizado', 'email' => $aluno->email,
            'curso_id' => $aluno->curso_id, 'user_id' => $outro->id,
        ])->assertRedirectToRoute('alunos.index');

        $this->assertDatabaseHas('alunos', ['id' => $aluno->id, 'nome' => 'Nome atualizado', 'user_id' => $professor->id]);
    }

    public function test_admin_edita_e_transfere_aluno(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $professor = User::factory()->create(['role' => 'professor']);
        $aluno = Aluno::factory()->create();

        $this->actingAs($admin)->put(route('alunos.update', $aluno), [
            'nome' => 'Atualizado pelo admin', 'email' => $aluno->email,
            'curso_id' => $aluno->curso_id, 'user_id' => $professor->id,
        ])->assertRedirectToRoute('alunos.index');

        $this->assertDatabaseHas('alunos', ['id' => $aluno->id, 'nome' => 'Atualizado pelo admin', 'user_id' => $professor->id]);
    }

    public function test_admin_exclui_aluno_sem_excluir_curso_ou_professor(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $professor = User::factory()->create(['role' => 'professor']);
        $aluno = Aluno::factory()->for($professor, 'user')->create();
        $curso = $aluno->curso;

        $this->actingAs($admin)->delete(route('alunos.destroy', $aluno))->assertRedirectToRoute('alunos.index');

        $this->assertModelMissing($aluno);
        $this->assertModelExists($curso);
        $this->assertModelExists($professor);
    }

    public function test_filtra_por_nome_curso_e_data_e_conta_total(): void
    {
        $this->freezeTime();
        $user = User::factory()->create();
        $curso = Curso::factory()->create(['nome' => 'Informática']);
        $esperado = Aluno::factory()->for($curso)->create(['nome' => 'Ana Silva', 'created_at' => now()->subDays(7)]);
        Aluno::factory()->for($curso)->create(['nome' => 'Ana Antiga', 'created_at' => now()->subDays(8)]);
        Aluno::factory()->for($curso)->create(['nome' => 'Bruno']);
        Aluno::factory()->create(['nome' => 'Ana Outro Curso']);

        $this->actingAs($user)->get(route('alunos.index', ['nome' => 'Ana', 'curso' => 'Informática', 'recentes' => 1]))
            ->assertViewHas('quantidade', 4)
            ->assertViewHas('alunos', fn ($alunos) => $alunos->modelKeys() === [$esperado->id]);
    }

    public function test_lista_todos_os_alunos_sem_filtros(): void
    {
        $user = User::factory()->create();
        Aluno::factory()->count(2)->create();

        $this->actingAs($user)->get(route('alunos.index'))
            ->assertViewHas('quantidade', 2)->assertViewHas('alunos', fn ($alunos) => $alunos->count() === 2);
    }

    public function test_curso_mostra_somente_seus_alunos(): void
    {
        $user = User::factory()->create();
        $aluno = Aluno::factory()->create(['nome' => 'Aluno do curso']);
        Aluno::factory()->create(['nome' => 'Aluno de outro curso']);

        $this->actingAs($user)->get(route('cursos.show', $aluno->curso))
            ->assertSeeText('Aluno do curso')->assertDontSeeText('Aluno de outro curso');
    }

    public function test_view_escapa_conteudo_do_aluno(): void
    {
        $user = User::factory()->create();
        $aluno = Aluno::factory()->create(['nome' => '<script>alert(1)</script>']);

        $this->actingAs($user)->get(route('alunos.show', $aluno))
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_filtro_nao_interpreta_sql(): void
    {
        $user = User::factory()->create();
        Aluno::factory()->create();

        $this->actingAs($user)->get(route('alunos.index', ['nome' => "' OR 1=1 --"]))
            ->assertViewHas('alunos', fn ($alunos) => $alunos->isEmpty());
    }

    public function test_registro_inexistente_retorna_404(): void
    {
        $this->actingAs(User::factory()->create())->get(route('alunos.show', 999))->assertNotFound();
    }

    public function test_seeder_cria_dez_alunos_relacionados(): void
    {
        $this->seed();

        $this->assertDatabaseCount('alunos', 10);
        $this->assertDatabaseCount('cursos', 3);
        $this->assertDatabaseCount('users', 2);
        $this->assertSame(10, Aluno::whereHas('curso')->whereHas('user', fn ($query) => $query->where('role', 'professor'))->count());
    }
}
