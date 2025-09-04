<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pureTamanu =Product::create([
            'name' => 'Pure Tamanu Oil',
            'type' => 'pure',
            'description' => '100% pure, cold-pressed Tamanu oil, offering deep skin healing and hydration. Ideal for wound healing, scar fading, skin tag removal, and balancing skin tone—all without clogging pores. Perfect for all skin types.',
        ]);

        ProductVariant::create([
            'product_id' => $pureTamanu->id, 
            'size' => '1 oz',
            'price' => 45.00, 
            'stock_quantity' => 12,
            'sku' => 'PUR30',
            'image_url' => 'https://duplinestephanus.github.io/WebbApp-Files/images/alanastamanu_product_imgs/PureTamanu-1oz.webp',
        ]);

        ProductVariant::create([
            'product_id' => $pureTamanu->id, 
            'size' => '15 ml',
            'price' => 25.00, 
            'stock_quantity' => 23,
            'sku' => 'PUR15',
            'image_url' => 'https://duplinestephanus.github.io/WebbApp-Files/images/alanastamanu_product_imgs/PureTamanu-15ml.webp',
        ]);

        ProductVariant::create([
            'product_id' => $pureTamanu->id, 
            'size' => '10 ml',
            'price' => 15.00, 
            'stock_quantity' => 30,
            'sku' => 'PUR10',
            'image_url' => 'https://duplinestephanus.github.io/WebbApp-Files/images/alanastamanu_product_imgs/PureTamanu-10ml.webp',
        ]);

        $fragrantTamanu = Product::create([
            'name' => 'Fragrant Tamanu Oil',
            'type' => 'fragrant',
            'description' => 'Pure Tamanu oil infused with natural Ylang Ylang essential oil for a calming fragrance. Provides the same healing benefits as the original—hydration, skin tag removal, and tone balancing—with a soothing aromatic twist for a luxurious skincare experience.',
        ]);

        ProductVariant::create([
            'product_id' => $fragrantTamanu->id, 
            'size' => '1 oz',
            'price' => 46.00, 
            'stock_quantity' => 23,
            'sku' => 'FRA30',
            'image_url' => 'https://duplinestephanus.github.io/WebbApp-Files/images/alanastamanu_product_imgs/FragrantTamanu-1oz.webp'
        ]);

        ProductVariant::create([
            'product_id' => $fragrantTamanu->id, 
            'size' => '15 ml',
            'price' => 26.00, 
            'stock_quantity' => 18,
            'sku' => 'FRA15',
            'image_url' => 'https://duplinestephanus.github.io/WebbApp-Files/images/alanastamanu_product_imgs/FragrantTamanu-15ml.webp'
        ]);

        ProductVariant::create([
            'product_id' => $fragrantTamanu->id, 
            'size' => '10 ml',
            'price' => 16.00, 
            'stock_quantity' => 45,
            'sku' => 'FRA10',
            'image_url' => 'https://duplinestephanus.github.io/WebbApp-Files/images/alanastamanu_product_imgs/FragrantTamanu-10ml.webp'
        ]);
    }
}
