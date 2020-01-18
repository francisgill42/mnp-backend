<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_name')->nullable();
            $table->string('trade_name')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('delivery_from')->nullable();
            $table->string('delivery_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('company_name');
            $table->dropColumn('trade_name');
            $table->dropColumn('contact_person_name');
            $table->dropColumn('payment_type');
            $table->dropColumn('delivery_from');
            $table->dropColumn('delivery_to');
        });
    }
}
