<?php


namespace app\Libraries\WeatherAPIs;


use App\Traits\CityWeather;
use phpDocumentor\Reflection\Types\Self_;

class WeatherApiApi extends WeatherApiBase
{
    use CityWeather;


    public function __construct()
    {
        parent::__construct(
            env('api.weatherApi'),
            'WeatherApiApi',
            'https://api.weatherapi.com/v1/current.json?key={apiKey}&q={queryCity}'
        );
    }

    /**
     * Return temperature extracted from API response
     *
     * @return mixed
     */
    public function getCityTemperature()
    {
        return $this->cityWeather()['current']['temp_c'];
    }

    public function verifyResponse(array $response): void
    {
        if (!isset($response['error'])) {
            $this->verifyCity($response);
            $this->verifyCountry($response);

            return;
        }

        $apiError = 'API Error';

        if (!empty($response['error']['message'])) {
            $apiError = $response['error']['message'];
        }

        switch ($response['error']['code']) {
            case 1006:
                $errorType = self::API_DATA_ERROR;
                break;
            case 2006:
                $errorType = self::API_KEY_ERROR;
                break;
            default:
                $errorType = self::API_ERROR;
                break;
        }

        $this->setApiError($apiError, $errorType);
    }

    public function verifyCity(array $response): void
    {
        if ($this->getCity() === $response['location']['name']) {
            return;
        }

        $this->setApiError('Wrong city response', self::API_DATA_ERROR);
    }

    public function verifyCountry(array $response): void
    {
        if ($this->getCountry() === $response['location']['country']) {
            return;
        }

        $this->setApiError('Wrong country response', self::API_DATA_ERROR);
    }


}