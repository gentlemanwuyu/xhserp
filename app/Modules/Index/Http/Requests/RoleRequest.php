<?php
namespace App\Modules\Index\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'name' => 'required|max:255|alpha_dash|unique:roles,name' . ($this->get('role_id') ? ',' . $this->get('role_id') : ''),
			'display_name' => 'required|max:255|unique:roles,display_name' . ($this->get('role_id') ? ',' . $this->get('role_id') : ''),
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
			'name.required' => '请输入角色名',
			'name.max' => '角色名不能超过:max个字符',
			'name.alpha_dash' => '角色名只能包含字母、数字、破折号（ - ）以及下划线（ _ ）',
			'name.unique' => '角色名已存在',
			'display_name.required' => '请输入显示名称',
			'display_name.max' => '显示名称不能超过:max个字符',
			'display_name.unique' => '显示名称已存在',
		];
	}
}
