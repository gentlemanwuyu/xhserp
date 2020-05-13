<?php
namespace App\Modules\Index\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'type' => 'required',
			'name' => 'required|max:255|alpha_dash|unique:permissions,name' . ($this->get('permission_id') ? ',' . $this->get('permission_id') : ''),
			'display_name' => 'required|max:255|unique:permissions,display_name' . ($this->get('permission_id') ? ',' . $this->get('permission_id') : ''),
			'route' => 'max:255',
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
			'type.required' => '请选择类型',
			'name.required' => '请输入权限名',
			'name.max' => '权限名不能超过:max个字符',
			'name.alpha_dash' => '权限名只能包含字母、数字、破折号（ - ）以及下划线（ _ ）',
			'name.unique' => '权限名已存在',
			'display_name.required' => '请输入显示名称',
			'display_name.max' => '显示名称不能超过:max个字符',
			'display_name.unique' => '显示名称已存在',
			'route.max' => '路由不能超过:max个字符',
		];
	}
}
