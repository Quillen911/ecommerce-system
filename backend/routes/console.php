<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use App\Models\ProductVariant;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Artisan::command('variants:fix-slugs', function () {
    $variants = ProductVariant::with(['product', 'attributes.attribute', 'attributes.option'])->get();

    foreach ($variants as $variant) {
        $productSlug = Str::slug($variant->product->title);

        // Renk attribute'unu attribute.code üzerinden bul
        $colorAttr = $variant->attributes->firstWhere('attribute.code', 'color');
        $colorSlug = $colorAttr?->option?->slug ?? Str::slug($colorAttr?->value ?? 'renk-yok');

        // slug formatı: renk-ürünadı-variantID
        $newSlug = "{$colorSlug}-{$productSlug}-{$variant->id}";

        $variant->slug = $newSlug;
        $variant->save();
    }

    $this->info('Variant slugs generated successfully!');
});

