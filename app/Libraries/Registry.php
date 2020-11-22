<?php


namespace app\Libraries;


use App\Interfaces\WeatherApiInterface;

class Registry
{

    private static $instance;
    private array $values = [];

    static function instance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function addApi(WeatherApiInterface $api): self
    {
        if (!isset($this->values[$api->getName()])) {
            $this->values[$api->getName()] = $api;
        }

        return $this;
    }

    /**
     * @return WeatherApiInterface[]
     */
    public function getApiObjects(): array
    {
        return $this->values;
    }

    /**
     * @return int
     */
    public function getApiCount() : int
    {
        return count($this->values);
    }

}