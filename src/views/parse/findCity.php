<?php


use yii\widgets\ActiveForm;
use yii\helpers\Html;


/**
 * @var $form yii\widgets\ActiveForm
 * @var $this yii\web\View
 * @var $model app\models\loginModel
 */

$this->title = 'Ищем городаю.';

?>

<h1><?= Html::encode($this->title) ?></h1>


<?php $form = ActiveForm::begin(); ?>
<p>Введите страну</p>
<?= $form->field($model, 'Страна') ?>


<div class="form-group">
    <br>
    <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
</div>

<?php echo Yii::$app->session->getFlash('alert'); ?>

<?php ActiveForm::end(); ?>
