<?php
/* @var $this yii\web\View */
/* @var $model \common\models\News */
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
            'action' => Url::to(['news/edit', ['id' => $model->id]])
        ]
    ]
)
?>
    <div class="form-group">
        <label for="title">标题</label>
        <input type="text" class="form-control" id="title"/>
    </div>
    <div class="form-group">
        <label for="dateline">发布日期</label>
        <input type="text" class="form-control" id="dateline"/>
    </div>
        <?= $form->field($model, 'cover')->fileInput(['id' => 'cover']) ?>
        <p class="help-block">
            上传750*425左右的尺寸
            <?php if ($model): ?>
                <a href="" target="_blank"></a>
            <?php endif; ?>
        </p>
<?php ActiveForm::end() ?>