<?php

namespace almacenGH\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VentaFormRequest extends FormRequest
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
            'idcliente'=>'required',
            'idlocalidad'=>'required',
            'idred'=>'required',
            'fecha'=>'required',
            'idarticulo'=>'required',
            'cantidad'=>'required',
        ];
    }
}
