<?php
namespace App\Modules\Sale\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReturnOrderRequest extends FormRequest
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
			'code' => 'required|max:80|unique:return_orders,code' . ($this->get('return_order_id') ? ',' . $this->get('return_order_id') : ''),
			'method' => 'required',
			'reason' => 'required',
			'items' => 'required',
		];

		if (!empty($inputs['items'])) {
			foreach ($inputs['items'] as $index => $item) {
				$key = 'items.' . $index . '.order_item_id';
				$rules[$key] = 'required';
				$this->messages = array_merge($this->messages, [
					$key . '.required' => '请选择订单Item',
				]);
				$key = 'items.' . $index . '.quantity';
				$rules[$key] = 'required|integer';
				$this->messages = array_merge($this->messages, [
					$key . '.required' => '请输入退货数量',
					$key . '.integer' => '退货数量必须是整数',
				]);
				$key = 'items.' . $index . '.received_quantity';
				$rules[$key] = 'required|integer|max:' . $item['quantity'];
				$this->messages = array_merge($this->messages, [
					$key . '.required' => '请输入实收数量',
					$key . '.integer' => '实收数量必须是整数',
					$key . '.max' => '实收数量不能超过退货数量',
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
			'code.required' => '请输入退货单号',
			'code.max' => '退货单号不能超过:max个字符',
			'code.unique' => '退货单号已存在',
			'method.required' => '请选择退货方式',
			'reason.required' => '请输入退货原因',
			'items.required' => '请添加出货明细',
		], $this->messages);
	}
}
