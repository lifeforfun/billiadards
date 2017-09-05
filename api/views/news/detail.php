<?php
/**
 * @var $this \api\lib\Controller
 */

use yii\helpers\Html;

$this->title = $detail['title'];
?>
<div id="carousel" class="carousel">
    <ul class="carousel-indicators">
        <?php foreach ($detail['pic'] as $pic):?>
        <li></li>
        <?php endforeach;?>
    </ul>
    <div class="carousel-inner">
        <?php foreach ($detail['pic'] as $pic):?>
            <div class="carousel-item">
                <img src="<?=$pic['thumb']?>" alt="">
            </div>
        <?php endforeach;?>
    </div>
</div>

<div class="detail-title"><?=$detail['title']?></div>
<div class="detail-dateline"><?=$detail['dateline']?></div>

<?php if($detail['video']):?>
    <video class="detail-video" src="<?=$detail['video']?>"></video>
<?php endif?>

<hr style="background:darkgray;margin:10px" />
<div class="detail-content"><?=nl2br($detail['content'])?></div>
<hr style="background:#eee;height:10px" />
<div class="detail-relate-t">相关新闻</div>
<?php foreach ($detail['relate'] as $item):?>
<div class="media">
    <?php if($item['cover']):?>
    <div class="media-left">
        <a href="<?=Yii::$app->urlManager->createUrl(['news/detail', 'id' => $item['id']])?>">
            <img class="media-object" src="<?=$item['cover']?>" alt="<?=Html::encode($item['title'])?>">
        </a>
    </div>
    <?php endif;?>
    <div class="media-body">
        <h4 class="media-heading">
            <a href="<?=Yii::$app->urlManager->createUrl(['news/detail', 'id' => $item['id']])?>">
                <?=Html::encode($item['title'])?>
            </a>
        </h4>
        <div class="media-subtitle"><?=Html::encode($item['dateline'])?></div>
    </div>
</div>
<?php endforeach;?>
<br />
<br />
