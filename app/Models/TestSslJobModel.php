<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class TestSslJobModel extends Model
{
    use HasFactory;

    protected $table = 'jobs';

    protected $fillable = [
        'id',
        'queue',
        'payload',
        'attempts',
        'reserved_at',
    ];

    protected $dates = [
        'create_at',
        'update_at'
    ];

    /**
     * Check if the dns name already in the queue scan
     * @param $url
     * @return Model|Builder|null
     */
    public static function findUrlAlreadyInTheJobForScan($url): Model|Builder|null
    {
        return TestSslJobModel::query('id')
            ->where('payload', 'like', '%' . $url . '%')
            ->first();
    }
}
