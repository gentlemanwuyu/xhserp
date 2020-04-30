<?php
namespace App\Modules\Index\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfigRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'key' => 'required|alpha_dash|max:255',
			'value' => 'required',
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

	/**
	 * 自定义验证信息
	 *
	 * @return array
	 */
	public function messages()
	{
		return [
			'key.required' => '请输入键',
			'key.alpha_dash' => '键只能包含字母、数字、破折号（ - ）以及下划线（ _ ）',
			'key.max' => '键不能超过:max个字符',
			'value.required' => '请输入值',
		];
	}
}
