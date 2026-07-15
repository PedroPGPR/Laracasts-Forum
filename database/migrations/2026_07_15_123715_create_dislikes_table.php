<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dislikes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->morphs('dislikeable');
            $table->timestamps();

            $table->unique(['user_id', 'dislikeable_id', 'dislikeable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dislikes');
    }
};
