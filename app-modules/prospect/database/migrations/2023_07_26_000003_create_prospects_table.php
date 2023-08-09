<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProspectsTable extends Migration
{
    public function up(): void
    {
        Schema::create('prospects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('status_id')->references('id')->on('prospect_statuses');
            $table->foreignId('source_id')->references('id')->on('prospect_sources');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full');
            $table->string('preferred')->nullable();
            $table->longText('description')->nullable();
            $table->string('email')->nullable();
            $table->string('email_2')->nullable();
            $table->string('mobile')->nullable();
            $table->boolean('sms_opt_out')->default(false);
            $table->boolean('email_bounce')->default(false);
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('address_2')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('hsgrad')->nullable();
            // TODO Determine if there can be more than one assignment to a prospect
            $table->foreignId('assigned_to_id')->references('id')->on('users');
            $table->foreignId('created_by_id')->nullable()->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
