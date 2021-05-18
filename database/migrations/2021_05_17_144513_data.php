<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Data extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sendportal_subscribers', function (Blueprint $table) {
            $table->string('text1')->nullable();     
            $table->string('text2')->nullable();     
            $table->json('data')->nullable();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sendportal_subscribers', function($table) {
            $table->dropColumn('text1');
            $table->dropColumn('text2');
            $table->dropColumn('data');
        }); 
    }
}
