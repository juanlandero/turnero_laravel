<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class OfficeValidator extends FormRequest
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
            //
        ];
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'txtName'           => 'required|string|max:255',
            'txtPhone'          => 'required|string|max:50',
            'txtAddress'        => 'nullable|string',
            'txtChannel'        => 'required|string|unique:offices|max:80',
            'cmbMunicipality'   => 'required|integer|exists:municipalities,id',
        ]);
       
        if($validator->fails()) {
            return response()->json($validator->errors());
        }
        return true;
    }
}
