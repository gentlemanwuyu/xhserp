<?php
namespace App\Modules\Index\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'email' => 'required|email',
			'password' => 'required',
			'captcha' => 'required|captcha',
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
			'email.required' => '请输入您的Email',
			'email.email' => '邮箱格式不正确',
			'password.required' => '请输入密码',
			'captcha.required' => '请输入验证码',
			'captcha.captcha' => '验证码不正确',
		];
	}
}
