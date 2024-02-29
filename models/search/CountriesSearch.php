<?php

namespace app\models\search;

use app\models\Country;
use Exception;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class CountriesSearch extends Country
{
    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'status.id',
            'status.name'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [
                [
                    'id',
                    'name',
                    'status.id',
                    'status.name'
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
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     * @throws Exception
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Country::find()->alias('c')->select([
                'c.id',
                'c.name',
                'c.statusId'
            ])
            ->joinWith(['status st' => function(ActiveQuery $q){
                $q->select([
                    'st.id',
                    'st.name'
                ]);
            }], true, 'INNER JOIN')
            ->orderBy(['c.id' => SORT_ASC])
            ->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pagesize' => 20,
            ],
        ]);

        $this->load($params);

        $query->andFilterWhere(['like', 'c.name', $this->name]);
        $query->andFilterWhere(['st.id' => $this->getAttribute('status.id')]);

        return $dataProvider;
    }
}