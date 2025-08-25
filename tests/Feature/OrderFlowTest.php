<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Bag;
use App\Models\CreditCard;
use App\Models\Seller;
use App\Models\Store;
use App\Models\Category;
use App\Models\Campaign;
use App\Models\CampaignCondition;
use App\Models\CampaignDiscount;


class OrderFlowTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_can_create_order_with_campaign(): void
    {
        $user = User::factory()->create([
            'username' => 'ismail',
            'email' => 'hecksoft0@gmail.com',
            'password' => bcrypt('ismail')
        ]);
        
        $seller = Seller::factory()->create();
        $store = Store::factory()->create(['seller_id' => $seller->id]);
        $category = Category::factory()->create([
            'category_title' => 'Roman'
        ]);
        
        $campaign = Campaign::factory()->create([
            'store_id' => $store->id,
            'store_name' => $store->name,
            'name' => 'Test Kampanya - Yazar İndirimi',
            'type' => 'percentage',
            'usage_limit_for_user' => 1,
            'usage_limit' => 1,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
            'description' => 'Sabahattin Ali kitaplarında %10 indirim',
            'is_active' => true,
        ]);

        CampaignCondition::factory()->create([
            'campaign_id' => $campaign->id,
            'condition_type' => 'author',
            'condition_value' => json_encode(['Sabahattin Ali']),
            'operator' => '=',
        ]);

        CampaignDiscount::factory()->create([
            'campaign_id' => $campaign->id,
            'discount_type' => 'percentage',
            'discount_value' => json_encode(['percentage' => 10]),
        ]);
        
        $product = Product::factory()->create([
            'store_id' => $store->id,
            'category_id' => $category->id,
            'author' => 'Sabahattin Ali',
            'stock_quantity' => 10,
            'list_price' => 200.00,
        ]);

        $creditCard = CreditCard::factory()->create([
            'user_id' => $user->id,
            'card_holder_name' => 'John Doe',
            'card_number' => '5528790000000008',
            'expire_year' => '2030',
            'expire_month' => '12',
            'cvv' => '123',
            'card_type' => 'mastercard'
        ]);

        $response = $this->actingAs($user)->post('/api/bags', [
            'product_id' => $product->id,
            'quantity' => 1
        ]);

        $response = $this->actingAs($user)->post('/api/orders', [
            'credit_card_id' => $creditCard->id,
        ]);
        $response = $this->actingAs($user)->post('/api/myorders/1/refund', [
            'refund_quantities' => [
                "1" => 1
            ]
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
        ]);
        
        $order = \DB::table('orders')->where('user_id', $user->id)->first();
        
        if ($order->campaign_id) {
            $this->assertGreaterThan(0, $order->discount);
            $this->assertEquals($campaign->id, $order->campaign_id);
        }
        
        dd([
            'order' => $order,
            'campaign_applied' => $order->campaign_id,
            'discount_amount' => $order->discount,
            'campaign_info' => $order->campaign_info,
            'campaign_works' => $order->campaign_id ? 'Evet' : 'Hayır',
            'order_items' => \DB::table('order_items')->get(),
        ]);
    }
}