<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OrderItem
 * @package App\Models
 * @property $id
 * @property $order_id
 * @property $product_id
 * @property $quantity
 * @property $unit_price
 * @property $total
 */
class OrderItem extends Model
{
    use SoftDeletes;

    protected $table = 'order_items';
    protected $fillable = ['order_id', 'product_id', 'quantity', 'unit_price', 'total'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
