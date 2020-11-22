<?php

declare(strict_types=1);

namespace App\Models;

use App\Entities\LocationWeatherEntity;
use CodeIgniter\Model;

class LocationWeatherModel extends Model
{

    protected $table = 'location_weather';
    protected $primaryKey = 'id';
    protected $returnType = 'App\Entities\LocationWeatherEntity';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'id',
        'country',
        'city',
        'temperature',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $skipValidation = false;


    public function getTemperaturesFromApis(){

    }

    public function saveWeather(LocationWeatherEntity $weather){
        $this->save($weather);
    }

}