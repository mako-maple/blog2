<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUQsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uqs', function (Blueprint $table) {
            $table->increments('id');
//            $table->date('add_date')->comment('有給付与日');
            $table->integer('user_id')->unsigned()->comment('対象者ID');
            $table->string('add_YM')->comment('有給付与年月');
            $table->decimal('initial', 3, 1)->unsigned()->default(0.0)->comment('有給付与日数(時間)');
            $table->decimal('use', 3, 1)->unsigned()->default(0.0)->comment('有給利用日数(時間)');
//            $table->date('expire_date')->comment('有給期限日');
            $table->string('expire_YM')->comment('有給期限年月');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uqs');
    }
}
