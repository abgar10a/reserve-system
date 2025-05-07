<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeServiceCommand extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Create a service class';

    public function handle(): void
    {
        $name = $this->argument('name');
        $serviceName = $name . 'Service';
        $repositoryName = 'I' . $name . 'Repository';
        $varName = '$' . strtolower($name);

        $serviceNamespace = 'App\\Services';

        $interfacePath = app_path("Services/{$serviceName}.php");

        File::ensureDirectoryExists(app_path('Services'));

        $interfaceContent = <<<PHP
<?php

namespace $serviceNamespace;

use App\Repositories\Interfaces\\$repositoryName;

class $serviceName
{
    public function __construct(private readonly $repositoryName $varName)
    {

    }


}
PHP;

        if (!File::exists($interfacePath)) {
            File::put($interfacePath, $interfaceContent);
            $this->info("Interface created: {$serviceName}");
        } else {
            $this->warn("Interface already exists: {$serviceName}");
        }
    }
}
