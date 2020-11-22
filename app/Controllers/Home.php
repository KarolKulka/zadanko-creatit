<?php


namespace App\Controllers;

use App\Entities\LocationWeatherEntity;
use App\Libraries\WeatherApi;
use App\Models\LocationWeatherModel;
use CodeIgniter\HTTP\{
    RequestInterface,
    ResponseInterface
};
use CodeIgniter\Validation\Validation;
use Config\Services;
use Psr\Log\LoggerInterface;

class Home extends BaseController
{

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor.
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param LoggerInterface $logger
     */
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
    }

    public function index()
    {
        $data['validationErrors'] = $this->checkSubmitErrors();

        return $this->renderView('start', $data);
    }

    /**
     * @return array|null
     */
    private function checkSubmitErrors(): ?array
    {
        $validationErrors = session()->get('validationErrors');
        session()->remove('validationErrors');

        return $validationErrors;
    }

    public function getWeather()
    {
        /* @var $validation Validation */
        $validation = Services::validation();

        if ($validation->run($this->request->getPost(), 'getWeather')) {
            $city = $this->request->getPost('city');
            $country = $this->request->getPost('country');
            $weatherApi = new WeatherApi($country, $city);

            $locationWeather = new LocationWeatherEntity(
                [
                    'country'     => $weatherApi->getCountry(),
                    'city'        => $weatherApi->getCity(),
                    'temperature' => $weatherApi->getTemperature(),
                ]
            );
            if (empty($weatherApi->getApiDataErrors())) {
                if (!$saved = cache()->get(
                    md5($locationWeather->country . $locationWeather->city . $locationWeather->temperature)
                )) {
                    /* @var $locationWeatherModel LocationWeatherModel */
                    $locationWeatherModel = model('App\Models\LocationWeatherModel');
                    $locationWeatherModel->saveWeather($locationWeather);
                    cache()->save(
                        md5($locationWeather->country . $locationWeather->city . $locationWeather->temperature),
                        1,
                        3600
                    );
                }
            }
            $data['weatherApi'] = $weatherApi;
            $data['locationWeather'] = $locationWeather;

            return $this->renderView('weatherResult', $data);
        }

        session()->set('validationErrors', $validation->getErrors());

        return redirect()->route('home.Home')->withInput();
    }

    //--------------------------------------------------------------------

}
