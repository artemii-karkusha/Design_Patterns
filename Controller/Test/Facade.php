<?php
/**
 * @author Artemii Karkusha
 * @copyright Copyright (c) (https://www.linkedin.com/in/artemiy-karkusha/)
 */

declare(strict_types=1);

namespace ArtemiiKarkusha\DesignPatterns\Controller\Test;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use ArtemiiKarkusha\DesignPatterns\Api\Facade\CalculatorFacadeInterface;

class Facade implements HttpGetActionInterface
{
    /**
     * @param ResultFactory $resultFactory
     * @param CalculatorFacadeInterface $calculatorFacade
     */
    public function __construct(
        private ResultFactory $resultFactory,
        private CalculatorFacadeInterface $calculatorFacade
    ) {}

    /**
     * @inheritDoc
     * @noinspection PhpCSValidationInspection
     */
    public function execute()
    {
        return $this->resultFactory->create(ResultFactory::TYPE_RAW)
            ->setContents($this->getContents());
    }

    /**
     * @return string
     */
    public function getContents(): string
    {
        $firstNumber = 10;
        $secondNumber = 5;
        $subtractedNumber = $this->calculatorFacade->subtract($firstNumber, $secondNumber);
        $dividedNumber = $this->calculatorFacade->divide($firstNumber, $secondNumber);
        return sprintf("Devided number: %s. Subtracted number: %s", $dividedNumber, $subtractedNumber);
    }
}
