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
        self::SINGLE => '单品',
        self::COMBO => '组合',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function delete()
    {
        // 将对应关系删除掉
        if (Goods::SINGLE == $this->type) {
            SingleProduct::where('goods_id', $this->id)->delete();
        }elseif (Goods::COMBO == $this->type) {
            GoodsSku::where('goods_id', $this->id)->delete();
        }

        return parent::delete();
    }

    public function category()
    {
        return $this->belongsTo(Category::class)->where('type', 2);
    }

    public function skus()
    {
        return $this->hasMany(GoodsSku::class, 'goods_id');
    }

    public function getTypeNameAttribute()
    {
        return isset(self::$types[$this->type]) ? self::$types[$this->type] : '';
    }

    public function getDeletableAttribute()
    {
        foreach ($this->skus as $goods_sku) {
            if (!$goods_sku->deletable) {
                return false;
            }
        }

        return true;
    }
}