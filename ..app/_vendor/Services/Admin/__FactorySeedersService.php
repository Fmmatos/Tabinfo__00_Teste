<?php

namespace Vendor\Services\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class __FactorySeedersService
{
    // GENERATE ALL FACTORIES AND SEEDERS
        public static function generateAll(): array
        {
            $factoryDir = DIR_F.'/laravel/database/factories/';
            $seederDir = DIR_F.'/laravel/database/seeders/';
            
            // CREATE DIRECTORIES IF NOT EXISTS
                if (!is_dir($factoryDir)) {
                    mkdir($factoryDir, 0777, true);
                }
                if (!is_dir($seederDir)) {
                    mkdir($seederDir, 0777, true);
                }
            // CREATE DIRECTORIES IF NOT EXISTS
            
            // GET ALL TABLES
                $tables = DB::select("SHOW TABLES");
                $database = DB::getDatabaseName();
                $tableKey = "Tables_in_{$database}";
            // GET ALL TABLES
            
            $createdFiles = [];
            $totalTables = 0;
            
            foreach ($tables as $tableObj) {
                $table = $tableObj->$tableKey;
                
                // SKIP SYSTEM TABLES
                    if (in_array($table, ['migrations', 'password_resets', 'failed_jobs', 'personal_access_tokens', 'cache', 'sessions'])) {
                        continue;
                    }
                // SKIP SYSTEM TABLES
                
                $totalTables++;
                
                // CREATE FACTORY
                    $factoryFile = self::createFactory($table, $factoryDir);
                    if ($factoryFile) {
                        $createdFiles['factories'][] = $factoryFile;
                    }
                // CREATE FACTORY
                
                // CREATE SEEDER
                    $seederFile = self::createSeeder($table, $seederDir);
                    if ($seederFile) {
                        $createdFiles['seeders'][] = $seederFile;
                    }
                // CREATE SEEDER
            }
            
            // CREATE DATABASE SEEDER
                $databaseSeederFile = self::createDatabaseSeeder($seederDir, $tables, $database, $tableKey);
                $createdFiles['database_seeder'] = $databaseSeederFile;
            // CREATE DATABASE SEEDER
            
            return [
                'files' => $createdFiles,
                'total_tables' => $totalTables
            ];
        }
    // GENERATE ALL FACTORIES AND SEEDERS
    
    
    // CREATE FACTORY FOR TABLE
        private static function createFactory(string $table, string $factoryDir): ?string
        {
            // GET TABLE COLUMNS
                $columns = DB::select("SHOW COLUMNS FROM `{$table}`");
            // GET TABLE COLUMNS
            
            // GET FOREIGN KEYS
                $foreignKeys = DB::select("
                    SELECT 
                        COLUMN_NAME,
                        REFERENCED_TABLE_NAME,
                        REFERENCED_COLUMN_NAME
                    FROM information_schema.KEY_COLUMN_USAGE
                    WHERE TABLE_SCHEMA = ?
                    AND TABLE_NAME = ?
                    AND REFERENCED_TABLE_NAME IS NOT NULL
                ", [DB::getDatabaseName(), $table]);
                
                $fkMapping = [];
                foreach ($foreignKeys as $fk) {
                    $fkMapping[$fk->COLUMN_NAME] = [
                        'table' => $fk->REFERENCED_TABLE_NAME,
                        'column' => $fk->REFERENCED_COLUMN_NAME
                    ];
                }
            // GET FOREIGN KEYS
            
            $modelName = str_replace(' ', '', ucwords(str_replace('_', ' ', Str::singular($table))));
            $factoryName = $modelName . 'Factory';
            $factoryFile = $factoryDir . $factoryName . '.php';
            
            // Get correct model namespace
            $modelNamespace = MODEL__ROOT__OR__ALL($table);
            
            // BUILD FACTORY CONTENT
                $content = "<?php\n\n";
                $content .= "namespace Database\\Factories;\n\n";
                $content .= "use Illuminate\\Database\\Eloquent\\Factories\\Factory;\n";
                $content .= "use Illuminate\\Support\\Facades\\DB;\n";
                $content .= "use Illuminate\\Support\\Facades\\Hash;\n\n";
                
                $content .= "/**\n";
                $content .= " * @extends \\Illuminate\\Database\\Eloquent\\Factories\\Factory<\\{$modelNamespace}>\n";
                $content .= " */\n";
                $content .= "class {$factoryName}\n";
                $content .= "{\n";
                $content .= "    protected \$model = \\{$modelNamespace}::class;\n";
                $content .= "    public \$faker;\n\n";
                
                $content .= "    public function __construct()\n";
                $content .= "    {\n";
                $content .= "        \$this->faker = \\Faker\\Factory::create('pt_BR');\n";
                $content .= "    }\n\n";
                
                $content .= "    public function definition()\n";
                $content .= "    {\n";
                $content .= "        return [\n";
                
                foreach ($columns as $column) {
                    $field = $column->Field;
                    $type = $column->Type;
                    $null = $column->Null;
                    $default = $column->Default;
                    $extra = $column->Extra;
                    
                    // Skip auto increment fields
                    if ($extra === 'auto_increment') {
                        continue;
                    }
                    
                    // Skip timestamp fields handled by Laravel
                    if (in_array($field, ['created_at', 'updated_at', 'deleted_at'])) {
                        continue;
                    }
                    
                    $content .= "            '{$field}' => ";
                    
                    // Handle foreign keys
                    if (isset($fkMapping[$field])) {
                        $refTable = $fkMapping[$field]['table'];
                        $refColumn = $fkMapping[$field]['column'];
                        
                        if ($null === 'YES') {
                            $content .= "\$this->faker->optional(0.8)->randomElement(DB::table('{$refTable}')->pluck('{$refColumn}')->toArray())";
                        } else {
                            $content .= "DB::table('{$refTable}')->inRandomOrder()->value('{$refColumn}') ?: 1";
                        }
                    }
                    // Handle different field types
                    else {
                        $content .= self::getFakerForColumn($field, $type, $null, $default);
                    }
                    
                    $content .= ",\n";
                }
                
                $content .= "        ];\n";
                $content .= "    }\n";
                $content .= "}\n";
            // BUILD FACTORY CONTENT
            
            file_put_contents($factoryFile, $content);
            return $factoryFile;
        }
    // CREATE FACTORY FOR TABLE
    
    
    // CREATE SEEDER FOR TABLE
        private static function createSeeder(string $table, string $seederDir): ?string
        {
            $modelName = str_replace(' ', '', ucwords(str_replace('_', ' ', Str::singular($table))));
            $seederName = Str::plural($modelName) . 'TableSeeder';
            $seederFile = $seederDir . $seederName . '.php';
            
            // BUILD SEEDER CONTENT
                $content = "<?php\n\n";
                $content .= "namespace Database\\Seeders;\n\n";
                $content .= "use Illuminate\\Database\\Seeder;\n";
                $content .= "use Illuminate\\Support\\Facades\\DB;\n";
                $content .= "use Database\\Factories\\{$modelName}Factory;\n\n";
                
                $content .= "class {$seederName} extends Seeder\n";
                $content .= "{\n";
                $content .= "    public function run()\n";
                $content .= "    {\n";
                $content .= "        // Clear existing data\n";
                $content .= "        DB::statement('SET FOREIGN_KEY_CHECKS=0;');\n";
                $content .= "        DB::table('{$table}')->truncate();\n";
                $content .= "        DB::statement('SET FOREIGN_KEY_CHECKS=1;');\n\n";
                
                $content .= "        // Create records using factory\n";
                $content .= "        \$factory = new {$modelName}Factory();\n";
                $content .= "        \$records = [];\n\n";
                
                $content .= "        for (\$i = 0; \$i < 50; \$i++) {\n";
                $content .= "            \$records[] = \$factory->definition();\n";
                $content .= "        }\n\n";
                
                $content .= "        // Insert in chunks for better performance\n";
                $content .= "        foreach (array_chunk(\$records, 100) as \$chunk) {\n";
                $content .= "            DB::table('{$table}')->insert(\$chunk);\n";
                $content .= "        }\n";
                $content .= "    }\n";
                $content .= "}\n";
            // BUILD SEEDER CONTENT
            
            file_put_contents($seederFile, $content);
            return $seederFile;
        }
    // CREATE SEEDER FOR TABLE
    
    
    // CREATE DATABASE SEEDER
        private static function createDatabaseSeeder(string $seederDir, $tables, string $database, string $tableKey): string
        {
            $seederFile = $seederDir . 'DatabaseSeeder.php';
            
            // BUILD DATABASE SEEDER CONTENT
                $content = "<?php\n\n";
                $content .= "namespace Database\\Seeders;\n\n";
                $content .= "use Illuminate\\Database\\Seeder;\n\n";
                
                $content .= "class DatabaseSeeder extends Seeder\n";
                $content .= "{\n";
                $content .= "    public function run()\n";
                $content .= "    {\n";
                $content .= "        // Seeders are ordered by foreign key dependencies\n";
                $content .= "        \$this->call([\n";
                
                // Order tables by dependencies
                $orderedTables = self::orderTablesByDependencies($tables, $database, $tableKey);
                
                foreach ($orderedTables as $table) {
                    // Skip system tables
                    if (in_array($table, ['migrations', 'password_resets', 'failed_jobs', 'personal_access_tokens', 'cache', 'sessions'])) {
                        continue;
                    }
                    
                    $modelName = str_replace(' ', '', ucwords(str_replace('_', ' ', Str::singular($table))));
                    $seederName = Str::plural($modelName) . 'TableSeeder';
                    
                    $content .= "            {$seederName}::class,\n";
                }
                
                $content .= "        ]);\n";
                $content .= "    }\n";
                $content .= "}\n";
            // BUILD DATABASE SEEDER CONTENT
            
            file_put_contents($seederFile, $content);
            return $seederFile;
        }
    // CREATE DATABASE SEEDER
    
    
    // GET FAKER FOR COLUMN TYPE
        private static function getFakerForColumn(string $field, string $type, string $null, $default): string
        {
            $isNullable = ($null === 'YES');
            $faker = $isNullable ? "\$this->faker->optional(0.8)->" : "\$this->faker->";
            
            // Special field names
            switch ($field) {
                case 'name':
                case 'nome':
                    return $faker . "name()";
                    
                case 'email':
                    return $faker . "unique()->safeEmail()";
                    
                case 'phone':
                case 'telefone':
                case 'celular':
                    return $faker . "numerify('(##) #####-####')";
                    
                case 'cpf':
                    return $faker . "numerify('###.###.###-##')";
                    
                case 'cnpj':
                    return $faker . "numerify('##.###.###/####-##')";
                    
                case 'cep':
                case 'zipcode':
                    return $faker . "numerify('#####-###')";
                    
                case 'address':
                case 'endereco':
                case 'rua':
                    return $faker . "streetName()";
                    
                case 'number':
                case 'numero':
                    return $faker . "buildingNumber()";
                    
                case 'complement':
                case 'complemento':
                    return $isNullable ? "\$this->faker->optional(0.3)->randomElement([null, \$this->faker->secondaryAddress()])" : "\$this->faker->secondaryAddress()";
                    
                case 'neighborhood':
                case 'bairro':
                    return $faker . "citySuffix()";
                    
                case 'city':
                case 'cidade':
                    return $faker . "city()";
                    
                case 'state':
                case 'estado':
                case 'uf':
                    return $faker . "stateAbbr()";
                    
                case 'country':
                case 'pais':
                    return $faker . "country()";
                    
                case 'password':
                case 'senha':
                    return "Hash::make('password')";
                    
                case 'birth':
                case 'birth_date':
                case 'data_nascimento':
                    if ($isNullable) {
                        return "\$this->faker->optional(0.8)->dateTimeBetween('-60 years', '-18 years') ? \$this->faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d') : null";
                    }
                    return "\$this->faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d')";
                    
                case 'active':
                case 'ativo':
                case 'status':
                    return $faker . "boolean(80)";
                    
                case 'url':
                case 'website':
                case 'site':
                    return $faker . "regexify('https://[a-z]{5,10}\.com')";
                    
                case 'description':
                case 'descricao':
                case 'bio':
                    return $faker . "paragraph(3)";
                    
                case 'price':
                case 'preco':
                case 'valor':
                    return $faker . "randomFloat(2, 10, 1000)";
                    
                case 'order':
                case 'ordem':
                case 'position':
                    return $faker . "numberBetween(1, 999)";
            }
            
            // By column type
            if (strpos($type, 'varchar') !== false) {
                preg_match('/varchar\((\d+)\)/', $type, $matches);
                $length = isset($matches[1]) ? intval($matches[1]) : 255;
                
                // Special handling for URL fields
                if (in_array($field, ['url', 'website', 'site', 'link'])) {
                    $maxDomainLength = max(5, $length - 15); // Reserve space for https:// and .com
                    return $faker . "regexify('https://[a-z]{5," . min($maxDomainLength, 15) . "}\.com')";
                }
                
                if ($length <= 50) {
                    return $faker . "lexify('" . str_repeat('?', min($length, 20)) . "')";
                } else {
                    return $faker . "sentence(5)";
                }
            }
            else if (strpos($type, 'text') !== false || strpos($type, 'longtext') !== false) {
                if (strpos($field, 'json') !== false || strpos($field, 'data') !== false) {
                    return $faker . "optional(0.5)->passthrough(json_encode(['key' => \$this->faker->word()]))";
                }
                return $faker . "paragraph(5)";
            }
            else if (strpos($type, 'int') !== false) {
                if (strpos($type, 'tinyint(1)') !== false) {
                    return $faker . "boolean()";
                }
                return $faker . "numberBetween(1, 100)";
            }
            else if (strpos($type, 'decimal') !== false || strpos($type, 'float') !== false || strpos($type, 'double') !== false) {
                return $faker . "randomFloat(2, 0, 1000)";
            }
            else if (strpos($type, 'date') !== false && $type === 'date') {
                if ($isNullable) {
                    return "\$this->faker->optional(0.8)->dateTimeBetween('-2 years', 'now') ? \$this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d') : null";
                }
                return "\$this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d')";
            }
            else if (strpos($type, 'datetime') !== false || strpos($type, 'timestamp') !== false) {
                if ($isNullable) {
                    return "\$this->faker->optional(0.8)->dateTimeBetween('-2 years', 'now') ? \$this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s') : null";
                }
                return "\$this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d H:i:s')";
            }
            else if (strpos($type, 'time') !== false) {
                return $faker . "time('H:i:s')";
            }
            else if (strpos($type, 'year') !== false) {
                return $faker . "year()";
            }
            else if (strpos($type, 'enum') !== false) {
                preg_match('/enum\((.*)\)/', $type, $matches);
                if (isset($matches[1])) {
                    $values = explode(',', $matches[1]);
                    $values = array_map(function($v) { return trim($v, "'\""); }, $values);
                    return $faker . "randomElement(['" . implode("', '", $values) . "'])";
                }
            }
            
            // Default
            if ($default !== null && $default !== 'NULL') {
                return is_numeric($default) ? $default : "'{$default}'";
            }
            
            return $isNullable ? "null" : "''";
        }
    // GET FAKER FOR COLUMN TYPE
    
    
    // ORDER TABLES BY DEPENDENCIES
        private static function orderTablesByDependencies($tables, string $database, string $tableKey): array
        {
            $tableNames = [];
            $dependencies = [];
            
            // Get all table names
            foreach ($tables as $tableObj) {
                $tableName = $tableObj->$tableKey;
                $tableNames[] = $tableName;
                $dependencies[$tableName] = [];
            }
            
            // Get foreign key dependencies for each table
            foreach ($tableNames as $table) {
                $foreignKeys = DB::select("
                    SELECT REFERENCED_TABLE_NAME
                    FROM information_schema.KEY_COLUMN_USAGE
                    WHERE TABLE_SCHEMA = ?
                    AND TABLE_NAME = ?
                    AND REFERENCED_TABLE_NAME IS NOT NULL
                    AND REFERENCED_TABLE_NAME != ?
                ", [$database, $table, $table]);
                
                foreach ($foreignKeys as $fk) {
                    if (!in_array($fk->REFERENCED_TABLE_NAME, $dependencies[$table])) {
                        $dependencies[$table][] = $fk->REFERENCED_TABLE_NAME;
                    }
                }
            }
            
            // Topological sort
            $sorted = [];
            $visited = [];
            
            $visit = function($table) use (&$visit, &$sorted, &$visited, $dependencies) {
                if (isset($visited[$table])) {
                    return;
                }
                
                $visited[$table] = true;
                
                if (isset($dependencies[$table])) {
                    foreach ($dependencies[$table] as $dep) {
                        $visit($dep);
                    }
                }
                
                $sorted[] = $table;
            };
            
            foreach ($tableNames as $table) {
                $visit($table);
            }
            
            return $sorted;
        }
    // ORDER TABLES BY DEPENDENCIES
    
    
    // SEED DATABASE - EXECUTE ALL SEEDERS
        public static function seedDatabase(): array
        {
            try {
                $seederFile = DIR_F.'/laravel/database/seeders/DatabaseSeeder.php';
                
                // Check if DatabaseSeeder exists
                if (!file_exists($seederFile)) {
                    return [
                        'success' => false,
                        'message' => 'DatabaseSeeder not found. Please run /factories first.'
                    ];
                }
                
                // Include the seeder file
                require_once $seederFile;
                
                // Run the seeder
                $seeder = new \Database\Seeders\DatabaseSeeder();
                $seeder->run();
                
                return [
                    'success' => true,
                    'message' => 'Database seeded successfully'
                ];
            } catch (\Exception $e) {
                return [
                    'success' => false,
                    'message' => 'Error seeding database',
                    'error' => $e->getMessage()
                ];
            }
        }
    // SEED DATABASE
    
    
    // SEED SPECIFIC TABLE
        public static function seedTable(string $table): array
        {
            try {
                $modelName = str_replace(' ', '', ucwords(str_replace('_', ' ', Str::singular($table))));
                $seederName = Str::plural($modelName) . 'TableSeeder';
                $seederFile = DIR_F.'/laravel/database/seeders/' . $seederName . '.php';
                
                // Check if seeder exists
                if (!file_exists($seederFile)) {
                    return [
                        'success' => false,
                        'message' => "Seeder for table {$table} not found. Please run /factories first."
                    ];
                }
                
                // Include the seeder file
                require_once $seederFile;
                
                // Run the seeder
                $seederClass = '\\Database\\Seeders\\' . $seederName;
                $seeder = new $seederClass();
                $seeder->run();
                
                return [
                    'success' => true,
                    'message' => "Table {$table} seeded successfully"
                ];
            } catch (\Exception $e) {
                return [
                    'success' => false,
                    'message' => "Error seeding table {$table}",
                    'error' => $e->getMessage()
                ];
            }
        }
    // SEED SPECIFIC TABLE
}
