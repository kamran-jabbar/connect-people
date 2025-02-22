<?php

namespace App\Http\Controllers;

use App\Meeting;
use App\Meeting_Type;
use App\Task;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

/**
 * Class MeetingController
 * @package App\Http\Controllers
 */
class MeetingController extends Controller
{
    const WALKING = 'WALKING';
    /**
     * The Meeting model implementation.
     * @var Meeting
     */
    private $meeting;

    /**
     * The Meeting_Type model implementation.
     * @var Meeting_Type
     */
    private $meetingType;

    /**
     * The user model implementation.
     * @var User
     */
    private $user;

    /**
     * MeetingController constructor.
     * @param Meeting $meeting
     * @param Meeting_Type $meetingType
     * @param User $user
     */
    public function __construct(Meeting $meeting, Meeting_Type $meetingType, User $user)
    {
        $this->middleware('auth');
        $this->meeting = $meeting;
        $this->meetingType = $meetingType;
        $this->user = $user;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createMeetingForm()
    {
        return view('create-meeting', [
                'meeting_types' => $this->meetingType->getMeetingType()
            ]
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function storeMeeting(Request $request)
    {
        $validatedData = $request->validate([
            'meeting_type_id' => 'required|integer',
            'location' => 'required',
            'time' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ],
        [
            'meeting_type_id.required' => 'Meeting type is required.',
            'meeting_type_id.integer' => 'Meeting type is required.'
        ]
        );

        if (!$validatedData) {
            return view('create-meeting');
        }
        // save record in db.
        if (Input::get('update') == 1 && Input::get('id') != "") {
            $meetingUpdateData = [
                'time' => Input::get('time'),
                'meeting_type_id' => Input::get('meeting_type_id'),
                'location' => Input::get('location'),
                'latitude' => Input::get('latitude'),
                'longitude' => Input::get('longitude')
            ];
            if ($this->meeting->storeMeeting($meetingUpdateData, Input::get('id')) === true) {
                return redirect('dashboard')->with([
                    'message' => 'Meeting updated successfully.',
                    'status' => 'success'
                ]);
            }
        } else {
            if ($this->meeting->storeMeeting($request) instanceof Meeting) {
                return redirect('dashboard')->with([
                    'message' => 'Meeting created successfully.',
                    'status' => 'success'
                ]);
            }
        }

        return redirect('dashboard')->with([
            'message' => 'Failed to create the task, please try again.',
            'status' => 'danger'
        ]);
    }

    public function editMeeting($id)
    {
        return view('create-meeting', [
                'meeting_detail' => $this->meeting->getMeetingById($id),
                'meeting_types' => $this->meetingType->getMeetingType()
            ]
        );
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteMeeting($id)
    {
        if ($this->meeting->deleteById($id)) {
            return redirect('dashboard')->with(['message' => 'Meeting deleted successfully.', 'status' => 'success']);
        }

        return redirect('dashboard')->with([
            'message' => 'Failed to delete the meeting, please try again.',
            'status' => 'danger'
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function trackMeeting(Request $request, $id)
    {
        return view('track-meeting', [
                'meeting_detail' => $this->meeting->getMeetingById($id),
                'currentUser' => $this->user->getCurrentUser(),
                'mode' =>  $this->matchTravellingMode($request->get('mode'))
            ]
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function trackFriends(Request $request, $id)
    {
        return view('track-friends', [
                'meeting_detail' => $this->meeting->getMeetingById($id),
                'otherUsers' => $this->user->getOtherUsers(),
                'currentUser' => $this->user->getCurrentUser(),
                'mode' =>  $this->matchTravellingMode($request->get('mode'))
            ]
        );
    }

    /**
     * @param $mode
     * @return string
     */
    private function matchTravellingMode($mode)
    {
        if($mode === 'walking' || $mode === 'bicycling' || $mode === 'driving' || $mode === 'transit') {
            return strtoupper($mode);
        }
        return self::WALKING;
    }

}
