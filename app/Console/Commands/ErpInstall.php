<?php
/**
 * Created by PhpStorm.
 * User: Woozee
 * Date: 2019/11/23
 * Time: 20:43
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ErpInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'erp:install {--seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the ERP project';

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
        $this->execShellWithPrettyPrint("php artisan migrate");
        $this->execShellWithPrettyPrint("php artisan world:init");
        $this->execShellWithPrettyPrint("php artisan module:migrate");
        $this->execShellWithPrettyPrint("php artisan db:seed");
        $this->createAdminAccount();
        // 是否填充数据
        if ($this->option('seed')) {
            $this->execShellWithPrettyPrint("php artisan module:seed");
        }
        // 填充权限数据
        $this->execShellWithPrettyPrint("php artisan index:fill_permission");
    }

    /**
     * 执行脚本
     *
     * @param  string $command
     * @return mixed
     */
    public function execShellWithPrettyPrint($command)
    {
        $this->info('---');
        $this->info($command);
        $output = shell_exec($command);
        $this->info($output);
        $this->info('---');
    }

    /**
     * 创建管理员账号
     *
     * @return mixed
     */
    public function createAdminAccount()
    {
        return DB::table('users')->insert([
            'name' => 'admin',
            'email' => '492444775@qq.com',
            'password' => bcrypt('admin'),
            'is_admin' => 1,
        ]);
    }
}