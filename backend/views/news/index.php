<?php
/**
 * @var $this yii\web\View
 */

use yii\helpers\Url;

?>
<form class="form-inline" onsubmit="return false">
    <div class="form-group">
        <label for="s-title">标题</label>
        <input type="text" name="title" class="form-control" id="s-title" placeholder="关键字" />
    </div>
    <div class="form-group input-daterange" id="s-date" data-provide="datepicker">
        <label for="s-date">日期</label>
        <input type="text" class="form-control" name="start" />
        <span>-</span>
        <input type="text" class="form-control" name="end" />
    </div>
    <div class="form-group">
        <select id="s-status" name="status" class="form-control">
            <option value="">状态</option>
            <option value="0">未审核</option>
            <option value="1">已通过</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">筛选</button>
    <a href="<?= Url::to(['news/edit'])?>" class="btn btn-success">
        <i class="glyphicon glyphicon-plus"></i>
        新增信息
    </a>
</form>
<table class="table table-striped">
    <thead>
        <tr>
            <th>标题</th>
            <th>发布日期</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody id="list-wrap"></tbody>
</table>
<div id="page"></div>