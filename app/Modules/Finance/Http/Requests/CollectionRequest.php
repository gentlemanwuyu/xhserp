<?php
namespace App\Modules\Finance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollectionRequest extends FormRequest
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
			'customer_id' => 'required',
			'currency_code' => 'required',
			'amount' => 'required|numeric',
			'method' => 'required',
		];

		if (\Payment::CASH == $inputs['method']) {
			$rules = array_merge($rules, ['collect_user_id' => 'required']);
			$this->messages = array_merge($this->messages, ['collect_user_id.required' => '请选择收款人']);
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
			'customer_id.required' => '请选择客户',
			'currency_code.required' => '请选择币种',
			'amount.required' => '请输入收款金额',
			'amount.numeric' => '收款金额必须是数值',
			'method.required' => '请选择收款方式',
		], $this->messages);
	}
}
