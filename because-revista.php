<?php
/**
 * Plugin Name: Because Revista (revista.transformando.com.vc)
 * Plugin URI: #
 * Description: Exibe registros feitos em revista.transformando.com.vc
 * Version: 1.0
 * Author: Moysés Oliveira
 * Author URI: http://moysesoliveira.com.br/
 **/
namespace BecauseRevista;

require 'Api/Projeto.php';

use BecauseRevista\Api\Projeto;

class Bootstrap {

    /**
     * Initializes the plugin by setting filters and administration functions.
     */
    public static function run() {
        $self = static::class;
        add_action('rest_api_init', [$self , 'rest']);
        add_action('init', [$self, 'init']);
        add_filter( 'query_vars', [$self, 'defineQueryVars']);
        add_action( 'template_redirect', [$self, 'router']);
    }

    public static function init() {
        add_rewrite_rule('projetos', 'index.php?revista-transformando-com-vc-projects=show', 'top');
        add_rewrite_rule('projeto/([0-9]+)/[\S]+$', 'index.php?revista-transformando-com-vc-project-id=$matches[1]', 'top');
        flush_rewrite_rules();
    }

    public static function rest() {
        register_rest_route('revista/v1', 'projects',
            [
                'methods' => 'GET',
                'callback' => Projeto::class . '::getCollection'
            ]);
    }

    public static function defineQueryVars($vars) {
        $vars[] = 'revista-transformando-com-vc-project-id';
        $vars[] = 'revista-transformando-com-vc-projects';
        return $vars;
    }

    public static function router() {

        if (get_query_var( 'revista-transformando-com-vc-projects')):
            add_filter( 'template_include', function() {
                return __DIR__ .  '/Pages/projects.php';
            });
            return;
        endif;

        if (!get_query_var( 'revista-transformando-com-vc-project-id'))
            return;

        add_filter( 'template_include', function() {
            return __DIR__ .  '/Pages/project.php';
        });
    }

}
Bootstrap::run();




