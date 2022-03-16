<?php

namespace Interfaces;

require 'vendor/autoload.php';

interface ScrapInterface {

    function scrapSingleAuction(string $url);
    function checkUrl(string $url);
}