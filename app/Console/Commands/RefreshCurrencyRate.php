<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/4/20
 * Time: 17:17
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Swap\Laravel\Facades\Swap;
use App\Models\Currency;

class RefreshCurrencyRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '刷新汇率';

    /**
     * @var string
     */
    protected $local_currency;

    /**
     * 支持币种
     *
     * @var array
     */
    protected $support_currencies;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->initializeParams();

        foreach ($this->support_currencies as $currency_code => $currency_name) {
            $currency = Currency::updateOrCreate(['code' => $currency_code], ['code' => $currency_code, 'name' => $currency_name]);
            try {
                $swap = Swap::latest($currency_code . '/' . $this->local_currency);
                $currency->update(['rate' => $swap->getValue()]);
            }catch (\Exception $e) {
                $currency->update(['rate' => 0]);
                Log::info("货币[{$currency_code}]不支持");
                $this->info("货币[{$currency_code}]不支持");
                continue;
            }
        }
    }

    /**
     * 初始化脚本参数
     */
    protected function initializeParams()
    {
        $this->local_currency = config('erp.local_currency');
        $this->support_currencies = config('erp.support_currencies');
    }
}