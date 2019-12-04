<?php
/**
 * Created by PhpStorm.
 * User: moyses-oliveira
 * Date: 03/05/19
 * Time: 10:15
 */

get_header();
$banner = $wpdb->get_var(<<<SQL
SELECT img.guid as `banner`
FROM wp_postmeta m 
JOIN wp_posts p ON p.id=m.post_id AND p.post_name='projetos'
JOIN wp_posts img ON img.id=m.meta_value AND m.meta_key='_thumbnail_id';
SQL
);
?>
<div id="vue-app">
    <projects wpbg="<?php echo $banner; ?>" year="<?php echo YEAR;?>"></projects>
</div>

<style type="text/css">
    body {
        background: #f1f1f1 !important;
    }
    .header-projects {
        margin-top: 65px;
    }
    .siteHeader {
        top: 0;
    }
</style>
<?php
wp_enqueue_script('because-revista-main', plugin_dir_url(__DIR__) . 'assets/js/build.js');
get_footer();

