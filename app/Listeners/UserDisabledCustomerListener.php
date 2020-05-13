<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/5/13
 * Time: 11:30
 */

namespace App\Listeners;

use App\Events\UserDisabled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use App\Modules\Index\Models\User;
use App\Modules\Sale\Models\Customer;
use App\Modules\Sale\Models\CustomerLog;

class UserDisabledCustomerListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserDisabled  $event
     * @return void
     */
    public function handle(UserDisabled $event)
    {
        try {
            $user = User::find($event->user_id);
            $customers = Customer::where('manager_id', $event->user_id)->get();
            foreach ($customers as $customer) {
                $customer->manager_id = 0;
                $customer->save();
                CustomerLog::create([
                    'customer_id' => $customer->id,
                    'action' => CustomerLog::RELEASE,
                    'content' => "禁用用户{$user->name}[{$user->id}]，系统自动释放",
                ]);
                Log::info("[UserDisabledCustomerListener]客户释放成功, customer_id:{$customer->id}, user_id:{$event->user_id}");
            }

        }catch (\Exception $e) {
            Log::info("[UserDisabledCustomerListener]事件发生异常:" . $e->getMessage());
            throw new \Exception("系统内部出错，请联系程序猿！");
        }
    }
}