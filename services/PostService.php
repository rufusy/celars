<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 3/2/2024
 * @time: 1:39 PM
 */

namespace app\services;

use app\models\Post;
use app\traits\DateTrait;
use app\traits\ErrorsTrait;
use Exception;
use Yii;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

final class PostService
{
    use DateTrait, ErrorsTrait;

    /**
     * @param array $postedData
     * @return void
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     * @throws \yii\db\Exception
     * @throws Exception
     */
    public function storeOrUpdate(array $postedData): void
    {
        $transaction = Yii::$app->db->beginTransaction();

        $currentDate = $this->formatDate('now', 'Y-m-d h:m:s');

        if (array_key_exists('id', $postedData)) {
            $post = $this->findPostById($postedData['id']);
        } else {
            $post = new Post();
            $post->created_at = $currentDate;
        }

        $post->title = $postedData['title'];
        $post->body = $postedData['body'];
        $post->updated_at = $currentDate;
        if($postedData['publishStatus'] === 'final') {
            $post->published_at = $currentDate;
        } else {
            $post->published_at = null;
        }

        if (!$post->save()) {
            $transaction->rollBack();
            if (!$post->validate()) {
                throw new UnprocessableEntityHttpException($this->stringifyModelErrors($post->getErrors()));
            } else {
                throw new ServerErrorHttpException('Post failed to save or update.');
            }
        }

        try {
            // check for empty attachments before trying to save files
            if (!empty($postedData['images']['name'][0])) {
                $this->uploadAttachments($post->id, 'image', $_FILES['images']);
            }
            if (!empty($postedData['documents']['name'][0])) {
                $this->uploadAttachments($post->id, 'document', $_FILES['documents']);
            }
        } catch (ServerErrorHttpException $ex) {
            $transaction->rollBack();
            throw new ServerErrorHttpException($ex->getMessage());
        }

        $transaction->commit();
    }

    /**
     * @param string $postId
     * @return void
     * @throws ServerErrorHttpException
     * @throws \yii\db\Exception
     * @throws Exception
     */
    public function delete(string $postId): void
    {
        $transaction = Yii::$app->db->beginTransaction();

        $post = Post::findOne($postId);
        $post->deleted_at = $this->formatDate('now', 'Y-m-d h:m:s');
        if(!$post->save()) {
            $transaction->rollBack();
            throw new ServerErrorHttpException('Post failed to delete.');
        }
        $transaction->commit();
    }

    /**
     * @param string $id
     * @return array|ActiveRecord|null
     * @throws NotFoundHttpException
     */
    public function findPostById(string $id): array|ActiveRecord|null
    {
        $post = Post::findOne($id);
        if (!$post) {
            throw new NotFoundHttpException('Post not found.');
        }
        return $post;
    }

    /**
     * @param string $postId
     * @return array[]
     */
    public function viewAttachments(string $postId): array
    {
        $docs = [];
        $images = [];

        $imagesPath = Yii::getAlias('@attachmentsUrl') . $postId . '/img/';
        if(is_dir($imagesPath)){
            $images = array_diff(scandir($imagesPath), ['.', '..']);
        }

        $docsPath = Yii::getAlias('@attachmentsUrl') . $postId . '/doc/';
        if(is_dir($docsPath)){
            $docs = array_diff(scandir($docsPath), ['.', '..']);
        }

        return [
            'docs' => $docs,
            'images' => $images
        ];
    }

    /**
     * @throws ServerErrorHttpException
     */
    private function uploadAttachments(string $postId, string $attachmentType, array $files): void
    {
        /**
         * File paths take the format:
         * uploads/attachments/1/img/file.ext
         * where:
         * 1 is the post id
         * img is the file type. can also be doc.
         */
        $path = Yii::getAlias('@attachmentsUrl') . $postId . '/img/';
        $validExtensions = ['jpeg', 'jpg', 'png'];

        if ($attachmentType === 'document') {
            $path = Yii::getAlias('@attachmentsUrl') . $postId . '/doc/';
            $validExtensions = ['pdf'];
        }

        if(!is_dir($path)) {
            if(!mkdir($path, 0777, true)) {
                throw new ServerErrorHttpException('Failed to create uploads directory.');
            }
        }

        for ($idx = 0; $idx < count($files['name']); $idx++) {

            if($files['error'][$idx] !== 0){
                throw new ServerErrorHttpException('File error code: ' . $files['error'][$idx]);
            }

            $ext = strtolower(pathinfo($files['name'][$idx], PATHINFO_EXTENSION));
            if(!in_array($ext, $validExtensions)){
                throw new ServerErrorHttpException($ext . ' file is not allowed as an attachment of type ' . $attachmentType);
            }

            $newFileName = strtolower(pathinfo($files['name'][$idx], PATHINFO_FILENAME));
            $newFileName = preg_replace('/\s/','_', $newFileName);
            $newFileName .= '.' . $ext;

            $destinationFile = $path . $newFileName;

            if(!move_uploaded_file($files['tmp_name'][$idx], $destinationFile)){
                throw new ServerErrorHttpException('Document failed to uploaded.');
            }
        }
    }
}