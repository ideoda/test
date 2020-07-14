<?php

namespace App\TestWorkBundle\Controller;

use App\TestWorkBundle\Descriptor\ListMakerDescriptor;
use App\TestWorkBundle\ErrorHandler\ErrorHandler;
use App\TestWorkBundle\Exception\RequestValidationException;
use App\TestWorkBundle\ListMaker\ListMaker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TestController
 * @package App\TestWorkBundle\Controller
 */
class Controller extends AbstractController
{
    /**
     * @var ErrorHandler
     */
    protected $errorHandler;

    /**
     * @var ListMaker
     */
    protected $listMaker;

    /**
     * Controller constructor.
     * @param ErrorHandler $errorHandler
     * @param ListMaker    $listMaker
     */
    public function __construct(ErrorHandler $errorHandler, ListMaker $listMaker)
    {
        $this->errorHandler = $errorHandler;
        $this->listMaker    = $listMaker;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function endPointOne(Request $request): Response
    {
        try {
            $this->validateRequest($request);

            $descriptor = $this->createDescriptor($request);

            $responseData = $this->listMaker->makeList($descriptor);
        }
        catch (\Exception $e) {
            $responseData = $this->errorHandler->handleError($request, $e);
        }

        return new Response($responseData);
    }

    /**
     * @param Request $request
     * @throws RequestValidationException
     */
    protected function validateRequest(Request $request):void{
        $handle1 = $request->get('handle1');
        $handle2 = $request->get('handle2');
        $method = $request->get('method');

        if ($handle1 === NULL || (!\is_string($handle1))) {
            throw new RequestValidationException('parameter.handle1.not.valid');
        }

        if ($handle2 === NULL || (!\is_string($handle2))) {
            throw new RequestValidationException('parameter.handle2.not.valid');
        }

        if ($handle1 === $handle2) {
            throw new RequestValidationException('parameter.handle1+handle2.not.valid');
        }

        if ($method !== null && !\in_array($method, ['mod', 'fib'])) {
            throw new RequestValidationException('parameter.method.not.valid');
        }
    }

    /**
     * @param Request $request
     * @return ListMakerDescriptor
     */
    protected function createDescriptor(Request $request): ListMakerDescriptor
    {
        $descriptor = new ListMakerDescriptor();
        $descriptor->setHandle1($request->get('handle1'));
        $descriptor->setHandle2($request->get('handle2'));
        $descriptor->setMethod($request->get('method') ?? 'fib');

        return $descriptor;
    }
}