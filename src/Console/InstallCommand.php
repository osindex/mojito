<?php

namespace Moell\Mojito\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the commands to prepare Mojito for use';

    /**
     * Execute the console command.
     *
     * @author moell<moel91@foxmail.com>
     * @return mixed
     */
    public function handle()
    {
        $this->call('vendor:publish', ['--provider' => 'Spatie\Permission\PermissionServiceProvider']);
        $this->call('vendor:publish', ['--provider' => 'Moell\Mojito\Providers\MojitoServiceProvider']);

        $vendorPath = 'vendor/moell/';
        $migrationsPath = $vendorPath . 'mojito/database/migrations';
        $res = $this->call('migrate', ['--path' => $migrationsPath]);
        // 修改配置文件
        $this->changePermissionConfig();
        $this->call('vendor:publish', ['--provider' => 'Laravel\Sanctum\SanctumServiceProvider']);
    }
    // 重写配置文件
    public function changePermissionConfig()
    {
        $config = config('permission');
        $config['models']['role'] = 'Moell\Mojito\Models\Role';
        inputFile(base_path('config/permission.php'), "<?php return " . var_export($config, true) . ';');
    }
}
