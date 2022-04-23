<?php

namespace Devian2011\Seeder\Ast\Columns;

use Devian2011\Seeder\Configuration\Column;
use Faker\Generator;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ColumnValueResolver implements ColumnValueResolverInterface
{

    private Column $column;
    private array $columns;
    private ColumnValueRegistryInterface $registry;
    private ExpressionLanguage $expressions;
    private Generator $faker;
    private ColumnValueResolverFactoryInterface $columnValueResolverFactory;

    public function __construct(
        ColumnValueResolverFactoryInterface $columnValueResolverFactory,
        Column                              $column,
        array                               $columns,
        ColumnValueRegistryInterface        $registry,
        ExpressionLanguage                  $expressions,
        Generator                           $faker)
    {
        $this->columnValueResolverFactory = $columnValueResolverFactory;
        $this->column = $column;
        $this->columns = $columns;
        $this->registry = $registry;
        $this->expressions = $expressions;
        $this->faker = $faker;
    }

    /**
     * @return mixed
     * @throws ColumnValueResolverException
     */
    public function resolve()
    {
        if ($this->registry->isValueExists($this->column->getName())) {
            return $this->registry->getValue($this->column->getValue());
        }
        $addictions = $this->column->getDepends();
        foreach ($addictions as $addiction) {
            if (!$this->registry->isValueExists($addiction)) {
                if (!isset($this->columns[$addiction])) {
                    throw new ColumnValueResolverException("Unknown dependence column: {$addiction} for resolver");
                }
                $resolver = $this->columnValueResolverFactory->getColumnValueResolver(
                    $this->columns[$addiction],
                    $this->columns,
                    $this->registry,
                    $this->expressions,
                    $this->faker
                );
                $this->registry->setColumnValue($addiction, $resolver->resolve());
            }
        }

        return $this->expressions->evaluate($this->column->getValue(), [
            'faker' => $this->faker,
            'context' => (object)$this->registry->asArray()
        ]);
    }

}
