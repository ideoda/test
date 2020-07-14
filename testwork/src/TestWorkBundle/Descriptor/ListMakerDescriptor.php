<?php

namespace App\TestWorkBundle\Descriptor;

use App\TestWorkBundle\Enum\MethodEnum;

/**
 * Class ListMakerDescriptor
 * @package App\TestWorkBundle\Descriptor
 */
class ListMakerDescriptor
{
    /**
     * @var string
     */
    protected $handle1;

    /**
     * @var string
     */
    protected $handle2;

    /**
     * @var string
     */
    protected $method;

    /**
     * @return string
     */
    public function getHandle1(): string
    {
        return $this->handle1;
    }

    /**
     * @param string $handle1
     */
    public function setHandle1(string $handle1): void
    {
        $this->handle1 = $handle1;
    }

    /**
     * @return string
     */
    public function getHandle2(): string
    {
        return $this->handle2;
    }

    /**
     * @param string $handle2
     */
    public function setHandle2(string $handle2): void
    {
        $this->handle2 = $handle2;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }




}