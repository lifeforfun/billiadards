<?php
/**
 * @var $this yii\web\View
 */


$this->title = '信息列表';
?>
<form class="form-inline">
    <div class="form-group">
        <label for="s-title">标题</label>
        <input type="text" name="title" class="form-control" id="s-title" placeholder="关键字" />
    </div>
    <div class="form-group" id="s-date">
        <label for="s-date">日期</label>
        <input type="text" class="form-control" name="start" />
        <span>-</span>
        <input type="text" class="form-control" name="end" />
    </div>
</form>
<table class="table table-striped">

</table>
