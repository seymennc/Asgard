<?php

namespace Asgard\app\Console\Command;


class Generate
{
    public function handle(): void
    {
        echo "Please provide a valid sub-command (e.g., migration:create).\n";
    }

    /**
     * @param array $args
     * @return void
     */
    public function migration(array $args): void
    {
        $name = $args[0] ?? null;
        $extension = $args[1] ?? null;

        if (!$name) {
            echo "Lütfen migration ismi belirtin.\n";
            exit(1);
        }

        $table_name = sprintf('%ss', strtolower($name));
        $fileName = date('Y_m_d_His') . '_' . $table_name . '_table' . '.php';
        $filePath = dirname(__DIR__, 3) . '/database/migrations/' . $fileName;

        $stub = "<?php\n\nnamespace Asgard\\database\\migrations;\n\nuse Asgard\\system\\Blueprint;\nuse Asgard\\system\\Migration;\n\nreturn new class extends Migration\n{\n    public function up(): void\n    {\n        Blueprint::createTable('" . $table_name . "', function (Blueprint \$table) {\n            \$table->addColumn('created_at', 'TIMESTAMP', ['DEFAULT CURRENT_TIMESTAMP']);\n            \$table->addColumn('updated_at', 'TIMESTAMP', ['DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']);\n        });\n    }\n\n    public function down(): void\n    {\n        Blueprint::dropTable('" . $table_name ."');\n    }\n};\n";

        file_put_contents($filePath, $stub);


        if(!empty($extension)){
            switch ($extension){
                case '-m':
                    $this->model($args);
                    break;
                case '-s':
                    $this->seeder($args);
                    break;
                case '-sm':
                case '-ms':
                    $this->model($args);
                    $this->seeder($args);
                    break;
                default:
                    echo "Invalid arguments.\n";
                    break;
            }
        }
        echo "Generate file created: $fileName\n";
    }

    /**
     * @param array $args
     * @return void
     */
    public function model(array $args): void
    {
        $name = $args[0] ?? null;

        if (!$name) {
            echo "Lütfen model ismi belirtin.\n";
            exit(1);
        }

        $table_name = sprintf('%ss', strtolower($name));
        $fileName = sprintf('%s.php', ucfirst($table_name));

        $filePath = dirname(__DIR__, 3) . '/database/models/' . $fileName;

        $stub = "<?php\n\nnamespace Asgard\\database\\models;\n\nuse Asgard\\system\\Model;\n\nclass " . ucfirst($table_name) . " extends Model\n{\n    protected \$tableName = '" . $table_name . "';\n\n    protected \$fillable = [];\n}\n";

        file_put_contents($filePath, $stub);

        echo "Generate file created: $fileName\n";

    }

    /**
     * @param array $args
     * @return void
     */
    public function seeder(array $args): void
    {
        $name = $args[0] ?? null;

        if (!$name) {
            echo "Lütfen seeder ismi belirtin.\n";
            exit(1);
        }

        $table_name = sprintf('%s', ucfirst($name) . 'sSeeder');
        $fileName = sprintf('%s.php', ucfirst($name) . 'sSeeder');
        $filePath = dirname( __DIR__, 3) . '/database/seeders/' . $fileName;

        $stub = "<?php\n\nnamespace Asgard\\database\\seeders;\n\nclass " . $table_name ."\n{\n    public function run(): void\n    {\n        //TODO: Implement run() method.\n    }\n}\n";

        file_put_contents($filePath, $stub);

        echo "Generate file created: $fileName\n";
    }

    /**
     * @param array $args
     * @return void
     */
    public function controller(array $args): void
    {
        $name = $args[0] ?? null;

        if (!$name) {
            echo "Lütfen controller ismi belirtin.\n";
            exit(1);
        }
        $className = sprintf('%s', ucfirst($name));
        $fileName = sprintf('%s.php', ucfirst($name));
        $filePath = dirname(__DIR__, 3) . '/Controllers/' . $fileName;

        $stub = "<?php\n\nnamespace Asgard\\app\\Controllers;\n\nuse Asgard\\database\\models\\User;\n\nclass " . $className . " extends Controllers\n{\n    public function index()\n    {\n        //TODO: Implement index() method.\n    }\n}\n";

        file_put_contents($filePath, $stub);

        echo "Generate file created: $fileName\n";
    }

    /**
     * @param array $args
     * @return void
     */
    public function request(array $args): void
    {
        $name = $args[0] ?? null;

        if (!$name) {
            echo "Lütfen request ismi belirtin.\n";
            exit(1);
        }
        $className = sprintf('%s', ucfirst($name));
        $filePath = dirname(__DIR__, 3) . '/Requests/' . $className;
    }
}
