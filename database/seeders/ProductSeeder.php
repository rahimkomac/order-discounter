<?php


namespace Database\Seeders;


use App\Models\Product;
use Illuminate\Database\Seeder;
use File;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

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
