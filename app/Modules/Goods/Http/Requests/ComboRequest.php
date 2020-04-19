<?php
namespace App\Modules\Goods\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Goods\Models\ComboProduct;

class ComboRequest extends FormRequest
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
		if (isset($inputs['product_ids'])) {
			$product_ids = array_keys($inputs['product_ids']);
		}else {
			$product_ids = array_unique(ComboProduct::where('goods_id', $inputs['goods_id'])->pluck('product_id')->toArray());
		}

		$rules = [
			'code' => 'required|max:80|unique:goods,code' . ($this->get('goods_id') ? ',' . $this->get('goods_id') : ''),
			'name' => 'required|max:80',
			'category_id' => 'required',
			'skus' => 'required',
		];

		if (!empty($inputs['skus'])) {
			foreach ($inputs['skus'] as $index => $goods_sku) {
				$key = 'skus.' . $index . '.code';
				$rules[$key] = 'required|max:80|unique:goods_skus,code' . ($index ? ',' . $index : '');
				$this->messages = array_merge($this->messages, [
					$key . '.required' => '请输入SKU编号',
					$key . '.max' => 'SKU编号不能超过:max个字符',
					$key . '.unique' => 'SKU编号已存在',
				]);
				$key = 'skus.' . $index . '.parts';
				$rules[$key] = 'required';
				$this->messages = array_merge($this->messages, [
					$key . '.required' => 'SKU has no parts',
				]);
				foreach ($product_ids as $product_id) {
					$key = 'skus.' . $index . '.parts' . '.' . $product_id;
					$rules[$key] = 'required';
					$this->messages = array_merge($this->messages, [
						$key . '.required' => '请选择产品SKU',
					]);
				}
				$key = 'skus.' . $index . '.lowest_price';
				$rules[$key] = 'required|numeric';
				$this->messages = array_merge($this->messages, [
					$key . '.required' => '最低售价不能为空',
					$key . '.numeric' => '最低售价必须是数值',
				]);
				$key = 'skus.' . $index . '.msrp';
				$rules[$key] = 'numeric';
				$this->messages = array_merge($this->messages, [$key . '.numeric' => '建议零售价必须是数值']);
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

	public function messages()
	{
		return array_merge([
			'code.required' => '请输入商品编号',
			'code.max' => '商品编号不能超过:max个字符',
			'code.unique' => '商品编号已存在',
			'name.required' => '请输入商品名称',
			'name.max' => '商品名称不能超过:max个字符',
			'category_id.required' => '请选择分类',
			'skus.required' => '请至少添加一个SKU',
		], $this->messages);
	}
}
