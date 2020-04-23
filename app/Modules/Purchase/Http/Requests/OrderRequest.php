<?php
namespace App\Modules\Purchase\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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

		$rules = [];

		if (empty($inputs['order_id'])) {
			$rules['supplier_id'] = 'required';
			$this->messages = array_merge($this->messages, [
				'supplier_id.required' => '请选择供应商',
			]);
		}

		$rules = array_merge($rules, [
			'code' => 'required|max:80|unique:purchase_orders,code' . ($this->get('order_id') ? ',' . $this->get('order_id') : ''),
			'payment_method' => 'required',
			'tax' => 'required',
			'currency_code' => 'required',
			'items' => 'required',
		]);

		if (!empty($inputs['items'])) {
			foreach ($inputs['items'] as $index => $item) {
				$key = 'items.' . $index . '.product_id';
				$rules[$key] = 'required';
				$this->messages = array_merge($this->messages, [
					$key . '.required' => '请选择产品',
				]);
				$key = 'items.' . $index . '.sku_id';
				$rules[$key] = 'required';
				$this->messages = array_merge($this->messages, [
					$key . '.required' => '请选择SKU',
				]);
				$key = 'items.' . $index . '.title';
				$rules[$key] = 'required|max:80';
				$this->messages = array_merge($this->messages, [
					$key . '.required' => '请输入标题',
					$key . '.max' => '标题不能超过:max个字符',
				]);
				$key = 'items.' . $index . '.unit';
				$rules[$key] = 'required|max:80';
				$this->messages = array_merge($this->messages, [
					$key . '.required' => '请输入单位',
					$key . '.max' => '单位不能超过:max个字符',
				]);
				$key = 'items.' . $index . '.quantity';
				$rules[$key] = 'required|integer';
				$this->messages = array_merge($this->messages, [
					$key . '.required' => '请输入数量',
					$key . '.integer' => '数量必须是整数',
				]);
				$key = 'items.' . $index . '.price';
				$rules[$key] = 'required|numeric';
				$this->messages = array_merge($this->messages, [
					$key . '.required' => '请输入单价',
					$key . '.integer' => '单价必须是数值',
				]);
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
			'code.required' => '请输入订单号',
			'code.max' => '订单号不能超过:max个字符',
			'code.unique' => '订单号已存在',
			'payment_method.required' => '请选择付款方式',
			'tax.required' => '请选择税率',
			'currency_code.required' => '请选择币种',
			'items.required' => '请添加订单明细',
		], $this->messages);
	}
}
