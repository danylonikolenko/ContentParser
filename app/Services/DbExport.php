<?php


namespace App\Services;


use Maatwebsite\Excel\Concerns\FromArray;

class DbExport implements FromArray
{
    protected array $articles;

    public function __construct(array $articles)
    {
        $this->articles = $articles;
    }

    public function array(): array
    {
        return $this->articles;
    }
}
