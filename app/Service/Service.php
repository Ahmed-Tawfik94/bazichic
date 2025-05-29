<?php

namespace App\Service;

class Service
{
    protected $db;
    protected $logger;

    public function __construct($db,$logger)
    {
        $this->db = $db;
        $this->logger = $logger;
    }
}