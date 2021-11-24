<?php
if ($_SERVER['SERVER_ADDR'] === '127.0.0.1' ) {
  define('WWW_DIR', '/Users/joakim/Utvikling/kinoprogram');
} else {
  define('WWW_DIR', '/home/5/k/kinoprogram/www');
}

const CINEMAS = array(
  "stavanger" => "ST",
  "sandnes" => "SA",
  "lillestrøm" => "LI",
  "moss" => "MO",
  "oslo" => "OS",
  "sandvika" => "SN",
  "sarpsborg" => "SB",
  "ski" => "SI" ,
  "skien" => "SK", 
  "sotra" => "SO",  
  "ålesund" => "AL" 
);

function cinemaProps($cinema, $day) {
  $result = array(
    'currentCinema' => $cinema,
    'cityCode' => CINEMAS[$cinema],
    'programme' => getMoviesJson($cinema, $day), 
    'cinemas' => CINEMAS,
    'currentUrl' => trim($_SERVER['PHP_SELF'], "/"),
    'currentDay' => $day
  );
  return $result;
}

function getMoviesJson($city, $day = "") {
  $results = json_decode(file_get_contents(WWW_DIR."/assets/movies/$city.json"));
  $days = (array) $results->days;
  if (isset($day)) {
    $movies = array_filter((array) $results->movies, function($value) use($day) {
      foreach ($value->shows as $show) {
        if (date("d-m-Y", strtotime($show->utc)) === $day) {
          return date("d-m-Y", strtotime($show->utc)) === $day;
        }
      }
    });
    
    return array(
      "movies" => (array) $movies,
      "days" => $days
    );
  }
  return array(
    "movies" => (array) $results->movies,
    "days" => $days
  );
}

