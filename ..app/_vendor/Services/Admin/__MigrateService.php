<?php

namespace Vendor\Services\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class __MigrateService
{

    // GENERATE MIGRATION FOR SINGLE TABLE
        public static function generateMigrationForTable(string $table): array
        {
            $migration_dir = DIR_F.'/laravel/database/migrations/';
            $createdFiles = [];
            
            // CREATE MIGRATION FILE 1 - TABLE STRUCTURE
                $file1 = self::createTableMigration($table, $migration_dir);
                $createdFiles[] = $file1;
            // CREATE MIGRATION FILE 1 - TABLE STRUCTURE
            
            // CREATE MIGRATION FILE 2 - INDEXES AND FOREIGN KEYS
                $file2 = self::createIndexesAndForeignKeysMigration($table, $migration_dir);
                if ($file2) {
                    $createdFiles[] = $file2;
                }
            // CREATE MIGRATION FILE 2 - INDEXES AND FOREIGN KEYS
            
            // CREATE MIGRATION FILE 3 - DATA SEEDER
                // Não criar arquivo 3 aqui - somente na rota /migrate
            // CREATE MIGRATION FILE 3 - DATA SEEDER
            
            return $createdFiles;
        }
    // GENERATE MIGRATION FOR SINGLE TABLE
    
    
    // GENERATE MIGRATIONS FOR ALL TABLES
        public static function generateAllMigrations(): array
        {
            $migration_dir = DIR_F.'/laravel/database/migrations/';
            
            // CREATE MIGRATIONS DIRECTORY IF NOT EXISTS
                if (!is_dir($migration_dir)) {
                    mkdir($migration_dir, 0777, true);
                }
            // CREATE MIGRATIONS DIRECTORY IF NOT EXISTS
            
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
                    if (in_array($table, ['migrations', 'password_resets', 'failed_jobs'])) {
                        continue;
                    }
                // SKIP SYSTEM TABLES
                
                $totalTables++;
                $files = self::generateMigrationForTable($table);
                $createdFiles = array_merge($createdFiles, $files);
            }
            
            return [
                'files' => $createdFiles,
                'total_tables' => $totalTables
            ];
        }
    // GENERATE MIGRATIONS FOR ALL TABLES
    
    
    // REGISTER ALL MIGRATIONS IN DATABASE - SYNCS ALL PHYSICAL FILES
        public static function registerAllMigrationInDatabase(): bool
        {
            try {
                // CHECK IF MIGRATIONS TABLE EXISTS
                    $tableExists = DB::select("SHOW TABLES LIKE 'migrations'");
                    if (empty($tableExists)) {
                        // Create migrations table if it doesn't exist - USING BIGINT UNSIGNED LIKE LARAVEL
                        DB::statement("
                            CREATE TABLE IF NOT EXISTS `migrations` (
                                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                                `migration` VARCHAR(255) NOT NULL,
                                `batch` INT NOT NULL,
                                PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                        ");
                    }
                // CHECK IF MIGRATIONS TABLE EXISTS
                
                $migration_dir = DIR_F.'/laravel/database/migrations/';
                
                // GET ALL PHP FILES FROM MIGRATIONS DIRECTORY
                    $physicalFiles = [];
                    if (is_dir($migration_dir)) {
                        $files = scandir($migration_dir);
                        foreach ($files as $file) {
                            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                                // Remove .php extension to get migration name
                                $physicalFiles[] = pathinfo($file, PATHINFO_FILENAME);
                            }
                        }
                    }
                // GET ALL PHP FILES FROM MIGRATIONS DIRECTORY
                
                // GET ALL MIGRATIONS FROM DATABASE
                    $databaseMigrations = DB::table('migrations')->pluck('migration')->toArray();
                // GET ALL MIGRATIONS FROM DATABASE
                
                // DELETE MIGRATIONS THAT DON'T HAVE PHYSICAL FILES
                    $toDelete = array_diff($databaseMigrations, $physicalFiles);
                    if (!empty($toDelete)) {
                        DB::table('migrations')->whereIn('migration', $toDelete)->delete();
                        Log::info('Deleted migrations from database: ' . implode(', ', $toDelete));
                    }
                // DELETE MIGRATIONS THAT DON'T HAVE PHYSICAL FILES
                
                // GET NEXT BATCH NUMBER
                    $maxBatch = DB::table('migrations')->max('batch');
                    $nextBatch = $maxBatch ? $maxBatch + 1 : 1;
                // GET NEXT BATCH NUMBER
                
                // INSERT MIGRATIONS THAT EXIST AS FILES BUT NOT IN DATABASE
                    $toInsert = array_diff($physicalFiles, $databaseMigrations);
                    if (!empty($toInsert)) {
                        $insertData = [];
                        foreach ($toInsert as $migration) {
                            $insertData[] = [
                                'migration' => $migration,
                                'batch' => $nextBatch
                            ];
                        }
                        DB::table('migrations')->insert($insertData);
                        Log::info('Added ' . count($toInsert) . ' migrations to database');
                    }
                // INSERT MIGRATIONS THAT EXIST AS FILES BUT NOT IN DATABASE
                
                Log::info('Migration sync completed. Total migrations: ' . count($physicalFiles));
                
                return true;
                
            } catch (\Exception $e) {
                // Log error but don't stop the process
                Log::error('Failed to sync all migrations with database: ' . $e->getMessage());
                return false;
            }
        }
    // REGISTER ALL MIGRATIONS IN DATABASE - SYNCS ALL PHYSICAL FILES
    
    
    // REGISTER MIGRATION IN DATABASE (BY TABLE NAME) - SYNCS WITH PHYSICAL FILES
        public static function registerMigrationInDatabase(string $table): bool
        {
            try {
                // CHECK IF MIGRATIONS TABLE EXISTS
                    $tableExists = DB::select("SHOW TABLES LIKE 'migrations'");
                    if (empty($tableExists)) {
                        // Create migrations table if it doesn't exist - USING BIGINT UNSIGNED LIKE LARAVEL
                        DB::statement("
                            CREATE TABLE IF NOT EXISTS `migrations` (
                                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                                `migration` VARCHAR(255) NOT NULL,
                                `batch` INT NOT NULL,
                                PRIMARY KEY (`id`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
                        ");
                    }
                // CHECK IF MIGRATIONS TABLE EXISTS
                
                $migration_dir = DIR_F.'/laravel/database/migrations/';
                
                // GET ALL PHP FILES FROM MIGRATIONS DIRECTORY
                    $physicalFiles = [];
                    if (is_dir($migration_dir)) {
                        $files = scandir($migration_dir);
                        foreach ($files as $file) {
                            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                                // Remove .php extension to get migration name
                                $physicalFiles[] = pathinfo($file, PATHINFO_FILENAME);
                            }
                        }
                    }
                // GET ALL PHP FILES FROM MIGRATIONS DIRECTORY
                
                // GET ALL MIGRATIONS FROM DATABASE
                    $databaseMigrations = DB::table('migrations')->pluck('migration')->toArray();
                // GET ALL MIGRATIONS FROM DATABASE
                
                // DELETE MIGRATIONS THAT DON'T HAVE PHYSICAL FILES
                    $toDelete = array_diff($databaseMigrations, $physicalFiles);
                    if (!empty($toDelete)) {
                        DB::table('migrations')->whereIn('migration', $toDelete)->delete();
                        Log::info('Deleted migrations from database: ' . implode(', ', $toDelete));
                    }
                // DELETE MIGRATIONS THAT DON'T HAVE PHYSICAL FILES
                
                // GET NEXT BATCH NUMBER
                    $maxBatch = DB::table('migrations')->max('batch');
                    $nextBatch = $maxBatch ? $maxBatch + 1 : 1;
                // GET NEXT BATCH NUMBER
                
                // INSERT MIGRATIONS THAT EXIST AS FILES BUT NOT IN DATABASE
                    $toInsert = array_diff($physicalFiles, $databaseMigrations);
                    foreach ($toInsert as $migration) {
                        DB::table('migrations')->insert([
                            'migration' => $migration,
                            'batch' => $nextBatch
                        ]);
                        Log::info('Added migration to database: ' . $migration);
                    }
                // INSERT MIGRATIONS THAT EXIST AS FILES BUT NOT IN DATABASE
                
                // SPECIFIC CHECK FOR THE TABLE PASSED AS PARAMETER
                    $migration1 = '1_' . $table;  // First file: 1_users.php
                    $migration2 = '2_' . $table;  // Second file: 2_users.php
                    
                    // Ensure the migrations for this specific table are registered
                    if (file_exists($migration_dir . $migration1 . '.php')) {
                        if (!DB::table('migrations')->where('migration', $migration1)->exists()) {
                            DB::table('migrations')->insert([
                                'migration' => $migration1,
                                'batch' => $nextBatch
                            ]);
                        }
                    }
                    
                    if (file_exists($migration_dir . $migration2 . '.php')) {
                        if (!DB::table('migrations')->where('migration', $migration2)->exists()) {
                            DB::table('migrations')->insert([
                                'migration' => $migration2,
                                'batch' => $nextBatch
                            ]);
                        }
                    }
                // SPECIFIC CHECK FOR THE TABLE PASSED AS PARAMETER
                
                return true;
                
            } catch (\Exception $e) {
                // Log error but don't stop the process
                Log::error('Failed to sync migrations with database: ' . $e->getMessage());
                return false;
            }
        }
    // REGISTER MIGRATION IN DATABASE (BY TABLE NAME) - SYNCS WITH PHYSICAL FILES
    
    
    // BACKUP ALL TABLES DATA
        public static function backupItems(): array
        {
            $backup_dir = DIR_F.'/laravel/database/migrations/backup/';
            
            // CREATE BACKUP DIRECTORY IF NOT EXISTS
                if (!is_dir($backup_dir)) {
                    mkdir($backup_dir, 0777, true);
                }
            // CREATE BACKUP DIRECTORY IF NOT EXISTS
            
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
                    if (in_array($table, ['migrations', 'password_resets', 'failed_jobs'])) {
                        continue;
                    }
                // SKIP SYSTEM TABLES
                
                $totalTables++;
                
                // CREATE DATA BACKUP FILE (FILE 3)
                    $file3 = self::createDataBackupMigration($table, $backup_dir);
                    if ($file3) {
                        $createdFiles[] = $file3;
                    }
                // CREATE DATA BACKUP FILE (FILE 3)
            }
            
            return [
                'files' => $createdFiles,
                'total_tables' => $totalTables
            ];
        }
    // BACKUP ALL TABLES DATA
    
    
    // BACKUP DAILY - FULL DATABASE DUMP
        public static function backupDaily(): array
        {
            $backup_dir = DIR_D.'/backup/';
            
            // CREATE BACKUP DIRECTORY IF NOT EXISTS
                if (!is_dir($backup_dir)) {
                    mkdir($backup_dir, 0777, true);
                }
            // CREATE BACKUP DIRECTORY IF NOT EXISTS
            
            // GET DATABASE CREDENTIALS
                $database = DB::getDatabaseName();
                $host = config('database.connections.mysql.host');
                $username = config('database.connections.mysql.username');
                $password = config('database.connections.mysql.password');
                $port = config('database.connections.mysql.port', 3306);
            // GET DATABASE CREDENTIALS
            
            // CREATE FILENAME WITH DATE
                $date = date('Y_m_d');
                $filename = "{$date}_{$database}.sql";
                $filepath = $backup_dir . $filename;
            // CREATE FILENAME WITH DATE
            
            // CHECK IF BACKUP ALREADY EXISTS FOR TODAY
                if (file_exists($filepath)) {
                    return [
                        'success' => true,
                        'file' => $filepath,
                        'filename' => $filename,
                        'size' => filesize($filepath),
                        'message' => 'Backup already exists for today',
                        'skipped' => true
                    ];
                }
            // CHECK IF BACKUP ALREADY EXISTS FOR TODAY
            
            // BUILD MYSQLDUMP COMMAND
                $command = sprintf(
                    'mysqldump --host=%s --port=%s --user=%s --password=%s --single-transaction --routines --triggers --events --complete-insert --extended-insert=FALSE --quote-names --dump-date --default-character-set=utf8mb4 %s > %s 2>&1',
                    escapeshellarg($host),
                    escapeshellarg($port),
                    escapeshellarg($username),
                    escapeshellarg($password),
                    escapeshellarg($database),
                    escapeshellarg($filepath)
                );
            // BUILD MYSQLDUMP COMMAND
            
            // EXECUTE BACKUP
                $output = [];
                $return_var = 0;

                // CHECK IF EXEC IS AVAILABLE
                    if (function_exists('exec') && !in_array('exec', explode(',', ini_get('disable_functions')))) {
                        \exec($command, $output, $return_var);
                    } else {
                        // If exec is disabled, force alternative method
                        $return_var = 1; // Force failure to trigger alternative method
                    }
                // CHECK IF EXEC IS AVAILABLE
            // EXECUTE BACKUP
            
            // CHECK IF BACKUP WAS SUCCESSFUL
                if ($return_var !== 0) {
                    // Try alternative method if mysqldump fails
                    return self::backupDailyAlternative($backup_dir, $filename);
                }
            // CHECK IF BACKUP WAS SUCCESSFUL
            
            // CREATE LOG FILE
                $logFile = $backup_dir . 'backup_log.json';
                $log = [];
                
                if (file_exists($logFile)) {
                    $log = json_decode(file_get_contents($logFile), true) ?: [];
                }
                
                $log[] = [
                    'date' => date('Y-m-d H:i:s'),
                    'filename' => $filename,
                    'size' => filesize($filepath),
                    'method' => 'mysqldump'
                ];
                
                // Keep only last 30 entries
                $log = array_slice($log, -30);
                
                file_put_contents($logFile, json_encode($log, JSON_PRETTY_PRINT));
            // CREATE LOG FILE
            
            return [
                'success' => true,
                'file' => $filepath,
                'filename' => $filename,
                'size' => filesize($filepath)
            ];
        }
    // BACKUP DAILY - FULL DATABASE DUMP
    
    
    // BACKUP DAILY ALTERNATIVE - PHP METHOD
        private static function backupDailyAlternative(string $backup_dir, string $filename): array
        {
            $filepath = $backup_dir . $filename;
            $database = DB::getDatabaseName();
            
            // START SQL CONTENT - HEADER
                $sql = "-- phpMyAdmin SQL Dump\n";
                $sql .= "-- version 5.2.2\n";
                $sql .= "-- https://www.phpmyadmin.net/\n";
                $sql .= "--\n";
                $sql .= "-- Host: " . config('database.connections.mysql.host') . "\n";
                $sql .= "-- Tempo de geração: " . date('d/m/Y') . " às " . date('H:i') . "\n";
                $sql .= "-- Versão do servidor: " . DB::select("SELECT VERSION() as version")[0]->version . "\n";
                $sql .= "-- Versão do PHP: " . phpversion() . "\n\n";
                
                $sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
                $sql .= "START TRANSACTION;\n";
                $sql .= "SET time_zone = \"+00:00\";\n\n\n";
                
                $sql .= "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\n";
                $sql .= "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\n";
                $sql .= "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\n";
                $sql .= "/*!40101 SET NAMES utf8mb4 */;\n\n";
                
                $sql .= "--\n";
                $sql .= "-- Banco de dados: `{$database}`\n";
                $sql .= "--\n\n";
            // START SQL CONTENT - HEADER
            
            // GET ALL TABLES
                $tables = DB::select("SHOW TABLES");
                $tableKey = "Tables_in_{$database}";
            // GET ALL TABLES
            
            // STORE TABLE INFO FOR LATER PROCESSING
                $tableStructures = [];
                $tableData = [];
                $tableIndexes = [];
                $tableForeignKeys = [];
                $tableAutoIncrements = [];
            // STORE TABLE INFO FOR LATER PROCESSING
            
            foreach ($tables as $tableObj) {
                $table = $tableObj->$tableKey;
                
                // GET CREATE TABLE STATEMENT (WITHOUT INDEXES AND CONSTRAINTS)
                    $createTable = DB::select("SHOW CREATE TABLE `{$table}`");
                    $createStatement = $createTable[0]->{'Create Table'};
                    
                    // Remove AUTO_INCREMENT value from create statement
                    $createStatement = preg_replace('/ AUTO_INCREMENT=\d+/', '', $createStatement);
                    
                    // Extract and remove constraints and keys for later
                    $lines = explode("\n", $createStatement);
                    $cleanedLines = [];
                    $constraints = [];
                    $keys = [];
                    $isFirstLine = true;
                    
                    foreach ($lines as $line) {
                        $trimmedLine = trim($line);
                        
                        // Skip the CREATE TABLE line and the closing line
                        if ($isFirstLine || strpos($trimmedLine, 'CREATE TABLE') === 0) {
                            $cleanedLines[] = $line;
                            $isFirstLine = false;
                            continue;
                        }
                        
                        // Skip ENGINE line at the end
                        if (strpos($trimmedLine, ') ENGINE=') === 0 || strpos($trimmedLine, 'ENGINE=') === 0) {
                            continue;
                        }
                        
                        if (strpos($trimmedLine, 'PRIMARY KEY') !== false) {
                            $keys[] = $trimmedLine;
                        } else if (strpos($trimmedLine, 'KEY ') === 0 || strpos($trimmedLine, 'UNIQUE KEY') === 0) {
                            $keys[] = $trimmedLine;
                        } else if (strpos($trimmedLine, 'CONSTRAINT') === 0) {
                            $constraints[] = $trimmedLine;
                        } else if (!empty($trimmedLine) && $trimmedLine !== ')') {
                            // Remove AUTO_INCREMENT from column definitions
                            $cleanLine = preg_replace('/ AUTO_INCREMENT/i', '', $line);
                            $cleanedLines[] = $cleanLine;
                        }
                    }
                    
                    // Rebuild CREATE TABLE without keys and constraints
                    $createClean = implode("\n", $cleanedLines);
                    
                    // Remove trailing comma from last field
                    $lines = explode("\n", $createClean);
                    $lastFieldIndex = -1;
                    
                    // Find the last field line (not empty and contains column definition)
                    for ($i = count($lines) - 1; $i >= 0; $i--) {
                        $trimmed = trim($lines[$i]);
                        if (!empty($trimmed) && strpos($trimmed, 'CREATE TABLE') === false) {
                            $lastFieldIndex = $i;
                            break;
                        }
                    }
                    
                    // Remove trailing comma from last field
                    if ($lastFieldIndex !== -1) {
                        $lines[$lastFieldIndex] = rtrim($lines[$lastFieldIndex], ',');
                    }
                    
                    $createClean = implode("\n", $lines);
                    $createClean .= "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                    
                    $tableStructures[$table] = $createClean;
                    $tableIndexes[$table] = $keys;
                    $tableForeignKeys[$table] = $constraints;
                // GET CREATE TABLE STATEMENT
                
                // GET TABLE DATA
                    $data = DB::select("SELECT * FROM `{$table}`");
                    $tableData[$table] = $data;
                // GET TABLE DATA
                
                // GET AUTO INCREMENT VALUE AND COLUMN
                    $autoIncrement = DB::select("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?", [$database, $table]);
                    if (!empty($autoIncrement) && $autoIncrement[0]->AUTO_INCREMENT) {
                        // Find which column has AUTO_INCREMENT
                        $columns = DB::select("SHOW COLUMNS FROM `{$table}`");
                        foreach ($columns as $column) {
                            if (strpos($column->Extra, 'auto_increment') !== false) {
                                $tableAutoIncrements[$table] = [
                                    'column' => $column->Field,
                                    'type' => $column->Type,
                                    'value' => $autoIncrement[0]->AUTO_INCREMENT
                                ];
                                break;
                            }
                        }
                    }
                // GET AUTO INCREMENT VALUE AND COLUMN
            }
            
            // WRITE STRUCTURES
                foreach ($tableStructures as $table => $structure) {
                    $sql .= "-- --------------------------------------------------------\n\n";
                    $sql .= "--\n";
                    $sql .= "-- Estrutura para tabela `{$table}`\n";
                    $sql .= "--\n\n";
                    $sql .= $structure . ";\n\n";
                }
            // WRITE STRUCTURES
            
            // WRITE DATA
                foreach ($tableData as $table => $data) {
                    if (!empty($data)) {
                        $sql .= "--\n";
                        $sql .= "-- Despejando dados para a tabela `{$table}`\n";
                        $sql .= "--\n\n";
                        
                        // Get column names
                        $columns = array_keys((array)$data[0]);
                        $columnList = '`' . implode('`, `', $columns) . '`';
                        
                        $sql .= "INSERT INTO `{$table}` ({$columnList}) VALUES\n";
                        
                        $valueRows = [];
                        foreach ($data as $row) {
                            $values = [];
                            foreach ($row as $value) {
                                if ($value === null) {
                                    $values[] = 'NULL';
                                } else if (is_numeric($value)) {
                                    $values[] = $value;
                                } else {
                                    $escaped = str_replace("\\", "\\\\", $value);
                                    $escaped = str_replace("'", "\\'", $escaped);
                                    $escaped = str_replace("\n", "\\n", $escaped);
                                    $escaped = str_replace("\r", "\\r", $escaped);
                                    $escaped = str_replace("\t", "\\t", $escaped);
                                    $values[] = "'{$escaped}'";
                                }
                            }
                            $valueRows[] = "(" . implode(', ', $values) . ")";
                        }
                        
                        $sql .= implode(",\n", $valueRows) . ";\n\n";
                    }
                }
            // WRITE DATA
            
            // WRITE INDEXES
                $sql .= "--\n";
                $sql .= "-- Índices para tabelas despejadas\n";
                $sql .= "--\n\n";
                
                foreach ($tableIndexes as $table => $indexes) {
                    if (!empty($indexes)) {
                        $sql .= "--\n";
                        $sql .= "-- Índices de tabela `{$table}`\n";
                        $sql .= "--\n";
                        $sql .= "ALTER TABLE `{$table}`\n";
                        
                        $indexLines = [];
                        foreach ($indexes as $index) {
                            $cleanIndex = trim($index, ",");
                            $indexLines[] = "  ADD " . $cleanIndex;
                        }
                        
                        $sql .= implode(",\n", $indexLines) . ";\n\n";
                    }
                }
            // WRITE INDEXES
            
            // WRITE AUTO INCREMENTS
                $sql .= "--\n";
                $sql .= "-- AUTO_INCREMENT para tabelas despejadas\n";
                $sql .= "--\n\n";
                
                foreach ($tableAutoIncrements as $table => $autoIncData) {
                    $sql .= "--\n";
                    $sql .= "-- AUTO_INCREMENT de tabela `{$table}`\n";
                    $sql .= "--\n";
                    $sql .= "ALTER TABLE `{$table}`\n";
                    
                    // Format column type properly
                    $columnType = strtoupper($autoIncData['type']);
                    $columnType = str_replace('UNSIGNED', '', $columnType);
                    $columnType = trim($columnType);
                    
                    // Add UNSIGNED back if it was there
                    if (strpos(strtoupper($autoIncData['type']), 'UNSIGNED') !== false) {
                        $columnType .= ' UNSIGNED';
                    }
                    
                    $sql .= "  MODIFY `{$autoIncData['column']}` {$columnType} NOT NULL AUTO_INCREMENT, AUTO_INCREMENT={$autoIncData['value']};\n\n";
                }
            // WRITE AUTO INCREMENTS
            
            // WRITE FOREIGN KEYS
                $sql .= "--\n";
                $sql .= "-- Restrições para tabelas despejadas\n";
                $sql .= "--\n\n";
                
                foreach ($tableForeignKeys as $table => $constraints) {
                    if (!empty($constraints)) {
                        $sql .= "--\n";
                        $sql .= "-- Restrições para tabelas `{$table}`\n";
                        $sql .= "--\n";
                        $sql .= "ALTER TABLE `{$table}`\n";
                        
                        $constraintLines = [];
                        foreach ($constraints as $constraint) {
                            $cleanConstraint = trim($constraint, ",");
                            $constraintLines[] = "  ADD " . $cleanConstraint;
                        }
                        
                        $sql .= implode(",\n", $constraintLines) . ";\n";
                    }
                }
            // WRITE FOREIGN KEYS
            
            // FOOTER
                $sql .= "COMMIT;\n\n";
                $sql .= "/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\n";
                $sql .= "/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\n";
                $sql .= "/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;\n";
            // FOOTER
            
            // SAVE TO FILE
                file_put_contents($filepath, $sql);
            // SAVE TO FILE
            
            // CREATE LOG FILE
                $logFile = $backup_dir . 'backup_log.json';
                $log = [];
                
                if (file_exists($logFile)) {
                    $log = json_decode(file_get_contents($logFile), true) ?: [];
                }
                
                $log[] = [
                    'date' => date('Y-m-d H:i:s'),
                    'filename' => $filename,
                    'size' => filesize($filepath),
                    'method' => 'php'
                ];
                
                // Keep only last 30 entries
                $log = array_slice($log, -30);
                
                file_put_contents($logFile, json_encode($log, JSON_PRETTY_PRINT));
            // CREATE LOG FILE
            
            return [
                'success' => true,
                'file' => $filepath,
                'filename' => $filename,
                'size' => filesize($filepath),
                'method' => 'php'
            ];
        }
    // BACKUP DAILY ALTERNATIVE - PHP METHOD
    
    
    // CREATE TABLE MIGRATION (FILE 1)
        private static function createTableMigration(string $table, string $migration_dir): string
        {
            $migration_file = $migration_dir . '1_' . $table . '.php';
            
            // GET TABLE COLUMNS
                $columns = DB::select("SHOW COLUMNS FROM `{$table}`");
            // GET TABLE COLUMNS
            
            // BUILD MIGRATION CONTENT
                $className = 'Create' . str_replace(' ', '', ucwords(str_replace('_', ' ', $table))) . 'Table';
                $content = self::buildMigrationHeader($className);
                $content .= "        Schema::create('{$table}', function (Blueprint \$table) {\n";
                
                foreach ($columns as $column) {
                    $content .= self::buildColumnDefinition($column);
                }
                
                $content .= "        });\n";
                $content .= self::buildMigrationFooter($table);
            // BUILD MIGRATION CONTENT
            
            file_put_contents($migration_file, $content);
            return $migration_file;
        }
    // CREATE TABLE MIGRATION (FILE 1)
    
    
    // CREATE INDEXES AND FOREIGN KEYS MIGRATION (FILE 2)
        private static function createIndexesAndForeignKeysMigration(string $table, string $migration_dir): ?string
        {
            // GET INDEXES
                $indexes = DB::select("SHOW INDEX FROM `{$table}`");
            // GET INDEXES
            
            // GET FOREIGN KEYS
                $foreignKeys = DB::select("
                    SELECT 
                        kcu.CONSTRAINT_NAME,
                        kcu.COLUMN_NAME,
                        kcu.REFERENCED_TABLE_NAME,
                        kcu.REFERENCED_COLUMN_NAME,
                        rc.DELETE_RULE,
                        rc.UPDATE_RULE
                    FROM information_schema.KEY_COLUMN_USAGE kcu
                    JOIN information_schema.REFERENTIAL_CONSTRAINTS rc
                        ON kcu.CONSTRAINT_NAME = rc.CONSTRAINT_NAME
                        AND kcu.TABLE_SCHEMA = rc.CONSTRAINT_SCHEMA
                    WHERE kcu.TABLE_SCHEMA = ?
                    AND kcu.TABLE_NAME = ?
                    AND kcu.REFERENCED_TABLE_NAME IS NOT NULL
                ", [DB::getDatabaseName(), $table]);
            // GET FOREIGN KEYS
            
            // ONLY CREATE FILE IF THERE ARE INDEXES OR FOREIGN KEYS
                if (empty($indexes) && empty($foreignKeys)) {
                    return null;
                }
            // ONLY CREATE FILE IF THERE ARE INDEXES OR FOREIGN KEYS
            
            $migration_file = $migration_dir . '2_' . $table . '.php';
            
            // BUILD MIGRATION CONTENT
                $className = 'Add' . str_replace(' ', '', ucwords(str_replace('_', ' ', $table))) . 'IndexesAndForeignKeys';
                $content = self::buildMigrationHeader($className);
                $content .= "        Schema::table('{$table}', function (Blueprint \$table) {\n";
                
                // ADD INDEXES
                    $content .= self::buildIndexDefinitions($indexes, $foreignKeys);
                // ADD INDEXES
                
                // ADD FOREIGN KEYS
                    $content .= self::buildForeignKeyDefinitions($foreignKeys);
                // ADD FOREIGN KEYS
                
                $content .= "        });\n";
                $content .= "    }\n\n";
                $content .= "    public function down()\n";
                $content .= "    {\n";
                $content .= "        Schema::table('{$table}', function (Blueprint \$table) {\n";
                
                // DROP FOREIGN KEYS FIRST
                    foreach ($foreignKeys as $fk) {
                        $content .= "            \$table->dropForeign('{$fk->CONSTRAINT_NAME}');\n";
                    }
                // DROP FOREIGN KEYS FIRST
                
                // DROP INDEXES
                    $processedIndexes = self::getProcessedIndexes($indexes, $foreignKeys);
                    foreach ($processedIndexes as $indexName) {
                        $content .= "            \$table->dropIndex('{$indexName}');\n";
                    }
                // DROP INDEXES
                
                $content .= "        });\n";
                $content .= "    }\n";
                $content .= "}\n";
            // BUILD MIGRATION CONTENT
            
            file_put_contents($migration_file, $content);
            return $migration_file;
        }
    // CREATE INDEXES AND FOREIGN KEYS MIGRATION (FILE 2)
    
    
    // BUILD COLUMN DEFINITION
        private static function buildColumnDefinition($column): string
        {
            $field = $column->Field;
            $type = $column->Type;
            $null = $column->Null;
            $default = $column->Default;
            $extra = $column->Extra;
            
            $definition = "            ";
            
            // HANDLE AUTO INCREMENT ID
                if ($field === 'id' && $extra === 'auto_increment') {
                    return $definition . "\$table->bigIncrements('id');\n";
                }
            // HANDLE AUTO INCREMENT ID
            
            // DETERMINE COLUMN TYPE
                if (strpos($type, 'bigint') !== false && strpos($type, 'unsigned') !== false) {
                    $definition .= "\$table->unsignedBigInteger('{$field}')";
                } else if (strpos($type, 'bigint') !== false) {
                    $definition .= "\$table->bigInteger('{$field}')";
                } else if (strpos($type, 'int') !== false) {
                    $definition .= "\$table->integer('{$field}')";
                } else if (strpos($type, 'tinyint(1)') !== false) {
                    $definition .= "\$table->boolean('{$field}')";
                } else if (strpos($type, 'varchar') !== false) {
                    preg_match('/varchar\((\d+)\)/', $type, $matches);
                    $length = isset($matches[1]) ? $matches[1] : 255;
                    $definition .= "\$table->string('{$field}', {$length})";
                } else if (strpos($type, 'text') !== false) {
                    $definition .= "\$table->text('{$field}')";
                } else if (strpos($type, 'longtext') !== false) {
                    $definition .= "\$table->longText('{$field}')";
                } else if (strpos($type, 'decimal') !== false) {
                    preg_match('/decimal\((\d+),(\d+)\)/', $type, $matches);
                    $precision = isset($matches[1]) ? $matches[1] : 10;
                    $scale = isset($matches[2]) ? $matches[2] : 2;
                    $definition .= "\$table->decimal('{$field}', {$precision}, {$scale})";
                } else if (strpos($type, 'date') !== false && $type === 'date') {
                    $definition .= "\$table->date('{$field}')";
                } else if (strpos($type, 'datetime') !== false) {
                    $definition .= "\$table->dateTime('{$field}')";
                } else if (strpos($type, 'timestamp') !== false) {
                    $definition .= "\$table->timestamp('{$field}')";
                } else {
                    $definition .= "\$table->string('{$field}')";
                }
            // DETERMINE COLUMN TYPE
            
            // ADD NULLABLE
                if ($null === 'YES') {
                    $definition .= "->nullable()";
                }
            // ADD NULLABLE
            
            // ADD DEFAULT VALUE
                if ($default !== null && $default !== 'NULL') {
                    if (is_numeric($default)) {
                        $definition .= "->default({$default})";
                    } else if ($default === 'CURRENT_TIMESTAMP') {
                        $definition .= "->useCurrent()";
                    } else {
                        $definition .= "->default('{$default}')";
                    }
                }
            // ADD DEFAULT VALUE
            
            return $definition . ";\n";
        }
    // BUILD COLUMN DEFINITION
    
    
    // BUILD INDEX DEFINITIONS
        private static function buildIndexDefinitions(array $indexes, array $foreignKeys): string
        {
            $content = '';
            $processedIndexes = [];
            
            // GROUP INDEXES BY NAME
                $indexGroups = [];
                foreach ($indexes as $index) {
                    if ($index->Key_name !== 'PRIMARY') {
                        $indexGroups[$index->Key_name][] = $index;
                    }
                }
            // GROUP INDEXES BY NAME
            
            foreach ($indexGroups as $indexName => $indexColumns) {
                // CHECK IF FOREIGN KEY
                    $isForeignKey = false;
                    foreach ($foreignKeys as $fk) {
                        if ($fk->CONSTRAINT_NAME === $indexName) {
                            $isForeignKey = true;
                            break;
                        }
                    }
                // CHECK IF FOREIGN KEY
                
                if (!$isForeignKey && !in_array($indexName, $processedIndexes)) {
                    $processedIndexes[] = $indexName;
                    
                    // GET COLUMNS
                        $columns = array_column($indexColumns, 'Column_name');
                    // GET COLUMNS
                    
                    if (count($columns) > 1) {
                        // COMPOSITE INDEX
                            $columnsStr = "['" . implode("', '", $columns) . "']";
                            if ($indexColumns[0]->Non_unique == 0) {
                                $content .= "            \$table->unique({$columnsStr}, '{$indexName}');\n";
                            } else {
                                $content .= "            \$table->index({$columnsStr}, '{$indexName}');\n";
                            }
                        // COMPOSITE INDEX
                    } else {
                        // SINGLE COLUMN INDEX
                            if ($indexColumns[0]->Non_unique == 0) {
                                $content .= "            \$table->unique('{$columns[0]}', '{$indexName}');\n";
                            } else {
                                $content .= "            \$table->index('{$columns[0]}', '{$indexName}');\n";
                            }
                        // SINGLE COLUMN INDEX
                    }
                }
            }
            
            return $content;
        }
    // BUILD INDEX DEFINITIONS
    
    
    // BUILD FOREIGN KEY DEFINITIONS
        private static function buildForeignKeyDefinitions(array $foreignKeys): string
        {
            $content = '';
            
            foreach ($foreignKeys as $fk) {
                $deleteRule = strtolower($fk->DELETE_RULE);
                $updateRule = strtolower($fk->UPDATE_RULE);
                
                $content .= "            \$table->foreign('{$fk->COLUMN_NAME}', '{$fk->CONSTRAINT_NAME}')\n";
                $content .= "                  ->references('{$fk->REFERENCED_COLUMN_NAME}')\n";
                $content .= "                  ->on('{$fk->REFERENCED_TABLE_NAME}')\n";
                
                // ON DELETE
                    if ($deleteRule === 'cascade') {
                        $content .= "                  ->onDelete('cascade')\n";
                    } else if ($deleteRule === 'set null') {
                        $content .= "                  ->onDelete('set null')\n";
                    } else if ($deleteRule === 'no action') {
                        $content .= "                  ->onDelete('no action')\n";
                    } else {
                        $content .= "                  ->onDelete('restrict')\n";
                    }
                // ON DELETE
                
                // ON UPDATE
                    if ($updateRule === 'cascade') {
                        $content .= "                  ->onUpdate('cascade');\n";
                    } else if ($updateRule === 'set null') {
                        $content .= "                  ->onUpdate('set null');\n";
                    } else if ($updateRule === 'no action') {
                        $content .= "                  ->onUpdate('no action');\n";
                    } else {
                        $content .= "                  ->onUpdate('restrict');\n";
                    }
                // ON UPDATE
            }
            
            return $content;
        }
    // BUILD FOREIGN KEY DEFINITIONS
    
    
    // HELPER METHODS
        private static function buildMigrationHeader(string $className): string
        {
            $content = "<?php\n\n";
            $content .= "use Illuminate\\Database\\Migrations\\Migration;\n";
            $content .= "use Illuminate\\Database\\Schema\\Blueprint;\n";
            $content .= "use Illuminate\\Support\\Facades\\Schema;\n";
            
            // Add DB import for data seeders
            if (strpos($className, 'Insert') === 0 && strpos($className, 'Data') !== false) {
                $content .= "use Illuminate\\Support\\Facades\\DB;\n";
            }
            
            $content .= "\n";
            $content .= "class {$className} extends Migration\n";
            $content .= "{\n";
            $content .= "    public function up()\n";
            $content .= "    {\n";
            return $content;
        }
        
        private static function buildMigrationFooter(string $table): string
        {
            $content = "    }\n\n";
            $content .= "    public function down()\n";
            $content .= "    {\n";
            $content .= "        Schema::dropIfExists('{$table}');\n";
            $content .= "    }\n";
            $content .= "}\n";
            return $content;
        }
        
        private static function getProcessedIndexes(array $indexes, array $foreignKeys): array
        {
            $processedIndexes = [];
            $indexGroups = [];
            
            foreach ($indexes as $index) {
                if ($index->Key_name !== 'PRIMARY') {
                    $indexGroups[$index->Key_name][] = $index;
                }
            }
            
            foreach ($indexGroups as $indexName => $indexColumns) {
                $isForeignKey = false;
                foreach ($foreignKeys as $fk) {
                    if ($fk->CONSTRAINT_NAME === $indexName) {
                        $isForeignKey = true;
                        break;
                    }
                }
                
                if (!$isForeignKey) {
                    $processedIndexes[] = $indexName;
                }
            }
            
            return $processedIndexes;
        }
    // HELPER METHODS
    
    
    // CREATE DATA BACKUP MIGRATION (FILE 3)
        private static function createDataBackupMigration(string $table, string $backup_dir): ?string
        {
            // GET ALL DATA FROM TABLE
                $data = DB::table($table)->get();
            // GET ALL DATA FROM TABLE
            
            // ONLY CREATE FILE IF THERE IS DATA
                if ($data->isEmpty()) {
                    return null;
                }
            // ONLY CREATE FILE IF THERE IS DATA
            
            $migration_file = $backup_dir . '3_' . $table . '.php';
            
            // BUILD MIGRATION CONTENT
                $className = 'Insert' . str_replace(' ', '', ucwords(str_replace('_', ' ', $table))) . 'Data';
                $content = self::buildMigrationHeader($className);
                $content .= "        DB::table('{$table}')->insert([\n";
                
                foreach ($data as $index => $row) {
                    $content .= "            [\n";
                    
                    foreach ($row as $column => $value) {
                        if ($value === null) {
                            $content .= "                '{$column}' => null,\n";
                        } else if (is_numeric($value)) {
                            $content .= "                '{$column}' => {$value},\n";
                        } else if (is_bool($value)) {
                            $content .= "                '{$column}' => " . ($value ? 'true' : 'false') . ",\n";
                        } else {
                            // Escape special characters
                            $escapedValue = str_replace("\\", "\\\\", $value);
                            $escapedValue = str_replace("'", "\\'", $escapedValue);
                            $escapedValue = str_replace("\n", "\\n", $escapedValue);
                            $escapedValue = str_replace("\r", "\\r", $escapedValue);
                            $escapedValue = str_replace("\t", "\\t", $escapedValue);
                            $content .= "                '{$column}' => '{$escapedValue}',\n";
                        }
                    }
                    
                    $content .= "            ]";
                    
                    // Add comma if not last item
                    if ($index < count($data) - 1) {
                        $content .= ",";
                    }
                    
                    $content .= "\n";
                }
                
                $content .= "        ]);\n";
                $content .= "    }\n\n";
                $content .= "    public function down()\n";
                $content .= "    {\n";
                $content .= "        DB::table('{$table}')->truncate();\n";
                $content .= "    }\n";
                $content .= "}\n";
            // BUILD MIGRATION CONTENT
            
            file_put_contents($migration_file, $content);
            return $migration_file;
        }
    // CREATE DATA BACKUP MIGRATION (FILE 3)

}