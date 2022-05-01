<?php


namespace App\Services;


use App\Dto\ContentDto;
use App\Models\DbList;
use App\Services\DtoTransformers\ContentTransformer;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use PDO;

class DbService
{
    public ?string $currentDatabase = null;

    private ContentTransformer $contentTransformer;

    public function __construct(ContentTransformer $contentTransformer)
    {
        $this->contentTransformer = $contentTransformer;
    }

    public function createDb(string $dbName): bool
    {
        $this->clearConfig();
        $this->createDbConnection($dbName);
        DB::statement("CREATE DATABASE IF NOT EXISTS $dbName ");
        $this->currentDatabase = $dbName;

        return true;
    }

    public function getAvailableDb(): array
    {
        $dbNames = [];
        $tables = DB::select('SHOW DATABASES');
        foreach ($tables as $table) {
            $dbNames[] = $table->Database;
        }
        $dbNames = array_filter($dbNames, function ($value, $key) {
            return str_starts_with($value, 'db');
        }, ARRAY_FILTER_USE_BOTH);

        return array_values($dbNames) ?? [];
    }

    public function fillDb(string $sql, string $dbName)
    {
        if (!$this->checkDbNotEmpty($dbName)) {
            DB::connection('mysql_' . $dbName)->unprepared($sql);
        }
    }

    /**
     * @param string $dbName
     * @return ContentDto[]
     */
    public function getContent(string $dbName): array
    {
        $table = $this->getTablePosts($dbName);
        $sql = "SELECT `post_title`, `post_content` FROM $table";
        $result = DB::connection('mysql_' . $dbName)->select($sql);

        return $this->contentTransformer->transform($result);
    }

    private function getTablePosts(string $dbName): string
    {
        $tables = $this->getAvailableTables($dbName);
        $property = "Tables_in_" . $dbName;
        foreach ($tables as $table) {
            if (str_ends_with(strtolower($table->$property), '_posts')) {
                return $table->$property;
            }
        }
        return '';
    }

    public function dropDb(string $dbName): bool
    {
        $this->clearConfig();
        $this->createDbConnection($dbName);
        DbList::where('db_name', $dbName)->delete();
        DB::connection('mysql_' . $dbName)->statement("DROP DATABASE IF EXISTS $dbName;");

        return true;
    }

    public function getAvailableTables(string $dbName): array
    {
        $sql = "SHOW TABLES";
        $result = DB::connection('mysql_' . $dbName)->select($sql);
        return $result ?? [];
    }

    private function checkDbNotEmpty(string $dbName): bool
    {
        $sql = "SHOW TABLES";
        $result = DB::connection('mysql_' . $dbName)->select($sql);
        return (bool)$result;
    }

    private function createDbConnection(string $dbName): void
    {
        $dbList = DbList::all()->where('db_name', $dbName)->first();
        if ($dbList) {
            config(["database.connections.mysql_" . $dbName => $dbList->connection_config]);
            return;
        }
        $config = [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => $dbName,
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', 'root'),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ];

        $dbList = new DbList();
        $dbList->db_name = $dbName;
        $dbList->connection_config = $config;
        $dbList->save();
        config(["database.connections.mysql_" . $dbName => $dbList->connection_config]);
    }

    private function clearConfig()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:cache');
        Artisan::call('config:clear');
        Artisan::call('optimize');
    }
}
