<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    // Specify the table name if it doesn't follow Laravel's naming convention
    protected $table = 'query'; // This is optional if your table name is plural of model name

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'id';

    // Specify the fields that can be mass assignable
    protected $fillable = [
        'title', 'name', 'mobile', 'email', 'destinations',
        'adult_count', 'child_count', 'infant_count',
        'from_date', 'to_date', 'source', 'status',
        'priority', 'assign_to', 'created_on', 'updated_on'
    ];

    // Set the timestamps to false since you have custom timestamp fields
    public $timestamps = false;

    // Optionally, you can define the date fields
    protected $dates = [
        'from_date', 'to_date', 'created_on', 'updated_on'
    ];

    public function queryDestinations()
    {
        return $this->hasMany(QueryDestination::class, 'query_id', 'id');
    }
}
