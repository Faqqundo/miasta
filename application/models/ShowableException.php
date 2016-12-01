<?php

/**
 *  Moduł ogólny
 *
 *
 */

namespace Model;

use Exception;
use Throwable;

/**
 * Pokazywalne exception - treść tego wyjątku należy pokazać użytkownikowi
 *
 * PHP version 7.0
 *
 *
 * @category  PHP
 * @package   Default
 * @author    Mariusz Wintoch <biuro@informatio.pl>
 * @copyright 2016 (c) Informatio, Mariusz Wintoch
 */
class ShowableException extends Exception
{
    /**
     * Sugerowany kod odpowiedzi HTTP - jeśli błąd zostanie przekazany użytkownikowi
     *
     * @var int
     */
    public $responseCode = null;

    /**
     * Konstruktor wyjatku
     *
     * @param string $message
     * @param int $code
     * @param Throwable $previous
     * @param int $responseCode Jeśli chcemy zasugerować kod odpowiedzi HTTP
     */
    public function __construct(string $message = "", int $code = 0, /*Throwable dla php7*/ $previous = null, $responseCode = null) {
        parent::__construct($message, $code, $previous);

        $this->responseCode = $responseCode;
    }
}
