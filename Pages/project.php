<?php
/**
 * Created by PhpStorm.
 * User: moyses-oliveira
 * Date: 03/05/19
 * Time: 10:15
 */
$id = get_query_var('revista-transformando-com-vc-project-id');
$project = \BecauseRevista\Api\Projeto::getInfo($id);
$cityProjects = \BecauseRevista\Api\Projeto::getCollection([
    'city' => $project['data']['city_id'],
    'uf' => $project['data']['uf'],
    'limit' => 5
]);
$data = $project['data'];
$fields = $project['fields'];
$testmonials = $project['testmonials'];
$images = $project['images'];
$messages = $project['messages'];

get_header();
?>
    <main class="layoutBody clearfix project">
        <article
                class="postSingle postSingle--fullwidth postSingle--headerWide hentry projects type-projects status-publish has-post-thumbnail">

            <?php include('slider.php'); ?>
            <!--div class="full-image" style="">
                <div class="overlay-black"></div>
            </div-->

            <div class="container">
                <div class="layoutContent clearfix">

                    <div class="layoutContent-main">

                        <div itemprop="" class="postContent postContent--fullwidth bodyCopy entry-content">

                            <div class="pergunta">
                                <h2><?php echo $data['title']; ?></h2>
                            </div>

                            <div class="project-meta-top">
                                <div class="cidade"><?php echo "{$data['city']} - {$data['uf']}"; ?></div>

                                <?php foreach ($fields as $field): ?>
                                    <?php if ($field['type'] !== 'number') continue; ?>
                                    <div class="<?php echo $field['key']; ?>">
                                        <span><?php echo $field['name'] ?>: </span>
                                        <?php echo $field['value']; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <?php foreach ($fields as $field): ?>
                                <?php if ($field['type'] === 'number' || $field['key'] === 'name') {
                                    continue;
                                } ?>
                                <div class="custom-info <?php echo $field['key']; ?>">
                                    <h3><?php echo $field['name']; ?></h3>
                                    <div class="page">
                                        <div class="layoutArea">
                                            <div class="column">
                                                <?php echo $field['value']; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <?php include 'share.php'; ?>
                        </div>

                    </div>

                    <aside id="mdSidebar" class="layoutContent-sidebar sidebar">

                        <a href="/projetos" class="btn btn--pill btn--solid project-name-submit">
                            <i class="fa fa-search"></i> Buscar mais projetos
                        </a>
                        <div class="nossa_causa_sidebar_project">

                            <div class="projects-same-city">
                                <h4 class="title-widget">Projetos da mesma cidade</h4>
                                <?php foreach ($cityProjects['data'] as $p): ?>
                                    <?php if ($p['id'] === $project['data']['id']) {
                                        continue;
                                    } ?>
                                    <?php $url = "/projeto/{$p['id']}/{$p['slug']}"; ?>
                                    <div class="post projects type-projects status-publish has-post-thumbnail">

                                        <a class="thumb" href="<?php echo $url; ?>"
                                           style="background-image: url('<?php echo $p['thumb'] ?>')">

                                        </a>
                                        <div class="about">
                                            <h3 class="project-title">
                                                <a href="<?php echo $url; ?>">
                                                    <?php echo $p['project'] ?>
                                                </a>
                                            </h3>
                                            <div class="project-school"><?php echo $p['school']; ?></div>
                                        </div>
                                        <div class="city"><?php echo $p['city']; ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </aside>

                </div>
            </div>
        </article>
    </main>
    <style type="text/css">
        body {
            background: #f1f1f1 !important;
        }

        .project .o-backgroundImg {
            background-size: contain;
            background-color: #92bfd6 !important;
        }

        .project .custom-info h3 {
            color: #555;
            font-size: 20px;
        }

        .project .custom-info {
            margin-bottom: 20px;
        }
        .project .cidade {
            text-transform: uppercase;
        }

        .layoutContent-main {
            background-color: #fff;
            border-radius: 10px;
            border: 2px solid #ddd;
        }

        .projects-same-city .post .thumb {
        }

        .projects-same-city .post {
            height: auto;
            margin-bottom: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            -webkit-border-bottom-right-radius: 10px;
            -webkit-border-bottom-left-radius: 10px;
            -moz-border-radius-bottomright: 10px;
            -moz-border-radius-bottomleft: 10px;
            border-bottom-right-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        .projects-same-city .post .thumb {
            margin-top: -1px;
            margin-left: -1px;
            margin-right: -1px;
            background-color: #fff;
            border-bottom: 4px solid #28b0d3;
            float: left;
            width: calc(100% + 2px);
            height: 180px;
            background-color: #fff;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: top center;
        }

        .projects-same-city .post .about {
            padding: 10px 20px 0 20px;
            clear: both;
            font-size: 12px;
        }

        .projects-same-city .post .about h3 {
            font-size: 20px;
            margin-top: 0px;
            margin-bottom: 5px;
            text-align: justify;
        }

        .projects-same-city .post .city {
            padding: 0px 20px 10px 20px;
            margin-top: auto;
            text-align: right;
        }
    </style>
<?php
get_footer();

