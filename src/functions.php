<?php
define('WWW_DIR', dirname(dirname(__FILE__)));

function get_locations() {
  $locations = json_decode(file_get_contents(WWW_DIR . '/src/locations.json'), true);
  uasort($locations, function($a, $b) {
    return strcmp($a["name"], $b["name"]);
  });
  return $locations;
}

function get_single_location($slug) {
  $locations = get_locations();
  $location = array_filter($locations, function($location) use($slug){
    return $location["slug"] === $slug;
  });

  if (!empty($location)) {
    return array_values($location)[0];
  }

  return false;
}

function location_info($location, $date) {
  $movies = get_movies($location["slug"]);
  return array(
    'location_name' => $location["name"],
    'location_slug' => $location["slug"],
    'programme' => $movies,
    'date' => $date,
    'dates_with_movies' => get_dates_with_movies($movies),
    'form_url' => trim($_SERVER['PHP_SELF'], "/"),
    'locations' => get_locations()
  );
}

function get_movies($slug) {
  $file = WWW_DIR.'/assets/locations/' . $slug . '.json';
  if (file_exists($file)) {
    $results = json_decode(file_get_contents($file));
    return $results;
  } else {
    return false;
  }
}

function get_dates_with_movies($movies) {
  $dates_with_movies = [];

  if ($movies) {
    foreach ($movies as $movie) {
      foreach ($movie->shows as $show) {
        $date = date("d-m-Y", strtotime($show->showStart));
        if (!in_array($date, $dates_with_movies)) {
          array_push($dates_with_movies, $date);
        }
      }
    }
  }

  usort($dates_with_movies, function($a, $b) {
    return strtotime($a) - strtotime($b);
  });
  
  return $dates_with_movies;
}

function get_slug($location) {
  return str_replace(" ", "-", mb_strtolower($location));
};

function logg($message)
{
    $message = date("H:i:s") . " $message".PHP_EOL;
    print($message);
    flush();
}
