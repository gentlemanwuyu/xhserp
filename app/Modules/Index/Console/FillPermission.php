<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2020/4/12
 * Time: 13:21
 */

namespace App\Modules\Index\Console;

use Illuminate\Console\Command;
use App\Modules\Index\Models\Permission;

class FillPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:fill_permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '填充系统权限';

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
        try {
            $permissions = config('laravel-permission.erp_permissions', []);
            foreach ($permissions as $l1) {
                if (empty($l1['name'])) {
                    $this->info("Level1权限名不能为空");
                    continue;
                }
                $data = [
                    'name' => array_get($l1, 'name'),
                    'display_name' => array_get($l1, 'display_name', ''),
                    'route' => array_get($l1, 'route', ''),
                    'type' => array_get($l1, 'type', 0),
                    'parent_id' => 0,
                ];
                if (!empty($l1['id'])) {
                    $p1 = Permission::find($l1['id']);
                    $p1->update($data);
                }else {
                    $p1 = Permission::updateOrCreate(['name' => $l1['name']], $data);
                }
                if (!empty($l1['children'])) {
                    foreach ($l1['children'] as $l2) {
                        if (empty($l2['name'])) {
                            $this->info("Level2权限名不能为空");
                            continue;
                        }
                        $data = [
                            'name' => array_get($l2, 'name'),
                            'display_name' => array_get($l2, 'display_name', ''),
                            'route' => array_get($l2, 'route', ''),
                            'type' => array_get($l2, 'type', 0),
                            'parent_id' => $p1->id,
                        ];
                        if (!empty($l2['id'])) {
                            $p2 = Permission::find($l2['id']);
                            $p2->update($data);
                        }else {
                            $p2 = Permission::updateOrCreate(['name' => $l2['name']], $data);
                        }
                        if (!empty($l2['children'])) {
                            foreach ($l2['children'] as $l3) {
                                if (empty($l3['name'])) {
                                    $this->info("Level3权限名不能为空");
                                    continue;
                                }
                                $data = [
                                    'name' => array_get($l3, 'name'),
                                    'display_name' => array_get($l3, 'display_name', ''),
                                    'route' => array_get($l3, 'route', ''),
                                    'type' => array_get($l3, 'type', 0),
                                    'parent_id' => $p2->id,
                                ];
                                if (!empty($l3['id'])) {
                                    $p3 = Permission::find($l3['id']);
                                    $p3->update($data);
                                }else {
                                    Permission::updateOrCreate(['name' => $l3['name']], $data);
                                }
                            }
                        }
                    }
                }
            }


        }catch (\Exception $e) {
            $this->info("[" . get_class($e) . "]" . $e->getMessage());
        }
    }
}