<?php
namespace App\Modules\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return  [
			'name' => 'required|max:64|unique:categories,name' . ($this->get('category_id') ? ',' . $this->get('category_id') : ''),
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
			'name.required' => '分类名不能为空',
			'name.max' => '分类名的长度不能超过:max个字符',
			'name.unique' => '分类名已存在',
		];
	}
}
