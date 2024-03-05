<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_number');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->tinyInteger('user_type')->comment('0: customer, 1: valet manager , 2: valet');
            $table->text('profile')->nullable();
            $table->string('company')->nullable();
            $table->string('location')->nullable();
            $table->string('otp')->nullable();
            $table->integer('created_by')->default(0)->comment('valet manager id');
            $table->tinyInteger('is_free')->default(1)->comment('0: busy, 1: free');
            $table->enum('pooled_tip_frequency', ['daily', 'weekly', 'monthly'])->default('daily');
            $table->tinyInteger('first_login')->default(1);
            $table->tinyInteger('contribution')->default(1);
            $table->integer('contribution_percentage')->default(0);
            $table->integer('is_admin')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
