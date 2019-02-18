<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Mailer\Mailer;
use Psr\Log\LoggerInterface;

class ExceptionListener {

    private $mailer;
    private $logger;
    
    public function __construct(Mailer $mailer, LoggerInterface $logger) {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public function onKernelException(GetResponseForExceptionEvent $event) {

        $exception = $event->getException();
        $message = sprintf(
                'Ocurrio un error: %s with code: %s', $exception->getMessage(), $exception->getCode()
        );

        if ($exception instanceof HttpExceptionInterface) {
            $status = $exception->getStatusCode();
        } else {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        $this->logger->alert($message);
        $this->mailer->sendExceptionEmail($message, $status);
        
    }

}
