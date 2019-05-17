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

class Bootstrap
{

    /**
     * Initializes the plugin by setting filters and administration functions.
     */
    public static function run()
    {
        $self = static::class;
        add_action('rest_api_init', [$self, 'rest']);
        add_action('init', [$self, 'init']);
        add_filter('query_vars', [$self, 'defineQueryVars']);
        add_action('template_redirect', [$self, 'router']);
    }

    public static function init()
    {
        add_rewrite_rule('projetos$', 'index.php?revista-transformando-com-vc-projects=show', 'top');
        add_rewrite_rule('projeto/([0-9]+)/[\S]+$', 'index.php?revista-transformando-com-vc-project-id=$matches[1]', 'top');
        flush_rewrite_rules();
    }

    public static function rest()
    {
        register_rest_route('revista/v1', 'projects',
            [
                'methods' => 'GET',
                'callback' => Projeto::class . '::getCollection'
            ]);
    }

    public static function defineQueryVars($vars)
    {
        $vars[] = 'revista-transformando-com-vc-project-id';
        $vars[] = 'revista-transformando-com-vc-projects';
        return $vars;
    }

    public static function projectSEO($data, $fields, $images)
    {
        global $wp_query;
        $wp_query->is_page = true;
        $seoTitle = 'Transformando.com.vc • Projeto: ' . $data['title'];
        $seoDescription = $fields['objetivo']['value'];
        \WPSEO_Options::set('og_frontpage_title', $seoTitle);
        \WPSEO_Options::set('og_frontpage_desc', $seoDescription);

        if (isset($images[0]))
            \WPSEO_Options::set('og_frontpage_image', $images[0]['src']);

        add_filter('wpseo_metadesc', function () use ($seoDescription){
            return $seoDescription;
        });
        add_filter('wpseo_opengraph_url', function () {
            return get_site_url() . $_SERVER['REQUEST_URI'];
        });
        add_filter('wpseo_opengraph_site_name', function () use ($seoTitle) {
            return $seoTitle;
        });
        add_filter('wpseo_twitter_title', function () use ($seoTitle) {
            return $seoTitle;
        });
        add_filter('wpseo_twitter_description', function () use ($seoDescription) {
            return $seoDescription;
        });


    }


    public static function router()
    {

        if (get_query_var('revista-transformando-com-vc-projects')):
            add_filter('template_include', function () {
                return __DIR__ . '/Pages/projects.php';
            });
            return;
        endif;

        if (!get_query_var('revista-transformando-com-vc-project-id'))
            return;

        add_filter('template_include', function () {
            return __DIR__ . '/Pages/project.php';
        });
    }

}

Bootstrap::run();




