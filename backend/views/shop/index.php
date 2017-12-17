<?php
/* @var $this yii\web\View */

use yii\helpers\Url;

?>
<form class="form-inline" onsubmit="return false">
    <div class="form-group">
        <label for="s-name">店铺</label>
        <input type="text" name="name" id="s-name" class="form-control" placeholder="店铺名" />
    </div>
    <button type="submit" class="btn btn-primary">筛选</button>
    <a href="<?=Url::to(['shop/edit']) ?>" class="btn btn-success">
        <i class="glyphicon glyphicon-plus"></i>
        添加店铺
    </a>
</form>
<table class="table table-striped">
    <thead>
    <tr>
        <th width="30%">ID</th>
        <th>店铺名</th>
        <th width="20%">操作</th>
    </tr>
    </thead>
    <tbody id="list-wrap"></tbody>
</table>
<div id="page"></div>