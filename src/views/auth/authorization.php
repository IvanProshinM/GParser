<?php


use yii\widgets\ActiveForm;
use yii\helpers\Html;


/**
 * @var $form yii\widgets\ActiveForm
 * @var $this yii\web\View
 * @var $model app\models\loginModel
 */

$this->title = 'Авторизация';

?>

<h1><?= Html::encode($this->title) ?></h1>


<?php $form = ActiveForm::begin(); ?>
<?= $form->field($model, 'login') ?>
<?= $form->field($model, 'password')->passwordInput() ?>


<div class="form-group">
    <br>
    <?= Html::submitButton('Войти', ['class' => 'btn btn-primary']) ?>
</div>

<?php echo Yii::$app->session->getFlash('alert'); ?>

<?php ActiveForm::end(); ?>
