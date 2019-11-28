@extends('layouts.app')

@section('content')
    @push('head')
    {{--<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('css/bootstrap-theme.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-css.css') }}" rel="stylesheet">
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('js/custom-js.js') }}"></script>
    <script src="http://maps.google.com/maps/api/js?libraries=places&key=AIzaSyBE3ApJmvci0C2rTN1A6en5vj2Uuo3R6LA"></script>
    <script>
        window.onload = loadMap();
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
                        {!! Form::open() !!}
                        <div class="form-group">
                            <select class="form-control" id="exampleFormControlSelect1">
                                <option>Meeting Type</option>
                                <option>Friends Meeting</option>
                                <option>Family Meeting</option>
                                <option>Business Meeting</option>
                                <option>Personal Meeting</option>
                                <option>Office Meeting</option>
                            </select>
                        </div>
                        <div class="form-group">
                            {!! Form::label('Meeting Time') !!}
                            {!! Form::text('time', old('time'), ['class'=>'form-control meeting-time', 'placeholder'=>'Meeting Time']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('Meeting Location') !!}
                            {!! Form::text('location', old('location'), ['class'=>'form-control', 'id' => 'search-location', 'placeholder'=>'Meeting Location']) !!}
                        </div>
                        <div class="form-group">
                            <div id="map-area"></div>
                        </div>
                        <button type="submit" class="btn btn-primary" id="create-meeting-submit-icon">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                        </button>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
