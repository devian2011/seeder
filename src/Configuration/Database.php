<?php

namespace Devian2011\Seeder\Configuration;

class Database
{
    /** @var string Database Code name */
    private string $code;
    /** @var string DSN format like PDO */
    private string $dsn;
    /** @var string DB User name */
    private string $user;
    /** @var string DB Password */
    private string $password;
    /** @var array Database connection options (PDO) */
    private array $options = [];

    public function __construct(string $code, string $dsn, string $user, string $password, array $options)
    {
        $this->code = $code;
        $this->dsn = $dsn;
        $this->user = $user;
        $this->password = $password;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getDsn(): string
    {
        return $this->dsn;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
