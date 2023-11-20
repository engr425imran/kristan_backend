<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->string('location');
            $table->string('ticket_number');
            $table->string('duration');
            $table->integer('valet_manager');
            $table->integer('valet')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0: incomplete, 1: completed, 2: delete');
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
        Schema::dropIfExists('vehicle_requests');
    }
}
