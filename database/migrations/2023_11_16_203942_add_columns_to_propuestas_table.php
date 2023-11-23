<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('propuestas', function (Blueprint $table) {
            Schema::table('propuestas', function (Blueprint $table) {
                $table->string('monto_a_pagar_en_cuotas')->nullable();
                $table->string('cantidad_tipos_de_cuotas')->nullable();
                $table->string('cantidad_de_cuotas_tres')->nullable();
                $table->string('monto_de_cuotas_tres')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('propuestas', function (Blueprint $table) {
            //
        });
    }
};
