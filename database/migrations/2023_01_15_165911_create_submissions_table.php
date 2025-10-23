<?php

use App\Enums\ReasonSubmission;
use App\Models\Comment;
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
        Schema::create('submissions', function (Blueprint $table){
            $table->id();
            $table->json('content');
            $table->enum('reason', array_column(ReasonSubmission::cases(), 'value'));
            $table->foreignIdFor(Comment::class)->references('id')->on('comments')->onDelete('cascade');
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
