<?php

/**
 * Modulo del controlador de la pagina "index" de la plantilla.
 */

namespace SoftnCMS\controllers\themes;

use SoftnCMS\controllers\Controller;
use SoftnCMS\controllers\Pagination;
use SoftnCMS\models\theme\PostsListTemplate;
use SoftnCMS\models\theme\PostsTemplate;
use SoftnCMS\models\theme\Template;

/**
 * Clase del controlador de la pagina "index" de la plantilla.
 * @author Nicolás Marulanda P.
 */
class IndexController extends Controller {
    
    /**
     * Metodo llamado por la función INDEX.
     *
     * @param array $data Lista de argumentos.
     *
     * @return array
     */
    protected function dataIndex($data) {
        $output     = [];
        $template   = new Template();
        $countData  = PostsListTemplate::count();
        $pagination = new Pagination($data['paged'], $countData);
        $limit      = $pagination->getBeginRow() . ',' . $pagination->getRowCount();
        $posts      = PostsListTemplate::selectAll($limit);
        
        if ($posts !== \FALSE) {
            $postsTemplate = new PostsTemplate();
            $postsTemplate->addData($posts->getAll());
            $output = $postsTemplate->getAll();
        }
        
        return [
            'posts'      => $output,
            'pagination' => $pagination,
            'template'   => $template,
        ];
    }
    
}
