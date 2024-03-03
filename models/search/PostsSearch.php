<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 2/29/2024
 * @time: 4:31 PM
 */

namespace app\models\search;

use app\models\Post;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

final class PostsSearch extends Post
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [
                [
                    'id',
                    'title',
                    'created_at',
                    'updated_at',
                    'published_at',
                    'deleted_at',
                ],
                'safe'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = (new Query())
            ->select(['p.id', 'p.title', 'p.created_at', 'p.updated_at', 'p.published_at', 'p.deleted_at'])
            ->from(self::tableName() . ' p')
            ->where(['p.deleted_at' => null]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pagesize' => 50,
            ],
        ]);

        $this->load($params);

        if(!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'p.title', $this->title]);

        return $dataProvider;
    }
}