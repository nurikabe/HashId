<?php


namespace Pgs\HashIdBundle\ParametersProcessor\Factory;


use Pgs\HashIdBundle\Annotation\Hash;
use Pgs\HashIdBundle\AnnotationProvider\ControllerAnnotationProvider;
use Pgs\HashIdBundle\ParametersProcessor\ParametersProcessorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DecodeParametersProcessorFactory extends AbstractParametersProcessorFactory
{
    /**
     * @var ParametersProcessorInterface
     */
    protected $decodeParametersProcessor;

    public function __construct(
        ControllerAnnotationProvider $annotationProvider,
        ParametersProcessorInterface $noOpParametersProcessor,
        ParametersProcessorInterface $decodeParametersProcessor
    )
    {
        parent::__construct($annotationProvider, $noOpParametersProcessor);
        $this->decodeParametersProcessor = $decodeParametersProcessor;
    }

    protected function getDecodeParametersProcessor(): ParametersProcessorInterface
    {
        return $this->decodeParametersProcessor;
    }

    public function createControllerDecodeParametersProcessor($controller, string $method)
    {
        $annotation = null;
        if ($controller instanceof Controller){
            /** @var Hash $annotation */
            $annotation = $this->getAnnotationProvider()->getFromObject($controller, $method, Hash::class);
        }
        return $annotation !== null ? $this->getDecodeParametersProcessor()->setParametersToProcess($annotation->getParameters()) : $this->getNoOpParametersProcessor();
    }
}