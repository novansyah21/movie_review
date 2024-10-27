<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Movie Details</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>

@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center text-white">{{ $movieData['Title'] ?? 'No Title Available' }}</h2>
    
    @if($movieData)
        <div class="row">
            <div class="col-md-4">
                <img data-src="{{ $movieData['Poster'] }}" class="img-fluid rounded lazysizes" alt="{{ $movieData['Title'] }}">
            </div>
            <div class="col-md-8 text-white">
                <h4>Details</h4>
                <p><strong>Year:</strong> {{ $movieData['Year'] }}</p>
                <p><strong>Rated:</strong> {{ $movieData['Rated'] }}</p>
                <p><strong>Released:</strong> {{ $movieData['Released'] }}</p>
                <p><strong>Runtime:</strong> {{ $movieData['Runtime'] }}</p>
                <p><strong>Genre:</strong> {{ $movieData['Genre'] }}</p>
                <p><strong>Director:</strong> {{ $movieData['Director'] }}</p>
                <p><strong>Writer:</strong> {{ $movieData['Writer'] }}</p>
                <p><strong>Actors:</strong> {{ $movieData['Actors'] }}</p>
                <p><strong>Plot:</strong> {{ $movieData['Plot'] }}</p>
                <p><strong>Language:</strong> {{ $movieData['Language'] }}</p>
                <p><strong>Country:</strong> {{ $movieData['Country'] }}</p>
                <p><strong>Awards:</strong> {{ $movieData['Awards'] }}</p>
                <p><strong>IMDB Rating:</strong> {{ $movieData['imdbRating'] }}</p>
                <p><strong>IMDB Votes:</strong> {{ $movieData['imdbVotes'] }}</p>
            </div>
        </div>
    @else
        <p class="text-danger">Movie data is not available.</p>
    @endif
    
    <div class="mt-4">
        <a href="{{ route('movies.index') }}" class="btn btn-primary">Back to Movies</a>
    </div>
</div>
@endsection