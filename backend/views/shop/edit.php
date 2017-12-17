<?php
/**
 * @var $this yii\web\view
 * @var $model common\models\Shop
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php
$form = ActiveForm::begin([
    'options' => [
        'id' => 'edit-form',
        'enctype' => 'multipart/form-data',
        'class' => 'form-horizontal',
        'action' => Url::to(['shop/edit', [
            'id' => $model->id
        ]])
    ]
])
?>
<div class="form-group">
    <label class="col-sm-2 control-label" for="shop-name">店铺名</label>
    <div class="col-sm-6">
        <input type="text" name="name" id="shop-name" class="form-control" value="<?=Html::encode($model->name)?>" />
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label" for="shop-address">详细地址</label>
    <div class="col-sm-6">
        <input type="text" name="address" id="shop-address" class="form-control" value="<?=Html::encode($model->address)?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label" for="shop-phones">详细</label>
    <div class="col-sm-6">
        <input type="text" name="phones" placeholder="多个手机英文逗号分割" class="form-control" id="shop-phones" value="<?=Html::encode($model->phones)?>" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label" for="shop-desc">店铺描述</label>
    <div class="col-sm-6">
        <textarea class="form-control" id="shop-desc" placeholder="店铺描述" rows="4"><?=$model->description?></textarea>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">位置坐标</label>
    <div class="col-sm-6" id="poi" data-poi="<?=Html::encode($model->poi_id)?>">

    </div>
</div>
<?php ActiveForm::end()?>