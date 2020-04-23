<?php
namespace App\Modules\Purchase\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Purchase\Models\PurchaseOrderItem;

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
			'code' => 'required|max:80|unique:purchase_return_orders,code',
			'method' => 'required',
			'reason' => 'required',
			'delivery_method' => 'required',
			'items' => 'required',
		];

		if (3 == $inputs['delivery_method']) {
			$rules['express_id'] = 'required';
			$this->messages = array_merge($this->messages, ['express_id.required' => '请选择快递公司']);
			$rules['track_no'] = 'max:80';
			$this->messages = array_merge($this->messages, [
				'track_no.max' => '物流单号不能超过:max个字符',
			]);
		}

		if (in_array($inputs['delivery_method'], [2, 3])) {
			$rules['address'] = 'required|max:80';
			$this->messages = array_merge($this->messages, [
				'address.required' => '请填写地址',
				'address.max' => '地址不能超过:max个字符',
			]);
			$rules['consignee'] = 'required|max:80';
			$this->messages = array_merge($this->messages, [
				'consignee.required' => '请填写收货人',
				'consignee.max' => '收货人不能超过:max个字符',
			]);
			$rules['consignee_phone'] = 'required|max:80';
			$this->messages = array_merge($this->messages, [
				'consignee_phone.required' => '请填写联系电话',
				'consignee_phone.max' => '联系电话不能超过:max个字符',
			]);
		}

		if (!empty($inputs['items'])) {
			foreach ($inputs['items'] as $index => $item) {
				$key = 'items.' . $index . '.purchase_order_item_id';
				$rules[$key] = 'required';
				$this->messages = array_merge($this->messages, [$key . '.required' => '请选择采购订单Item']);

				$purchase_order_item = PurchaseOrderItem::find($item['purchase_order_item_id']);
				$key = 'items.' . $index . '.quantity';
				$rules[$key] = 'required|integer|max:' . $purchase_order_item->returnable_quantity;
				$this->messages = array_merge($this->messages, [
					$key . '.required' => '请输入退货数量',
					$key . '.integer' => '退货数量必须是整数',
					$key . '.max' => '退货数量不能大于可退数量',
				]);
				$key = 'items.' . $index . '.egress_quantity';
				$rules[$key] = 'required|integer|max:' . $item['quantity'];
				$this->messages = array_merge($this->messages, [
					$key . '.required' => '请输入实出数量',
					$key . '.integer' => '实出数量必须是整数',
					$key . '.max' => '实出数量不能大于退货',
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
			'name.required' => '请输入名称',
			'name.max' => '名称不能超过:max个字符',
			'name.unique' => '名称已存在',
			'code.required' => '请输入编号',
			'code.max' => '编号不能超过:max个字符',
			'code.unique' => '编号已存在',
			'company.max' => '公司不能超过:max个字符',
			'phone.max' => '电话不能超过:max个字符',
			'fax.max' => '传真不能超过:max个字符',
			'tax.required' => '请选择税率',
			'currency_code.required' => '请选择币种',
			'payment_method.required' => '请输入付款方式',
			'contacts.required' => '请至少添加一个联系人',
		], $this->messages);
	}
}
