<?php

namespace app\Libraries\WeatherAPIs;


use App\Traits\CityWeather;

class WeatherstackApi extends WeatherApiBase
{
    use CityWeather;

    public function __construct()
    {
        parent::__construct(
            env('api.weatherStackApi'),
            'WheatherstackApi',
            'http://api.weatherstack.com/current?access_key={apiKey}&query={queryCity}'
        );
    }

    /**
     * @param array $response
     */
    public function verifyCity(array $response): void
    {
        if ($this->getCity() === $response['location']['name']) {
            return;
        }

        $this->setApiError('Wrong city response', self::API_DATA_ERROR);
    }

    /**
     * @param array $response
     */
    public function verifyCountry(array $response): void
    {
        if ($this->getCountry() === $response['location']['country']) {
            return;
        }

        $this->setApiError('Wrong counutry response', self::API_DATA_ERROR);
    }

    /**
     * Return temperature extracted from API response
     *
     * @return mixed
     */
    public function getCityTemperature()
    {
        return $this->cityWeather()['current']['temperature'];
    }

    public function verifyResponse(array $response): void
    {
        if (!isset($response['success'])) {
            $this->verifyCity($response);
            $this->verifyCountry($response);

            return;
        }

        $apiError = 'API Error';

        if (!empty($response['error']['info'])) {
            $apiError = $response['error']['info'];
        }

        switch ($response['error']['code']) {
            case 615:
                $errorType = self::API_DATA_ERROR;
                break;
            case 101:
                $errorType = self::API_KEY_ERROR;
                break;
            default:
                $errorType = self::API_ERROR;
                break;
        }

        $this->setApiError($apiError, $errorType);
    }

}