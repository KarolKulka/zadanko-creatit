<?php


namespace App\Libraries;


use App\Interfaces\WeatherApiInterface;
use App\Libraries\WeatherAPIs\{
    OpenWeatherApi,
    WeatherApiApi,
    WeatherApiBase,
    WeatherstackApi
};

class WeatherApi
{
    /**
     * @var Registry
     */
    private Registry $apiRegistry;

    private float $temperature;
    private int $apiCount = 0;
    private string $country;
    private string $city;
    private array $apiErrors = [];
    private array $apiErrorInfo = [
        'API_ERROR'      =>
            [
                'level'   => 0,
                'message' => 'Some APIs had issues your data might not be accurate'
            ],
        'API_DATA_ERROR' =>
            [
                'level'   => 1,
                'message' => 'Your input data is invalid. Data might not be accurate'
            ],
        'API_KEY_ERROR'  =>
            [
                'level'   => 2,
                'message' => 'There is internal Api error. Data might not be accurate'
            ],
    ];

    public function __construct($country, $city)
    {
        $this->setCountry($country);
        $this->setCity($city);

        $openWeatherApi = new OpenWeatherApi();
        $openWeatherApi->setCity($this->getCity())
            ->setCountry($this->getCountry())
            ->setWeatherEndpoint()
            ->getCityWeather();

        $weatherApiApi = new WeatherApiApi();
        $weatherApiApi->setCity($this->getCity())
            ->setCountry($this->getCountry())
            ->setWeatherEndpoint()
            ->getCityWeather();

        $weatherstackApi = new WeatherstackApi();
        $weatherstackApi->setCity($this->getCity())
            ->setCountry($this->getCountry())
            ->setWeatherEndpoint()
            ->getCityWeather();

        $this->apiRegistry = Registry::instance();
        $this->apiRegistry->addApi($openWeatherApi);
        $this->apiRegistry->addApi($weatherApiApi);
        $this->apiRegistry->addApi($weatherstackApi);

        $this->setApiCount($this->apiRegistry->getApiCount());
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * @param int $apiCount
     * @return WeatherApi
     */
    private function setApiCount(int $apiCount): self
    {
        $this->apiCount = $apiCount;

        return $this;
    }

    /**
     * @return float
     */
    public function getTemperature(): float
    {
        if (!isset($this->temperature)) {
            $this->setTemperature(0);
            $tempTemperature = 0;
            $apiDataCounter = 0;
            foreach ($this->apiRegistry->getApiObjects() as $currentApi) {
                if ($currentApi->haveError()) {
                    $this->addApiDataErrors($currentApi);
                    continue;
                }

                $tempTemperature += $currentApi->getCityTemperature();
                $apiDataCounter++;
            }

            if ($apiDataCounter > 0) {
                $tempTemperature /= $apiDataCounter;
            }

            $this->setTemperature($tempTemperature);
        }

        return $this->temperature;
    }

    /**
     * @param $temperature
     * @return $this
     */
    private function setTemperature($temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    /**
     * @param WeatherApiInterface $api
     */
    public function addApiDataErrors(WeatherApiInterface $api): void
    {
        $this->apiErrors[$api->getName()] = $api->getApiError();
    }

    /**
     * @return int
     */
    public function getApiCount(): int
    {
        return $this->apiCount;
    }

    /**
     * @return mixed|string
     */
    public function showErrorInfo()
    {
        $output = 'Some APIs had issues your data might not be accurate';

        $currentActivatedError = 0;
        foreach ($this->getApiDataErrors() as $apiError) {
            if ($this->apiErrorInfo[$apiError['type']]['level'] > $currentActivatedError) {
                $currentActivatedError = $this->apiErrorInfo[$apiError['type']]['level'];
                $output = $this->apiErrorInfo[$apiError['type']]['message'];
            }
        }

        return $output;
    }

    /**
     * @return array
     */
    public function getApiDataErrors(): array
    {
        return $this->apiErrors;
    }


}