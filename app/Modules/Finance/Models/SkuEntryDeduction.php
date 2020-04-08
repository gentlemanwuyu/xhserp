<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/4/8
 * Time: 17:40
 */

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Warehouse\Models\SkuEntry;

class SkuEntryDeduction extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function skuEntry()
    {
        return $this->belongsTo(SkuEntry::class);
    }
}