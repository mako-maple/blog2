<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uq_id')->unsigned()->nullable()->comment('利用元有給ID');
            $table->integer('user_id')->unsigned()->comment('対象者ユーザID');
            $table->char('admin', 1)->nullable()->comment('管理者登録フラグ： NULL:一般　1:管理者登録情報');

            $table->char('request_type_id', 2)->comment('申請タイプ： 0:有給　他(代休、夏季休暇...)');
            $table->datetime('request_date')->nullable()->comment('申請日時');
            $table->date('use_date')->nullable()->comment('対象日');
            $table->decimal('use', 3, 2)->unsigned()->nullable()->comment('利用数(時間)');
            $table->text('memo')->nullable()->comment('メモ');

            $table->datetime('accept_date')->nullable()->comment('承認日時');
            $table->char('accept_type', 1)->default('0')->comment('承認タイプ： 0:未承認  1:承認  2:不承認');
            $table->integer('accept_userid')->unsigned()->nullable()->comment('承認者ユーザID');

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
        Schema::dropIfExists('requests');
    }
}
