<?php

/* @var $this yii\web\View */

use app\widgets\fileinputWidget\FileInput;

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <?= FileInput::widget(['model' => $model,
        'attribute' => 'name',
        'metaAttribute' => 'id_meta',
        'extensions' => ['png', 'jpg'],
        'maxSizeOne' => 1024*1024,
        'maxSizeMore' => 1024*1024,
        'maxFiles' => 10,
        'path' => 'yourpath']) ?>
</div>