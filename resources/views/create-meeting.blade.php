@extends('layouts.app')
@php
    $lat = isset($meeting_detail[0]['latitude']) ? $meeting_detail[0]['latitude'] : 62.60226;
    $lang = isset($meeting_detail[0]['longitude']) ? $meeting_detail[0]['longitude'] : 29.76359;
    $icon = isset($meeting_detail[0]['meetingType'][0]['reference']) ?
                '/meeting-images/'. $meeting_detail[0]['meetingType'][0]['reference'] . '.png' : '/meeting-destination.png';
    $time = isset($meeting_detail[0]['time']) ? (strtotime($meeting_detail[0]['time']) - 7200) * 1000 : '';
@endphp
@section('content')
    @push('head')
    <link href="{{ asset('css/bootstrap-theme.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-css.css') }}" rel="stylesheet">
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('js/create-meeting-custom-js.js') }}"></script>
    <script src="http://maps.google.com/maps/api/js?libraries=places&key={{env('GOOLE_MAP_API_KEY')}}"></script>
    <script>
        $(document).ready(function () {
            $(function () {
                $('.meeting-time').datetimepicker({
                    format: 'DD.MM.YYYY HH:mm',
                    minDate: new Date({!! $time !!}),
                });
            });
        });

        window.onload = loadMap({!! $lat  !!}, {!! $lang !!}, '{!! $icon !!}');
        var latitudeMeeting = {!! $lat !!};
        var longitudeMeeting = {!! $lang !!};
    </script>
    @endpush
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Create Meeting</div>
                    <div class="panel-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul id="create-meeting-error-li">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        {!! Form::open(array('url' => 'create-meeting')) !!}
                        <div class="form-group">
                            <select name="meeting_type_id" class="form-control" id="meeting_type">
                                <option>Meeting Type</option>
                                @foreach($meeting_types as $meeting_type)
                                    <option value="{{ $meeting_type['id'] }}"
                                            data="{{$meeting_type['reference']}}"
                                            {{isset($meeting_detail[0]['meeting_type_id']) && $meeting_type['id'] == $meeting_detail[0]['meeting_type_id'] ? "selected" : ""}}>
                                        {{ $meeting_type['meeting_name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            {!! Form::text('time',  isset($meeting_detail) ? $meeting_detail[0]['time'] : old('time'), ['class'=>'form-control meeting-time',
                            'placeholder'=>'Meeting Time']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::text('location', isset($meeting_detail) ? $meeting_detail[0]['location'] : old('location'), ['class'=>'form-control',
                            'id' => 'search-location', 'placeholder'=>'Meeting Location']) !!}
                        </div>
                        <div class="form-group">
                            <div id="map-area"></div>
                        </div>
                        {!! Form::hidden('latitude', old('latitude'), ['id' => 'latitude']) !!}
                        {!! Form::hidden('longitude', old('longitude'), ['id' => 'longitude']) !!}
                        @if(isset($meeting_detail))
                            {!! Form::hidden('update', '1') !!}
                            {!! Form::hidden('id', $meeting_detail[0]['id']) !!}
                        @endif
                        <div id="create-meeting-action-btn">
                            <a href="/dashboard" class="btn btn-primary btn-display-create-mtg"
                               id="create-meeting-submit-icon">
                                <span class="glyphicon glyphicon-chevron-left"></span>
                            </a>
                            <button type="submit" class="btn btn-primary btn-display-create-mtg"
                                    id="create-meeting-submit-icon">
                                <span class="glyphicon glyphicon-chevron-right"></span>
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
