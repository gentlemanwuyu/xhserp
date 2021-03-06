<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/11/28
 * Time: 14:01
 */

namespace App\Modules\Category\Database\Seeds;

use Illuminate\Database\Seeder;
use App\Modules\Category\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        $this->seedProductCategory();
        $this->seedGoodsCategory();
    }

    protected function seedProductCategory()
    {
        // 压力表
        $pressure_meter = Category::create(['name' => '压力表', 'type' => Category::PRODUCT]);
        Category::create(['name' => '不锈钢压力表', 'parent_id' => $pressure_meter->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => 'PP压力表', 'parent_id' => $pressure_meter->id, 'type' => Category::PRODUCT]);

        // 液位开关
        $level_switch = Category::create(['name' => '液位开关', 'type' => Category::PRODUCT]);
        Category::create(['name' => '立式液位开关', 'parent_id' => $level_switch->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '卧式液位开关', 'parent_id' => $level_switch->id, 'type' => Category::PRODUCT]);

        //流量计配件
        $flowmeter_parts = Category::create(['name' => '流量计配件', 'type' => Category::PRODUCT]);
        Category::create(['name' => '视管', 'parent_id' => $flowmeter_parts->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '转子', 'parent_id' => $flowmeter_parts->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '导轨', 'parent_id' => $flowmeter_parts->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '接头', 'parent_id' => $flowmeter_parts->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '螺母', 'parent_id' => $flowmeter_parts->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '指示扣', 'parent_id' => $flowmeter_parts->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '胶圈', 'parent_id' => $flowmeter_parts->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '盖子', 'parent_id' => $flowmeter_parts->id, 'type' => Category::PRODUCT]);

        // 滚轮片
        $disk = Category::create(['name' => '行辘片', 'type' => Category::PRODUCT]);
        $category = Category::create(['name' => '一体滚轮片', 'parent_id' => $disk->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => 'PVC滚轮片', 'parent_id' => $category->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => 'PP滚轮片', 'parent_id' => $category->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '包胶滚轮片', 'parent_id' => $category->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '孟山都滚轮片', 'parent_id' => $category->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '耐高温滚轮片', 'parent_id' => $category->id, 'type' => Category::PRODUCT]);
        $category = Category::create(['name' => '套胶滚轮片', 'parent_id' => $disk->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '套胶滚轮片骨架', 'parent_id' => $category->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '套胶滚轮片胶圈', 'parent_id' => $category->id, 'type' => Category::PRODUCT]);
        $category = Category::create(['name' => 'LCD滚轮', 'parent_id' => $disk->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => 'LCD滚轮内套', 'parent_id' => $category->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => 'LCD滚轮外套', 'parent_id' => $category->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => 'LCD滚轮胶圈', 'parent_id' => $category->id, 'type' => Category::PRODUCT]);

        // 间距杆
        $connect = Category::create(['name' => '间距杆', 'type' => Category::PRODUCT]);
        Category::create(['name' => '连接套', 'parent_id' => $connect->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '数字码', 'parent_id' => $connect->id, 'type' => Category::PRODUCT]);

        // 固定套
        $fixed = Category::create(['name' => '固定套', 'type' => Category::PRODUCT]);
        Category::create(['name' => '外套', 'parent_id' => $fixed->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '内套', 'parent_id' => $fixed->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '卡环', 'parent_id' => $fixed->id, 'type' => Category::PRODUCT]);

        // 轴套
        $sleeve = Category::create(['name' => '轴套', 'type' => Category::PRODUCT]);

        // 插件
        $cup = Category::create(['name' => '插件', 'type' => Category::PRODUCT]);
        Category::create(['name' => '限位块', 'parent_id' => $cup->id, 'type' => Category::PRODUCT]);

        // 齿轮
        $gear = Category::create(['name' => '齿轮', 'type' => Category::PRODUCT]);
        Category::create(['name' => '直齿轮', 'parent_id' => $gear->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '伞齿轮', 'parent_id' => $gear->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '钉齿轮', 'parent_id' => $gear->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '螺旋齿轮', 'parent_id' => $gear->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '组合齿轮', 'parent_id' => $gear->id, 'type' => Category::PRODUCT]);

        // 喷管接头
        $nozzle_joint = Category::create(['name' => '喷管接头', 'type' => Category::PRODUCT]);
        Category::create(['name' => '外套', 'parent_id' => $nozzle_joint->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '内套', 'parent_id' => $nozzle_joint->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '胶圈', 'parent_id' => $nozzle_joint->id, 'type' => Category::PRODUCT]);

        // 喷咀配件
        $nozzle_parts = Category::create(['name' => '喷咀', 'type' => Category::PRODUCT]);
        Category::create(['name' => '喷咀头', 'parent_id' => $nozzle_parts->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '喷咀芯', 'parent_id' => $nozzle_parts->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '底座', 'parent_id' => $nozzle_parts->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '叶片', 'parent_id' => $nozzle_parts->id, 'type' => Category::PRODUCT]);

        // 螺丝
        $screw = Category::create(['name' => '紧固件', 'type' => Category::PRODUCT]);
        Category::create(['name' => '螺丝', 'parent_id' => $screw->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '螺母', 'parent_id' => $screw->id, 'type' => Category::PRODUCT]);

        // 轴心
        $axis = Category::create(['name' => '轴心', 'type' => Category::PRODUCT]);
        Category::create(['name' => '玻纤棒', 'parent_id' => $axis->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '碳纤棒', 'parent_id' => $axis->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '304轴心', 'parent_id' => $axis->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '316轴心', 'parent_id' => $axis->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '钛轴心', 'parent_id' => $axis->id, 'type' => Category::PRODUCT]);

        // 胶圈
        $rubber_gasket = Category::create(['name' => '胶圈', 'type' => Category::PRODUCT]);

        // 其他配件
        $other = Category::create(['name' => '其他配件', 'type' => Category::PRODUCT]);
        Category::create(['name' => '拉手', 'parent_id' => $other->id, 'type' => Category::PRODUCT]);
        Category::create(['name' => '水泵过滤器配件', 'parent_id' => $other->id, 'type' => Category::PRODUCT]);
    }

    protected function seedGoodsCategory()
    {
        // 压力表
        $pressure_meter = Category::create(['name' => '压力表', 'type' => Category::GOODS]);
        Category::create(['name' => '不锈钢压力表', 'parent_id' => $pressure_meter->id, 'type' => Category::GOODS]);
        Category::create(['name' => 'PP压力表', 'parent_id' => $pressure_meter->id, 'type' => Category::GOODS]);

        // 液位开关
        $level_switch = Category::create(['name' => '液位开关', 'type' => Category::GOODS]);
        Category::create(['name' => '立式液位开关', 'parent_id' => $level_switch->id, 'type' => Category::GOODS]);
        Category::create(['name' => '卧式液位开关', 'parent_id' => $level_switch->id, 'type' => Category::GOODS]);

        // 流量计
        $flowmeter = Category::create(['name' => '流量计', 'type' => Category::GOODS]);
        Category::create(['name' => '国产流量计', 'parent_id' => $flowmeter->id, 'type' => Category::GOODS]);
        Category::create(['name' => '加强型流量计', 'parent_id' => $flowmeter->id, 'type' => Category::GOODS]);
        Category::create(['name' => 'Kingspring流量计', 'parent_id' => $flowmeter->id, 'type' => Category::GOODS]);

        // 滚轮片
        $disk = Category::create(['name' => '滚轮片', 'type' => Category::GOODS]);
        Category::create(['name' => 'PP滚轮片', 'parent_id' => $disk->id, 'type' => Category::GOODS]);
        Category::create(['name' => 'PVC滚轮片', 'parent_id' => $disk->id, 'type' => Category::GOODS]);
        Category::create(['name' => '包胶滚轮片', 'parent_id' => $disk->id, 'type' => Category::GOODS]);
        Category::create(['name' => '孟山都滚轮片', 'parent_id' => $disk->id, 'type' => Category::GOODS]);
        Category::create(['name' => '耐高温滚轮片', 'parent_id' => $disk->id, 'type' => Category::GOODS]);
        Category::create(['name' => 'LCD滚轮', 'parent_id' => $disk->id, 'type' => Category::GOODS]);

        // 固定套
        $fixed = Category::create(['name' => '固定套', 'type' => Category::GOODS]);

        // 间距杆
        $connect = Category::create(['name' => '间距杆', 'type' => Category::GOODS]);
        Category::create(['name' => '连接套', 'parent_id' => $connect->id, 'type' => Category::GOODS]);
        Category::create(['name' => '数字码', 'parent_id' => $connect->id, 'type' => Category::GOODS]);

        // 齿轮
        $gear = Category::create(['name' => '齿轮', 'type' => Category::GOODS]);
        Category::create(['name' => '直齿轮', 'parent_id' => $gear->id, 'type' => Category::GOODS]);
        Category::create(['name' => '伞齿轮', 'parent_id' => $gear->id, 'type' => Category::GOODS]);
        Category::create(['name' => '钉齿轮', 'parent_id' => $gear->id, 'type' => Category::GOODS]);
        Category::create(['name' => '螺旋齿轮', 'parent_id' => $gear->id, 'type' => Category::GOODS]);
        Category::create(['name' => '组合齿轮', 'parent_id' => $gear->id, 'type' => Category::GOODS]);

        // 喷咀配件
        $nozzle = Category::create(['name' => '喷咀', 'type' => Category::GOODS]);
        Category::create(['name' => '喷咀', 'parent_id' => $nozzle->id, 'type' => Category::GOODS]);
        Category::create(['name' => '喷咀芯', 'parent_id' => $nozzle->id, 'type' => Category::GOODS]);

        // 螺丝
        $screw = Category::create(['name' => '紧固件', 'type' => Category::GOODS]);
        Category::create(['name' => '螺丝', 'parent_id' => $screw->id, 'type' => Category::GOODS]);
        Category::create(['name' => '螺母', 'parent_id' => $screw->id, 'type' => Category::GOODS]);

        // 喷管接口
        $interface = Category::create(['name' => '喷管接口', 'type' => Category::GOODS]);

        // 轴心
        $axis = Category::create(['name' => '轴心', 'type' => Category::GOODS]);
        Category::create(['name' => '玻纤棒', 'parent_id' => $axis->id, 'type' => Category::GOODS]);
        Category::create(['name' => '碳纤棒', 'parent_id' => $axis->id, 'type' => Category::GOODS]);
        Category::create(['name' => '304轴心', 'parent_id' => $axis->id, 'type' => Category::GOODS]);
        Category::create(['name' => '316轴心', 'parent_id' => $axis->id, 'type' => Category::GOODS]);
        Category::create(['name' => '钛轴心', 'parent_id' => $axis->id, 'type' => Category::GOODS]);

        // 胶圈
        $rubber_gasket = Category::create(['name' => '胶圈', 'type' => Category::GOODS]);

        // 滚轮
        $roller = Category::create(['name' => '滚轮', 'type' => Category::GOODS]);
        Category::create(['name' => '行辘', 'parent_id' => $roller->id, 'type' => Category::GOODS]);
        Category::create(['name' => '压辘', 'parent_id' => $roller->id, 'type' => Category::GOODS]);

        // 其他配件
        $other = Category::create(['name' => '其他配件', 'type' => Category::GOODS]);
        Category::create(['name' => '拉手', 'parent_id' => $other->id, 'type' => Category::GOODS]);
        Category::create(['name' => '水泵过滤器配件', 'parent_id' => $other->id, 'type' => Category::GOODS]);
    }
}