<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('order_status_id')->default(1);
            $table->string('order_confirmed_date', 100)->default('');
            $table->string('order_shipped_date', 100)->default('');
            $table->string('order_delivered_date', 100)->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_status_id');
            $table->dropColumn('order_confirmed_date');
            $table->dropColumn('order_shipped_date');
            $table->dropColumn('order_delivered_date');
        });
    }
}
