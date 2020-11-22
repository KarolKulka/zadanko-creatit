<?php


namespace App\Libraries\WeatherAPIs;


use App\Traits\CityWeather;

class OpenWeatherApi extends WeatherApiBase
{
    use CityWeather;

    public function __construct()
    {
        parent::__construct(
            env('api.openWeatherApi'),
            'OpenWeatherApi',
            'http://api.openweathermap.org/data/2.5/weather?q={queryCity}&units=metric&appid={apiKey}'
        );
    }

    /**
     * Return temperature extracted from API response
     *
     * @return mixed
     */
    public function getCityTemperature()
    {
        return $this->cityWeather()['main']['temp'];
    }

    /**
     * @param array $response
     */
    public function verifyCountry(array $response): void
    {
        $countryCode = $response['sys']['country'];
        $countryData = $this->getCountryByShortName($countryCode);

        if (empty($countryData)){
            $this->setApiError('Wrong country response', self::API_DATA_ERROR);
            return;
        }

        if ($this->getCountry() === $countryData['name']){
            return;
        }

        $this->setApiError('Wrong country response', self::API_DATA_ERROR);
        return;
    }

    public function verifyResponse(array $response): void
    {
        if (200 === $response['cod']) {
            $this->verifyCity($response);
            $this->verifyCountry($response);
            return;
        }

        $apiError = 'API Error';

        if (!empty($response['message'])) {
            $apiError = $response['message'];
        }

        switch ($response['cod']) {
            case "404":
                $errorType = self::API_DATA_ERROR;
                break;
            case "401":
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
        if ($this->getCity() === $response['name']) {
            return;
        }

        $this->setApiError('Wrong city response', self::API_DATA_ERROR);
    }

    /**
     * @param string $countryShortName
     * @return array
     */
    private function getCountryByShortName(string $countryShortName) : array
    {

        $connection = curl_init();
        curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($connection, CURLOPT_URL, 'http://api.worldbank.org/v2/country/'.strtolower($countryShortName).'?format=json');
        $result = curl_exec($connection);
        curl_close($connection);

        $response = json_decode($result, true);

        if (isset($response[1])){
            return $response[1][0];
        }

        return [];
    }


}