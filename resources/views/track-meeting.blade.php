@extends('layouts.app')
@php
    $lat = isset($meeting_detail[0]['latitude']) ? $meeting_detail[0]['latitude'] : 62.6010;
    $lang = isset($meeting_detail[0]['longitude']) ? $meeting_detail[0]['longitude'] : 29.7636;
    $location = isset($meeting_detail[0]['location']) ? $meeting_detail[0]['location'] : '';
    $time = isset($meeting_detail[0]['time']) ? $meeting_detail[0]['time'] : 'No Time';
@endphp
@section('content')
    @push('head')
    <script src="http://maps.google.com/maps/api/js?libraries=places&key={{env('GOOLE_MAP_API_KEY')}}"></script>
    <script src="{{ asset('js/tracking-custom-js.js') }}"></script>
    <link href="{{ asset('css/custom-css.css') }}" rel="stylesheet">
    <script>
        window.onload = loadMap({!! $lat  !!}, {!! $lang !!}, '{!! $location !!}', '{!! $time !!}', '{!! $user_name !!}');
    </script>
    @endpush
    <div id="map-area-tracking"></div>
@endsection
