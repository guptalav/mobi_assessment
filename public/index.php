<?php
require 'vendor/autoload.php';

use Mobi\Assessment\Classes\Connection\POSXMLConnect;
use Mobi\Assessment\Classes\POS;
use \Mobi\Assessment\Exceptions\MobiException;

$pos = new POS(new POSXMLConnect());

try {
    $products   = $pos->getProducts();
    $categories = $pos->getCategories();
    $modifiers  = $pos->getModifiers();
} catch (MobiException $e) {}
