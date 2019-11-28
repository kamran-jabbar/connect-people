@extends('layouts.app')
@section('content')
    @push('head')
    <script src="http://maps.google.com/maps/api/js?libraries=places&key=AIzaSyBE3ApJmvci0C2rTN1A6en5vj2Uuo3R6LA"></script>
    <script src="{{ asset('js/custom-js.js') }}"></script>
    <link href="{{ asset('css/custom-css.css') }}" rel="stylesheet">
    @endpush
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>Welcome! You are logged in</p>
                @if (\Session::has('status'))
                    <div class="alert alert-{!! \Session::get('status') !!}">
                        <ul  id="create-meeting-error-li">
                            <li>{!! \Session::get('message') !!}</li>
                        </ul>
                    </div>
            @endif
            <!-- Modal -->
                <div class="modal fade" id="meeting-popup" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Meeting Name</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table class="table">
                                    <thead>
                                    <tr class="meeting-modal-heading">
                                        <th scope="col">Meeting Time</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    <tr class="meeting-modal-row meeting-row">
                                        <td>Science Park</td>
                                        <td>
                                            <span class="glyphicon glyphicon-edit icon-custom-style"></span>
                                           {{-- <span class="glyphicon glyphicon-map-marker icon-custom-style"></span>--}}
                                            <span class="glyphicon glyphicon-trash icon-custom-style"></span>
                                        </td>
                                    </tr>
                                    </thead>
                                </table>
                                <div id="map-area"></div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Meetings
                        <a href="{{ url('create-task') }}" id="create-meeting-icon">
                            <span class="glyphicon glyphicon-plus-sign create-meeting"></span>
                        </a>
                    </div>
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                            <tr class="meeting-table-heading">
                                <th scope="col">Type</th>
                                <th scope="col">Location</th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr class="meeting-table-row">
                                <td>Business Meeting</td>
                                <td>Science Park</td>
                            </tr>
                            <tr class="meeting-table-row">
                                <td>Business Meeting</td>
                                <td>Science Park</td>
                            </tr>
                            <tr class="meeting-table-row">
                                <td>Business Meeting</td>
                                <td>Science Park</td>
                            </tr>
                            @if(count($tasks) > 0)
                                @foreach($tasks as $task)
                                    <tr>
                                        <th scope="row">{{ $task['id'] }}</th>
                                        <td>{{ $task['name'] }}</td>
                                        <td>{{ $task['description'] }}</td>
                                        <td>{{ $task['start_time'] }}</td>
                                        <td>{{ $task['end_time'] }}</td>
                                        <td>@if($task['start_time'] && $task['end_time'])
                                                Completed
                                            @elseif($task['start_time'])
                                                In progress
                                            @else
                                                Not started
                                            @endif
                                        </td>
                                        <td>
                                            {{--@todo: Conditions are too odd so that should get data from query in controller .--}}
                                            @if($task['start_time'] && $task['end_time'])
                                                {{
                                                    (new \Carbon\Carbon($task['start_time']))
                                                    ->diff(new \Carbon\Carbon($task['end_time']))
                                                    ->format('%Y-%m-%d %H:%i:%s')
                                                }}
                                            @endif
                                        </td>
                                        <td>
                                            {{--@todo: Conditions are too odd so that should get data from query.--}}
                                            @if($task['start_time'] && $task['end_time'] === null)
                                                <a href="{{ url('finish-task') . '/' . $task['id'] }}"
                                                   onclick="return confirm('Are you sure to finish this task?')">Finish</a>
                                                |
                                            @elseif($task['start_time'] === null)
                                                <a href="{{ url('start-task') . '/' . $task['id'] }}"
                                                   onclick="return confirm('Are you sure to start this task?')">Start</a>
                                                |
                                            @endif
                                            <a href="{{ url('delete-task') . '/' . $task['id'] }}"
                                               onclick="return confirm('Are you sure to delete this task?')">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                {{--<tr>
                                    <td colspan="7" align="center">
                                        No task found.
                                    </td>
                                </tr>--}}
                            @endif
                            </tbody>
                        </table>
                        {{ $tasks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
