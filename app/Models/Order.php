<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Order
 * @package App\Models
 * @property $id
 * @property Customer $customer
 * @property OrderItem $items
 * @property $total
 */
class Order extends Model
{
    use SoftDeletes;

    protected $table = 'orders';
    protected $fillable = ['customer_id', 'total'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
