<?php

use App\Models\Category;
use App\Models\Sticker;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('preview_image')->nullable();
            $table->string('attributes')->nullable();
            $table->unsignedInteger('price');
            $table->unsignedInteger('orders_quantity')->default(0);
            $table->unsignedInteger('count')->default(0);
            $table->float('rating', precision: 1)->default(0);
            $table->boolean('is_published')->default(true);

            $table->foreignIdFor(Category::class)
                ->index()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(Sticker::class)
                ->index()
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (app()->isLocal()) {
            Schema::dropIfExists('products');
        }
    }
};
