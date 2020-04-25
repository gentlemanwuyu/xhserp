<?php
namespace App\Modules\Purchase\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
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
			'name' => 'required|max:80|unique:suppliers,name' . ($this->get('supplier_id') ? ',' . $this->get('supplier_id') : ''),
			'code' => 'required|max:80|unique:suppliers,code' . ($this->get('supplier_id') ? ',' . $this->get('supplier_id') : ''),
			'company' => 'max:80',
			'phone' => 'max:80',
			'fax' => 'max:80',
			'tax' => 'required',
			'currency_code' => 'required',
			'payment_method' => 'required',
			'street_address' => 'max:80',
			'contacts' => 'required',
		];

		if (\PaymentMethod::MONTHLY == $inputs['payment_method']) {
			$rules['monthly_day'] = 'required|integer';
			$this->messages = array_merge($this->messages, [
				'monthly_day.required' => '请输入月结天数',
				'monthly_day.integer' => '月结天数必须是整数',
			]);
		}

		if (!empty($inputs['contacts'])) {
			foreach ($inputs['contacts'] as $index => $contact) {
				$key = 'contacts.' . $index . '.name';
				$rules[$key] = 'required|max:80';
				$this->messages = array_merge($this->messages, [
					$key . '.required' => '请输入联系人名称',
					$key . '.max' => '联系人名称不能超过:max个字符',
				]);
				$key = 'contacts.' . $index . '.position';
				$rules[$key] = 'max:80';
				$this->messages = array_merge($this->messages, [
					$key . '.max' => '联系人职位不能超过:max个字符',
				]);
				$key = 'contacts.' . $index . '.phone';
				$rules[$key] = 'max:80';
				$this->messages = array_merge($this->messages, [
					$key . '.max' => '联系人电话不能超过:max个字符',
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
			'street_address.max' => '街道地址不能超过:max个字符',
			'contacts.required' => '请至少添加一个联系人',
		], $this->messages);
	}
}
