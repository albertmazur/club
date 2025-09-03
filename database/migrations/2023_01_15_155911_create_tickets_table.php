<?php

use App\Enums\TicketStatus;
use App\Models\Event;
use App\Models\User;
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
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table){
            $table->id();
            $table->string('qr_token')->unique()->nullable();
            $table->date('dateBuy');
            $table->time('timeBuy');
            $table->string('stripe_payment_id');
            $table->enum('state', array_column(TicketStatus::cases(), 'value'));
            $table->foreignIdFor(User::class);
            $table->timestamp('used_at')->nullable();
            $table->foreignIdFor(Event::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
