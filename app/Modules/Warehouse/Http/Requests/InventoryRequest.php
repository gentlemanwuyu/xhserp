<?php
namespace App\Modules\Warehouse\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest
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
		foreach ($inputs['inventory'] as $sku_id => $inventory) {
			$key = 'inventory.' . $sku_id . '.stock';
			$rules[$key] = 'required|integer';
			$this->messages[$key . '.required'] = '请输入库存数量';
			$this->messages[$key . '.integer'] = '库存数量必须为整数';
			$key = 'inventory.' . $sku_id . '.highest_stock';
			$rules[$key] = 'integer';
			$this->messages[$key . '.integer'] = '最高库存必须为整数';
			$key = 'inventory.' . $sku_id . '.lowest_stock';
			$rules[$key] = 'integer';
			$this->messages[$key . '.integer'] = '最低库存必须为整数';
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
		return $this->messages;
	}
}
