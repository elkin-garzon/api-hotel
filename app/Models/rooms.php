<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $hotel_id
 * @property integer $count
 * @property string $room_type
 * @property string $lodging
 * @property string $created_at
 * @property string $updated_at
 * @property Hotel $hotel
 */
class rooms extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['hotel_id', 'count', 'room_type', 'lodging', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hotel()
    {
        return $this->belongsTo('App\Models\Hotel');
    }
}
