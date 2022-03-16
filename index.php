<?php

use Services\OtodomService;

require 'vendor/autoload.php';
require 'Services/OtodomService.php';

$results = [];


if(isset($_GET['url'])) {
    $otodomService = new OtodomService();
    
    try {
        $results = $otodomService->scrapSingleAuction($_GET['url']);

        if($results) {
            foreach($results as $key => $result) {
                echo $key. ': ' . $result . '<br />';
            }
        } else {
            echo 'Nieprawidlowy url!';
        }
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
}

?>

<form action="" style="margin-top:40px; display:block">
    <input type="text" name="url" placeholder="otodom url">
    <input type="submit">
</form>