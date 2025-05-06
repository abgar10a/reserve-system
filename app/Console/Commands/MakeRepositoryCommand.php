<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRepositoryCommand extends Command
{
    protected $signature = 'make:repo {name}';
    protected $description = 'Create a Repository and Interface';

    public function handle(): void
    {
        $name = $this->argument('name');
        $interfaceName = 'I' . $name . 'Repository';
        $className = $name . 'Repository';

        $interfaceNamespace = 'App\\Repositories\\Interfaces';
        $classNamespace = 'App\\Repositories';

        $interfacePath = app_path("Repositories/Interfaces/{$interfaceName}.php");
        $classPath = app_path("Repositories/{$className}.php");

        File::ensureDirectoryExists(app_path('Repositories/Interfaces'));
        File::ensureDirectoryExists(app_path('Repositories'));

        $interfaceContent = <<<PHP
<?php

namespace $interfaceNamespace;

interface $interfaceName extends IRepository
{

}
PHP;

        $classContent = <<<PHP
<?php

namespace $classNamespace;

use $interfaceNamespace\\$interfaceName;

class $className implements $interfaceName
{

}
PHP;

        if (!File::exists($interfacePath)) {
            File::put($interfacePath, $interfaceContent);
            $this->info("Interface created: {$interfaceName}");
        } else {
            $this->warn("Interface already exists: {$interfaceName}");
        }

        if (!File::exists($classPath)) {
            File::put($classPath, $classContent);
            $this->info("Repository created: {$className}");
        } else {
            $this->warn("Repository already exists: {$className}");
        }
    }
}
