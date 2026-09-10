<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alunos', function (Blueprint $table) {
            $table->foreignId('curso_id')->nullable()->constrained()->restrictOnDelete();
        });
        foreach (DB::table('alunos')->distinct()->pluck('curso') as $nome) {
            DB::table('cursos')->insertOrIgnore(['nome' => $nome, 'created_at' => now(), 'updated_at' => now()]);
            $cursoId = DB::table('cursos')->where('nome', $nome)->value('id');
            DB::table('alunos')->where('curso', $nome)->update(['curso_id' => $cursoId]);
        }
        Schema::table('alunos', function (Blueprint $table) {
            $table->unsignedBigInteger('curso_id')->nullable(false)->change();
            $table->dropColumn('curso');
        });
    }

    public function down(): void
    {
        Schema::table('alunos', function (Blueprint $table) {
            $table->string('curso')->default('');
        });
        foreach (DB::table('cursos')->get() as $curso) {
            DB::table('alunos')->where('curso_id', $curso->id)->update(['curso' => $curso->nome]);
        }
        Schema::table('alunos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('curso_id');
        });
    }
};
