<?php
/**
 * @var yii\web\View $this
 * @var string $title
 * @var string $postId
 * @var string $path
 * @var array $files
 * @var app\models\Post $post
 */

use yii\helpers\Url;

$this->title = $title;
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="page-header">
        <h1> Post Title: <?=$post->title?> </h1>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-lg-10 offset-md-1 offset-lg-1">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <div class="row" style="margin-bottom: 20px;">
                            <div class="col-12">
                                <a href="<?=Url::to(['/posts/edit', 'id' => $postId])?>"  title="Edit post" id="btn-clear-student" class="btn btn-success float-right">
                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                    Edit post
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <?php if (!empty($files['images'])):
                                foreach ($files['images'] as $image):
                                    $src = '../../uploads/attachments/';
                                    $src .= $postId;
                                    $src .= '/img/';
                                    $src .= $image;
                            ?>
                            <div class="col-3 mb-3">
                                <img class="img-fluid" src="<?=$src?>" alt="Photo">
                                <a href="javascript:void(0)" data-type="img" data-file-name="<?=$image?>" target="_blank"
                                   class="btn btn-link delete-attachment">
                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                    Click on this link to delete
                                </a>
                            </div>
                            <?php endforeach;
                            endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-10 col-lg-10 offset-md-1 offset-lg-1">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <div class="row">
                            <?php if(!empty($files['docs'])):?>
                                <div class="col-6">
                                    <div>
                                        <p class="text-success">Click on a document to download</p>
                                    </div>
                                    <?php foreach ($files['docs'] as $doc): ?>
                                        <a href="<?= Url::to(['/posts/download-attachment', 'fileType' => 'doc', 'postId' => $postId, 'docName' => $doc])?>"
                                           target="_blank" class="btn btn-link">
                                            <i class="fas fa-download" aria-hidden="true"></i>
                                            <?=$doc?>
                                        </a>
                                    <?php endforeach;?>
                                </div>

                                <div class="col-6">
                                    <div>
                                        <p class="text-danger">Click on a document to delete</p>
                                    </div>
                                    <?php foreach ($files['docs'] as $doc): ?>
                                        <a href="javascript:void(0)" data-type="doc" data-file-name="<?=$doc?>" target="_blank"
                                           class="btn btn-link delete-attachment">
                                            <i class="fas fa-trash" aria-hidden="true"></i>
                                            <?=$doc?>
                                        </a>
                                    <?php endforeach;?>
                                </div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
$deleteAttachmentUrl = Url::to(['/posts/delete-attachment']);
$attachmentsJs = <<< JS
const deleteAttachmentUrl = '$deleteAttachmentUrl';
const postId = '$postId';

$('.delete-attachment').on('click', function(e) {
    e.preventDefault();
    if(confirm('Delete this file?')) {
        $.ajax({
            url: deleteAttachmentUrl,
            type: 'POST',
            data: {
                'postId': postId,
                'fileType': $(this).attr('data-type'),
                'fileName': $(this).attr('data-file-name')
            }
        }).done(function (data){
        }).fail(function (data){
            console.error(data.responseText);
        });
    }
});
JS;
$this->registerJs($attachmentsJs, yii\web\View::POS_READY);

