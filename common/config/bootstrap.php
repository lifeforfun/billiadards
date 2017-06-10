<?php
$root = dirname(dirname(__DIR__));
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', $root . '/frontend');
Yii::setAlias('@backend', $root . '/backend');
Yii::setAlias('@console', $root . '/console');
Yii::setAlias('@api', $root . '/api');
Yii::setAlias('@emchat', '@vendor/easemob/emchat');