<?php

namespace app\models\search;

use app\models\Tip;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class TipsSearch extends Tip
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [
                [
                    'name'
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
     * Creates data provider instance with search query applied
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Tip::find()->alias('t')->select(['t.id', 't.name'])
            ->orderBy(['t.id' => SORT_ASC])->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pagesize' => 20,
            ],
        ]);

        $this->load($params);

        $query->andFilterWhere(['like', 't.name', $this->name]);

        return $dataProvider;
    }
}