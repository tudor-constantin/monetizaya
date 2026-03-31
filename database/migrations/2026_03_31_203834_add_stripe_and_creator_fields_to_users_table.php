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
            $table->string('avatar')->nullable()->after('email');
            $table->string('cover_image')->nullable()->after('avatar');
            $table->text('bio')->nullable()->after('cover_image');
            $table->string('stripe_connect_id')->nullable()->after('bio')->comment('Stripe Connect account ID for creators');
            $table->decimal('subscription_price', 8, 2)->nullable()->after('stripe_connect_id')->comment('Monthly subscription price set by creator');
            $table->json('social_links')->nullable()->after('subscription_price')->comment('Social media URLs');
            $table->boolean('is_creator')->default(false)->after('social_links');
            $table->boolean('is_active')->default(true)->after('is_creator');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar',
                'cover_image',
                'bio',
                'stripe_connect_id',
                'subscription_price',
                'social_links',
                'is_creator',
                'is_active',
            ]);
        });
    }
};
