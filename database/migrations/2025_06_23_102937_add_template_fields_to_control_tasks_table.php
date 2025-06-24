<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
   {
       Schema::table('control_tasks', function (Blueprint $table) {
           $table->unsignedBigInteger('truck_template_id')->nullable()->after('is_required');
           $table->integer('template_reference_number')->nullable()->after('truck_template_id');
           
           // Add foreign key constraint
           $table->foreign('truck_template_id')->references('id')->on('truck_templates')->onDelete('set null');
       });
   }

   public function down()
   {
       Schema::table('control_tasks', function (Blueprint $table) {
           $table->dropForeign(['truck_template_id']);
           $table->dropColumn(['truck_template_id', 'template_reference_number']);
       });
   }
};