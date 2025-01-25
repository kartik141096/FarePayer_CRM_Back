<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueryDestination extends Model
{
    use HasFactory;

    // Define the table name if it does not follow Laravel's naming convention
    protected $table = 'query_destinations';

    // Set the primary key if it's not 'id'
    protected $primaryKey = 'id';

    // Disable timestamps if your table doesn't have created_at or updated_at
    public $timestamps = true;

    // Define the fillable attributes
    protected $fillable = [
        'query_id',
        'destination_id',
        'name',
        'type',
    ];

    // Define relationships

    // A QueryDestination belongs to a Query
    public function query()
    {
        return $this->belongsTo(Query::class, 'query_id', 'id');
    }

    // Define other relationships if needed (e.g., a destination may belong to a City, State, or Country)
    // Example: If destination_id refers to a City, State, or Country, you can create a polymorphic relationship or separate relations.
}
