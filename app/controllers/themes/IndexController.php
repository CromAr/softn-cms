<?php

/**
 * Modulo del controlador de los temas de la aplicación.
 */

namespace SoftnCMS\controllers\themes;

use SoftnCMS\controllers\Controller;
use SoftnCMS\controllers\Pagination;
use SoftnCMS\models\admin\Posts;

/**
 * Clase controlador de los temas.
 *
 * @author Nicolás Marulanda P.
 */
class IndexController extends Controller {

    /**
     * Metodo llamado por la función INDEX.
     * @param int $paged Pagina actual.
     * @return array
     */
    protected function dataIndex($paged) {
        $output = [];
        $countData = Posts::count();
        $pagination = new Pagination($paged, $countData);
        $limit = $pagination->getBeginRow() . ',' . $pagination->getRowCount();
        $posts = Posts::selectByLimit($limit);

        $pagination->concatUrl('post');

        if ($posts !== \FALSE) {
            $output = $posts->getAll();
        }

        return [
            'posts' => $output
        ];
    }

}
