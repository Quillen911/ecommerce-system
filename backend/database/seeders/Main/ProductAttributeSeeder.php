<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductAttributeSeeder extends Seeder
{
    public function run(): void
    {
        // Map attribute codes to ids
        $attrIds = DB::table('attributes')->pluck('id', 'code');

        $gameAttrId = $attrIds['game'] ?? null;
        $typeAttrId = $attrIds['type'] ?? null;

        if (!$gameAttrId || !$typeAttrId) {
            return; // attributes not seeded
        }

        // Map option slugs to ids for game and type
        $gameOptions = DB::table('attribute_options')->where('attribute_id', $gameAttrId)->pluck('id', 'slug');
        $typeOptions = DB::table('attribute_options')->where('attribute_id', $typeAttrId)->pluck('id', 'slug');

        // Helper to insert or ignore
        $insert = function (int $productId, int $attributeId, ?int $optionId = null, array $extra = []) {
            DB::table('product_attributes')->updateOrInsert(
                ['product_id' => $productId, 'attribute_id' => $attributeId, 'option_id' => $optionId],
                array_merge($extra, ['created_at' => now(), 'updated_at' => now()])
            );
        };

        // Build product_id map by slug for convenience
        $products = DB::table('products')->pluck('id', 'slug');

        // Valorant ürünleri
        if (isset($products['valorant-jett-hesabi'])) {
            $insert($products['valorant-jett-hesabi'], $gameAttrId, $gameOptions['valorant'] ?? null);
            $insert($products['valorant-jett-hesabi'], $typeAttrId, $typeOptions['character'] ?? null);
        }
        if (isset($products['valorant-vandal-skin'])) {
            $insert($products['valorant-vandal-skin'], $gameAttrId, $gameOptions['valorant'] ?? null);
            $insert($products['valorant-vandal-skin'], $typeAttrId, $typeOptions['weapon'] ?? null);
        }

        // CS:GO
        if (isset($products['csgo-ak47-redline'])) {
            $insert($products['csgo-ak47-redline'], $gameAttrId, $gameOptions['csgo'] ?? null);
            $insert($products['csgo-ak47-redline'], $typeAttrId, $typeOptions['weapon'] ?? null);
        }

        // LoL
        if (isset($products['lol-ahri'])) {
            $insert($products['lol-ahri'], $gameAttrId, $gameOptions['league-of-legends'] ?? null);
            $insert($products['lol-ahri'], $typeAttrId, $typeOptions['character'] ?? null);
        }

        // Fortnite
        if (isset($products['fortnite-skull-trooper'])) {
            $insert($products['fortnite-skull-trooper'], $gameAttrId, $gameOptions['fortnite'] ?? null);
            $insert($products['fortnite-skull-trooper'], $typeAttrId, $typeOptions['skin'] ?? null);
        }
        if (isset($products['fortnite-raven-skin'])) {
            $insert($products['fortnite-raven-skin'], $gameAttrId, $gameOptions['fortnite'] ?? null);
            $insert($products['fortnite-raven-skin'], $typeAttrId, $typeOptions['skin'] ?? null);
        }
    }
}


