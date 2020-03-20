<?php
// Show Errors
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('max_execution_time', 0);

// Load Libraries
require_once 'vendor/autoload.php';

use DiDom\Document;
$base_url = 'https://appshopper.com/iphone/all/prices/free/';
$html = new Document($base_url, true);
$number = 1;
$myApps = [];
foreach ($html->find('.main-content .section') as $body) {
    foreach ($body->find('.details h2') as $title) {
        $title = $title->text();
    }
    foreach ($body->find('.actions .price') as $price) {
        $price = $price->text();
        $price = str_replace('was', '', $price);
        $price = explode(' ', $price);
        $free = $price[0];
        $price = $price[2];
        $price = str_replace('$', '', $price);
        $western_arabic = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        $eastern_arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
        $price = str_replace($western_arabic, $eastern_arabic, $price);
    }
    foreach ($body->find('.actions .buttons.desktop a') as $href) {
        $href = $href->href;
        $href = str_replace('apple.com/nl/app', 'apple.com/us/app', $href);
        $url = 'https://appshopper.com' . $href;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $a = curl_exec($ch);
        $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        $app_url = $url;
        $appstore = new Document($app_url, true);
        $first = 0;
        foreach ($appstore->find('.product-hero__media picture source') as $image) {
            $image = $image->srcset;
            $image = explode(' 1x', $image);
            $image = $image[0];
            $url_image = $image;
            $img_name = '../../../../../var/www/img.macneed.ir/freeapps/' . $title . '.png';
            // $img_name = './' . $title . '.png';
            file_put_contents($img_name, file_get_contents($url_image));
            $im = imagecreatefrompng('../../../../../var/www/img.macneed.ir/freeapps/' . $title . '.png');
            // $im = imagecreatefrompng('./' . $title . '.png');
            $srcWidth = imagesx($im);
            $srcHeight = imagesy($im);
            $nWidth = 81;
            $nHeight = 81;
            $newImg = imagecreatetruecolor($nWidth, $nHeight);
            imagealphablending($newImg, false);
            imagesavealpha($newImg, true);
            $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
            imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
            imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight,
                $srcWidth, $srcHeight);
            imagepng($newImg,'../../../../../var/www/img.macneed.ir/freeapps/' . $title . '.png');
            // imagepng($newImg, './' . $title . '.png');
            $first++;
            if ($first == 1) {
                break;
            }

        }
    }
    $myApps[$number] = array(
        "title" => $title,
        "price" => $price,
        "url" => $app_url,
        "img_url" => '//img.macneed.ir/freeapps/' . $title . '.png',
    );
    $number++;
    if ($number >= 5) {
        break;
    }
}