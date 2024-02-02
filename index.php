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

$slug = "stavanger";

$datetime = new DateTime();
$date = $_POST["date"] ?? $datetime->setTimezone(new DateTimeZone("Europe/Oslo"))
    ->format('d-m-Y');

if (preg_match('/\/([A-Øa-ø()\s|-]+)\/?(\d{2}-\d{2}-\d{4})?\/?/', $request, $matches)) {
    $slug = $matches[1];
    $date = $matches[2] ?? $date;
}

$location = get_single_location($slug);

switch (urldecode($request)) {
    case '/' :
    case '' :
        echo $twig->render('kino.html', location_info($location, $date));
        break;
    case is_array($location) :
        echo $twig->render('kino.html', location_info($location, $date));
        break;
    default:
        http_response_code(404);
        echo $twig->render('404.html');
        break;
}
