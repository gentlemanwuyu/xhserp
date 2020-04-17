<?php
namespace App\Modules\Goods\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Goods\Models\SingleSkuProductSku;

class SingleRequest extends FormRequest
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
			'code' => 'required|max:80|unique:goods,code' . ($this->get('goods_id') ? ',' . $this->get('goods_id') : ''),
			'name' => 'required|max:80',
			'category_id' => 'required',
			'skus' => 'required',
		];

		if (!empty($inputs['skus'])) {
			foreach ($inputs['skus'] as $product_sku_id => $goods_sku) {
				$goods_sku_id = SingleSkuProductSku::where('product_sku_id', $product_sku_id)->value('goods_sku_id');
				$key = 'skus.' . $product_sku_id . '.code';
				$rules[$key] = 'required|max:80|unique:goods_skus,code' . ($goods_sku_id ? ',' . $goods_sku_id : '');
				$this->messages = array_merge($this->messages, [
					$key . '.required' => '请输入SKU编号',
					$key . '.max' => 'SKU编号不能超过:max个字符',
					$key . '.unique' => 'SKU编号已存在',
				]);
				$key = 'skus.' . $product_sku_id . '.lowest_price';
				$rules[$key] = 'required|numeric';
				$this->messages = array_merge($this->messages, [
					$key . '.required' => '最低售价不能为空',
					$key . '.numeric' => '最低售价必须是数值',
				]);
				$key = 'skus.' . $product_sku_id . '.msrp';
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
