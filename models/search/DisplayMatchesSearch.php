<?php

namespace app\models\search;

use app\models\Match;
use Exception;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class DisplayMatchesSearch extends Match
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
            'league.name'
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
                    'awayTeam.name',
                    'homeTeam.name',
                    'league.name',
                ],
                'safe'
            ],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @param array $moreParams
     * @return ActiveDataProvider
     * @throws Exception
     */
    public function search(array $params, array $moreParams): ActiveDataProvider
    {
        $onlyFree = $moreParams['onlyFree'];

        $query = Match::find()->alias('m')
            ->select([
                'm.id',
                'm.leagueId',
                'm.homeTeamId',
                'm.awayTeamId',
                'm.dateTime',
                'm.tipsReasoning',
                'm.statusId'
            ]);

        if($onlyFree){
            $query->where(['m.free' => 1]);
        }else{
            $query->where(['in', 'm.free', [0, 1]]);
        }

        $query->joinWith(['homeTeam ht' => function(ActiveQuery $q){
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
            ->andWhere(['st.name' => 'not_played'])
            ->orderBy(['l.id' => SORT_ASC])
            ->asArray();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pagesize' => 50,
            ],
        ]);

        $this->load($params);

        $query->andFilterWhere(['like', 'ht.name', $this->getAttribute('homeTeam.name')]);
        $query->andFilterWhere(['like', 'at.name', $this->getAttribute('awayTeam.name')]);
        $query->andFilterWhere(['like', 'l.name', $this->getAttribute('league.name')]);

        return $dataProvider;
    }

}