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
                <p id="welcome-message">Welcome! You are logged in</p>
                @if (\Session::has('status'))
                    <div class="alert alert-{!! \Session::get('status') !!}">
                        <ul id="create-meeting-error-li">
                            <li>{!! \Session::get('message') !!}</li>
                        </ul>
                    </div>
            @endif
                <div class="panel panel-default">
                    <div class="panel-heading meeting-heading">
                        Meetings
                        <a href="{{ url('create-meeting') }}" id="create-meeting-icon">
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
                            @if(count($meetings) > 0)
                                @foreach($meetings as $meeting)
                                    <tr class="meeting-table-row"
                                        onclick="openMeetingDetailPopup('{{ $meeting['latitude'] }}', '{{ $meeting['longitude'] }}'
                                                , '{{ $meeting->meetingType[0]->meeting_name }}', '{{ $meeting['time'] }}',
                                                '{{ $meeting['id'] }}', '{{ str_limit($meeting['location'], $limit = 35, $end = '.') }}')">
                                        <td>{{ $meeting->meetingType[0]->meeting_name }}</td>
                                        <td>{{ str_limit($meeting['location'], $limit = 16, $end = '...') }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2" align="center">
                                        No Meeting found.
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        {{ $meetings->links() }}
                    </div>
                </div>
                <div class="modal fade" id="meeting-popup" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h2 class="modal-title" id="exampleModalLabel"></h2>
                            </div>
                            <div class="modal-body">
                                <div id="action-icon">
                                    <a class="track-meeting" href="">
                                        <span class="glyphicon glyphicon-random icon-custom-style"></span>
                                    </a>
                                    <a class="edit-meeting" href="">
                                        <span class="glyphicon glyphicon-edit icon-custom-style"></span>
                                    </a>
                                    <a class="delete-meeting" href=""
                                       onclick="return confirm('Are you sure to delete this meeting?')">
                                        <span class="glyphicon glyphicon-trash icon-custom-style"></span>
                                    </a>
                                </div>
                                <div id="map-area-modal"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
