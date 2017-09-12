<?php
/* @var $this yii\web\View */
/* @var $model \common\models\News */
/* @var $video \common\models\UploadFile */
/* @var $pics \common\models\UploadFile[] */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\block\ThumbTrait;
use yii\helpers\Html;

?>
<?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'edit-form',
            'enctype' => 'multipart/form-data',
            'class' => 'form-horizontal',
            'action' => Url::to(['news/edit', ['id' => $model->id]])
        ]
    ]
)
?>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="title">标题</label>
        <div class="col-sm-6">
            <input type="text" name="title" id="title" class="form-control" value="<?=Html::encode($model->title)?>"/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="dateline">发布日期</label>
        <div class="col-sm-6">
            <input type="text" data-provide="datepicker" name="dateline" id="dateline" value="<?=$model->dateline?>" class="form-control" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="video">视频</label>
        <div class="col-sm-4">
            <input type="file" id="video" accept="*.mp4,*.avi,*.flv" name="video" />
        </div>
        <div class="col-sm-2">
            <?php if($video):?>
                <a href="<?php echo $video->url?>" target="_blank">查看视频</a>
            <?php endif;?>
        </div>
    </div>
    <?php for ($i=0;$i<9;++$i) :?>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="pic-<?=$i?>">图片</label>
        <div class="col-sm-4">
            <input type="file" id="pic-<?=$i?>" accept="*.png,*.gif,*.jpg" name="pic[<?=$i?>]" />
        </div>
        <div class="col-sm-2">
            <?php if(isset($pics[$i]) && $pic = $pics[$i]):?>
                <a href="<?=$pic->url?>" target="_blank">
                    <img src="<?=ThumbTrait::getThumb($pic->url, 'small')?>" />
                </a>
            <?php endif?>
        </div>
    </div>
    <?php endfor;?>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="tag">标签</label>
        <div class="col-sm-6">
            <input type="text" name="tag" id="tag" class="form-control" value="<?=Html::encode($model->tag)?>" placeholder="多个关键字用竖线(|)分隔" />
        </div>
    </div>
    <div class="form-group">
        <label for="icontent" class="col-sm-2 control-label">内容</label>
        <div class="col-sm-6">
            <textarea
                    id="icontent"
                    name="content"
                    placeholder="内容"
                    rows="10"
                    class="form-control"
            ><?=Html::encode($model->content)?></textarea>
        </div>
    </div>
    <div class="form-group">
        <lable class="col-sm-2"></lable>
        <div class="col-sm-6">
            <button type="submit" class="btn btn-primary btn-large">保存</button>
        </div>
    </div>

<?php ActiveForm::end() ?>