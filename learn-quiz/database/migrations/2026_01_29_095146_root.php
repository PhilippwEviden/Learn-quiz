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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deck_id')->constrained()->onDelete('cascade');
            // These two columns are the magic:
            $table->unsignedBigInteger('cardable_id');   // The ID of the MC or Basic card
            $table->string('cardable_type');             // The Class Name (e.g., 'App\Models\MultipleChoiceCard')
            $table->timestamps();
        });
        Schema::create('basic_cards', function (Blueprint $table) {
            $table->id();
            $table->string('expression');
            $table->string('definition');
            $table->timestamps();
        });
        Schema::create('multiple_choice_cards', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer1');
            $table->text('answer2')->nullable();
            $table->text('answer3')->nullable();
            $table->text('answer4')->nullable();
            $table->integer('correct_answer'); // 1, 2, 3, or 4
            $table->timestamps();
        });
        Schema::create('decks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
        Schema::create('card_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained('cards')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('review_count')->default(0);
            $table->integer('correct_count')->default(0);
            $table->integer('wrong_count')->default(0);
            $table->timestamp('last_reviewed_at')->nullable();
            $table->timestamps();
        });
        Schema::create('deck_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deck_id')->constrained('decks')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('can_edit')->default(false);
            $table->boolean('favorite')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deck_user');
        Schema::dropIfExists('card_user');
        Schema::dropIfExists('deck_card');
        Schema::dropIfExists('decks');
        Schema::dropIfExists('cards');
    }
};
