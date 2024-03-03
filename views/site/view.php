<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 3/3/2024
 * @time: 2:34 PM
 */

/**
 * @var string $title
 * @var app\models\Post $post
 */

use yii\helpers\Url;

$this->title = $title;

$images = [];
$imagesPath = Yii::getAlias('@attachmentsUrl') . $post->id . '/img/';
if(is_dir($imagesPath)){
    $images = array_diff(scandir($imagesPath), ['.', '..']);
}

$docs = [];
$docsPath = Yii::getAlias('@attachmentsUrl') . $post->id . '/doc/';
if(is_dir($docsPath)){
    $docs = array_diff(scandir($docsPath), ['.', '..']);
}
?>
<!-- Left aligned image-->
<section class="section novi-background section-sm">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-md-10 offset-md-2 offset-lg-2">
                <h6><?=$post->title?></h6>
                <div class="row">
                    <div class="col-md-6 col-lg-6">
                        <?php if(!empty($docs)):?>
                        <div class="row">
                            <?php foreach ($docs as $doc):?>
                                    <a href="<?= Url::to(['/site/download-attachment', 'fileType' => 'doc', 'postId' => $post->id, 'docName' => $doc])?>"
                                       target="_blank" class="btn btn-link">
                                        <?=$doc?>
                                    </a>
                            <?php endforeach;?>
                        </div>
                        <?php endif;?>
                        <?php if(!empty($images)): ?>
                        <div class="row">
                            <?php foreach ($images as $image):?>
                                <div class="col-md-6 col-lg-6">
                                    <img
                                        src="<?= Url::to(['site/post-images', 'postId' => $post->id, 'fileName' => $image]); ?>"
                                        alt="" width="300" height="100"/>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <p> <?=$post->body?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
