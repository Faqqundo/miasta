<?php

/**
 *  Moduł ogólny
 *
 *
 */

namespace Model;

use Exception;
use Zend_Registry;

/**
 * Ogólny model obsługi miejscowości
 *
 * PHP version 7.0
 *
 *
 * @category  PHP
 * @package   Default
 * @author    Mariusz Wintoch <biuro@informatio.pl>
 * @copyright 2016 (c) Informatio, Mariusz Wintoch
 */
class Cities
{
    /**
     * Maksymalna ilość znaków w linii - jeśli linia będzie dłuższa to pojawią się błędy w numeracji.
     * Natomiast bez użycia stałej plik bedzie przeglądany wolniej
     * 
     */
    const MAX_ROZMIAR_LINII = 512;

    /**
     * Definicja prawidłowego nagłówka
     *
     * @var array
     */
    protected static $prawidlowyNaglowek = array(
        'Country', 'City', 'AccentCity', 'Region', 'Population',
        'Latitude', 'Longitude'
    );

    /**
     * Instancja singletonu
     *
     * @var Cities
     */
    protected static $instance;

    /**
     * Zasób pliku miast
     *
     * @var resource
     */
    protected $plik;

    /**
     * Konstruktor zasadniczy
     *
     * @throws ShowableException
     * @throws Exception
     */
    public function __construct()
    {
        $config = Zend_Registry::get('config');
        if (empty($config['cities_csv'])) {
            throw new Exception('Brak konfiguracji - miejsce przechowywania pliku CSV miejscowości');
        }

        $this->otworzPlik($config['cities_csv']);
    }

    /**
     * Destruktor
     *
     */
    public function __destruct() {
        if ($this->plik) {
            fclose($this->plik);
        }
    }
    
    /**
     * Pobiera prostą listę miast, bez szczegółów
     * 
     * @param int $strona
     * @param int $ile
     * @return array
     * @throws ShowableException
     * @throws Exception
     */
    public function pobierzListe($strona = 1, $ile = 50)
    {
        if (!flock($this->plik, LOCK_SH)) {
            throw new ShowableException('Trwa aktualizacja zasobu miast, należy spróbować ponownie za chwilę.', 0, null. 503);
        }

        $od = ((int)$strona - 1) * (int)$ile;
        if ($od < 0) {
            flock($this->plik, LOCK_UN);
            throw new ShowableException('Niewłaściwe parametry wejściowe', 0, null, 400);
        }
        $id = $od + 1;

        rewind($this->plik); //nie każdy pamięta o przewinięciu kasety po obejrzeniu;p

        //pierwsze śliwki robaczywki
        $this->sprawdzIPrzewinNaglowek();

        //przewijamy do pożądanego rekordu
        $czyPrzewinieto = $this->przewinWiersze($od);

        if (!$czyPrzewinieto) {
            flock($this->plik, LOCK_UN);
            return array(); //chyba nie ma co rzucać błędem o przekroczeniu zakresu;p
        }

        //tworzenie listy
        $lista = array();
        for ($i=0; $i < $ile; $i++) {
            $linia = fgetcsv($this->plik, self::MAX_ROZMIAR_LINII);
            if (!$linia) {
                if (!feof($this->plik)) {
                    throw new Exception('Wystąpił bład odczytu pliku CSV miast');
                }
                break;
            }

            $lista[] = array(
                'ID' => $id,
                'nazwa' => $linia[1],
                'sciezka' => '/cities/' . $id
            );

            $id++;
        }

        flock($this->plik, LOCK_UN);

        return $lista;
    }

    /**
     * Pobiera szczegóły miejscowości o podoanym id
     *
     * @param int $id
     * @return City
     * @throws ShowableException
     * @throws Exception
     */
    public function pobierzSzczegoly($id)
    {
        if (!flock($this->plik, LOCK_SH)) {
            throw new ShowableException('Trwa aktualizacja zasobu miast, należy spróbować ponownie za chwilę.', 0, null. 503);
        }

        $id = (int)$id;
        if ($id < 1) {
            flock($this->plik, LOCK_UN);
            throw new ShowableException('Niewłaściwe parametry wejściowe', 0, null, 400);
        }

        rewind($this->plik); //nie każdy pamięta o przewinięciu kasety po obejrzeniu;p

        //pierwsze śliwki robaczywki
        $this->sprawdzIPrzewinNaglowek();

        //przewijamy do pożądanego rekordu
        $czyPrzewinieto = $this->przewinWiersze($id - 1);

        if (!$czyPrzewinieto) {
            flock($this->plik, LOCK_UN);
            throw new ShowableException('Brak miejscowości o podanym ID', 0, null, 400);
        }

        $linia = fgetcsv($this->plik, self::MAX_ROZMIAR_LINII);
        if (!$linia) {
            if (feof($this->plik)) {
                throw new ShowableException('Brak miejscowości o podanym ID', 0, null, 400);
            } else {
                throw new Exception('Bład odczytu pliku CSV lub przekroczony zakres'); //o jeden
            }
        }

        flock($this->plik, LOCK_UN);

        return $this->parsujCity($linia);
    }

    /**
     * Tworzy i zwraca instancję singletonu
     *
     * @return Cities
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof Cities) {
            self::$instance = new Cities;
        }

        return self::$instance;
    }

    /**
     * Otwiera plik
     *
     * @param string $nazwa
     * @throws Exception
     */
    protected function otworzPlik($nazwa)
    {
        if (!file_exists($nazwa)) {
            throw new Exception('Brak pliku CSV miejscowości');
        }

        $this->plik = fopen($nazwa, 'r');
        if (!$this->plik) {
            throw new Exception('Błąd otwierania pliku CSV miejscowości');
        }

    }

    /**
     * Sprawdz prawidłość linii z zaprogramowanym formatem i przewija plik do następnej linii
     *
     * @throws Exception
     */
    protected function sprawdzIPrzewinNaglowek()
    {        
        $naglowek = fgetcsv($this->plik, self::MAX_ROZMIAR_LINII);
        if ($naglowek === false) {
            throw new Exception('Błąd odczytu lub pusty plik CSV miast');
        } elseif ($naglowek !== self::$prawidlowyNaglowek) {
            throw new Exception('Struktura pliku CSV miast nieprawidłowa');
        }
    }

    /**
     * Przewija plik o n linii
     *
     * @param int $ile
     * @return boolean czy przewinięto skutecznie
     * @throws Exception jeśli błąd odczytu pliku
     */
    protected function przewinWiersze($ile)
    {
        while ($ile > 0) {
            if (false === fgets($this->plik, self::MAX_ROZMIAR_LINII)) {
                break;
            }

            $ile--;
        }

        if ($ile > 0) {
            if (!feof($this->plik)) {
                throw new Exception('Błąd odczytu pliku');
            }

            return false;
        }

        return true;
    }

    /**
     * Tworzy obiekt z tablicy
     *
     * @param array $linia
     * @return City
     * @throws Exception
     */
    protected function parsujCity(array $linia)
    {
        if (count($linia) != count(self::$prawidlowyNaglowek)) {
            throw new Exception('Nieprawidłowo odczytane miasto');
        }

        $city = new City;
        $city->country = $linia[0];
        $city->city = $linia[1];
        $city->accentCity = $linia[2];
        $city->region = (int)$linia[3];
        $city->population = strlen($linia[4]) ? (int)$linia[4] : null;
        $city->latitude = (float)$linia[5];
        $city->longitude = (float)$linia[6];

        return $city;
    }
}
