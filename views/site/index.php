<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 2/29/2024
 * @time: 12:39 PM
 */

/**
 * @var string $title
 * @var array $posts
 * @var string $pagination
 */

use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = $title;

?>

<section class="section novi-background section-md text-center">
    <div class="container">
        <div class="row row-lg-50 row-35 offset-top-2">
            <?php
            foreach ($posts as $post):
                $images = [];

                $imagesPath = Yii::getAlias('@attachmentsUrl') . $post['id'] . '/img/';
                if(is_dir($imagesPath)){
                    $images = array_diff(scandir($imagesPath), ['.', '..']);
                }

                if(empty($images)) {
                    continue;
                }

                $imageName = '';
                foreach ($images as $image) { //only show first image
                    $imageName = $image;
                    break;
                }
            ?>
            <div class="col-md-3 wow-outer">
                <!-- Post Modern-->
                <article class="post-modern wow slideInLeft">
                    <div class="row spot-img">
                        <img  src="<?= Url::to(['site/post-images', 'postId' => $post['id'], 'fileName' => $imageName]); ?>" alt="" width="571" height="353">
                    </div>
                    <div class="row">
                        <a href="<?=Url::to(['site/view', 'post' => $post['id']])?>">
                            <?=$post['title']?>
                        </a>
                    </div>
                </article>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="row text-center">
            <?php
            echo LinkPager::widget([
                'pagination' => $pagination,
            ]);?>
        </div>
    </div>
</section>


