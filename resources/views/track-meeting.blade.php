@extends('layouts.app')
@php
    $destLat = isset($meeting_detail[0]['latitude']) ? $meeting_detail[0]['latitude'] : 62.60226;
    $detLang = isset($meeting_detail[0]['longitude']) ? $meeting_detail[0]['longitude'] : 29.76359;
    $location = isset($meeting_detail[0]['location']) ? $meeting_detail[0]['location'] : '';
    $time = isset($meeting_detail[0]['time']) ? $meeting_detail[0]['time'] : 'No Time';
    $icon = isset($meeting_detail[0]['meetingType'][0]['reference']) ?
                '/meeting-images/'. $meeting_detail[0]['meetingType'][0]['reference'] . '.png' : '/meeting-destination.png';
@endphp
@section('content')
    @push('head')
    <script src="http://maps.google.com/maps/api/js?key={{env('GOOLE_MAP_API_KEY')}}"></script>
    <script>
        var lat = '{!! $destLat !!}';
        var lng = '{!! $detLang !!}';
        var meeting_name = '{!! $location !!}';
        var time = '{!! $time !!}';
        var currentUserData = '{!! json_encode($currentUser) !!}';
        var travelModeCustom = '{!! $mode !!}';
        var meetingIcon = '{!! $icon !!}';
    </script>
    <script src="{{ asset('js/tracking-custom-js.js') }}"></script>
    <link href="{{ asset('css/custom-css.css') }}" rel="stylesheet">
    <script>
        window.onload = initialize();
        setTimeout(calcRoute(), 2000)
    </script>
    @endpush
    <div id="map-area-tracking"></div>

@endsection
