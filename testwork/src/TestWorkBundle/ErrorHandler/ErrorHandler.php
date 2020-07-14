<?php

namespace App\TestWorkBundle\ErrorHandler;

use App\TestWorkBundle\Exception\RequestValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ErrorHandler
 * @package App\TestWorkBundle\ErrorHandler
 */
class ErrorHandler
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * TestWorkApiErrorHandler constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Request    $request
     * @param \Exception $e
     * @return Response
     */
    public function handleError(Request $request, \Exception $e): Response
    {
        $this->logger->error(\get_class($e).' / '.$e->getMessage());

        switch (\get_class($e)) {
            case RequestValidationException::class:
                return new Response($e->getMessage());
            default:
                return new Response('test.work.api.error');
        }
    }
}