<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //'items.*.productId' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric',
            'items' => [
                'required', 'array' ,'min:1',
                function ($attribute, $items, $fail) {
                    $errors = [];
                    foreach ($items as $item) {
                        // TODO : burada ürün çekilerek daha detaylı bir error message döndürülebilir.
                        if (!Product::where('id', $item['productId'])->where('stock','>=', $item['quantity'])->exists()) {
                            $errors[] = 'There is not enough stock for item id '.$item['productId'];

                        }
                    }
                    if(count($errors) > 0) {
                        $fail($errors);
                    }
                }
            ],
        ];
    }

    public function all($keys = null){
        if(empty($keys)){
            return parent::json()->all();
        }

        return collect(parent::json()->all())->only($keys)->toArray();
    }
}
