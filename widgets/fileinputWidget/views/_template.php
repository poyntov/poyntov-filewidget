<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>
<div class="form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, $attribute.'[]')->fileInput(['multiple' => true])->label('Файл') ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>