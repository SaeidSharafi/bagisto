<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixArabicCharactersCommand extends Command
{
    protected $signature = 'arabic:fix {--dry-run : Only show what would be changed without making changes}';

    protected $description = 'Replace Arabic characters in specific tables string columns';

    // Tables to process
    private $includeTables = [
        'products',
        'product_flat',
        'customers',
        'orders',
        'product_attribute_values',
    ];

    public function handle(): void
    {
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->info('Running in DRY RUN mode. No actual changes will be made.');
        }

        $this->info('Starting Arabic character replacement on specified tables...');

        $totalUpdates = 0;

        foreach ($this->includeTables as $tableName) {
            try {
                // Check if table exists
                if (!$this->tableExists($tableName)) {
                    $this->warn("Table '{$tableName}' does not exist - skipping");
                    continue;
                }

                $tableUpdates = $this->processTable($tableName, $isDryRun);
                $totalUpdates += $tableUpdates;
            } catch (\Exception $e) {
                $this->error("Error processing table {$tableName}: " . $e->getMessage());
            }
        }

        $this->info("Arabic character replacement completed! {$totalUpdates} fields " .
            ($isDryRun ? "would be" : "were") . " updated.");
    }

    private function tableExists(string $tableName): bool
    {
        $databaseName = DB::connection()->getDatabaseName();
        $result = DB::select("
            SELECT COUNT(*) as count 
            FROM INFORMATION_SCHEMA.TABLES 
            WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?
        ", [$databaseName, $tableName]);

        return $result[0]->count > 0;
    }

    private function processTable(string $tableName, bool $isDryRun): int
    {
        $columns = $this->getStringColumns($tableName);
        $updates = 0;
        $this->info("Checking Table '{$tableName}': ");
        foreach ($columns as $column) {
            $updates += $this->replaceCharactersInColumn($tableName, $column->COLUMN_NAME, $isDryRun);
        }

        if ($updates > 0) {
            $this->info("Table '{$tableName}': {$updates} fields updated");
        }

        return $updates;
    }

    private function getStringColumns(string $tableName): array
    {
        $databaseName = DB::connection()->getDatabaseName();
        return DB::select("
            SELECT COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = ?
            AND TABLE_NAME = ?
            AND (DATA_TYPE LIKE '%char%' OR DATA_TYPE LIKE '%text%')
        ", [$databaseName, $tableName]);
    }

    private function replaceCharactersInColumn(string $table, string $column, bool $isDryRun): int
    {
        $updates = 0;

        $replacements = [
            'ي' => 'ی',
            'كٔ' => 'ک',
            'ك' => 'ک',
        ];

        foreach ($replacements as $from => $to) {
            $rowsToUpdate = DB::query()->from($table)
                ->whereNotNull($column)
                ->where($column, 'like', '%' . $from . '%')->count();

            if ($rowsToUpdate > 0) {
                if (!$isDryRun) {
                    DB::statement("UPDATE `{$table}` SET `{$column}` = REPLACE(`{$column}`, ?, ?) WHERE `{$column}` IS NOT NULL",
                        [$from, $to]);
                }
                $updates += $rowsToUpdate;
                $this->line("  - " . ($isDryRun ? "Would replace" : "Replaced") .
                    " '{$from}' with '{$to}' in {$rowsToUpdate} rows of {$table}.{$column}");
            }
        }

        return $updates;
    }
}
