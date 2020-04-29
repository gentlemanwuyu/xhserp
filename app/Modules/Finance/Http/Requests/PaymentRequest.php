<?php
namespace App\Modules\Finance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
			'supplier_id' => 'required',
			'currency_code' => 'required',
			'amount' => 'required|numeric',
			'method' => 'required',
		];

		if (\Payment::CASH == $inputs['method']) {
			$rules = array_merge($rules, ['pay_user_id' => 'required']);
			$this->messages = array_merge($this->messages, ['pay_user_id.required' => '请选择付款人']);
		}elseif (\Payment::REMITTANCE == $inputs['method']) {
			$rules = array_merge($rules, ['account_id' => 'required']);
			$this->messages = array_merge($this->messages, ['account_id.required' => '请选择汇款账户']);
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
			'supplier_id.required' => '请选择供应商',
			'currency_code.required' => '请选择币种',
			'amount.required' => '请输入付款金额',
			'amount.numeric' => '付款金额必须是数值',
			'method.required' => '请选择付款方式',
		], $this->messages);
	}
}
