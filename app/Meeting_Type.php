<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meeting_Type extends Model
{

    public $table = 'meeting_type';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'meeting_name'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getMeetingType()
    {
        return Meeting_Type::all();
    }

    /**
     * Get the tasks of a user.
     */
    public function meeting()
    {
        return $this->hasOne(Meeting::class, 'meeting_type_id', 'id');
    }
}
