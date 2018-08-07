<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCsvSlipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csv_slips', function (Blueprint $table) {
            $table->increments('id');
            $table->char('target',6)->comment('明細対象月:yyyymm');
            $table->string('filename')->comment('アップロードファイル名');
            $table->text('header')->comment('CSVヘッダー（１行目）');
            $table->unsignedinteger('line')->default(0)->comment('対象者数（CSV行数）');
            $table->unsignedinteger('error')->default(0)->comment('エラー数');
            $table->string('upload_userid')->comment('登録者');
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
        Schema::dropIfExists('csv_slips');
    }
}
