<?php

namespace app\models\search;

use app\models\Team;
use Exception;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class TeamsSearch extends Team
{
    /**
     * Add relation search attributes
     * @return array
     */
    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'country.name',
            'status.id',
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
        $query = Team::find()->alias('t')
            ->select([
                't.id',
                't.name',
                't.countryId',
                't.statusId'
            ])
            ->joinWith(['country c' => function(ActiveQuery $q){
                $q->select([
                    'c.id',
                    'c.name'
                ]);
            }], true, 'INNER JOIN')
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

        $query->andFilterWhere(['like', 't.name', $this->name]);
        $query->andFilterWhere(['like', 'c.name', $this->getAttribute('country.name')]);
        $query->andFilterWhere(['st.id' => $this->getAttribute('status.id')]);

        return $dataProvider;
    }
}