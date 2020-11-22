<?php


namespace App\Libraries\WeatherAPIs;


use App\Interfaces\WeatherApiInterface;
use CodeIgniter\Exceptions\PageNotFoundException;
use Exception;

abstract class WeatherApiBase implements WeatherApiInterface
{

    const API_ERROR = 1;
    const API_DATA_ERROR = 2;
    const API_KEY_ERROR = 3;
    protected array $cityWeather;
    /**
     * @var string
     */
    protected string $weatherEndpoint = '';
    private $apiErrorsInfo = [
        self::API_ERROR      => 'API_ERROR',
        self::API_DATA_ERROR => 'API_DATA_ERROR',
        self::API_KEY_ERROR  => 'API_KEY_ERROR',
    ];
    /**
     * @var string
     */
    private string $name;
    /**
     * @var string
     */
    private string $city;
    /**
     * @var string
     */
    private string $country;
    /**
     * @var string
     */
    private string $apiKey;
    private string $rawEndpoint;
    private array $apiError = [];

    /**
     * WeatherApiBase constructor.
     * @param $apiKey
     * @param $name
     * @param $rawEndpoint
     */
    public function __construct($apiKey, $name, $rawEndpoint)
    {
        $this->setApiKey($apiKey);
        $this->setName($name);
        $this->setRawEndpoint($rawEndpoint);
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
     * @return $this
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    abstract public function getCityTemperature();

    /**
     * @return array
     */
    public function cityWeather(): array
    {
        $this->checkCityWeather();

        return $this->cityWeather;
    }

    /**
     * If cityWheather is not set calls method do get data from API
     *
     */
    public function checkCityWeather()
    {
        if (!isset($this->cityWeather)) {
            $this->getCityWeather();
        }
    }

    /**
     * Method for making curl request to API endpoint
     *
     * @param bool $jsonResponse
     * @return bool|mixed|string
     * @throws Exception
     */
    public function apiCall($jsonResponse = true)
    {
        if (empty($this->getWeatherEndpoint())) {
            throw new Exception('You must provide an valid API endpoint');
        }

        $connection = curl_init();
        curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($connection, CURLOPT_URL, $this->getWeatherEndpoint());
        $result = curl_exec($connection);
        curl_close($connection);

        if ($jsonResponse) {
            return json_decode($result, true);
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getWeatherEndpoint(): string
    {
        return $this->weatherEndpoint;
    }

    /**
     * Method sets API endpoint with proper city and country data inserted into url
     *
     *
     * @return $this
     */
    public function setWeatherEndpoint(): self
    {
        $this->weatherEndpoint = str_replace(
            [
                '{queryCity}',
                '{apiKey}'
            ],
            [
                $this->getCity(),
                $this->getApiKey()
            ],
            $this->getRawEndpoint()
        );

        return $this;
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
     * @return $this
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    protected function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Return raw endpoint url without city or country data
     *
     * @return string
     */
    public function getRawEndpoint(): string
    {
        return $this->rawEndpoint;
    }

    /**
     * Sets raw endpoint url without city or country data
     *
     * @param string $rawEndpoint
     * @return string
     */
    public function setRawEndpoint(string $rawEndpoint)
    {
        return $this->rawEndpoint = $rawEndpoint;
    }

    /**
     * Checks if api have error registered during or after making a call
     *
     * @return bool
     */
    public function haveError(): bool
    {
        if (empty($this->getApiError())) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public function getApiError(): array
    {
        return $this->apiError;
    }

    /**
     * Sets error with error info and error message
     *
     * @param string $error
     * @param string $errorType
     */
    public function setApiError(string $error, string $errorType): void
    {
        $this->apiError = [
            'type'    => $this->apiErrorsInfo[$errorType],
            'message' => $error,
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

}