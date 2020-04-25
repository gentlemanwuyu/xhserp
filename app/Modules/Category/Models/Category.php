<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/11/28
 * Time: 14:03
 */

namespace App\Modules\Category\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    // 类型
    const PRODUCT   = 1;
    const GOODS     = 2;
    static $types = [
        self::PRODUCT   => 'product',
        self::GOODS     => 'goods',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * 子分类
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public static function tree($type, $parent_id = 0)
    {
        $categories = self::where('type', $type)->where('parent_id', $parent_id)->get();
        foreach ($categories as $category) {
            $children = self::tree($type, $category->id);
            if (!$children->isEmpty()) {
                $category->children = $children;
            }
        }

        return $categories;
    }

    /**
     * 所有的子孙ID
     *
     * @return array
     */
    public function getChildrenIdsAttribute()
    {
        $ids = [];

        $this->children->each(function ($c) use (&$ids) {
            $ids[] = $c->id;
            $ids = array_merge($ids, $c->children_ids);
        });

        return $ids;
    }

    public function getParentIdsAttribute()
    {
        $parent_ids = [$this->parent_id];
        $parent = $this->parent;
        if ($parent) {
            $parent_ids = array_merge($parent_ids, $parent->parent_ids);
        }

        return array_filter(array_unique($parent_ids));
    }
}