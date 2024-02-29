<?php

/**
 * @author Rufusy Idachi
 * @email idachirufus@gmail.com
 * @create date 25-09-2021 01:06:57 
 * @modify date 25-09-2021 01:06:57 
 * @desc manage users
 */

/**
 * @var yii\web\View $this
 * @var app\models\User $model
 * @var yii\data\usersDataProvider $usersDataProvider
 * @var app\models\search\UsersSearch $userSearchModel
 * @var string $title
 * @var app\models\Status $listOfStatus[]
 * @var string $modelStatus
 */

include_once Yii::getAlias('@views') . '/includes/_gridHelpers.php';

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\web\ServerErrorHttpException;

$this->title = $title;
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
                        'attribute' => 'firstName',
                        'label' => 'First name',
                    ],
                    [
                        'attribute' => 'lastName',
                        'label' => 'Last name'
                    ],
                    [
                        'attribute' => 'email',
                        'label'=> 'Email'
                    ],
                    [
                        'attribute' => 'mobileNumber',
                        'label' => 'Mobile number',
                        'value' => function($model){
                            if(empty($model['mobileNumber'])){
                                return '';
                            }else{
                                return $model['mobileNumber'];
                            }
                        }
                    ],
                    [
                        'attribute' => 'createdAt',
                        'label' => 'Created',
                        'filterType' => GridView::FILTER_DATE_RANGE,
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
                                'id' => 'users-grid-created-date'
                            ],
                        ]
                    ],
                    [
                        'attribute' => 'emailVerifiedAt',
                        'label' => 'Verified',
                        'filterType' => GridView::FILTER_DATE_RANGE,
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
                                'id' => 'users-grid-verified-date'
                            ]
                        ],
                        'value' => function($model){
                            $emailVerifiedAt = $model['emailVerifiedAt'];
                            if(is_null($emailVerifiedAt)){
                                return 'not verified';
                            }else{
                                return $emailVerifiedAt;
                            }
                        }
                    ],
                    [
                        'attribute' => 'status.id',
                        'label' => 'Status',
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => $listOfStatus,
                        'vAlign' => 'middle',
                        'format' => 'raw',
                        'width' => '30px',
                        'filterWidgetOptions' => [
                            'options'=>[
                                'id' => 'users-grid-status',
                                'placeholder' => '--- all ---'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'autoclose' => true
                            ]
                        ],
                        'value' => $modelStatus
                    ],
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'template' => '{update} {activate} {delete}',
                        'contentOptions' => [
                            'style'=>'white-space:nowrap;',
                            'class'=>'kartik-sheet-style kv-align-middle'
                        ],
                        'buttons' => [
                            'update' => function ($url, $model){
                                $status = $model['status']['name'];
                                if($status === 'active' || $status === 'not_active'){
                                    return Html::button('<i class="fas fa-edit"></i>', [
                                        'title' => 'Update user',
                                        'href' => Url::to(['/users/edit', 'id' => $model['id']]),
                                        'class' => 'btn btn-sm update-user-btn action-text-info'
                                    ]);
                                }
                            },
                            'activate' => function($url, $model){
                                $status = $model['status']['name'];
                                if ($status === 'not_active') {
                                    return Html::button('<i class="fa fa-check" aria-hidden="true"></i>', [
                                        'title' => 'Activate user',
                                        'href' => Url::to(['/users/change-status']),
                                        'data-id' => $model['id'],
                                        'data-status' => 'activate',
                                        'class' => 'btn btn-sm change-status-btn action-text-success'
                                    ]);
                                } elseif($status === 'active') {
                                    return Html::button('<i class="fas fa-ban"></i>', [
                                        'title' => 'Deactivate user',
                                        'href' => Url::to(['/users/change-status']),
                                        'data-id' => $model['id'],
                                        'data-status' => 'deactivate',
                                        'class' => 'btn btn-sm change-status-btn action-text-danger'
                                    ]);
                                }
                            },
                            'delete' => function ($url, $model){
                                $status = $model['status']['name'];
                                if($status === 'active' || $status === 'not_active'){
                                    return Html::button('<i class="fa fa-archive" aria-hidden="true"></i>', [
                                        'title' => 'Archive user',
                                        'href' => Url::to(['/users/change-status']),
                                        'data-id' => $model['id'],
                                        'data-status' => 'archive',
                                        'class' => 'btn btn-sm change-status-btn action-text-archive'
                                    ]);
                                }elseif($status === 'archived'){
                                    return Html::button('<i class="fa fa-undo" aria-hidden="true"></i>', [
                                        'title' => 'Restore user',
                                        'href' => Url::to(['/users/change-status']),
                                        'data-id' => $model['id'],
                                        'data-status' => 'restore',
                                        'class' => 'btn btn-sm change-status-btn action-text-success'
                                    ]).'&nbsp;'.
                                    Html::button('<i class="fa fa-trash" aria-hidden="true"></i>', [
                                        'title' => 'Delete user',
                                        'href' => Url::to(['/users/change-status']),
                                        'data-id' => $model['id'],
                                        'data-status' => 'delete',
                                        'class' => 'btn btn-sm change-status-btn action-text-danger'
                                    ]);
                                }
                            }
                        ],
                        'hAlign' => 'center',
                    ]
                ];
            try {
                echo GridView::widget([
                    'id' => 'users-grid',
                    'dataProvider' => $usersDataProvider,
                    'filterModel' => $userSearchModel,
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
                            'content' => Html::button('<i class="fas fa-plus"></i> user', [
                                'title' => 'Create new user',
                                'id' => 'new-user-btn',
                                'class' => 'btn btn-block btn-sm',
                                'href' => Url::to(['/users/create']),
                            ]),
                            'options' => ['class' => 'btn-group mr-2']
                        ],
                        '{export}',
                        '{toggleData}',
                    ],
                    'toggleDataContainer' => ['class' => 'btn-group mr-2'],
                    'export' => [
                        'fontAwesome' => false,
                        'label' => 'Export users'
                    ],
                    'panel' => [
                        'heading' => 'Users',
                    ],
                    'persistResize' => false,
                    'toggleDataOptions' => ['minCount' => 20],
                    'itemLabelSingle' => 'user',
                    'itemLabelPlural' => 'users',
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

<?php
echo $this->render('../includes/createUpdateResourceModal');
echo $this->render('../includes/appIsLoading');

$usersJs = <<< JS
$('#users-grid-pjax').on('click', '#new-user-btn', function(e){
    createOrUpdateModal.call(this, e, 'New user');
});
        
$('#users-grid-pjax').on('click', '.update-user-btn', function(e){
    createOrUpdateModal.call(this, e, 'Update user');
});
        
$('#users-grid-pjax').on('click', '.change-status-btn', function (e){
    changeStatus.call(this, e, 'user');
});
JS;
$this->registerJs($usersJs, yii\web\View::POS_READY);


