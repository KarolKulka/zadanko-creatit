<?php

namespace App\Traits;


use Exception;

trait CityWeather
{

    public function getCityWeather()
    {
        if (!$weather = cache()->get(
            'weather_' . $this->getName() . '_' . md5($this->getCity() . '_' . $this->getCountry())
        )) {
            try {
                $weather = $this->apiCall();
            } catch (Exception $e) {
                echo $e->getMessage() . "\n" . $e->getFile() . " " . $e->getLine();
            }

            cache()->save(
                'weather_' . $this->getName() . '_' . md5($this->getCity() . '_' . $this->getCountry()),
                $weather,
                60 * 60
            );
        }

        $this->verifyResponse($weather);



        $this->cityWeather = $weather;
    }

    public function removeCachedCityWeather()
    {
        cache()->delete('weather_' . $this->getName() . '_' . md5($this->getCity() . '_' . $this->getCountry()));
    }

}