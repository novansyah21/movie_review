<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Favorites</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>

@extends('layouts.app')

@section('content')

<body>
    <div class="container">
        <div class="mb-4">
            <a href="{{ route('locale.change', ['locale' => 'id']) }}" class="btn btn-primary" style="color: #000;">ID</a>
            <a href="{{ route('locale.change', ['locale' => 'en']) }}" class="btn btn-secondary" style="color: #000;">EN</a>
        </div>
        <h2 class="mb-4 text-white">My Favorites</h2>

        <div id="movie-list" class="row">
        @if (empty($favoriteMovies))
            <div class="col-12 text-center text-white">You have no favorite movies yet.</div>
        @else
            @foreach ($favoriteMovies as $movie)
                <div class="movie-card col-md-4 mb-4">
                    <div class="card">
                        <img src="{{ $movie['Poster'] }}" class="card-img-top img-fluid poster mb-2 rounded" alt="{{ $movie['Title'] }}">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $movie['Title'] }}<br><span>({{ $movie['Year'] }})</span>
                                <span class="imdb-rating ml-1">
                                    <i class="fas fa-star" style="color: #FFD700;"></i> {{ $movie['imdbRating'] ?? 'N/A' }}
                                </span>
                            </h5>
                            <div class="d-flex justify-content-center gap-2 mt-3">
                                <button class="btn btn-info text-white" data-id="{{ $movie['imdbID'] }}" onclick="showMovieDetails({{ json_encode($movie) }})">Details</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        </div>
    </div>

    <!-- Modal for Movie Details -->
    <div class="modal fade" id="movieModal" tabindex="-1" aria-labelledby="movieModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="movieModalLabel">Movie Details</h5>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>

        $(document).ready(function() {
            $('.movie-details-btn').on('click', function() {
                const imdbID = $(this).data('id');
                
                // Perform the AJAX request
                $.ajax({
                    url: `/movies/details/${imdbID}`,
                    method: 'GET',
                    success: function(response) {
                        showMovieDetails(response.movieData);
                    },
                    error: function(xhr) {
                        alert('Error fetching movie details. Please try again.');
                    }
                });
            });
        });

        function showMovieDetails(movieData) {
            // Example: Populate a modal with movie details
            $('#movieModal .modal-title').text(movieData.Title);
            $('#movieModal .modal-body').html(`
                <div class="row">
                    <div class="col-md-5">
                        <img src="${movieData.Poster}" class="img-fluid mb-2" alt="${movieData.Title}">
                    </div>
                    <div class="col-md-7">
                        <p><strong>Year:</strong> ${movieData.Year}</p>
                        <p><strong>Rated:</strong> ${movieData.Rated}</p>
                        <p><strong>Released:</strong> ${movieData.Released}</p>
                        <p><strong>Runtime:</strong> ${movieData.Runtime}</p>
                        <p><strong>Genre:</strong> ${movieData.Genre}</p>
                        <p><strong>Director:</strong> ${movieData.Director}</p>
                        <p><strong>Writer:</strong> ${movieData.Writer}</p>
                        <p><strong>Actors:</strong> ${movieData.Actors}</p>
                    </div>
                </div>
                <div class="mt-3">
                    <p><strong>Plot:</strong> ${movieData.Plot}</p>
                    <p><strong>Language:</strong> ${movieData.Language}</p>
                    <p><strong>Country:</strong> ${movieData.Country}</p>
                    <p><strong>Awards:</strong> ${movieData.Awards}</p>
                </div>
                <div class="mt-3">
                    <strong>Ratings:</strong>
                    <div class="row">
                        ${movieData.Ratings.map(rating => {
                            // Assign class based on the rating source
                            let cardClass = '';
                            if (rating.Source === "Internet Movie Database") {
                                cardClass = 'imdb-card';
                            } else if (rating.Source === "Rotten Tomatoes") {
                                cardClass = 'rottent-card';
                            } else if (rating.Source === "Metacritic") {
                                cardClass = 'metacritic-card';
                            }

                            return `
                                <div class="col-md-4">
                                    <div class="card mb-2 rating-card ${cardClass}">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">${rating.Source}</h6>
                                            <h3 class="card-text">${rating.Value}</h3>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('')}
                    </div>
                    <p><strong>IMDB Rating:</strong> ${movieData.imdbRating}</p>
                    <p><strong>IMDB Votes:</strong> ${movieData.imdbVotes}</p>
                    <p><strong>Box Office:</strong> ${movieData.BoxOffice}</p>
                    <p><strong>Production:</strong> ${movieData.Production}</p>
                    <p><strong>Website:</strong> <a href="${movieData.Website}" target="_blank">${movieData.Website}</a></p>
                </div>
            `);
            $('#movieModal').modal('show');
        }
    </script>
</body>
@endsection
</html>
