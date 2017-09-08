<?php

namespace backend\controllers;

use common\models\Feedback;
use common\models\User;

class FeedbackController extends \backend\lib\Controller
{
    public function actionIndex()
    {
        $this->registerJsFile('feed/index.js');
        return $this->render('index');
    }

    public function actionList()
    {
        $query = Feedback::find()
            ->andFilterWhere(['>=', 'dateline', self::getQuery('start')])
            ->andFilterWhere(['<=', 'dateline', self::getQuery('end')]);
        $total = $query->count('id');
        $page = (int)self::getQuery('page', 1);
        $pagesize = (int)self::getQuery('pagesize', 10);
        $list = $query
            ->orderBy(['dateline' => SORT_DESC])
            ->offset( ($page-1) * $pagesize )
            ->limit( $pagesize )
            ->asArray()
            ->all();
        foreach ($list as &$item) {
            $user = User::findOne(['uid' => $item['uid']]);
            if ($user)
                $item['uname'] = $user->uname;
        }

        return $this->asJson([
            'status' => true,
            'data' => [
                'total' => $total,
                'list' => $list
            ]
        ]);
    }
}
