<?php
/* @var $this yii\web\View */
?>
<form onsubmit="return false" class="form-inline">
    <div class="form-group input-daterange" id="s-date" data-provide="datepicker">
        <label for="s-date">日期</label>
        <input type="text" class="form-control" name="start" />
        <span>-</span>
        <input type="text" class="form-control" name="end" />
    </div>
    <button type="submit" class="btn btn-primary">筛选</button>
</form>
<table class="table table-striped">
    <thead>
        <tr>
            <th>用户</th>
            <th>日期</th>
            <th width="60%">内容</th>
        </tr>
    </thead>
    <tbody id="list-wrap"></tbody>
</table>
<div id="page"></div>