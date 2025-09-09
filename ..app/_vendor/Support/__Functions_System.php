<?php

use Illuminate\Support\Facades\DB;


    // SYSTEM

        // BACKUP__BD_LOCAL
        function backup__BD_LOCAL(): array
            {
                try {
                    $backupPath = DIR_F.'/../z_layout/BD_backup/local';
                    
                    // Criar diretório se não existir
                    if (!is_dir($backupPath)) {
                        mkdir($backupPath, 0755, true);
                    }
                    
                    // Nome do banco de dados
                    $databaseName = DB::connection()->getDatabaseName();
                    
                    // Data e hora atual
                    $dateTime = date('Y-m-d_H-i-s');
                    
                    // Nome do arquivo
                    $filename = "{$databaseName}_{$dateTime}.sql";
                    $filepath = $backupPath . '/' . $filename;
                    
                    // Buscar todas as tabelas
                    $tables = DB::select('SHOW TABLES');
                    $tableNames = array_map(function($table) {
                        return array_values((array) $table)[0];
                    }, $tables);
                    
                    $sqlContent = "-- Backup do banco de dados: {$databaseName}\n";
                    $sqlContent .= "-- Data: " . date('Y-m-d H:i:s') . "\n\n";
                    
                    $backedUpTables = [];
                    $foreignKeys = [];
                    
                    foreach ($tableNames as $tableName) {
                        // Pular tabelas do sistema se necessário
                        // if (in_array($tableName, ['migrations', 'password_resets', 'failed_jobs', 'personal_access_tokens', 'cache', 'cache_locks'])) {
                        //     continue;
                        // }
                        
                        // Estrutura da tabela
                        $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`")[0];
                        $createTableSQL = $createTable->{'Create Table'};
                        
                        // Extrair foreign keys para adicionar depois
                        preg_match_all('/CONSTRAINT\s+`([^`]+)`\s+FOREIGN KEY[^,\n]+(ON DELETE[^,\n]+)?(ON UPDATE[^,\n]+)?[,\n]/i', $createTableSQL, $matches);
                        
                        if (!empty($matches[0])) {
                            foreach ($matches[0] as $i => $constraintFull) {
                                // Guardar constraint para adicionar depois
                                $constraintName = $matches[1][$i];
                                $foreignKeys[$tableName][] = trim(rtrim($constraintFull, ",\n"));
                                
                                // Remover constraint do CREATE TABLE
                                $createTableSQL = str_replace($constraintFull, '', $createTableSQL);
                            }
                            
                            // Limpar vírgulas extras após remover constraints
                            $createTableSQL = preg_replace('/,(\s*\n*\s*\))/', '$1', $createTableSQL);
                            $createTableSQL = preg_replace('/,\s*,/', ',', $createTableSQL);
                        }
                        
                        $sqlContent .= "-- Estrutura da tabela `{$tableName}`\n";
                        $sqlContent .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                        $sqlContent .= $createTableSQL . ";\n\n";
                        
                        // Dados da tabela
                        $rows = DB::table($tableName)->get();
                        if ($rows->count() > 0) {
                            $sqlContent .= "-- Dados da tabela `{$tableName}`\n";
                            
                            foreach ($rows as $row) {
                                $columns = array_keys((array) $row);
                                $values = array_values((array) $row);
                                
                                // Escapar valores
                                $escapedValues = array_map(function($value) {
                                    if ($value === null) {
                                        return 'NULL';
                                    }
                                    // Substituir aspas inteligentes por aspas retas
                                    $value = str_replace(["'", "'", '"', '"'], ["'", "'", '"', '"'], $value);
                                    // Escapar caracteres especiais
                                    $value = str_replace(['\\', "'", '"', "\n", "\r", "\t", "\0", "\b"], 
                                                    ['\\\\', "''", '\"', '\\n', '\\r', '\\t', '\\0', '\\b'], $value);
                                    return "'" . $value . "'";
                                }, $values);
                                
                                $sqlContent .= "INSERT INTO `{$tableName}` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $escapedValues) . ");\n";
                            }
                            $sqlContent .= "\n";
                        }
                        
                        $backedUpTables[] = $tableName;
                    }
                    
                    // Adicionar todas as foreign keys no final
                    if (!empty($foreignKeys)) {
                        $sqlContent .= "\n-- Constraints de Foreign Keys\n";
                        $sqlContent .= "-- Adicionadas no final para evitar problemas de dependências\n\n";
                        
                        foreach ($foreignKeys as $tableName => $constraints) {
                            $sqlContent .= "-- Constraints para tabela `{$tableName}`\n";
                            foreach ($constraints as $constraint) {
                                // Extrair nome da constraint
                                preg_match('/CONSTRAINT\s+`([^`]+)`/', $constraint, $nameMatch);
                                $constraintName = $nameMatch[1] ?? '';
                                
                                // Converter CONSTRAINT para ALTER TABLE
                                $alterStatement = "ALTER TABLE `{$tableName}` ADD " . $constraint;
                                $alterStatement = rtrim($alterStatement, ",\n") . ";\n";
                                
                                $sqlContent .= $alterStatement;
                            }
                            $sqlContent .= "\n";
                        }
                    }
                    
                    // Salvar arquivo
                    file_put_contents($filepath, $sqlContent);
                    
                    return [
                        'success' => true,
                        'message' => 'Backup criado com sucesso!',
                        'database' => $databaseName,
                        'filename' => $filename,
                        'filepath' => $filepath,
                        'tables_backed_up' => $backedUpTables,
                        'total_tables' => count($backedUpTables),
                        'file_size' => filesize($filepath),
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    
                } catch (\Exception $e) {
                    return [
                        'success' => false,
                        'message' => 'Erro ao criar backup: ' . $e->getMessage()
                    ];
                }
            };
        // BACKUP__BD_LOCAL





        // IMPORT__BD_FROM_DOWNLOADS
            function import__BD_FROM_DOWNLOADS(): array
            {
                try {
                    // Configurar paths
                    $downloadsPath = 'Z:\Meus Documentos\Downloads\Chrome';
                    $db_name_production = env('DB_DATABASE'); // Nome do banco em produção
                    $destinationPath = DIR_F.'/../z_layout/BD_backup/production';
                    
                    // Criar diretório de destino se não existir
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }
                    
                    // Buscar arquivos que correspondem ao padrão
                    $pattern = $db_name_production . '*.sql';
                    $files = glob($downloadsPath . '/' . $pattern);
                    
                    if (empty($files)) {
                        return [
                            'success' => false,
                            'message' => "Nenhum arquivo encontrado com o padrão: {$pattern}"
                        ];
                    }
                    
                    // Ordenar arquivos por data de modificação (mais recente primeiro)
                    usort($files, function($a, $b) {
                        return filemtime($b) - filemtime($a);
                    });
                    
                    // Pegar o arquivo mais recente
                    $mostRecentFile = $files[0];
                    $fileName = basename($mostRecentFile);
                    
                    // Gerar novo nome com timestamp
                    $dateTime = date('Y-m-d_H-i-s');
                    $newFileName = str_replace('.sql', "_{$dateTime}.sql", $fileName);
                    $destinationFile = $destinationPath . '/' . $newFileName;
                    
                    // Copiar arquivo
                    if (copy($mostRecentFile, $destinationFile)) {
                        return [
                            'success' => true,
                            'message' => 'Arquivo importado com sucesso!',
                            'original_file' => $mostRecentFile,
                            'original_name' => $fileName,
                            'destination_file' => $destinationFile,
                            'destination_name' => $newFileName,
                            'file_size' => filesize($destinationFile),
                            'file_date' => date('Y-m-d H:i:s', filemtime($mostRecentFile)),
                            'imported_at' => date('Y-m-d H:i:s')
                        ];
                    } else {
                        return [
                            'success' => false,
                            'message' => 'Erro ao copiar arquivo'
                        ];
                    }
                    
                } catch (\Exception $e) {
                    return [
                        'success' => false,
                        'message' => 'Erro ao importar arquivo: ' . $e->getMessage()
                    ];
                }
            }
        // IMPORT__BD_FROM_DOWNLOADS







        // DROP__BD_LOCAL
            function drop__BD_LOCAL(): array
                {
                    try {
                        // Só executar em ambiente local
                        if (!LOCALHOST) {
                            return [
                                'success' => false,
                                'message' => 'Esta função só pode ser executada em ambiente local!'
                            ];
                        }
                        
                        // Obter nome do banco de dados atual
                        $databaseName = DB::connection()->getDatabaseName();
                        
                        // Desabilitar verificação de foreign keys
                        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
                        
                        // Dropar e recriar o banco de dados
                        try {
                            DB::statement("DROP DATABASE IF EXISTS `{$databaseName}`");
                            DB::statement("CREATE DATABASE `{$databaseName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                            DB::statement("USE `{$databaseName}`");
                            
                            $method = 'DROP DATABASE';
                            
                        } catch (\Exception $e) {
                            // Se falhar, tentar método alternativo (dropar todas as tabelas)
                            $tables = DB::select('SHOW TABLES');
                            $droppedTables = [];
                            
                            foreach ($tables as $table) {
                                $tableName = array_values((array) $table)[0];
                                DB::statement("DROP TABLE IF EXISTS `{$tableName}`");
                                $droppedTables[] = $tableName;
                            }
                            
                            $method = 'DROP TABLES (' . count($droppedTables) . ' tabelas)';
                        }
                        
                        // Reabilitar verificação de foreign keys
                        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
                        
                        return [
                            'success' => true,
                            'message' => 'Banco de dados local limpo com sucesso!',
                            'database' => $databaseName,
                            'method' => $method,
                            'executed_at' => date('Y-m-d H:i:s')
                        ];
                        
                    } catch (\Exception $e) {
                        // Reabilitar foreign keys em caso de erro
                        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
                        
                        return [
                            'success' => false,
                            'message' => 'Erro ao limpar banco de dados: ' . $e->getMessage()
                        ];
                    }
                }
            // DROP__BD_LOCAL





            // IMPORT__BD_PRODUCTION_TO_LOCAL
                // function import__BD_PRODUCTION_TO_LOCAL(array $previousResults = []): array
                // {
                //     try {
                //         // Só executar em ambiente local
                //         if (!LOCALHOST) {
                //             return [
                //                 'success' => false,
                //                 'message' => 'Esta função só pode ser executada em ambiente local!'
                //             ];
                //         }
                        
                //         // Verificar se o arquivo foi importado com sucesso
                //         if (!isset($previousResults['BACKUP_BD_PRODUCTION']['success']) || 
                //             !$previousResults['BACKUP_BD_PRODUCTION']['success'] ||
                //             !isset($previousResults['BACKUP_BD_PRODUCTION']['destination_file'])) {
                //             return [
                //                 'success' => false,
                //                 'message' => 'Arquivo SQL de produção não foi importado. Execute import__BD_FROM_DOWNLOADS() primeiro.'
                //             ];
                //         }
                        
                //         $sqlFile = $previousResults['BACKUP_BD_PRODUCTION']['destination_file'];
                        
                //         // Verificar se o arquivo existe
                //         if (!file_exists($sqlFile)) {
                //             return [
                //                 'success' => false,
                //                 'message' => 'Arquivo SQL não encontrado: ' . $sqlFile
                //             ];
                //         }
                        
                //         // Ler conteúdo do arquivo SQL
                //         $sqlContent = file_get_contents($sqlFile);
                        
                //         // Desabilitar verificação de foreign keys
                //         DB::statement('SET FOREIGN_KEY_CHECKS = 0');
                        
                //         // Separar comandos SQL
                //         $sqlCommands = array_filter(
                //             array_map('trim', explode(";\n", $sqlContent)),
                //             function($sql) {
                //                 return !empty($sql) && !preg_match('/^--/', $sql);
                //             }
                //         );
                        
                //         $executedCommands = 0;
                //         $errors = [];
                //         $importedTables = [];
                        
                //         foreach ($sqlCommands as $command) {
                //             if (empty(trim($command))) continue;
                            
                //             try {
                //                 // Detectar nome da tabela para logging
                //                 if (preg_match('/CREATE TABLE.*?`([^`]+)`/i', $command, $matches)) {
                //                     $importedTables[] = $matches[1];
                //                 }
                                
                //                 // Executar comando SQL
                //                 DB::statement($command);
                //                 $executedCommands++;
                                
                //             } catch (\Exception $e) {
                //                 // Capturar erros mas continuar processamento
                //                 $errors[] = [
                //                     'command' => substr($command, 0, 100) . '...',
                //                     'error' => $e->getMessage()
                //                 ];
                //             }
                //         }
                        
                //         // Reabilitar verificação de foreign keys
                //         DB::statement('SET FOREIGN_KEY_CHECKS = 1');
                        
                //         return [
                //             'success' => count($errors) === 0,
                //             'message' => count($errors) === 0 
                //                 ? 'Banco de dados importado com sucesso!' 
                //                 : 'Banco importado com ' . count($errors) . ' erros',
                //             'sql_file' => $sqlFile,
                //             'total_commands' => count($sqlCommands),
                //             'executed_commands' => $executedCommands,
                //             'imported_tables' => $importedTables,
                //             'total_tables' => count(array_unique($importedTables)),
                //             'first_error' => !empty($errors) ? $errors[0] : null,
                //             'errors_count' => count($errors),
                //             'imported_at' => date('Y-m-d H:i:s')
                //         ];
                        
                //     } catch (\Exception $e) {
                //         // Reabilitar foreign keys em caso de erro
                //         DB::statement('SET FOREIGN_KEY_CHECKS = 1');
                        
                //         return [
                //             'success' => false,
                //             'message' => 'Erro ao importar banco de dados: ' . $e->getMessage()
                //         ];
                //     }
                // }
        // IMPORT__BD_PRODUCTION_TO_LOCAL

    // SYSTEM