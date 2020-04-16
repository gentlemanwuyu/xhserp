<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/4/15
 * Time: 16:17
 */

namespace App\Modules\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Modules\Index\Models\User;

class InventoryLog extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log($ori, $new, $message = '')
    {
        if (!$message) {
            if (!$ori) {
                $message = '期初库存';
            }else {
                $message = '设置库存';
            }
        }

        $content = [];
        if (!$ori) {
            $content['stock'] = $new->stock;
            $content['highest_stock'] = $new->highest_stock;
            $content['lowest_stock'] = $new->lowest_stock;
        }else {
            if ((int)$ori['stock'] != (int)$new['stock']) {
                $content['stock'] = ['old' => $ori['stock'], 'new' => $new['stock']];
            }
            if ((int)$ori['highest_stock'] != (int)$new['highest_stock']) {
                $content['highest_stock'] = ['old' => $ori['highest_stock'], 'new' => $new['highest_stock']];
            }
            if ((int)$ori['lowest_stock'] != (int)$new['lowest_stock']) {
                $content['lowest_stock'] = ['old' => $ori['lowest_stock'], 'new' => $new['lowest_stock']];
            }
        }

        if ($content) {
            self::create([
                'sku_id' => $new->sku_id,
                'product_id' => $new->sku->product_id,
                'message' => $message,
                'content' => json_encode($content),
                'user_id' => Auth::user()->id,
            ]);
        }
    }
}