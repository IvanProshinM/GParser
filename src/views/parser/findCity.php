<?php


use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\typeahead\Typeahead;
use yii\helpers\Url;


/**
 * @var $form yii\widgets\ActiveForm
 * @var $this yii\web\View
 * @var $model app\models\FindCityModel
 * @var $city \app\models\Cities[]|array
 */

$this->title = 'Ищем города.';

?>


<h1><?= Html::encode($this->title) ?></h1>


<?php $form = ActiveForm::begin(); ?>
<? /*= $form->field($model, 'country')*/ ?>



<?php

/*echo $form->field($model, 'country')->widget(Typeahead::classname(), [
'options' => ['placeholder' => 'Filter as you type ...'],
'pluginOptions' => ['highlight'=>true],
'dataset' => [
[
'local' => $city,
'limit' => 10
]
]
]);

*/ ?>

<?php

echo $form->field($model, 'country')->widget(Typeahead::className(), [
    'name' => 'country',
    'options' => ['placeholder' => 'Filter as you type ...'],
    'pluginOptions' => ['highlight' => true],
    'pluginEvents' => [
        "typeahead:select" => 'function() { 
        $("#findcitymodel-city").prop("disabled", false);
        $("#findcitymodel-city").css("background-color","white");
        
        
        console.log("typeahead:select"); }'
    ],
    'dataset' => [
        [
            'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
            'display' => 'value',
            'remote' => [
                'url' => Url::to(['/parser/search-country']) . '?country=%QUERY',
                'wildcard' => '%QUERY'
            ]
        ]
    ]
]);

?>

<div class="city_input">

    <?php

    echo $form->field($model, 'city')->widget(Typeahead::className(), [
        'name' => 'country',
        'options' => ['placeholder' => 'Filter as you type ...'],
        'pluginOptions' => ['highlight' => true],
        'disabled' => true,

        'dataset' => [
            [
                'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                'display' => 'value',
                'remote' => [
                    'url' => Url::to(['/parser/search-city']) . '?city=%QUERY'/* . '&' . 'country_id=' . $countryId*/,
                    'wildcard' => '%QUERY'
                ]
            ]
        ]
    ]);

    ?>
</div>
<div class="form-group">
    <br>
    <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
</div>


<?php $form = ActiveForm::end() ?>

