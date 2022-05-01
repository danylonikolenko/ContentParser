<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DbList
 * @package App\Models
 * @property string $db_name
 * @property array $connection_config
 */
class DbList extends Model
{

    protected $connection = 'mysql';

    protected $table = 'db_list';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'db_name',
        'connection_config',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'connection_config' => 'array'
    ];
}
