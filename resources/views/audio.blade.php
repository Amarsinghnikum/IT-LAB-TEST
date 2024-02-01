@extends('layouts.app')

@section('content')
    <h1>Audio File</h1>
    <audio controls>
        <source src="{{ asset('mp3/sample.mp3') }}" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>
    <p id="audioLength">The playtime of the audio file is: {{ $playtime }}</p>
@endsection
