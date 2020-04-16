<?php
namespace App\Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
	protected $messages = [];

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$inputs = $this->all();

		$rules = [
			'code' => 'required|max:80|unique:products,code' . ($this->get('product_id') ? ',' . $this->get('product_id') : ''),
			'name' => 'required|max:80',
			'category_id' => 'required',
			'skus' => 'required',
		];

		if (!empty($inputs['skus'])) {
			foreach ($inputs['skus'] as $index => $sku) {
				$key = 'skus.' . $index . '.code';
				$rules[$key] = 'required|max:80|unique:product_skus,code,' . $index;
				$this->messages = array_merge($this->messages, [
					$key . '.required' => '请输入SKU编号',
					$key . '.max' => 'SKU编号不能超过:max个字符',
					$key . '.unique' => 'SKU编号已存在',
				]);
				$key = 'skus.' . $index . '.weight';
				$rules[$key] = 'numeric';
				$this->messages = array_merge($this->messages, [$key . '.numeric' => 'SKU重量必须是数值']);
				$key = 'skus.' . $index . '.cost_price';
				$rules[$key] = 'numeric';
				$this->messages = array_merge($this->messages, [$key . '.numeric' => 'SKU成本价必须是数值']);
			}
		}

		return $rules;
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
		return array_merge([
			'code.required' => '请输入产品编号',
			'code.max' => '产品编号不能超过:max个字符',
			'code.unique' => '产品编号已存在',
			'name.required' => '请输入产品名称',
			'name.max' => '产品名称不能超过:max个字符',
			'category_id.required' => '请选择分类',
			'skus.required' => '请至少添加一个SKU',
		], $this->messages);
	}
}
