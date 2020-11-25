<?php

namespace Moell\Mojito\Console;

use Illuminate\Console\Command;

class UninstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:uninstall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the commands to uninstall（only this package）';

    /**
     * Execute the console command.
     *
     * @author moell<moel91@foxmail.com>
     * @return mixed
     */
    public function handle()
    {
        $vendorPath = 'vendor/moell/';
        $migrationsPath = $vendorPath . 'mojito/database/migrations';
        $res = $this->call('migrate:rollback', ['--path' => $migrationsPath]);
        // $this->call('vendor:publish', ['--provider' => 'Moell\Mojito\Providers\MojitoServiceProvider']);
    }
}
