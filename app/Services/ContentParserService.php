<?php


namespace App\Services;


use App\Dto\ContentDto;

class ContentParserService
{
    private DbService $dbService;

    public function __construct(DbService $dbService)
    {
        $this->dbService = $dbService;
    }

    public function getAvailableDb(): array
    {
        return $this->dbService->getAvailableDb();
    }

    public function parseFolder(): array
    {
        $path = env('db_files_path', base_path('db_examples/'));
        $dbNames = [];
        foreach (glob($path . "*.sql") as $file) {
            $filename = basename($file);
            $dbName = preg_replace("/\.[^.]+$/", "", $filename);
            $dbNames[] = $filename;
            $this->dbService->createDb($dbName);
            $this->dbService->fillDb(file_get_contents($file), $dbName);
        }

        return $dbNames;
    }

    public function dropDb(array $dbs): bool
    {
        foreach ($dbs as $database) {
            $this->dbService->dropDb($database);
        }
        return true;
    }

    /**
     * @param array $dbs
     * @return ContentDto[]
     */
    public function getContent(array $dbs): array
    {
        $result = [];
        foreach ($dbs as $dbName) {
            $dbName = preg_replace("/\.[^.]+$/", "", $dbName);
            $this->dbService->createDb($dbName);
            $result = array_merge($result, $this->dbService->getContent($dbName));
        }
        return $result;

    }
}
