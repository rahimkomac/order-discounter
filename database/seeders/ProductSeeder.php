<?php


namespace Database\Seeders;


use App\Models\Product;
use Illuminate\Database\Seeder;
use File;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::truncate();

        $json = File::get("database/data/products.json");
        $products = json_decode($json);

        foreach ($products as $key => $value) {
            Product::create([
                "id" => $value->id, // Bu alan autoincrement ancak task geregi manuel ayarlandı
                "name" => $value->name,
                "category_id" => $value->category,
                "price" => $value->price,
                "stock" => $value->stock,
            ]);
        }
    }
}
