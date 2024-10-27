<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUnusedColumnsFromUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email', 'email_verified_at', 'remember_token']);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->unique()->nullable(); // Restore the email column
            $table->timestamp('email_verified_at')->nullable(); // Restore the email_verified_at column
            $table->string('remember_token')->nullable(); // Restore the remember_token column
        });
    }
}
