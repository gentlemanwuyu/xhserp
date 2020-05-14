<?php
namespace App\Modules\Sale\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Sale\Models\DeliveryOrder;

class DeliveryOrderRequest extends FormRequest
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
			'code' => 'required|max:80|unique:delivery_orders,code' . ($this->get('delivery_order_id') ? ',' . $this->get('delivery_order_id') : ''),
			'delivery_method' => 'required',
		];

		if (DeliveryOrder::EXPRESS == $inputs['delivery_method']) {
			$rules = array_merge($rules, ['express_id' => 'required']);
			if (YES == $inputs['is_collected']) {
				$rules = array_merge($rules, ['collected_amount' => 'required|numeric']);
			}
		}

		$rules = array_merge($rules, [
			'address' => 'required',
			'consignee' => 'required|max:80',
			'consignee_phone' => 'required|max:80',
			'items' => 'required',
		]);

		if (!empty($inputs['items'])) {
			foreach ($inputs['items'] as $index => $item) {
				$key = 'items.' . $index . '.order_id';
				$rules[$key] = 'required';
				$this->messages = array_merge($this->messages, [
					$key . '.required' => '请选择订单',
				]);
				$key = 'items.' . $index . '.item_id';
				$rules[$key] = 'required';
				$this->messages = array_merge($this->messages, [
					$key . '.required' => '请选择Item',
				]);
				$key = 'items.' . $index . '.title';
				$rules[$key] = 'required|max:80';
				$this->messages = array_merge($this->messages, [
					$key . '.required' => '请输入品名',
					$key . '.max' => '品名不能超过:max个字符',
				]);
				$key = 'items.' . $index . '.quantity';
				$rules[$key] = 'required|integer';
				$this->messages = array_merge($this->messages, [
					$key . '.required' => '请输入数量',
					$key . '.integer' => '数量必须是整数',
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
			'code.required' => '请输入出货单号',
			'code.max' => '出货单号不能超过:max个字符',
			'code.unique' => '出货单号已存在',
			'delivery_method.required' => '请选择出货方式',
			'express_id.required' => '请选择快递公司',
			'collected_amount.required' => '请输入代收金额',
			'collected_amount.numeric' => '代收金额必须是数值',
			'items.required' => '请添加出货明细',
		], $this->messages);
	}
}
