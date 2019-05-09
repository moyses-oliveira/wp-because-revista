<div class="featuredBlockWrapper">
    <div class="featuredBlock featuredBlock--slider md-theme owl-carousel js-feat-slider featuredBlock--slider--cover">
        <?php foreach($images as $img): ?>
        <div class="slide-content">
            <article
                    class="postItem post--slide u-hasBackgroundImg post type-post status-publish format-standard has-post-thumbnail category-noticias tag-cooperjovem tag-programa-a-uniao-faz-a-vida tag-sescoop tag-sicredi tag-transformacao">
                <div class="o-backgroundImg o-backgroundImg--dimmed"
                     style="background-image: url(<?php echo $img['src'];?>);">
                </div>
            </article>
        </div>
        <?php endforeach;?>
    </div>
</div>