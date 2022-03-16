<?php

namespace Services;

require 'vendor/autoload.php';
require 'Interfaces/ScrapInterface.php';

use DOMDocument;
use DOMXPath;
use Interfaces\ScrapInterface;

class OtodomService implements ScrapInterface {

    private $toFetch = [
        'area' => ['htmlElement' => 'div', 'indexBy' => 'aria-label', 'index'=> 'Powierzchnia'],
        'land_area' => ['htmlElement' => 'div', 'indexBy' => 'aria-label', 'index'=> 'Powierzchnia działki'],
        'floor' => ['htmlElement' => 'div', 'indexBy' => 'aria-label', 'index'=> 'Piętro'],
        'price' => ['htmlElement' => 'strong', 'indexBy' => 'aria-label', 'index'=> 'Cena'],
        'advertiser_type' => ['htmlElement' => 'div', 'indexBy' => 'aria-label', 'index'=> 'Typ ogłoszeniodawcy'],
    ];

    public function scrapSingleAuction (string $url): ?array {
        $httpClient = new \GuzzleHttp\Client();

        if(!$this->checkUrl($url)) {
            return null;
        }

        $response = $httpClient->get($url);
        $htmlString = (string) $response->getBody();
    
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $doc->loadHTML($htmlString);
        $xpath = new DOMXPath($doc);
    
        foreach($this->toFetch as $row) {
            $titles = $xpath->evaluate('//'.$row['htmlElement'].'[@'.$row['indexBy'].'="'.$row['index'].'"]');
    
            foreach ($titles as $key => $title) {
    
                $value = str_replace($row['index'], '', $title->textContent);
                $value = preg_replace('/^.*}\s*/', '', $value);
                
                $results[$row['index']] = str_replace($row['index'], '', $value) . ' <br />';
            }
        }

        return $results;
    }

    function checkUrl(string $url): bool {
        if(!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $headers = @get_headers($url);
        $httpStatus = intval(substr($headers[0], 9, 3));

        if ($httpStatus < 400 || strpos($url, 'otodom.pl') === false)
        {
            return true;
        }

        return false;
    }
}