<?php

namespace app\traits;

use app\models\Status;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Response;

trait StatusTrait
{
    /**
     * @return array of all status
     */
    private function statusList(): array
    {
        $status = Status::find()->select(['id', 'name'])->all();
        return ArrayHelper::map($status, 'id', function ($state){
            return str_replace('_', ' ', $state->name);
        });
    }

    /**
     * @return int id of active status
     */
    private function activeStatus(): int
    {
        $status = Status::find()->where(['name' => 'active'])->one();
        return $status->id;
    }

    /**
     * @return int id of not_active status
     */
    private function notActiveStatus(): int
    {
        $status = Status::find()->where(['name' => 'not_active'])->one();
        return $status->id;
    }

    /**
     * @return int id of archived status
     */
    protected function archivedStatus(): int
    {
        $status = Status::find()->where(['name' => 'archived'])->one();
        return $status->id;
    }

    /**
     * @return int id of deleted status
     */
    protected function deletedStatus(): int
    {
        $status = Status::find()->where(['name' => 'deleted'])->one();
        return $status->id;
    }

    /**
     * @return int id of played status
     */
    protected function playedStatus(): int
    {
        $status = Status::find()->where(['name' => 'played'])->one();
        return $status->id;
    }

    /**
     * @return int id of not played status
     */
    protected function notPlayedStatus(): int
    {
        $status = Status::find()->where(['name' => 'not_played'])->one();
        return $status->id;
    }

    /**
     * @param string $statusName
     * @return int id of status
     */
    protected function getStatusId(string $statusName): int
    {
        $status = Status::find()->where(['name' => $statusName])->one();
        return $status->id;
    }

    /**
     * @param $model
     * @return Response
     */
    private function changeStatus($model): Response
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $post = Yii::$app->request->post();
            switch ($post['status']){
                case 'activate':
                    $this->toggleStatus($model, $post['id'], 'activate');
                    break;
                case 'deactivate':
                    $this->toggleStatus($model, $post['id'], 'deactivate');
                    break;
                case 'restore':
                    $this->toggleStatus($model, $post['id'], 'restore');
                    break;
                case 'archive':
                    $this->toggleStatus($model, $post['id'], 'archive');
                    break;
                case 'delete':
                    $this->toggleStatus($model, $post['id'], 'delete');
                    break;
                case 'played':
                    $this->toggleStatus($model, $post['id'], 'played');
                    break;
                case 'not_played':
                    $this->toggleStatus($model, $post['id'], 'not_played');
                    break;
            }
            $transaction->commit();
            $this->setFlash('success', 'Status update', 'Status updated successfully');
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }catch (Exception $ex){
            $transaction->rollBack();
            $message = $ex->getMessage();
            if(YII_ENV_DEV){
                $message .= ' File: ' . $ex->getFile() . ' Line: ' . $ex->getLine();
            }
            return $this->asJson(['status' => 500, 'message' => $message]);
        }
    }

    /**
     * @param $model
     * @param string $id
     * @param string $status
     * @return void
     * @throws Exception
     */
    private function toggleStatus($model, string $id, string $status)
    {
        $modelInstance = $model::findOne($id);

        if(is_null($modelInstance)){
            throw new Exception('Model not found.');
        }

        if($status === 'activate' || $status === 'restore'){
            $statusId = $this->activeStatus();
        }elseif ($status === 'deactivate'){
            $statusId = $this->notActiveStatus();
        }elseif ($status === 'archive'){
            $statusId = $this->archivedStatus();
        }elseif ($status === 'delete'){
            $statusId = $this->deletedStatus();
        }elseif ($status === 'played'){
            $statusId = $this->playedStatus();
        }elseif ($status === 'not_played'){
            $statusId = $this->notPlayedStatus();
        }

        $modelInstance->statusId = $statusId;
        if(!$modelInstance->save()) {
            throw new Exception('Failed to update status.');
        }
    }
}