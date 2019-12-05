<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/12/5
 * Time: 11:03
 */

namespace App\Modules\Goods\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Category\Models\Category;

class Goods extends Model
{
    use SoftDeletes;

    protected $table = 'goods';

    /**
     * 商品类型
     */
    const SINGLE = 1;
    const COMBO = 2;

    static $types = [
        self::SINGLE => 'single',
        self::COMBO => 'combo',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function category()
    {
        return $this->belongsTo(Category::class)->where('type', 2);
    }

    public function skus()
    {
        return $this->hasMany(GoodsSku::class);
    }
}