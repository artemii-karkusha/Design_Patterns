<?php
/**
 * @author Artemii Karkusha
 * @copyright Copyright (c)
 */

declare(strict_types=1);

namespace Maestro\DesignPatterns\Controller\Test;

use InvalidArgumentException;
use Maestro\DesignPatterns\Api\Builder\PizzaInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Controller\ResultFactory;
use Maestro\DesignPatterns\Api\Builder\PizzaBuilderInterface;
use Maestro\DesignPatterns\Model\Builder\Cheese;
use Maestro\DesignPatterns\Model\Builder\Bacon;
use Maestro\DesignPatterns\Model\Builder\Pineapples;
use Maestro\DesignPatterns\Model\Builder\Mushrooms;

/**
 * Controller for test Builder functionality
 */
class Builder implements HttpGetActionInterface
{
    /**
     * @var ResultFactory
     */
    private ResultFactory $resultFactory;

    /**
     * @var PizzaBuilderInterface
     */
    private PizzaBuilderInterface $pizzaBuilder;

    /**
     * @param ResultFactory $resultFactory
     * @param PizzaBuilderInterface $pizzaBuilder
     */
    public function __construct(ResultFactory $resultFactory, PizzaBuilderInterface $pizzaBuilder)
    {
        $this->resultFactory = $resultFactory;
        $this->pizzaBuilder = $pizzaBuilder;
    }

    /**
     * Execute action based on request and return result
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        try {
            $this->pizzaBuilder
                ->addIngredient(Cheese::INGREDIENT_NAME)
                ->addIngredient(Bacon::INGREDIENT_NAME)
                ->addIngredient(Mushrooms::INGREDIENT_NAME)
                ->addIngredient(Pineapples::INGREDIENT_NAME);
        } catch (InvalidArgumentException $invalidArgumentException) {
            die((string)__($invalidArgumentException->getMessage()));
        }
        return $this->resultFactory->create(ResultFactory::TYPE_RAW)->setContents(
            $this->getResponseText($this->pizzaBuilder->getPizza())
        );
    }

    /**
     * @param PizzaInterface $pizza
     * @return string
     */
    private function getResponseText(PizzaInterface $pizza): string
    {
        if (count($pizza->getIngredients()) === 0) {
            return '';
        }

        $responseText = sprintf("<br>Pizza number\"#%s\"{", spl_object_id($pizza));
        foreach ($pizza->getIngredients() as $ingredient) {
            $responseText .= sprintf(
                "ingredient: \"%s\", ",
                $ingredient->getName(),
            );
        }
        $responseText .= '}';

        return $responseText;
    }
}
