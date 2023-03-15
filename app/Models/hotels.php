<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $city
 * @property string $nit
 * @property integer $room_count
 * @property string $created_at
 * @property string $updated_at
 * @property Room[] $rooms
 */
class hotels extends Model
{

     /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'hotels';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['name', 'address', 'city', 'nit', 'room_count', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rooms()
    {
        return $this->hasMany('App\Models\Room');
    }
}
