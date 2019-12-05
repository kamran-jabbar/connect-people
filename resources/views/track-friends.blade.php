@extends('layouts.app')
@php
    $lat = isset($meeting_detail[0]['latitude']) ? $meeting_detail[0]['latitude'] : 62.6010;
    $lang = isset($meeting_detail[0]['longitude']) ? $meeting_detail[0]['longitude'] : 29.7636;
    $location = isset($meeting_detail[0]['location']) ? $meeting_detail[0]['location'] : '';
@endphp
@section('content')
    @push('head')
    <script src="http://maps.google.com/maps/api/js?libraries=places&key={{env('GOOLE_MAP_API_KEY')}}"></script>
    <script>
        var otherUserData = '{!! json_encode($otherUsers) !!}';
        var currentUserData = '{!! json_encode($currentUser) !!}';
    </script>
    <script src="{{ asset('js/tracking-friends-custom-js.js') }}"></script>
    <link href="{{ asset('css/custom-css.css') }}" rel="stylesheet">
    <script>
        window.onload = loadMap({!! $lat  !!}, {!! $lang !!}, '{!! $location !!}');
    </script>
    @endpush
    <div id="map-area-tracking"></div>
@endsection
