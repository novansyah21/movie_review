
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <title>Movie List</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>

    <!-- Bootstrap Bundle with Popper.js -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-beta1/js/bootstrap.bundle.min.js"></script>

</head>

<style>
    .results-info {
        background-color: #333; /* Dark background for contrast */
        color: #ffc107; /* Yellow text color to match your theme */
        border-radius: 8px;
        padding: 8px 12px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .results-info i {
        color: #ffc107; /* Matching icon color */
        font-size: 1.2rem;
    }

    #results-count {
        font-weight: bold;
        font-size: 1.1rem;
    }
</style>

@extends('layouts.app')

@section('content')
<body>
    <div class="container">
        <div class="mb-4">
            <a href="{{ route('locale.change', ['locale' => 'id']) }}" class="btn btn-primary" style="color: #000;">ID</a>
            <a href="{{ route('locale.change', ['locale' => 'en']) }}" class="btn btn-secondary" style="color: #000;">EN</a>
        </div>
        <div class="search-container">
            <input type="text" id="search" placeholder="Search for movies..." style="margin-right: 3px;">
            <select id="year-filter">
                @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
            <button id="search-button"><b>Search</b></button>
        </div>

        <div class="results-info bg-dark text-warning rounded p-2 mb-3 d-flex align-items-center">
            <i class="fas fa-film me-2"></i>
            <span id="results-count" class="fw-bold fs-5">Find your Movie!</span>
        </div>

        <div id="movie-list" class="row">
        @foreach ($defaultMovies as $movie)
            <div class="movie-card col-md-4 mb-4">
                <div class="card">
                    <button class="btn btn-favorite position-absolute top-right @if(in_array($movie['imdbID'], $favoriteMovies)) favorited @endif" data-id="{{ $movie['imdbID'] }}">
                        <i class="fas fa-heart"></i>
                    </button>
                    <img data-src="{{ $movie['Poster'] }}" class="card-img-top img-fluid poster mb-2 rounded lazyload" alt="{{ $movie['Title'] }}" loading="lazy">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $movie['Title'] }}<br><span>({{ $movie['Year'] }})</span>
                            <span class="imdb-rating ml-1">
                                <i class="fas fa-star" style="color: #FFD700;"></i> {{ $movie['imdbRating'] }}
                            </span>
                        </h5>
                        <div class="d-flex justify-content-center gap-2 mt-3">
                            <button class="btn btn-info text-white movie-details-btn" data-id="{{ $movie['imdbID'] }}">
                                Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        </div>

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

        <div id="loading">Loading...</div>
        <div id="no-results">No movies found. Try another search!</div>
    </div>

    <script>
        let page = 1; 
        let bySearchPage = 1;
        let loading = false; 
        let scrollCount = 0; // Initialize a counter for scrolls
        const fetchMoviesUrl = 'http://www.omdbapi.com/';
        const currentYear = new Date().getFullYear();

        $(document).ready(function() {

            $('#search-button').on('click', function() {
                const query = $('#search').val();
                const year = $('#year-filter').val();

                // Check if query length is at least 3 characters
                if (query.length < 3) {
                    alert('Please enter at least 3 characters to search.');
                    return;
                }

                searchMovies(query, year);
            });

            $('#search').on('keypress', function(e) {
                if (e.which == 13) {
                    const query = $(this).val();
                    const year = $('#year-filter').val();

                    // Check if query length is at least 3 characters
                    if (query.length < 3) {
                        alert('Please enter at least 3 characters to search.');
                        return;
                    }

                    searchMovies(query, year);
                }
            });
        });
            
        $(document).on('click', '.movie-details-btn', function() {
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

        function searchMovies(query, year) {
            query = query.trim();
            scrollCount = 0; // Reset scroll count on new search
            bySearchPage++;
            $('#no-results').hide(); // Hide no results initially
            loadMoviesBySearch(bySearchPage, query, year);  
        }

        $(window).on('scroll', function () {
            // Check if the user has scrolled to the bottom of the page
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 10 && !loading) {
                if (scrollCount >= 26) {
                    scrollCount = 0;
                    currentYear = currentYear - 1;
                }

                scrollCount++;
                const letter = String.fromCharCode(96 + scrollCount); // Convert scroll count to corresponding letter (1 -> 'a', 2 -> 'b', etc.)
                page++;
                loadMovies(page, letter);
                if(bySearchPage > 1){
                    searchMovies(query, year);
                }
            }
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


        function loadMovies(page, letter) {
            loading = true;
            $('#loading').show();
            
            $.ajax({
                url: `{{ route('movies.fetch') }}?t=${letter}&y=${currentYear}&page=${page}`,
                method: 'GET',
                success: function(response) {
                    if (page === 1) $('#movie-list').empty();

                    const movie = response.movies
                    $('#movie-list').append(`
                        <div class="movie-card col-md-4 mb-4">
                            <div class="card position-relative">
                                <button class="btn btn-favorite position-absolute top-right" data-id="${movie.imdbID}">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <img data-src="${movie.Poster}" class="card-img-top img-fluid poster mb-2 rounded lazyload" alt="${movie.Title}" loading="lazy">
                                <div class="card-body text-center">
                                    <h5 class="card-title">
                                        ${movie.Title}<br>
                                        <span>(${movie.Year})</span>
                                        <span class="imdb-rating ml-1">
                                            <i class="fas fa-star" style="color: #FFD700;"></i> ${movie.imdbRating || 'N/A'}
                                        </span>
                                    </h5>
                                    <div class="d-flex justify-content-center gap-2 mt-3">
                                        <button class="btn btn-info text-white movie-details-btn" data-id="${movie.imdbID}">
                                            Details
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);

                },
                error: function(xhr) {
                    $('#no-results').show();
                },
                complete: function() {
                    loading = false;
                    $('#loading').hide();
                }
            });
        }

        function loadMoviesBySearch(bySearchPage, query, year) {
            loading = true;
            $('#loading').show();

            $.ajax({
                url: `{{ route('movies.searchMovies') }}?s=${query}&y=${year}&page=${bySearchPage}`,
                method: 'GET',
                success: function(response) {
                    $('#movie-list').empty(); // Clear the movie list for the first page

                    if (response.totalResults) {
                        $('#results-count').text(`Total Results: ${response.totalResults}`);
                    } else {
                        $('#results-count').text('No results found');
                    }

                    // Ensure response.movies is an array
                    if (response.movies && Array.isArray(response.movies)) {
                        response.movies.forEach(movieSearch => {
                            $('#movie-list').append(`
                                <div class="movie-card col-md-4 mb-4">
                                    <div class="card position-relative">
                                        <button class="btn btn-favorite position-absolute top-right" data-id="${movieSearch.imdbID}">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                        <img data-src="${movieSearch.Poster}" class="card-img-top img-fluid poster mb-2 rounded lazyload" alt="${movieSearch.Title}" loading="lazy">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">
                                                ${movieSearch.Title}<br>
                                                <span>(${movieSearch.Year})</span>
                                                <span>(${movieSearch.Type})</span>
                                            </h5>
                                            <div class="d-flex justify-content-center gap-2 mt-3">
                                                <button class="btn btn-info text-white movie-details-btn" data-id="${movieSearch.imdbID}">
                                                    Details
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `);
                        });
                    } else {
                        $('#no-results').show(); // Show "no results" if no movies are returned
                    }
                },
                error: function(xhr) {
                    $('#no-results').show(); // Handle errors, show no results
                },
                complete: function() {
                    loading = false; // Reset loading state
                    $('#loading').hide(); // Hide loading indicator
                }
            });
        }


        // Handle click event for adding/removing favorites
        $(document).on('click', '.btn-favorite', function() {
            const button = $(this);
            const imdbID = button.data('id');

            $.ajax({
                url: `/movies/${imdbID}/favorite`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.action === 'added') {
                        button.addClass('favorited');
                    } else if (response.action === 'removed') {
                        button.removeClass('favorited');
                    }
                },
                error: function(xhr) {
                    alert('Error adding/removing favorites!');
                }
            });
        });

    </script>
</body>
@endsection
</html>
