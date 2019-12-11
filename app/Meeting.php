<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class Meeting
 * @package App
 */
class Meeting extends Model
{
    const DEFAULT_PAGINATION_LIMIT = 4;

    public $table = 'meeting';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'meeting_type_id',
        'latitude',
        'longitude',
        'location',
        'time',
    ];

    public function meetingType()
    {
        return $this->hasMany(Meeting_Type::class, 'id', 'meeting_type_id');
    }


    /**
     * @param $request
     * @param string $updateId
     * @return bool
     */
    public function storeMeeting($request, $updateId = '')
    {
        if(!$updateId){
            $request->merge(array('user_id' => auth()->user()->id));
            return Meeting::create($request->all());

        }  else {
            $meeting = Meeting::find($updateId);
            $meeting->time = $request['time'];
            $meeting->location = $request['location'];
            $meeting->latitude = $request['latitude'];
            $meeting->longitude = $request['longitude'];
            $meeting->meeting_type_id = $request['meeting_type_id'];
            $meeting->save();

            return true;
        }
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getMeetingList()
    {
        return Meeting::with('meetingType')->orderBy('created_at', 'DESC')->paginate(
            self::DEFAULT_PAGINATION_LIMIT);
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteById($id)
    {
        return Meeting::where(['user_id' => auth()->user()->id, 'id' => $id])->delete();
    }

    /**
     * @param $meetingId
     * @return int
     */
    public function getMeetingById($meetingId)
    {
        return Meeting::where(['id' => $meetingId])->get();
    }
}
