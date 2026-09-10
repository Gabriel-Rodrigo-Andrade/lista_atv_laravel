<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AlunoPolicyTest extends TestCase
{
    public static function permissoes(): array
    {
        return [
            ['admin', 'viewAny', false, true], ['admin', 'view', false, true],
            ['admin', 'create', false, true], ['admin', 'update', false, true], ['admin', 'delete', false, true],
            ['professor', 'viewAny', false, true], ['professor', 'view', false, true],
            ['professor', 'create', false, false], ['professor', 'update', true, true],
            ['professor', 'update', false, false], ['professor', 'delete', true, false],
            ['invalido', 'viewAny', false, false], ['invalido', 'view', false, false],
            ['invalido', 'create', false, false], ['invalido', 'update', true, false], ['invalido', 'delete', true, false],
        ];
    }

    #[DataProvider('permissoes')]
    public function test_policy_respeita_role_e_responsabilidade(string $role, string $acao, bool $proprio, bool $permitido): void
    {
        $user = User::factory()->make(['id' => 1, 'role' => $role]);
        $aluno = Aluno::factory()->make(['id' => 1, 'curso_id' => 1, 'user_id' => $proprio ? 1 : 2]);
        $recurso = in_array($acao, ['viewAny', 'create']) ? Aluno::class : $aluno;

        $this->assertSame($permitido, Gate::forUser($user)->allows($acao, $recurso));
    }
}
