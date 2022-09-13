<?php


use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\typeahead\Typeahead;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

/**
 * @var $form yii\widgets\ActiveForm
 * @var $this yii\web\View
 * @var $model app\models\FindCityModel
 * @var $city \app\models\Cities[]|array
 */

$this->title = 'Ищем города.';

?>


<h1><?= Html::encode($this->title) ?></h1>


<?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off']]); ?>
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
        'pluginEvents' => [
            "typeahead:select" => 'function() { 
        $(".select2-search__field").prop("disabled", false);
        $(".select2-search__field").css("background-color","white");
        
        
        console.log("typeahead:select"); }'
        ],
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


<!--<div class="city_input">

    <?php
/*
    echo $form->field($model, 'category')->widget(Typeahead::className(), [
        'name' => 'country',
        'options' => ['placeholder' => 'Filter as you type ...'],
        'pluginOptions' => ['highlight' => true],
        'disabled' => true,

        'dataset' => [
            [
                'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                'display' => 'value',
                'remote' => [
                    'url' => Url::to(['/parser/search-category']) . '?category=%QUERY',
                    'wildcard' => '%QUERY'
                ]
            ]
        ]
    ]);

    */?>
</div>-->

<?php


$dataList = \app\models\Category::find()->andWhere(['id' => $model->categoryList])->all();
$data = ArrayHelper::map($dataList, 'id', 'name');


echo $form->field($model, 'categoryList')->widget(Select2::classname(), [
    'data' => $data,
    'options' => ['multiple' => true, 'placeholder' => 'Search for a category ...'],
    'pluginOptions' => [

        'allowClear' => true,
        'minimumInputLength' => 3,
        'language' => [
            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
        ],
        'ajax' => [
            'url' => Url::to(['/parser/search-category']),
            'dataType' => 'json',
            'data' => new JsExpression('function(params) { return {q:params.term}; }')
        ],
        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
        'templateResult' => new JsExpression('function(categoryList) { return categoryList.text; }'),
        'templateSelection' => new JsExpression('function (categoryList) { return categoryList.text; }'),
    ],
]);


?>

<div class="form-group">
    <br>
    <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
</div>


<?php $form = ActiveForm::end() ?>

