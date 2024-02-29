<?php

namespace app\models\search;

use app\models\League;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class LeaguesSearch extends League
{
    /**
     * Add relation search attributes
     * @return array
     */
    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'status.id',
            'country.name',
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
                    'name',
                    'country.name',
                    'status.id',
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
        $query = League::find()->alias('l')
            ->select([
                'l.id',
                'l.name',
                'l.countryId',
                'l.statusId'
            ])
            ->joinWith(['country c' => function(ActiveQuery $q){
                $q->select([
                    'c.id',
                    'c.name'
                ]);
            }])
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

        $query->andFilterWhere(['like', 'l.name', $this->name]);
        $query->andFilterWhere(['like', 'c.name', $this->getAttribute('country.name')]);
        $query->andFilterWhere(['st.id' => $this->getAttribute('status.id')]);

        return $dataProvider;
    }
}