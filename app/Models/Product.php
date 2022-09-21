<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

/**
 * Class Product
 * @package App\Models
 * @property $id
 * @property $name
 * @property $category_id
 * @property $price
 * @property $stock
 */
class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';
    // Fillable->id autoincrement ancak task geregi manuel ayarlandı
    protected $fillable = ['id', 'name', 'category_id', 'price', 'stock'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function decreaseStock($quantity)
    {
        if ($quantity < 0) {
            throw new InternalErrorException('AutOfStock');
        }

        return $this->where('id', $this->id)->where('stock', '>=', $quantity)->decrement('stock', $quantity);
    }

    // Hata tipine göre exception class oluşturulup fırlatılamlı !
    public function addStock($quantity)
    {
        if ($quantity < 0) {
            throw new InternalErrorException('AuthOfStock');
        }
        $this->increment('stock', $quantity);
    }
}
