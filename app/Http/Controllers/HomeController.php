<?php

namespace App\Http\Controllers;

use App\Meeting;
use App\Task;
use Illuminate\Support\Facades\Auth;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * The task model implementation.
     * @var Meeting
     */
    private $meeting;

    /**
     * HomeController constructor.
     * @param Meeting $meeting
     */
    public function __construct(Meeting $meeting)
    {
        $this->middleware('auth');
        $this->meeting = $meeting;
    }

    /**
     * Show the application dashboard with meeting list.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home', [
                'meetings' => $this->meeting->getMeetingList(),
            ]
        );
    }
}
