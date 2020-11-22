<?php

use App\Entities\LocationWeatherEntity;
use App\Libraries\WeatherApi;
/* @var $weatherApi WeatherApi */
/* @var $locationWeather LocationWeatherEntity */
?>
<div class="container">
    <div class="row ">
        <div class="col-12 ">
            <h1 class="text-center">Pogoda</h1>
            <?php if (!empty($weatherApi->getApiDataErrors())) { ?>
                <div class="border border-danger pt-3 pb-2 mb-5">
                    <p class="text-danger"><?= $weatherApi->showErrorInfo() ?></p>
                </div>
            <?php } ?>
            <p class="text-center">Aktualna temperatura <?= $locationWeather->city ?>, <?= $locationWeather->country ?> to <?= $locationWeather->temperature ?> &#8451; </p>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <a class="btn btn-primary" href="<?= route_to('home.Home') ?>">Powrót</a>
        </div>
    </div>
</div>