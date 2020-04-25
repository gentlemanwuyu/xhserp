<?php
namespace App\Modules\Sale\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentMethodApplicationRequest extends FormRequest
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
			'payment_method' => 'required',
		];

		if (\PaymentMethod::CREDIT == $inputs['payment_method']) {
			$rules['credit'] = 'required|integer';
			$this->messages = array_merge($this->messages, [
				'credit.required' => '请输入额度',
				'credit.integer' => '额度必须是整数',
			]);
		}
		if (\PaymentMethod::MONTHLY == $inputs['payment_method']) {
			$rules['monthly_day'] = 'required|integer';
			$this->messages = array_merge($this->messages, [
				'monthly_day.required' => '请输入月结天数',
				'monthly_day.integer' => '月结天数必须是整数',
			]);
		}

		$rules['reason'] = 'required';
		$this->messages = array_merge($this->messages, ['reason.required' => '请输入申请原因']);

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
			'payment_method.required' => '请选择付款方式',
		], $this->messages);
	}
}
