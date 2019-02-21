<?php
/**
 * Created by PhpStorm.
 * User: macbookpro
 * Date: 19/2/19
 * Time: 9:20 PM
 */

namespace App\Http\Requests;

use App\Models\Attribute;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    protected $productId = null;

    public function __construct()
    {
        if (!empty(\Route::current()->parameters['product'])) {
            $this->productId = intval(\Route::current()->parameters['product']);
        }

        parent::__construct();
    }

    public function authorize()
    {
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
//            'name'                  => 'required|max:255',
//            'description'           => 'required|min:3',
//            'price'                 => 'required|numeric|between:0,9999999999999.999999',
//            'sku'                   => 'required|unique:products,sku' . ( $this->request->get('id') ? ',' . $this->request->get('id') : '' ),
//            'stock'                 => 'required|numeric',
//            'status'                => 'required|numeric|between:0,1',
//            'attribute_set_id'      => 'required',
//            'attributes'            => 'sometimes|required',
//            'attributes.*'          => 'sometimes|required',
//            'base_product_images.*' => 'image|mimes:jpeg,bmp,png,jpg,gif|max:3000|dimensions:min_width=1000,min_height=1000'
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
//    public function attributes()
//    {

//        $attributes = [];
//
//        if ( $this->input('attributes') ) {
//            foreach ( $this->input('attributes') as $key => $option ) {
//                $attributes[ 'attributes.' . $key ] = strtolower(trans('attribute.attribute')) . " \"" . Attribute::find($key)->name . "\"";
//            }
//        }

//        return $attributes;
//    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
