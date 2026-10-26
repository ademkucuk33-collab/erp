<?php

namespace Tests\Feature;

use App\Models\ChannelAccount;
use App\Models\Order;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErpAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_depot_staff_cannot_create_another_warehouse_or_see_reports(): void
    {
        $warehouse = Warehouse::create(['code' => 'DE-01', 'name' => 'Almanya', 'country_code' => 'DE']);
        $staff = User::factory()->create(['role' => 'warehouse_staff', 'warehouse_id' => $warehouse->id]);
        $this->actingAs($staff)->post('/warehouses', ['code' => 'GB-01', 'name' => 'UK', 'country_code' => 'GB'])->assertForbidden();
        $this->actingAs($staff)->get('/reports')->assertForbidden();
    }

    public function test_staff_cannot_read_orders_in_another_warehouse(): void
    {
        $de = Warehouse::create(['code' => 'DE-01', 'name' => 'DE', 'country_code' => 'DE']);
        $fr = Warehouse::create(['code' => 'FR-01', 'name' => 'FR', 'country_code' => 'FR']);
        $staff = User::factory()->create(['role' => 'warehouse_staff', 'warehouse_id' => $de->id]);
        $account = ChannelAccount::create(['platform' => 'shopify', 'name' => 'Fr Store']);
        $order = Order::create([
            'channel_account_id' => $account->id, 'external_order_id' => 'X-001',
            'warehouse_id' => $fr->id, 'destination_country' => 'FR', 'currency' => 'EUR',
        ]);
        $this->actingAs($staff)->get('/orders/'.$order->id)->assertForbidden();
        $this->actingAs($staff)->get('/orders')->assertDontSee('X-001');
    }
}
