<?php

namespace app\models\search;

use app\models\Match;
use DateTime;
use Exception;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class MatchesSearch extends Match
{
    /**
     * Add relation search attributes
     * @return array
     */
    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'awayTeam.name',
            'homeTeam.name',
            'league.name',
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
                    'dateTime',
                    'featured',
                    'free',
                    'awayTeam.name',
                    'homeTeam.name',
                    'league.name',
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
     * @throws Exception
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Match::find()->alias('m')
            ->select([
                'm.id',
                'm.leagueId',
                'm.homeTeamId',
                'm.awayTeamId',
                'm.dateTime',
                'm.featured',
                'm.free',
                'm.tipsReasoning',
                'm.statusId'
            ])
            ->joinWith(['homeTeam ht' => function(ActiveQuery $q){
                $q->select([
                   'ht.id',
                   'ht.name'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['awayTeam at' => function(ActiveQuery $q){
                $q->select([
                    'at.id',
                    'at.name'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['league l' => function(ActiveQuery $q){
                $q->select([
                    'l.id',
                    'l.name'
                ]);
            }], true, 'INNER JOIN')
            ->joinWith(['status st' => function(ActiveQuery $q){
                $q->select([
                    'st.id',
                    'st.name'
                ]);
            }], true, 'INNER JOIN')
            ->where(['in', 'st.name', ['not_played', 'played']])
            ->orderBy(['l.id' => SORT_ASC])
            ->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pagesize' => 20,
            ],
        ]);

        $this->load($params);

        if(!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['free' => $this->free]);
        $query->andFilterWhere(['like', 'ht.name', $this->getAttribute('homeTeam.name')]);
        $query->andFilterWhere(['like', 'at.name', $this->getAttribute('awayTeam.name')]);
        $query->andFilterWhere(['like', 'l.name', $this->getAttribute('league.name')]);
        $query->andFilterWhere(['st.id' => $this->getAttribute('status.id')]);

        if(!empty($params['MatchesSearch']['dateTime'])) {
            $matchDate = $params['MatchesSearch']['dateTime'];
            $matchDateStart = new DateTime(substr($matchDate, 0, 10));
            $matchDateEnd = new DateTime(substr($matchDate, 13));
            $query->andFilterWhere(['between', 'm.dateTime', $matchDateStart->format('Y-m-d'),
                $matchDateEnd->format('Y-m-d')]);
        }

        return $dataProvider;
    }
}