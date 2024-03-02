<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 2/29/2024
 * @time: 8:55 PM
 */

/**
 * @var yii\web\View $this
 * @var string $title
 * @var app\models\Post $post
 */

use yii\helpers\Url;

$this->title = $title;
?>

<div class="content-header">
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-lg-10 offset-md-1 offset-lg-1">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Edit Post
                        </h3>
                    </div>
                    <form id="edit-post-form" method="post" action="<?=Url::to(['/posts/update'])?>"
                          enctype="multipart/form-data">
                        <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                        <input type="hidden" name="id" value="<?=$post->id?>" />
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title" class="required-control-label">Title</label>
                                <input type="text" class="form-control" required name="title" id="title" value="<?=$post->title?>">
                            </div>
                            <div class="form-group">
                                <label for="images">Image(s)</label>
                                <input type="file" class="form-control" id="images" multiple name="images[]">
                            </div>
                            <div class="form-group">
                                <label for="documents">Document(s)</label>
                                <input type="file" class="form-control" id="documents" multiple name="documents[]">
                            </div>
                            <div class="form-group">
                                <label for="summernote" class="required-control-label">Body</label>
                                <textarea id="summernote" class="form-control" required name="body" rows="20">
                                    <?=$post->body?>
                                </textarea>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="draft" name="publishStatus"
                                    value="draft" <?=(is_null($post->published_at)) ? 'checked': '';?>>
                                    <label for="draft" class="custom-control-label">Save as draft</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="publish" name="publishStatus"
                                    value="final" <?=(!is_null($post->published_at)) ? 'checked': '';?>>
                                    <label for="publish" class="custom-control-label">Publish now</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" id="submit-post" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
$newPostJs = <<< JS
$('#summernote').summernote();

$('#edit-post-form').on('submit', function(e){
    e.preventDefault();
    if($('#title').val() === '' || $('#body').val() === ''){
        alert('All mandatory fields should be provided.')
    }else{
        this.submit();
    }
});
JS;
$this->registerJs($newPostJs, yii\web\View::POS_READY);