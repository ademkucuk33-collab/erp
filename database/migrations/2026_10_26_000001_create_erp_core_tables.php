<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('name');
            $table->char('country_code', 2);
            $table->text('address')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 40)->default('warehouse_staff');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku', 100)->unique();
            $table->string('name');
            $table->string('barcode')->nullable()->index();
            $table->unsignedInteger('weight_g')->nullable();
            $table->unsignedInteger('length_mm')->nullable();
            $table->unsignedInteger('width_mm')->nullable();
            $table->unsignedInteger('height_mm')->nullable();
            $table->unsignedBigInteger('cost_minor')->nullable();
            $table->char('cost_currency', 3)->default('EUR');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('inventory_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->restrictOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('on_hand')->default(0);
            $table->unsignedInteger('reserved')->default(0);
            $table->timestamps();
            $table->unique(['warehouse_id', 'product_id']);
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->restrictOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('delta');
            $table->string('reason', 60);
            $table->string('reference_key', 150)->unique();
            $table->timestamps();
        });

        Schema::create('channel_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('platform', 40);
            $table->string('name');
            $table->char('country_code', 2)->nullable();
            $table->string('external_account_id')->nullable();
            $table->text('credentials')->nullable();
            $table->boolean('active')->default(false);
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
            $table->unique(['platform', 'external_account_id']);
        });

        Schema::create('channel_skus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->string('external_sku', 160);
            $table->timestamps();
            $table->unique(['channel_account_id', 'external_sku']);
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_account_id')->constrained()->restrictOnDelete();
            $table->string('external_order_id', 170);
            $table->foreignId('warehouse_id')->nullable()->constrained()->nullOnDelete();
            $table->char('destination_country', 2);
            $table->char('currency', 3)->default('EUR');
            $table->unsignedBigInteger('total_minor')->default(0);
            $table->string('status', 40)->default('new');
            $table->text('shipping_address')->nullable();
            $table->timestamp('placed_at')->nullable();
            $table->timestamps();
            $table->unique(['channel_account_id', 'external_order_id']);
            $table->index(['warehouse_id', 'status']);
            $table->index(['placed_at', 'destination_country']);
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('external_sku', 160);
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('unit_price_minor')->default(0);
            $table->timestamps();
        });

        Schema::create('stock_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained()->restrictOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('quantity');
            $table->string('status', 25)->default('active');
            $table->timestamps();
        });

        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('provider', 60)->nullable();
            $table->string('external_shipment_id')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('status', 40)->default('pending');
            $table->string('label_storage_path')->nullable();
            $table->timestamps();
            $table->unique(['provider', 'external_shipment_id']);
        });

        Schema::create('webhook_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_account_id')->constrained()->cascadeOnDelete();
            $table->string('external_event_id', 190);
            $table->string('event_type', 100);
            $table->string('status', 30)->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamps();
            $table->unique(['channel_account_id', 'external_event_id']);
        });
    }

    public function down(): void
    {
        foreach (['webhook_events', 'shipments', 'stock_reservations', 'order_items', 'orders', 'channel_skus', 'channel_accounts', 'stock_movements', 'inventory_balances', 'products'] as $table) {
            Schema::dropIfExists($table);
        }
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('warehouse_id');
            $table->dropColumn('role');
        });
        Schema::dropIfExists('warehouses');
    }
};
