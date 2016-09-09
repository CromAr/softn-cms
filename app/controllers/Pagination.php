<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\controllers;

use SoftnCMS\models\admin\Option;

/**
 * Description of Pagination
 *
 * @author Nicolás Marulanda P.
 */
class Pagination {

    /** @var int Pagina actual. */
    private $pagedNow;

    /** @var int Total de filas o datos. */
    private $countData;

    /** @var int Número de filas a mostrar. */
    private $rowCount;

    /** @var int Total de paginas posibles. */
    private $countPages;

    /** @var int Posición de inicio de los datos ha mostrar. */
    private $beginRow;

    /** @var bool Si es TRUE, se mostrara las opciones de paginación. */
    private $showPagination;

    /** @var string Datos a mantener entre pagina y pagina. */
    private $urlData;

    /** @var string Datos del boton izquierdo. */
    private $dataLeftArrow;

    /** @var string Datos del boton derecho. */
    private $dataRightArrow;

    /**
     * Contiene los datos de cada pagina.
     * Cada posición del array contiene los siguientes indices:
     * 'dataPaged' Contiene el número de la pagina más los valores a mantener.
     * 'page' Contiene solo el numero de la pagina, si es menor a 10 tendra concatenado el numero 0.
     * 'active' Si es TRUE, es la pagina actual.
     * @var array 
     */
    private $dataPaged;

    /**
     * Representa el número de paginas que mostrara, el modulo vista de paginación, 
     * hacia el lado derecho e izquierdo de la pagina "activa".
     * @var int 
     */
    private $maxShowPage;
    private $url;

    /**
     * Constructor.
     * @param int $pagedNow Pagina actual.
     * @param int $countData Total de filas o datos.
     * @param string $urlData [Opcional] Datos a mantener entre pagina y pagina.
     * @param int $maxShowPage [Opcional] Por defecto 3. Representa el número de paginas que mostrara, 
     * el modulo vista de paginación, hacia el lado derecho e izquierdo de la pagina "activa".
     */
    public function __construct($pagedNow, $countData, $urlData = '', $maxShowPage = 3) {
        global $urlSite;

        $this->rowCount = Option::selectByName('optionPaged')->getOptionValue();
        $this->pagedNow = $pagedNow;
        $this->countData = $countData;
        $this->urlData = $urlData;
        $this->beginRow = 0;
        $this->countPages = 0;
        $this->showPagination = \FALSE;
        $this->maxShowPage = $maxShowPage;
        $this->dataLeftArrow = '';
        $this->dataRightArrow = '';
        $this->dataPaged = [];
        $this->url = $urlSite;
        $this->init();
    }

    public function isShowPagination() {
        return $this->showPagination;
    }

    public function getPagedNow() {
        return $this->pagedNow;
    }

    public function getUrlData() {
        return $this->urlData;
    }

    public function getDataPaged() {
        return $this->dataPaged;
    }

    public function getDataLeftArrow() {
        return $this->dataLeftArrow;
    }

    public function getDataRightArrow() {
        return $this->dataRightArrow;
    }

    public function getRowCount() {
        return $this->rowCount;
    }

    public function getBeginRow() {
        return $this->beginRow;
    }

    public function getUrl() {
        return $this->url;
    }

    public function concatUrl($value) {
        $this->url .= "$value/";
    }

    private function init() {
        //Se comprueba que sea mayor que 0 para evitar errores en las operaciones.
        if ($this->countData > 0) {
            /*
             * Compruebo que el número de datos a mostrar por pagina
             * no sea mayor al total de filas a mostrar para 
             * obtener el número correcto de filas a mostrar.
             */
            $this->rowCount = $this->rowCount > $this->countData ? $this->countData : $this->rowCount;
            //Número de paginas posibles.
            $this->countPages = ceil($this->countData / $this->rowCount);

            //Debe haber más de una pagina para mostrar la paginación.
            if ($this->countPages > 1) {
                $this->showPagination = \TRUE;
                /*
                 * Pagina actual, se comprueba que sea valida, 
                 * de lo contrario se asigna el valor 1.
                 */
                $this->pagedNow = empty($this->pagedNow) || intval($this->pagedNow) <= 0 ? 1 : $this->pagedNow;
                /*
                 * Se realiza un segunda comprobación, si es mayor al total 
                 * de paginas, obtengo la ultima pagina.
                 */
                $this->pagedNow = $this->pagedNow > $this->countPages ? $this->countPages : $this->pagedNow;
                // Obtengo la posición del inicio de la fila
                $this->beginRow = ($this->rowCount * $this->pagedNow) - $this->rowCount;

                $this->pagedNav();
            }
        }
    }

    private function pagedNav() {
        $maxShowPage = $this->calculateMaxShowPage();
        $this->dataArrow();

        //Obtengo los datos de las paginas a mostrar.
        for ($i = $maxShowPage['begin']; $i <= $this->countPages && $i <= $maxShowPage['end']; ++$i) {
            $dataPaged = $i . $this->urlData;
            //Si $i es menor o igual a 9 le concateno el 0.
            $istr = $i <= 9 ? '0' . $i : $i;

            $data = [
                'dataPaged' => $dataPaged,
                'page' => $istr,
                'active' => $this->pagedNow == $i
            ];

            $this->dataPaged[] = $data;
        }
    }

    private function dataArrow() {
        /*
         * Si no estoy en la pagina 1, la flecha izquierda se habilita 
         * y le paso sus datos.
         */
        if ($this->pagedNow > 1) {
            $page = $this->pagedNow - 1;
            $this->dataLeftArrow = $page . $this->urlData;
        }

        /*
         * Si no estoy en la ultima pagina, la flecha derecha se habilita 
         * y le paso sus datos.
         */
        if ($this->pagedNow < $this->countPages) {
            $page = $this->pagedNow + 1;
            $this->dataRightArrow = $page . $this->urlData;
        }
    }

    private function calculateMaxShowPage() {
        /*
         * Para evitar los casos donde el total de paginas es demaciado grande
         * se emplea un maximo de paginas a mostrar ($this->maxShowPage).
         */

        $auxBeginNumPage = $this->pagedNow - $this->maxShowPage;
        //Compruebo si estoy a una distancia valida de la pagina 1.
        $beginNumPage = $auxBeginNumPage > 0 ? $auxBeginNumPage : 1;

        $auxEndNumPage = $this->pagedNow + $this->maxShowPage;
        //Compruebo si estoy a una distancia valida de la ultima pagina.
        $endNumPage = $auxEndNumPage < $this->countPages ? $auxEndNumPage : $this->countPages;

        /*
         * Para mostrar siempre el mismo numero de paginas,
         * si $auxBeginNumPage es menor o igual a 0, obtengo 
         * su valor positivo para sumarlo al valor de la posicion
         * de la ultima pagina.
         */
        if ($auxBeginNumPage <= 0) {
            $endNumPage += abs($auxBeginNumPage) + 1;
        } elseif ($auxEndNumPage >= $this->countPages) {
            /*
             * Para seguir mostrando el mismo numero de paginas
             * las paginas que sobran (de la operación siguiente) se las 
             * resto a la posición de la pagina de inicio.
             */
            $beginNumPage -= $auxEndNumPage - $this->countPages;
            //Compruebo que la pagina de inicio sea una posicion valida.
            $beginNumPage = $beginNumPage >= 1 ? $beginNumPage : 1;
        }

        return [
            'begin' => $beginNumPage,
            'end' => $endNumPage
        ];
    }

}