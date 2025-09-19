<?php

namespace Database\Seeders\Main;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        // Attributes
        $attributes = [
            ['name' => 'Game', 'code' => 'game', 'input_type' => 'select', 'is_filterable' => true],
            ['name' => 'Type', 'code' => 'type', 'input_type' => 'select', 'is_filterable' => true],
        ];

        foreach ($attributes as $attr) {
            DB::table('attributes')->updateOrInsert(['code' => $attr['code']], $attr + ['created_at' => now(), 'updated_at' => now()]);
        }

        // Options for select attributes
        $attributeCodes = DB::table('attributes')->pluck('id', 'code');

        $options = [
            // game
            ['attribute_code' => 'game', 'value' => 'Valorant', 'slug' => 'valorant'],
            ['attribute_code' => 'game', 'value' => 'CS:GO', 'slug' => 'csgo'],
            ['attribute_code' => 'game', 'value' => 'League of Legends', 'slug' => 'league-of-legends'],
            ['attribute_code' => 'game', 'value' => 'Fortnite', 'slug' => 'fortnite'],
            // type
            ['attribute_code' => 'type', 'value' => 'Character', 'slug' => 'character'],
            ['attribute_code' => 'type', 'value' => 'Weapon', 'slug' => 'weapon'],
            ['attribute_code' => 'type', 'value' => 'Skin', 'slug' => 'skin'],
        ];

        foreach ($options as $opt) {
            $attributeId = $attributeCodes[$opt['attribute_code']] ?? null;
            if (!$attributeId) { continue; }
            DB::table('attribute_options')->updateOrInsert(
                ['attribute_id' => $attributeId, 'slug' => $opt['slug']],
                ['value' => $opt['value'], 'sort_order' => 0, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}


