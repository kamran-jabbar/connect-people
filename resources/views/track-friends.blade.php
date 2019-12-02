@extends('layouts.app')
@php
    $lat = isset($meeting_detail[0]['latitude']) ? $meeting_detail[0]['latitude'] : 62.6010;
    $lang = isset($meeting_detail[0]['longitude']) ? $meeting_detail[0]['longitude'] : 29.7636;
    $location = isset($meeting_detail[0]['location']) ? $meeting_detail[0]['location'] : '';
@endphp
@section('content')
    @push('head')
    <script src="http://maps.google.com/maps/api/js?libraries=places&key=AIzaSyBE3ApJmvci0C2rTN1A6en5vj2Uuo3R6LA"></script>
    <script src="{{ asset('js/tracking-friends-custom-js.js') }}"></script>
    <link href="{{ asset('css/custom-css.css') }}" rel="stylesheet">
    <script>
        window.onload = loadMap({!! $lat  !!}, {!! $lang !!}, '{!! $location !!}');
    </script>
    @endpush
    <div id="map-area-tracking"></div>
@endsection
