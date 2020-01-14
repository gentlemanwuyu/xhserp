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
        $pressure_meter = Category::create(['name' => '压力表', 'type' => 1]);
        Category::create(['name' => '不锈钢压力表', 'parent_id' => $pressure_meter->id, 'type' => 1]);
        Category::create(['name' => 'PP压力表', 'parent_id' => $pressure_meter->id, 'type' => 1]);

        // 液位开关
        $level_switch = Category::create(['name' => '液位开关', 'type' => 1]);
        $vertical_switch = Category::create(['name' => '立式液位开关', 'parent_id' => $level_switch->id, 'type' => 1]);
        Category::create(['name' => 'PP立式液位开关', 'parent_id' => $vertical_switch->id, 'type' => 1]);
        Category::create(['name' => 'PVDF立式液位开关', 'parent_id' => $vertical_switch->id, 'type' => 1]);
        Category::create(['name' => '304立式液位开关', 'parent_id' => $vertical_switch->id, 'type' => 1]);
        $horizontal_switch = Category::create(['name' => '卧式液位开关', 'parent_id' => $level_switch->id, 'type' => 1]);
        Category::create(['name' => 'PP卧式液位开关', 'parent_id' => $horizontal_switch->id, 'type' => 1]);
        Category::create(['name' => 'PVDF卧式液位开关', 'parent_id' => $horizontal_switch->id, 'type' => 1]);
        Category::create(['name' => '304卧式液位开关', 'parent_id' => $horizontal_switch->id, 'type' => 1]);

        //流量计配件
        $flowmeter_parts = Category::create(['name' => '流量计配件', 'type' => 1]);
        $tube = Category::create(['name' => '视管', 'parent_id' => $flowmeter_parts->id, 'type' => 1]);
        Category::create(['name' => 'PC视管', 'parent_id' => $tube->id, 'type' => 1]);
        Category::create(['name' => 'PVC视管', 'parent_id' => $tube->id, 'type' => 1]);
        Category::create(['name' => 'PSU视管', 'parent_id' => $tube->id, 'type' => 1]);
        $rotor = Category::create(['name' => '转子', 'parent_id' => $flowmeter_parts->id, 'type' => 1]);
        Category::create(['name' => '304转子', 'parent_id' => $rotor->id, 'type' => 1]);
        Category::create(['name' => '316转子', 'parent_id' => $rotor->id, 'type' => 1]);
        Category::create(['name' => '铁氟龙转子', 'parent_id' => $rotor->id, 'type' => 1]);
        Category::create(['name' => '钛转子', 'parent_id' => $rotor->id, 'type' => 1]);
        $guide = Category::create(['name' => '导轨', 'parent_id' => $flowmeter_parts->id, 'type' => 1]);
        Category::create(['name' => '316导轨', 'parent_id' => $guide->id, 'type' => 1]);
        Category::create(['name' => '碳纤棒导轨', 'parent_id' => $guide->id, 'type' => 1]);
        Category::create(['name' => '螺母', 'parent_id' => $flowmeter_parts->id, 'type' => 1]);
        $joint = Category::create(['name' => '接头', 'parent_id' => $flowmeter_parts->id, 'type' => 1]);
        Category::create(['name' => 'PVC接头', 'parent_id' => $joint->id, 'type' => 1]);
        Category::create(['name' => 'PP接头', 'parent_id' => $joint->id, 'type' => 1]);
        Category::create(['name' => '指示扣', 'parent_id' => $flowmeter_parts->id, 'type' => 1]);
        $apron = Category::create(['name' => '流量计胶圈', 'parent_id' => $flowmeter_parts->id, 'type' => 1]);
        Category::create(['name' => 'EPDM胶圈', 'parent_id' => $apron->id, 'type' => 1]);
        Category::create(['name' => '铁氟龙胶圈', 'parent_id' => $apron->id, 'type' => 1]);

        // 滚轮片
        $disk = Category::create(['name' => '滚轮片', 'type' => 1]);
        Category::create(['name' => 'PP滚轮片', 'parent_id' => $disk->id, 'type' => 1]);
        $category = Category::create(['name' => '包胶滚轮片', 'parent_id' => $disk->id, 'type' => 1]);
        Category::create(['name' => '包胶滚轮片骨架', 'parent_id' => $category->id, 'type' => 1]);
        Category::create(['name' => '包胶滚轮片胶圈', 'parent_id' => $category->id, 'type' => 1]);
        Category::create(['name' => '孟山都滚轮片', 'parent_id' => $disk->id, 'type' => 1]);
        Category::create(['name' => '耐高温滚轮片', 'parent_id' => $disk->id, 'type' => 1]);
        Category::create(['name' => 'LCD滚轮', 'parent_id' => $disk->id, 'type' => 1]);

        // 连接套
        $connect = Category::create(['name' => '连接套', 'type' => 1]);
        Category::create(['name' => '卡环', 'parent_id' => $connect->id, 'type' => 1]);

        // 固定套
        $fixed = Category::create(['name' => '固定套', 'type' => 1]);

        // 插件
        $cup = Category::create(['name' => '插件', 'type' => 1]);
        Category::create(['name' => '卡扣', 'parent_id' => $cup->id, 'type' => 1]);

        // 齿轮
        $gear = Category::create(['name' => '齿轮', 'type' => 1]);
        Category::create(['name' => '直齿轮', 'parent_id' => $gear->id, 'type' => 1]);
        Category::create(['name' => '伞齿轮', 'parent_id' => $gear->id, 'type' => 1]);
        Category::create(['name' => '钉齿轮', 'parent_id' => $gear->id, 'type' => 1]);
        Category::create(['name' => '螺旋齿轮', 'parent_id' => $gear->id, 'type' => 1]);
        Category::create(['name' => '组合齿轮', 'parent_id' => $gear->id, 'type' => 1]);

        // 喷咀配件
        $nozzle_parts = Category::create(['name' => '喷咀', 'type' => 1]);
        Category::create(['name' => '喷咀头', 'parent_id' => $nozzle_parts->id, 'type' => 1]);
        Category::create(['name' => '喷嘴芯', 'parent_id' => $nozzle_parts->id, 'type' => 1]);
        Category::create(['name' => '底座', 'parent_id' => $nozzle_parts->id, 'type' => 1]);
        Category::create(['name' => '叶轮', 'parent_id' => $nozzle_parts->id, 'type' => 1]);

        // 螺丝
        $screw = Category::create(['name' => '紧固件', 'type' => 1]);
        Category::create(['name' => '螺丝', 'parent_id' => $screw->id, 'type' => 1]);
        Category::create(['name' => '螺母', 'parent_id' => $screw->id, 'type' => 1]);

        // 喷管接口
        $interface = Category::create(['name' => '喷管接口', 'type' => 1]);

        // 轴心
        $axis = Category::create(['name' => '轴心', 'type' => 1]);
        Category::create(['name' => '玻纤棒', 'parent_id' => $axis->id, 'type' => 1]);
        Category::create(['name' => '碳纤棒', 'parent_id' => $axis->id, 'type' => 1]);
        Category::create(['name' => '304轴心', 'parent_id' => $axis->id, 'type' => 1]);
        Category::create(['name' => '316轴心', 'parent_id' => $axis->id, 'type' => 1]);
        Category::create(['name' => '钛轴心', 'parent_id' => $axis->id, 'type' => 1]);

        // 胶圈
        $rubber_gasket = Category::create(['name' => '胶圈', 'type' => 1]);
        Category::create(['name' => 'EPDM胶圈', 'parent_id' => $rubber_gasket->id, 'type' => 1]);
        Category::create(['name' => '铁氟龙胶圈', 'parent_id' => $rubber_gasket->id, 'type' => 1]);

        // 其他配件
        $other = Category::create(['name' => '其他配件', 'type' => 1]);
        Category::create(['name' => '拉手', 'parent_id' => $other->id, 'type' => 1]);
        Category::create(['name' => '水泵过滤器配件', 'parent_id' => $other->id, 'type' => 1]);
    }

    protected function seedGoodsCategory()
    {
        // 压力表
        $pressure_meter = Category::create(['name' => '压力表', 'type' => 2]);
        Category::create(['name' => '不锈钢压力表', 'parent_id' => $pressure_meter->id, 'type' => 2]);
        Category::create(['name' => 'PP压力表', 'parent_id' => $pressure_meter->id, 'type' => 2]);

        // 液位开关
        $level_switch = Category::create(['name' => '液位开关', 'type' => 2]);
        $vertical_switch = Category::create(['name' => '立式液位开关', 'parent_id' => $level_switch->id, 'type' => 2]);
        Category::create(['name' => 'PP立式液位开关', 'parent_id' => $vertical_switch->id, 'type' => 2]);
        Category::create(['name' => 'PVDF立式液位开关', 'parent_id' => $vertical_switch->id, 'type' => 2]);
        Category::create(['name' => '304立式液位开关', 'parent_id' => $vertical_switch->id, 'type' => 2]);
        $horizontal_switch = Category::create(['name' => '卧式液位开关', 'parent_id' => $level_switch->id, 'type' => 2]);
        Category::create(['name' => 'PP卧式液位开关', 'parent_id' => $horizontal_switch->id, 'type' => 2]);
        Category::create(['name' => 'PVDF卧式液位开关', 'parent_id' => $horizontal_switch->id, 'type' => 2]);
        Category::create(['name' => '304卧式液位开关', 'parent_id' => $horizontal_switch->id, 'type' => 2]);

        // 流量计
        $flowmeter = Category::create(['name' => '流量计', 'type' => 2]);
        Category::create(['name' => '国产流量计', 'parent_id' => $flowmeter->id, 'type' => 2]);
        Category::create(['name' => '加强型流量计', 'parent_id' => $flowmeter->id, 'type' => 2]);
        Category::create(['name' => 'Kingspring流量计', 'parent_id' => $flowmeter->id, 'type' => 2]);

        // 滚轮片
        $disk = Category::create(['name' => '滚轮片', 'type' => 2]);
        Category::create(['name' => 'PP滚轮片', 'parent_id' => $disk->id, 'type' => 2]);
        Category::create(['name' => '包胶滚轮片', 'parent_id' => $disk->id, 'type' => 2]);
        Category::create(['name' => '孟山都滚轮片', 'parent_id' => $disk->id, 'type' => 2]);
        Category::create(['name' => '耐高温滚轮片', 'parent_id' => $disk->id, 'type' => 2]);
        Category::create(['name' => 'LCD滚轮', 'parent_id' => $disk->id, 'type' => 2]);

        // 连接套
        $connect = Category::create(['name' => '连接套', 'type' => 2]);
        Category::create(['name' => '卡环', 'parent_id' => $connect->id, 'type' => 2]);

        // 固定套
        $fixed = Category::create(['name' => '固定套', 'type' => 2]);

        // 插件
        $cup = Category::create(['name' => '插件', 'type' => 2]);
        Category::create(['name' => '卡扣', 'parent_id' => $cup->id, 'type' => 2]);

        // 齿轮
        $gear = Category::create(['name' => '齿轮', 'type' => 2]);
        Category::create(['name' => '直齿轮', 'parent_id' => $gear->id, 'type' => 2]);
        Category::create(['name' => '伞齿轮', 'parent_id' => $gear->id, 'type' => 2]);
        Category::create(['name' => '钉齿轮', 'parent_id' => $gear->id, 'type' => 2]);
        Category::create(['name' => '螺旋齿轮', 'parent_id' => $gear->id, 'type' => 2]);
        Category::create(['name' => '组合齿轮', 'parent_id' => $gear->id, 'type' => 2]);

        // 喷咀配件
        $nozzle = Category::create(['name' => '喷咀', 'type' => 2]);
        Category::create(['name' => '喷咀', 'parent_id' => $nozzle->id, 'type' => 2]);
        Category::create(['name' => '喷嘴芯', 'parent_id' => $nozzle->id, 'type' => 2]);

        // 螺丝
        $screw = Category::create(['name' => '紧固件', 'type' => 2]);
        Category::create(['name' => '螺丝', 'parent_id' => $screw->id, 'type' => 2]);
        Category::create(['name' => '螺母', 'parent_id' => $screw->id, 'type' => 2]);

        // 喷管接口
        $interface = Category::create(['name' => '喷管接口', 'type' => 2]);

        // 轴心
        $axis = Category::create(['name' => '轴心', 'type' => 2]);
        Category::create(['name' => '玻纤棒', 'parent_id' => $axis->id, 'type' => 2]);
        Category::create(['name' => '碳纤棒', 'parent_id' => $axis->id, 'type' => 2]);
        Category::create(['name' => '304轴心', 'parent_id' => $axis->id, 'type' => 2]);
        Category::create(['name' => '316轴心', 'parent_id' => $axis->id, 'type' => 2]);
        Category::create(['name' => '钛轴心', 'parent_id' => $axis->id, 'type' => 2]);

        // 胶圈
        $rubber_gasket = Category::create(['name' => '胶圈', 'type' => 2]);
        Category::create(['name' => 'EPDM胶圈', 'parent_id' => $rubber_gasket->id, 'type' => 2]);
        Category::create(['name' => '铁氟龙胶圈', 'parent_id' => $rubber_gasket->id, 'type' => 2]);

        // 滚轮
        $roller = Category::create(['name' => '滚轮', 'type' => 2]);
        Category::create(['name' => '行辘', 'parent_id' => $roller->id, 'type' => 2]);
        Category::create(['name' => '压辘', 'parent_id' => $roller->id, 'type' => 2]);

        // 其他配件
        $other = Category::create(['name' => '其他配件', 'type' => 2]);
        Category::create(['name' => '拉手', 'parent_id' => $other->id, 'type' => 2]);
        Category::create(['name' => '水泵过滤器配件', 'parent_id' => $other->id, 'type' => 2]);
    }
}