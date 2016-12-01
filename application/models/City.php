<?php

/**
 *  Moduł ogólny
 *
 *
 */

namespace Model;

/**
 * Szczegóły miejscowości
 *
 * PHP version 7.0
 *
 *
 * @category  PHP
 * @package   Default
 * @author    Mariusz Wintoch <biuro@informatio.pl>
 * @copyright 2016 (c) Informatio, Mariusz Wintoch
 */
class City
{
    /**
     * Państwo w którym leży miasto
     *
     * @var string
     */
    public $country;

    /**
     * Nazwa miasta maszynowa
     *
     * @var string
     */
    public $city;

    /**
     * Nazwa miasta do wyświetlania
     *
     * @var string
     */
    public $accentCity;

    /**
     * Identyfikator regionu
     *
     * @var int
     */
    public $region;

    /**
     * Populacja
     *
     * @var int|null
     */
    public $population;

    /**
     * Szerokość geograficzna
     *
     * @var float
     */
    public $latitude;

    /**
     * Długość geograficzna
     *
     * @var float
     */
    public $longitude;
}
