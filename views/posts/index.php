<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 2/29/2024
 * @time: 3:06 PM
 */

/**
 * @var yii\web\View $this
 * @var app\models\Post $model
 * @var yii\data\ActiveDataProvider $postsProvider
 * @var app\models\search\PostsSearch $postsSearch
 * @var string $title
 */

use kartik\grid\GridViewInterface;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\web\ServerErrorHttpException;

$this->title = $title
?>

<!-- Content Header (Page header) -->
<div class="content-header">
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-12">
                <?php
                $gridColumns = [
                    ['class' => 'kartik\grid\SerialColumn'],
                    [
                        'attribute' => 'title',
                        'label' => 'Title',
                    ],
                    [
                        'attribute' => 'slug',
                        'label' => 'Slug'
                    ],
                    [
                        'attribute' => 'created_at',
                        'label' => 'Created',
                        'filterType' => GridViewInterface::FILTER_DATE_RANGE,
                        'format' => 'raw',
                        'vAlign' => 'middle',
                        'filterWidgetOptions' => [
                            'presetDropdown'=>true,
                            'convertFormat'=>true,
                            'includeMonthsFilter'=>true,
                            'pluginOptions' => [
                                'locale' => ['format' => 'Y-m-d'],
                                'separator'=>' to '
                            ],
                            'options' => [
                                'id' => 'posts-grid-created-date'
                            ],
                        ]
                    ],
                    [
                        'attribute' => 'updated_at',
                        'label' => 'Updated',
                        'filterType' => GridViewInterface::FILTER_DATE_RANGE,
                        'format' => 'raw',
                        'vAlign' => 'middle',
                        'filterWidgetOptions' => [
                            'presetDropdown'=>true,
                            'convertFormat'=>true,
                            'includeMonthsFilter'=>true,
                            'pluginOptions' => [
                                'allowClear' => true,
                                'locale' => ['format' => 'Y-m-d'],
                                'separator'=>' to '
                            ],
                            'options' => [
                                'id' => 'posts-grid-verified-date'
                            ]
                        ],
                    ],
                    [
                        'attribute' => 'published_at',
                        'label' => 'Published',
                        'filterType' => GridViewInterface::FILTER_DATE_RANGE,
                        'format' => 'raw',
                        'vAlign' => 'middle',
                        'filterWidgetOptions' => [
                            'presetDropdown'=>true,
                            'convertFormat'=>true,
                            'includeMonthsFilter'=>true,
                            'pluginOptions' => [
                                'allowClear' => true,
                                'locale' => ['format' => 'Y-m-d'],
                                'separator'=>' to '
                            ],
                            'options' => [
                                'id' => 'posts-grid-published-date'
                            ]
                        ],
                    ],
                    [
                        'attribute' => 'deleted_at',
                        'label' => 'Deleted',
                        'filterType' => GridViewInterface::FILTER_DATE_RANGE,
                        'format' => 'raw',
                        'vAlign' => 'middle',
                        'filterWidgetOptions' => [
                            'presetDropdown'=>true,
                            'convertFormat'=>true,
                            'includeMonthsFilter'=>true,
                            'pluginOptions' => [
                                'allowClear' => true,
                                'locale' => ['format' => 'Y-m-d'],
                                'separator'=>' to '
                            ],
                            'options' => [
                                'id' => 'posts-grid-published-date'
                            ]
                        ],
                    ],
                ];
                try {
                    echo GridView::widget([
                        'id' => 'posts-grid',
                        'dataProvider' => $postsProvider,
                        'filterModel' => $postsSearch,
                        'columns' => $gridColumns,
                        'headerRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                        'filterRowOptions' => ['class' => 'kartik-sheet-style grid-header'],
                        'pjax' => true,
                        'responsiveWrap' => false,
                        'condensed' => true,
                        'hover' => true,
                        'striped' => false,
                        'bordered' => false,
                        'toolbar' => [
                            [
                                'content' => Html::a('<i class="fa fa-plus" aria-hidden="true">&nbsp;</i> new post',
                                    Url::to(['/posts/create']),
                                    [
                                        'title' => 'Create new post',
                                        'class' => 'btn btn-block btn-sm'
                                    ]
                                 ),
                                'options' => ['class' => 'btn-group mr-2']
                            ],
                            '{toggleData}',
                        ],
                        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
                        'export' => false,
                        'panel' => [
                            'heading' => 'Posts',
                        ],
                        'persistResize' => false,
                        'toggleDataOptions' => ['minCount' => 50],
                        'itemLabelSingle' => 'post',
                        'itemLabelPlural' => 'posts',
                    ]);
                } catch (Exception $ex) {
                    $message = $ex->getMessage().' File: '.$ex->getFile().' Line: '.$ex->getLine();
                    throw new ServerErrorHttpException($message, 500);
                }
                ?>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
</section>
<!-- /.content -->
