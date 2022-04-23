<?php

namespace Devian2011\Seeder\Configuration;

class Column
{
    private string $name;
    private string $value;
    private array $depends = [];

    /**
     * @param string $name
     * @param string $value
     * @param array $depends
     */
    public function __construct(string $name, string $value, array $depends = [])
    {
        $this->name = $name;
        $this->value = $value;
        $this->depends = $depends;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function getDepends(): array
    {
        return $this->depends;
    }
}
