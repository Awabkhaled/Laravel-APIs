<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Removing unwanted columns
            $table->dropColumn('email');
            $table->dropColumn('email_verified_at');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');

            $table->string('phone_number')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
