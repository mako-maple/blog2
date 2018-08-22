<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaySlipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_slips', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedinteger('csv_id')->comment('CSV ID');
            $table->unsignedinteger('line')->comment('CSV 行番号');
            $table->char('target',6)->comment('明細対象月:yyyymm');
            $table->unsignedinteger('user_id')->nullable()->comment('対象者ID');
            $table->string('loginid')->comment('対象者 ログインID');
            $table->text('slip')->comment('CSV行データ');
            $table->string('filename')->nullable()->comment('ファイル名');
            $table->unsignedinteger('download')->default(0)->comment('ユーザのダウンロード回数');
            $table->string('error')->nullable()->comment('エラー内容');
            $table->boolean('check')->default(false)->comment('監査チェック');
            $table->timestamp('checked_at')->nullable()->comment('監査チェック日時');
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
        Schema::dropIfExists('pay_slips');
    }
}
