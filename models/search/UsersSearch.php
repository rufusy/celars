<?php

/**
 * @author Rufusy Idachi
 * @email idachirufus@gmail.com
 * @create date 25-09-2021 00:38:28 
 * @modify date 25-09-2021 00:38:28 
 * @desc [description]
 */

namespace app\models\search;

use DateTime;
use Exception;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use app\models\User;
use yii\db\ActiveQuery;

class UsersSearch extends User
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
                    'firstName',
                    'lastName',
                    'email',
                    'emailVerifiedAt',
                    'createdAt',
                    'status',
                    'mobileNumber',
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
        $query = User::find()->alias('u')->select([
                'u.id',
                'u.firstName',
                'u.lastName',
                'u.email',
                'u.emailVerifiedAt',
                'u.createdAt',
                'u.mobileNumber',
                'u.statusId'
            ])
            ->joinWith(['status st' => function(ActiveQuery $q){
                $q->select([
                    'st.id',
                    'st.name'
                ]);
            }], true, 'INNER JOIN')
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

        $query->andFilterWhere(['like', 'u.firstName', $this->firstName]);
        $query->andFilterWhere(['like', 'u.lastName', $this->lastName]);
        $query->andFilterWhere(['like', 'u.email', $this->email]);
        $query->andFilterWhere(['like', 'u.mobileNumber', $this->mobileNumber]);
        $query->andFilterWhere(['st.id' => $this->getAttribute('status.id')]);

        if(!empty($params['UsersSearch']['createdAt'])) {
            $createdAt = $params['UsersSearch']['createdAt'];
            $createdAtStart = new DateTime(substr($createdAt, 0, 10));
            $createdAtEnd = new DateTime(substr($createdAt, 13));
            $query->andFilterWhere(['between', 'u.createdAt', $createdAtStart->format('Y-m-d'),
                $createdAtEnd->format('Y-m-d')]);
        }

        if(!empty($params['UsersSearch']['emailVerifiedAt'])){
            $emailVerifiedAt = $params['UsersSearch']['emailVerifiedAt'];
            $emailVerifiedAtStart = new DateTime(substr($emailVerifiedAt, 0,10));
            $emailVerifiedAtEnd = new DateTime(substr($emailVerifiedAt, 13));
            $query->andFilterWhere(['between', 'u.emailVerifiedAt', $emailVerifiedAtStart->format('Y-m-d'),
                $emailVerifiedAtEnd->format('Y-m-d')]);
        }

        $query->orderBy(['u.id' => SORT_ASC]);

        return $dataProvider;
    }
}
