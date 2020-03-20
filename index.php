<?php
// Show Errors
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('max_execution_time', 0);

// Load Libraries
require_once 'vendor/autoload.php';

use DiDom\Document;
$base_url = 'https://appshopper.com/mac/all/prices/free/';
$html = new Document($base_url, true);
$number = 1;
foreach ($html->find('.main-content .section') as $body) {
    foreach ($body->find('.details h2') as $title) {
        print_r('<br>');
        $title = $title->text();
        print_r($title);
    }
    foreach ($body->find('.actions .price') as $price) {
        $price = $price->text();
        $price = str_replace('was', '', $price);
        $price = explode(' ', $price);
        $free = $price[0];
        $price = $price[2];
        if ($free == 'Free') {
            print_r($price);
            print_r('<br>');
        }
    }
    foreach ($body->find('.actions .buttons.desktop a') as $href) {
        $href = $href->href;
        $href=str_replace('apple.com/nl/','apple.com/us/',$href);
        $url = 'https://appshopper.com' . $href;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $a = curl_exec($ch);
        $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        echo $url;
        print_r('<br>');
        $app_url = $url;
        $appstore = new Document($app_url, true);
        $first = 0;
        foreach ($appstore->find('.product-hero__media picture source') as $image) {
            $image = $image->srcset;
            $image = explode(' 1x', $image);
            $image = $image[0];
            print_r($image);
            print_r('<br>');
            $url_image = $image;
            $img_name = './' . $title . '.png';
            file_put_contents($img_name, file_get_contents($url_image));
            $first++;
            if ($first == 1) {
                break;
            }
        }
    }
    $number++;
    if ($number >= 5) {
        break;
    }
}
