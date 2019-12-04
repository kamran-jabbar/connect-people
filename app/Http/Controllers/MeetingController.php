<?php

namespace App\Http\Controllers;

use App\Meeting;
use App\Meeting_Type;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

/**
 * Class MeetingController
 * @package App\Http\Controllers
 */
class MeetingController extends Controller
{
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
     * MeetingController constructor.
     * @param Meeting $meeting
     * @param Meeting_Type $meetingType
     */
    public function __construct(Meeting $meeting, Meeting_Type $meetingType)
    {
        $this->middleware('auth');
        $this->meeting = $meeting;
        $this->meetingType = $meetingType;
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
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function trackMeeting($id)
    {
        return view('track-meeting', [
                'meeting_detail' => $this->meeting->getMeetingById($id),
                'user_name' => auth()->user()->name
            ]
        );
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function trackFriends($id)
    {
        return view('track-friends', [
                'meeting_detail' => $this->meeting->getMeetingById($id),
            ]
        );
    }
}
