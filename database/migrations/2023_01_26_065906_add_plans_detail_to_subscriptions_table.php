<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlansDetailToSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->bigInteger('plan_id')->nullable()->unsigned()->index()->after('name');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
            $table->float('price')->after('plan_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign('subscriptions_plan_id_foreign');
            $table->dropColumn('subscriptions_plan_id');
            $table->dropColumn('price');
        });
    }
}
