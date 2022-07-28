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
        Schema::create('poster_uploads', function (Blueprint $table) {
            $table->id();
            $table->string("matricNo");
            $table->string("file_id");
            $table->string("file_name");
            $table->string("budget");
            $table->string("email");
            $table->string("title");
            $table->text("desc");
            $table->string("pay_status");
            $table->string("isPicked");
            $table->date("deadline");
            $table->dateTime("date_upload");
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
        Schema::dropIfExists('poster_uploads');
    }
};
