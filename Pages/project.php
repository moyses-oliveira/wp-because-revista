<?php
/**
 * Created by PhpStorm.
 * User: moyses-oliveira
 * Date: 03/05/19
 * Time: 10:15
 */
$id = get_query_var( 'revista-transformando-com-vc-project-id');
$data = \BecauseRevista\Api\Projeto::getInfo($id);

get_header();
?>
    <div class="wrapper">
        <div class="content">
            <pre>
                <?php echo json_encode($data, JSON_PRETTY_PRINT); ?>
            </pre>
        </div>
    </div>
<?php
get_footer();

