<?php
require __DIR__ . '/functions.php';

$today = date('Y-m-d');

$locations = get_locations();

foreach ($locations as $location) {

    logg("🍿 Henter filmer for " . $location['name'] . "...");

  $get_movies = json_decode(file_get_contents('https://skynet.filmweb.no/MovieInfoQs/graphql/?query=query($location:String){movieQuery{getCurrentMovies(location:$location,removePastShows:true){mainVersionId%20title%20genres%20lengthInMinutes%20rating%20poster{versions{url}}%20sanityImagePosterUrl%20shows{firmName%20showStart%20screenName%20ticketSaleUrl%20theaterName%20movieVersionId}}}}&variables={%22location%22:%22' . urlencode($location["name"]) . '%22}'));
  $results = $get_movies->data->movieQuery->getCurrentMovies;

  if ($get_movies && !empty($results)) {

    $location_path = WWW_DIR . '/assets/locations/' . $location["slug"] . '.json';

    file_put_contents($location_path, json_encode($results));

    foreach($results as $movie) {

      if (!empty($movie->sanityImagePosterUrl)) {

        $filename = WWW_DIR . "/assets/images/".$movie->mainVersionId.".webp";

        if (!file_exists($filename)) {

            logg("   🎑 Laster ned plakat til " . $movie->title . "...");

            file_put_contents($filename, file_get_contents(preg_replace('/auto=format/', 'fm=webp', $movie->sanityImagePosterUrl)));

        }

      } else {
        logg("   ❌ Fikk ikke tak i plakat til " . $movie->title . "...");
      }

    }

  }

}

logg("✅ Ferdig! Nå skal /assets være full av godis.");
