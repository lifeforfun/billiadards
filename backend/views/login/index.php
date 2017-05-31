<?php

/**
 * @var $this backend\controllers\LoginController
 */

use yii\helpers\Html;
?>
<!DOCTYPE html>
<html>
<head>
    <title>登录</title>
</head>
<body>
    <?= Html::beginForm(['login/index'], 'post') ?>
        <table>
            <tr>
                <td>用户名：</td>
                <td>
                    <input type="text" name="user" />
                </td>
            </tr>
            <tr>
                <td>密码：</td>
                <td>
                    <input type="password" name="pwd" />
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" value="登录" />
                </td>
            </tr>
        </table>
    <?= Html::endForm()?>
</body>
</html>
