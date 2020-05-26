<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

//php bin/console debug:container App\Service\Greeting
//Autowired - means that all dependencies are automatically injected
//LoggerInterface - service is automatically found and injected
class Greeting {

    private $logger;
    private $message;

    public function __construct(LoggerInterface $logger, string $message)
    {
        $this->logger = $logger;
        $this->message = $message;
    }

    public function greet(string $name): string{
        //return "Hello " . $name;
        $this->logger->info("Greeted $name");
        return "{$this->message} $name";
    }
}