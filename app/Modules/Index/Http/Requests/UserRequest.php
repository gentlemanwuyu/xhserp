<?php
namespace App\Modules\Index\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$rules = [];

		$inputs = $this->all();

		if (empty($inputs['user_id'])) {
			$rules['email'] = 'required|email|max:255|unique:users,email' . ($this->get('user_id') ? ',' . $this->get('user_id') : '');
		}

		return array_merge($rules, [
			'name' => 'required|max:255',
		]);
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
			'email.required' => '请输入邮箱',
			'email.email' => '邮箱格式不正确',
			'email.max' => '邮箱不能超过:max个字符',
			'email.unique' => '邮箱已存在',
			'name.required' => '请输入用户名',
			'name.max' => '用户名不能超过:max个字符',
		];
	}
}
