<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client; // For HTTP requests
use Illuminate\Http\Request; // For Request
use Illuminate\Support\Facades\Log; // For logging
use Illuminate\Support\Facades\Auth;
use App\Movie;
use App\Favorite;

class MovieController extends Controller
{
    public function index(Request $request) 
    {
        // Call the fetchMovies method to get default movies
        $moviesResponse = $this->fetchMovies($request);

        // var_dump($moviesResponse);
        // die();
        // Check if the response is an instance of JsonResponse
        if ($moviesResponse instanceof \Illuminate\Http\JsonResponse) {
            $moviesData = json_decode($moviesResponse->getContent(), true);
            
            // Handle error if present in the movies data
            if (isset($moviesData['error'])) {
                return response()->json(['error' => 'Unexpected response format'], 500);
            }
            
            $movies = $moviesData['movies'] ?? []; // Use the 'movies' key or fallback to empty array
        } else {
            // If fetchMovies does not return a JsonResponse, handle it as an array
            $movies = $moviesResponse; // Assuming fetchMovies returns an array directly
        }

        // Fetch the user's favorite movies
        $userId = auth()->id(); // Get the authenticated user's ID
        $favoriteMovies = Favorite::where('user_id', $userId)->pluck('imdbID')->toArray();

        // Pass movies and favorite movies to the view
        return view('movies.index', [
            'defaultMovies' => $movies,
            'favoriteMovies' => $favoriteMovies,
        ]);
    }

    public function changeLocale($locale)
    {
        Session::put('app_locale', $locale);
        return redirect()->back();
    }

    public function fetchMovies(Request $request)
    {
        $client = new Client();
        $query = $request->query('t');
        $year = $request->query('y');
        $page = $request->query('page');

        try {

            if ($query) {
                // Search movies by title
                $response = $client->get('http://www.omdbapi.com/', [
                    'query' => [
                        't' => $query,
                        'y' => $year,
                        'page' => $page,
                        'apikey' => "e81649a7",
                        ]
                    ]);
                    
                    $moviesData = json_decode($response->getBody()->getContents(), true);
                    
                    // Check if the API response is valid
                    if (!isset($moviesData['Response']) || $moviesData['Response'] !== 'True') {
                        return response()->json(['error' => 'No movies found or invalid response'], 404);
                    }
                    
                    return response()->json(['movies' => $moviesData]);
            } else {
                // Load default movies by ID
                $defaultMovies = ['tt3896198', 'tt1285016', 'tt0088245', 'tt0120338', 'tt0111161', 'tt0133093', 'tt1375666', 'tt1853728', 'tt0816692', 'tt0137523'];
                $movies = [];

                foreach ($defaultMovies as $id) {
                    try {
                        $response = $client->get('http://www.omdbapi.com/', [
                            'query' => [
                                'i' => $id,
                                'apikey' => "e81649a7",
                            ]
                        ]);
                
                        $movieData = json_decode($response->getBody()->getContents(), true);

                
                        // Log each movie data response
                        Log::info('Movie data:', ['movieData' => $movieData]);
                
                        if (isset($movieData['Title'])) {
                            $movies[] = $movieData; // Add valid movie data to the array
                        }
                    } catch (\GuzzleHttp\Exception\RequestException $e) {
                        Log::error('Request error for movie ID '.$id.': '.$e->getMessage());
                    }
                }

                return response()->json(['movies' => $movies]);
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Log the detailed error
            Log::error('Request error: ' . $e->getMessage());
            return response()->json(['error' => 'Could not fetch movies.'], 500);
        } catch (\Exception $e) {
            // Log any other exception
            Log::error('Error fetching movies: ' . $e->getMessage());
            return response()->json(['error' => 'Could not fetch movies.'], 500);
        }
    }

    public function searchMovies(Request $request)
    {
        $client = new Client();
        $query = $request->query('s');
        $year = $request->query('y');
        $page = $request->query('page');

        try {
            // Search movies by title
            $response = $client->get('http://www.omdbapi.com/', [
                'query' => [
                    's' => $query,
                    'y' => $year,
                    'page' => $page,
                    'apikey' => "e81649a7",
                ]
            ]);
            
            $moviesData = json_decode($response->getBody()->getContents(), true);
            
            // Check if the API response is valid
            if (!isset($moviesData['Response']) || $moviesData['Response'] !== 'True') {
                return response()->json(['error' => 'No movies found or invalid response'], 404);
            }
            
            return response()->json([
                'movies' => $moviesData['Search'] ?? [], // Correctly accessing the Search key
                'totalResults' => $moviesData['totalResults'] ?? 0, // Correctly accessing totalResults
            ]);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Log the detailed error
            Log::error('Request error: ' . $e->getMessage());
            return response()->json(['error' => 'Could not fetch movies.'], 500);
        } catch (\Exception $e) {
            // Log any other exception
            Log::error('Error fetching movies: ' . $e->getMessage());
            return response()->json(['error' => 'Could not fetch movies.'], 500);
        }
    }

    
    public function addToFavorites(Request $request, $imdbID)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        try {
            $userId = auth()->id();
            
            // Check if the movie is already in favorites
            $favorite = Favorite::where('user_id', $userId)->where('imdbID', $imdbID)->first();

            if ($favorite) {
                // If it exists, delete it (un-favorite)
                $favorite->delete();
                return response()->json(['success' => true, 'action' => 'removed']); // Indicate that it was removed
            } else {
                // If it doesn't exist, create it (add to favorites)
                $favorite = new Favorite();
                $favorite->user_id = $userId;
                $favorite->imdbID = $imdbID;
                $favorite->save();
                return response()->json(['success' => true, 'action' => 'added']); // Indicate that it was added
            }
        } catch (\Exception $e) {
            Log::error('Error adding/removing favorite: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding/removing to favorites.',
                'error' => $e->getMessage() // Include the exception message for debugging
            ], 500);
        }        
    }

    public function details($imdbID)
    {
        $client = new Client();
        $response = $client->get("http://www.omdbapi.com/?i={$imdbID}&apikey=e81649a7");
        $movieData = json_decode($response->getBody(), true);

        \Log::info('Movie Data:', $movieData); // Log the movie data

        if ($movieData['Response'] === 'True') {
            // Return JSON response for AJAX request
            return response()->json(['movieData' => $movieData]);
        }

        return response()->json(['error' => 'Movie not found.'], 404);
    }

    public function favorites()
    {
        $user = Auth::user();

        // Ensure user is authenticated
        if (!$user) {
            return redirect()->route('login'); // Redirect to login if not authenticated
        }

        // Retrieve the user's favorite movies based on the `imdbID`
        $favorites = Favorite::where('user_id', $user->id)->get();
        $favoriteMovies = []; // Initialize an array for movie details

        // Fetch movie details based on the imdbID from the favorites
        foreach ($favorites as $favorite) {
            $response = file_get_contents("http://www.omdbapi.com/?i={$favorite->imdbID}&apikey=e81649a7");
            $movieData = json_decode($response, true);
            if ($movieData['Response'] === 'True') {
                $favoriteMovies[] = $movieData; // Add the movie data to the array
            }
        }

        return view('movies.favorite', compact('favoriteMovies'));
    }


}
