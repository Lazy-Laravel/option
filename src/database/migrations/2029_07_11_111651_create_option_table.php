<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Config::get('LazyLaravelOption.table_name','lazy_options'), function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('display_name')->nullable();
            $table->text('value')->nullable();
            $table->enum('autoload', ['yes', 'no'])->nullable()->default('no');
            $table->enum('type',['string','json'])->nullable()->default('string');
            $table->string('group')->nullable()->default('default');
            $table->string('model')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Config::get('LazyLaravelOption.table_name','lazy_options'));
        //Schema::dropIfExists('lazy_options');
    }
}
