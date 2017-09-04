<?php

namespace api\controllers;



use common\models\News;
use yii\db\Query;

class NewsController extends \api\lib\Controller
{
    /**
     * 信息列表
     * @var integer $page 页数,默认1
     * @var integer $pagesize 每页条数，默认10
     * @var string $tags 按标签搜索，多个标签用竖线(|)隔开
     * @var string $q 搜索标题
     * @return string
     */
    public function actionIndex()
    {
        $page = (int)self::getPost('page', 1);
        $pagesize = (int)self::getPost('pagesize', 10);
        $tags = (string)self::getPost('tags', '');
        $q = (string)self::getPost('q', '');

        $query = (new Query())
            ->select( ['id', 'title', 'dateline', 'cover'] )
            ->from('news')
            ->orderBy(['dateline' => SORT_DESC]);

        $query->filterWhere([
            'q' => $q,
        ]);

        if ($tags!=='') {
            $tags = explode('|', $tags);
            $_tags = [];
            foreach ($tags as $tag) {
                $_tags[] = '|' . $tag . '|';
            }
            $query->andFilterWhere(['or like', 'tag', $_tags]);
        }

        $total = $query->count();

        $list = $query
            ->offset(($page-1) * $pagesize)
            ->limit($pagesize)
            ->all();
        if (empty($list)) {
            $list = array();
        }

        foreach ($list as &$item) {
            if ($item['cover']) {
                $item['cover'] = News::getCover($item['cover'], 200);
            }
        }
        unset($item);

        return self::asJson([
            'status' => true,
            'data' => [
                'total' => $total,
                'list' => $list,
            ]
        ]);
    }

}
