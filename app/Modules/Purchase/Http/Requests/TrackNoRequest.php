<?php
namespace App\Modules\Purchase\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrackNoRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'track_no' => 'required|max:80',
		];
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	public function messages()
	{
		return [
			'track_no.required' => '请输入物流单号',
			'track_no.max' => '物流单号不能超过:max个字符',
		];
	}
}
