<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

    <img src="/img/routes.png" alt="Routes">

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'from')->dropDownList($froms) ?>

    <?= $form->field($model, 'to')->dropDownList($tos) ?>

    <div class="form-group">
        <?= Html::submitButton('Show', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <br><br><br>
</div>

<?php if (isset($findRoutes)) : ?>
    <?php
    echo "<pre>";
    print_r($findRoutes);
    echo "</pre><br><br><br>";
    ?>
<?php endif; ?>