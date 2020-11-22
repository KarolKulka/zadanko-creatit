<?php

declare(strict_types=1);

namespace App\Entities;

use CodeIgniter\Entity;

/**
 * Class LocationWeatherEntity
 * @package App\Entities
 *
 * @property $city
 * @property $country
 * @property $temperature
 *
 */
class LocationWeatherEntity extends Entity
{
    public function __construct(array $data = null)
    {
        parent::__construct($data);
    }

    public function setTemperature(float $temperature){
        $this->attributes['temperature'] = number_format($temperature , 1);
    }
}