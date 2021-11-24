<?php
  require __DIR__ . '/vendor/autoload.php';
  require __DIR__ . '/src/functions.php';

  use Twig\Extra\Intl\IntlExtension;
  $loader = new \Twig\Loader\FilesystemLoader('templates/');
  $twig = new \Twig\Environment($loader, [
    'debug' => true,
  ]);
  $twig->addExtension(new \Twig\Extension\DebugExtension());
  $twig->addExtension(new IntlExtension());
  $twig->getExtension(\Twig\Extension\CoreExtension::class)->setTimezone('Europe/Oslo');
  
  $request = urldecode($_SERVER['REQUEST_URI']);

  $city = "stavanger";
  $date = date("d-m-Y");

  if (preg_match('/\/([a-å]+)\/?(\d{2}-\d{2}-\d{4})?\/?/', $request, $matches)) {
    $city = $matches[1];
    $date = $matches[2] ?? $date;
  }

 $date = $_POST["date"] ?? $date;

  switch (urldecode($request)) {
    case '/' :
    case '' :
      header('Location: /stavanger');
      break;
    case array_key_exists($city, CINEMAS) :
      echo $twig->render('kino.html', cinemaProps($city, $date));
      break;                      
    default:
      http_response_code(404);
      echo $twig->render('404.html');
      break;
  }