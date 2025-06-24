<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('trucks', function (Blueprint $table) {
            $table->dropColumn(['year', 'color', 'mileage', 'vin']);
        });
    }

    public function down()
    {
        Schema::table('trucks', function (Blueprint $table) {
            $table->integer('year')->nullable();
            $table->string('color')->nullable();
            $table->decimal('mileage', 10, 2)->nullable();
            $table->string('vin', 17)->nullable();
        });
    }
};