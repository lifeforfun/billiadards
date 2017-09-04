<?php
/* @var $this yii\web\View */
/* @var $model \common\models\News */
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'edit-form',
            'enctype' => 'multipart/form-data',
            'action' => Url::to(['news/edit', ['id' => $model->id]])
        ]
    ]
)
?>
    <div class="form-group">
        <label for="title">标题</label>
        <input type="text" name="title" id="title" class="form-control" value="<?=$model->title?>"/>
    </div>
    <div class="form-group">
        <label for="dateline">发布日期</label>
        <input type="text" data-provide="datepicker" name="dateline" id="dateline" value="<?=$model->dateline?>" class="form-control" />
    </div>
    <?= $form->field($model, 'cover')->fileInput(['id' => 'cover', 'name' => 'cover']) ?>
    <p class="help-block">
        上传750*425左右的尺寸
        <?php if ($model->cover): ?>
            <a href="<?= $model::getCover($model->cover)?>" target="_blank">查看</a>
        <?php endif; ?>
    </p>
    <div class="form-group">
        <label for="tag">标签</label>
        <input type="text" name="tag" id="tag" class="form-control" value="<?=$model->getTag()?>" placeholder="多个关键字用竖线(|)分隔" />
    </div>
    <div class="form-group">
        <textarea
                name="content"
                placeholder="内容"
                rows="10"
                style="width: 100%;"
        ><?=$model->content?></textarea>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary btn-large">保存</button>
    </div>

<?php ActiveForm::end() ?>