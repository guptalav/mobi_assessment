<?php

namespace Mobi\Assessment\Classes\Connection;

use Mobi\Assessment\Exceptions\ConnectionException;
use SimpleXMLElement;

class POSXMLConnect {
    public function __construct() {
        print "In BaseClass constructor";
    }

    /**
     * Reads the xml payload from file
     *
     * @return SimpleXMLElement
     * @throws ConnectionException
     */
    public function getData(): SimpleXMLElement {
        try {
            // ideally this should return a certain type so any
            // class extending will not care on the details of usage
            return simplexml_load_file("assets/data/payload.xml");
        } catch (\Exception $e) {
            throw new ConnectionException('Cannot fetch the xml document');
        }
    }
}
