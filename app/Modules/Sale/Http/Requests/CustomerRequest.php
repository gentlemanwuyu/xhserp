<?php
namespace App\Modules\Sale\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
			'name' => 'required|max:80|unique:customers,name' . ($this->get('customer_id') ? ',' . $this->get('customer_id') : ''),
			'code' => 'required|max:80|unique:customers,code' . ($this->get('customer_id') ? ',' . $this->get('customer_id') : ''),
			'company' => 'max:80',
			'phone' => 'max:80',
			'fax' => 'max:80',
			'tax' => 'required',
			'currency_code' => 'required',
			'street_address' => 'max:80',
			'contacts' => 'required',
		];

		// 如果是新增客户并且不在客户池中，必须选择付款方式
		if (empty($inputs['customer_id']) && empty($inputs['in_pool'])) {
			$rules['payment_method'] = 'required';
			$this->messages = array_merge($this->messages, ['payment_method.required' => '请选择付款方式']);
		}
		// 如果选中了付款方式，进一步判断其他字段
		if (isset($inputs['payment_method'])) {
			if (2 == $inputs['payment_method']) {
				$rules['credit'] = 'required|integer';
				$this->messages = array_merge($this->messages, [
					'credit.required' => '请输入额度',
					'credit.integer' => '额度必须是整数',
				]);
			}
			if (3 == $inputs['payment_method']) {
				$rules['monthly_day'] = 'required|integer';
				$this->messages = array_merge($this->messages, [
					'monthly_day.required' => '请输入月结天数',
					'monthly_day.integer' => '月结天数必须是整数',
				]);
			}
			if (in_array($inputs['payment_method'], [2, 3])) {
				$rules['reason'] = 'required';
				$this->messages = array_merge($this->messages, ['reason.required' => '请输入申请原因']);
			}
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
			'street_address.max' => '街道地址不能超过:max个字符',
			'contacts.required' => '请至少添加一个联系人',
		], $this->messages);
	}
}
