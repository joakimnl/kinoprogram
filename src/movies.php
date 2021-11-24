<?php
require __DIR__ . '/functions.php';

foreach (CINEMAS as $displayName => $cityAlias) {
  $curloptReferer = "https://www.odeonkino.no/kinoprogram/";

  $getMovieShowsRequest = curl_init();
  curl_setopt($getMovieShowsRequest, CURLOPT_URL, "https://www.odeonkino.no/api/v2/show/stripped/no/1/1024?filter.countryAlias=no&filter.cityAlias=".$cityAlias);  
  curl_setopt($getMovieShowsRequest, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($getMovieShowsRequest, CURLOPT_REFERER, $curloptReferer);
    
  $movieShowsResult = json_decode(curl_exec($getMovieShowsRequest));
  curl_close($getMovieShowsRequest);

  $moviesWithShows = [];

  $daysWithShows = [];

  foreach ($movieShowsResult->items as $shows) {
    if (array_key_exists($shows->mId, $moviesWithShows)) {
      array_push($moviesWithShows[$shows->mId], (array) $shows);
    } else {
      $moviesWithShows[$shows->mId][] = (array) $shows;
    }
    $day = date("d-m-Y", strtotime($shows->utc));
    if (!in_array($day, $daysWithShows)) {
      array_push($daysWithShows, date("d-m-Y", strtotime($shows->utc)));
    }
  }
  
  $movieList = implode(",", array_keys($moviesWithShows));
  
  $getMoviesRequest = curl_init();
  curl_setopt($getMoviesRequest, CURLOPT_URL, "https://www.odeonkino.no/api/v2/movie/no?movieNcgIds=".$movieList);  
  curl_setopt($getMoviesRequest, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($getMoviesRequest, CURLOPT_REFERER, $curloptReferer);
    
  $moviesResult = json_decode(curl_exec($getMoviesRequest));
  curl_close($getMoviesRequest);

  $movies = [];

  foreach ($moviesResult->items as $movie) {
    if (!array_key_exists($movie->ncgId, $movies)) {
      $movies[$movie->ncgId] = array(
        "mId" => $movie->ncgId,
        "originalTitle" => $movie->originalTitle,
        "title" => $movie->title,
        "posterUrl" => preg_replace('/width=\d+/', "width=500", $movie->posterUrl),
        "genres" => $movie->genres,
        "rating" => $movie->rating->displayName,
        "shows" => $moviesWithShows[$movie->ncgId],
        "slug" => $movie->slug
      );
      $filename = WWW_DIR . "/assets/images/".$movie->ncgId.".jpg";
      if (!file_exists($filename)) {
        file_put_contents($filename, file_get_contents($movies[$movie->ncgId]["posterUrl"]));
      }
    }
  }

  uasort($movies, function($a, $b) {
    return strcmp($a["title"], $b["title"]);
  });

  $results = array(
    "movies" => $movies,
    "days" => $daysWithShows
  );

  file_put_contents(WWW_DIR . '/assets/movies/' . strtolower($displayName).".json", json_encode($results));
}