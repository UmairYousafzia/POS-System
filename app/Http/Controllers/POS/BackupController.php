<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Spatie\DbDumper\Databases\MySql;

class BackupController extends Controller
{
    public function index()
    {
        $hasPackage = class_exists(\Spatie\Backup\BackupServiceProvider::class);
        return view('pos.backup', compact('hasPackage'));
    }

    public function run(Request $request)
    {
        $hasPackage = class_exists(\Spatie\Backup\BackupServiceProvider::class);
        if (!$hasPackage) {
            return back()->with('error', 'Backup package not installed. Please install spatie/laravel-backup to enable backups.');
        }
        try {
            $withNotifications = (bool)$request->boolean('with_notifications', false);
            $params = [];
            if (!$withNotifications) {
                $params['--disable-notifications'] = true;
            }
            Artisan::call('backup:run', $params);
            return back()->with('success', 'Backup started. Check storage/ for latest backup.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function downloadSql(Request $request)
    {
        try {
            $connection = config('database.default');
            if ($connection !== 'mysql') {
                return back()->with('error','SQL download currently supports MySQL only.');
            }
            $db = config('database.connections.mysql.database');
            $user = config('database.connections.mysql.username');
            $pass = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            $port = (string)config('database.connections.mysql.port');

            $dir = storage_path('app/tmp');
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            $filename = 'db-backup-' . now()->format('Ymd-His') . '.sql';
            $fullPath = $dir . DIRECTORY_SEPARATOR . $filename;

            $binPath = env('MYSQLDUMP_PATH') ?: env('DB_DUMPER_MYSQLDUMP_PATH');
            if ($binPath) {
                // Use spatie/db-dumper with explicit binary
                $dumper = MySql::create()
                    ->setDbName($db)
                    ->setUserName($user)
                    ->setPassword($pass)
                    ->setHost($host)
                    ->setPort($port);
                if (preg_match('/mysqldump(\.exe)?$/i', $binPath)) {
                    $binPath = dirname($binPath);
                }
                $dumper->setDumpBinaryPath($binPath)->dumpToFile($fullPath);
            } else {
                // Fallback: build SQL via PDO without mysqldump
                $this->dumpViaPdo($fullPath);
            }

            return response()->download($fullPath)->deleteFileAfterSend(true);
        } catch (\Throwable $e) {
            return back()->with('error', 'SQL export failed: ' . $e->getMessage() . ' — Either set MYSQLDUMP_PATH in .env or let me know to troubleshoot.');
        }
    }

    private function dumpViaPdo(string $fullPath): void
    {
        $pdo = DB::connection()->getPdo();
        $fh = fopen($fullPath, 'w');
        if (!$fh) {
            throw new \RuntimeException('Cannot write to ' . $fullPath);
        }
        $write = function(string $s) use ($fh) { fwrite($fh, $s); };
        $write("SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n");
        $write("SET FOREIGN_KEY_CHECKS = 0;\n\n");

        // Get all tables
        $tables = [];
        $stmt = $pdo->query('SHOW TABLES');
        while ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }

        foreach ($tables as $table) {
            // DDL
            $create = $pdo->query('SHOW CREATE TABLE `'.$table.'`')->fetch(\PDO::FETCH_ASSOC);
            $write("DROP TABLE IF EXISTS `{$table}`;\n");
            $write($create['Create Table'] . ";\n\n");

            // Data in chunks
            $count = (int)$pdo->query('SELECT COUNT(*) FROM `'.$table.'`')->fetchColumn();
            if ($count === 0) { continue; }
            $offset = 0; $limit = 1000;
            while ($offset < $count) {
                $rows = $pdo->query('SELECT * FROM `'.$table.'` LIMIT '.$limit.' OFFSET '.$offset)->fetchAll(\PDO::FETCH_ASSOC);
                if (!$rows) { break; }
                $cols = array_map(fn($c)=>'`'.$c.'`', array_keys($rows[0]));
                $write('INSERT INTO `'.$table.'` ('.implode(',', $cols).') VALUES\n');
                $vals = [];
                foreach ($rows as $r) {
                    $quoted = array_map(function($v) use ($pdo){
                        if (is_null($v)) return 'NULL';
                        return $pdo->quote((string)$v);
                    }, array_values($r));
                    $vals[] = '(' . implode(',', $quoted) . ')';
                }
                $write(implode(",\n", $vals) . ";\n\n");
                $offset += $limit;
            }
        }

        $write("SET FOREIGN_KEY_CHECKS = 1;\n");
        fclose($fh);
    }
}
