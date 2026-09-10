<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AcessoTest extends TestCase
{
    use RefreshDatabase;

    public static function rotasProtegidas(): array
    {
        return [['GET', '/alunos'], ['GET', '/alunos/create'], ['POST', '/alunos'], ['GET', '/alunos/1'], ['GET', '/alunos/1/edit'], ['PUT', '/alunos/1'], ['DELETE', '/alunos/1'], ['GET', '/cursos'], ['GET', '/admin'], ['GET', '/professor']];
    }

    #[DataProvider('rotasProtegidas')]
    public function test_visitante_e_redirecionado_para_login(string $method, string $uri): void
    {
        $this->call($method, $uri)->assertRedirectToRoute('login');
    }

    public function test_professor_nao_cadastra_aluno(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'professor']))->post(route('alunos.store'), [])->assertForbidden();

        $this->assertDatabaseCount('alunos', 0);
    }

    public function test_professor_nao_exclui_aluno(): void
    {
        $professor = User::factory()->create(['role' => 'professor']);
        $aluno = Aluno::factory()->for($professor, 'user')->create();

        $this->actingAs($professor)->delete(route('alunos.destroy', $aluno))->assertForbidden();

        $this->assertModelExists($aluno);
    }

    public function test_professor_nao_edita_aluno_de_outro_professor(): void
    {
        $professor = User::factory()->create(['role' => 'professor']);
        $aluno = Aluno::factory()->for(User::factory(), 'user')->create(['nome' => 'Original']);

        $this->actingAs($professor)->put(route('alunos.update', $aluno), ['nome' => 'Indevido'])->assertForbidden();

        $this->assertDatabaseHas('alunos', ['id' => $aluno->id, 'nome' => 'Original']);
    }

    public static function areas(): array
    {
        return [['admin', '/admin', 200], ['admin', '/professor', 200], ['professor', '/admin', 403], ['professor', '/professor', 200], ['professor', '/alunos/create', 403]];
    }

    #[DataProvider('areas')]
    public function test_areas_respeitam_role(string $role, string $uri, int $status): void
    {
        $this->actingAs(User::factory()->create(['role' => $role]))->get($uri)->assertStatus($status);
    }

    public function test_cadastro_publico_nao_permite_escolher_role_admin(): void
    {
        $this->post('/register', [
            'name' => 'Novo usuário', 'email' => 'novo@example.com',
            'password' => 'password123', 'password_confirmation' => 'password123', 'role' => 'admin',
        ])->assertRedirectToRoute('dashboard');

        $this->assertDatabaseHas('users', ['email' => 'novo@example.com', 'role' => 'professor']);
    }

    public function test_menu_e_acoes_ocultam_operacoes_proibidas(): void
    {
        $professor = User::factory()->create(['role' => 'professor']);
        $aluno = Aluno::factory()->for($professor, 'user')->create();

        $this->actingAs($professor)->get(route('alunos.index'))
            ->assertSee(route('alunos.edit', $aluno))->assertDontSeeText('Excluir')->assertDontSeeText('Cadastrar aluno');
    }

    public static function paginasAdmin(): array
    {
        return [['/'], ['/dashboard'], ['/profile'], ['/alunos'], ['/alunos/create'], ['/alunos/1'], ['/alunos/1/edit'], ['/cursos'], ['/cursos/1']];
    }

    #[DataProvider('paginasAdmin')]
    public function test_paginas_renderizam_menu_compartilhado(string $uri): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Aluno::factory()->create();

        $this->actingAs($admin)->get($uri)->assertOk()->assertSee('Navegação principal');
    }

    public static function rotasTexto(): array
    {
        return [['/sobre', 'Sobre o projeto de alunos'], ['/contato', 'Contato da escola'], ['/produto/42', 'Produto: 42'], ['/categoria/5', 'Categoria: 5'], ['/usuario/9', 'Usuario: 9']];
    }

    #[DataProvider('rotasTexto')]
    public function test_rotas_retornam_texto_e_parametro(string $uri, string $texto): void
    {
        $this->get($uri)->assertContent($texto);
    }

    public function test_parametro_e_retornado_como_texto_sem_executar_html(): void
    {
        $this->get('/produto/'.rawurlencode('<img src=x onerror=alert(1)>'))->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
    }
}
