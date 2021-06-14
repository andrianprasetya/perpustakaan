<?php

namespace App;


use Illuminate\Database\Eloquent\Model;


/**
 * Class Cases
 * @package App
 */
class Book extends Model
{
    public $table = 'books';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'description',
        'is_active',
        'created_at',
        'updated_at',
    ];
}
