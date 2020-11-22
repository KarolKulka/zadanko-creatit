<?php

namespace App\Interfaces;

interface WeatherApiInterface
{

    public function getName(): string;

    public function setName(string $name);

    public function getCountry(): string;

    public function getCity(): string;

    public function setCountry(string $country): self;

    public function setCity(string $city): self;

    public function getCityTemperature();

    public function getCityWeather();

    public function checkCityWeather();

    public function cityWeather(): array;

    public function getApiKey(): string;

    public function apiCall($jsonResponse = true);

    public function getWeatherEndpoint(): string;

    public function setWeatherEndpoint(): self;

    public function verifyResponse(array $response): void;

    public function verifyCity(array $response): void;

    public function verifyCountry(array $response): void;

    public function setApiError(string $error, string $errorType): void;

    public function getApiError(): array;

    public function haveError(): bool;
}