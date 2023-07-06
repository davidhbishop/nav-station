<?php

namespace App\Factory;

/*
 * The abstract Forecast Factory interface declares a set of methods that return different forecasts. These forecasts are called
 * a family and are relate by a high-level theme or concept. Forecasts of one family are usually able to collaborate
 * among themselves. A family of forecasts may have several variants, but the forecasts of one variant are imcompatible
 * with forecasts of another.
 */
interface ForecastFactory
{
    public function createForecast(): Forecast;
}

/*
 * Each concrete Forecast Factory corresponds to a specific variant (or family) of forecasts.
 *
 * This concrete Factory creates inshore forecasts
 */
class InshoreForecastFactory implements ForecastFactory
{
    public function createForecast(): Forecast
    {
        return new InshoreForecast();
    }
}

/**
 * And this Concrete Factory creates Pressure Forecasts
 */
class PressureForecastFactory implements ForecastFactory
{
    public function createForecast(): Forecast
    {
        return new PressureForecast();
    }
}

/**
 * And this Concrete Factory creates Wind Forecasts
 */
class WindForecastFactory implements ForecastFactory
{
    public function createForecast(): Forecast
    {
        return new WindForecast();
    }
}

/**
 * Each distinct forecast type should have a seperate interface. All variants of the forecast must follow the same
 * interface.
 *
 * For instance, this Abstract Forecast interface describes the behaviour of loading forecasts from file.
 */
interface Forecast
{
    public function loadForecast(): array;
}

