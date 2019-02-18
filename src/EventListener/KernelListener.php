<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

class KernelListener
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
        $this->logger->info($response->getStatusCode());
    }

}
