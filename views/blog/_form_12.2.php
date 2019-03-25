<?php
// https://github.com/vova07/yii2-imperavi-widget#like-an-activeform-widget

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
//use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Blog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blog-form">

    <?php //$form = ActiveForm::begin(); ?>
    <?php $form = ActiveForm::begin([
		'options' => ['enctype' => 'multipart/form-data'],
	]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?//= $form->field($model, 'text')->textarea(['rows' => 6]) ?>
    <?//= $form->field($model, 'text')->textarea(['rows' => 6])->label('QQQ') //можем задавать название лейблов прямо в форме ?>
	<?php
		echo $form->field($model, 'text')->widget(Widget::className(), [
			'settings' => [
				'lang' => 'ru',
				'minHeight' => 200,
				
				//'formatting' => ['p', 'blockquote', 'h2'], // можем сами задавать формат
				
				// https://github.com/vova07/yii2-imperavi-widget#upload-image
				// http://yii2.blondy/yii2/backend/web/site/save-redactor-img?sub=blog
				'imageUpload' => \yii\helpers\Url::to(['/site/save-redactor-img', 'sub' => 'blog']), // в даном случае 'blog'
				
				'plugins' => [
					'clips',
					'fullscreen',
				],
				/* 'clips' => [
					['Lorem ipsum...', 'Lorem...'],
					['red', '<span class="label-red">red</span>'],
					['green', '<span class="label-green">green</span>'],
					['blue', '<span class="label-blue">blue</span>'],
				], */
			],
		]);
	?>
	
	<?php
		//use kartik\file\FileInput;
		// Usage with ActiveForm and model / Использование с ActiveForm и моделью
		echo $form->field($model, 'file')->widget(\kartik\file\FileInput::classname(), [
			'options' => ['accept' => 'image/*'],
		]);
	?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?//= $form->field($model, 'status_id')->textInput() ?>
    <?//= $form->field($model, 'status_id')->dropDownList(['off', 'on']) ?>
    <?//= $form->field($model, 'status_id')->dropDownList( \common\models\Blog::getStatusList() ) ?>
    <?= $form->field($model, 'status_id')->dropDownList( \common\models\Blog::STATUS_LIST ) ?>

    <?= $form->field($model, 'sort')->textInput() ?>
	
    <?php
		/* // Normal select with ActiveForm & model / Нормальный выбор с ActiveForm & моделью
		echo $form->field($model, 'tags')->widget(\kartik\select2\Select2::classname(), [
			// переменная 'data' будет по сути выбирать все теги которые есть, что бы он нам предлагал
			// нам нужны данные в формате ключ-значение, для этого используем ArrayHelper::map()
			'data' => \yii\helpers\ArrayHelper::map(\common\models\Tag::find()->all(), 'id', 'name'),
			'language' => 'ru',
			'options' => [
				'placeholder' => 'Выбрать tag',
				'multiple' => true, // чтобы позволял выбирать несколько значений
			],
			'pluginOptions' => [
				'allowClear' => true,
				'tags' => true,
				'maximumInputLength' => 10,
			],
		]); */
	?>
	<?php
		echo $form->field($model, 'tags_array')->widget(\kartik\select2\Select2::classname(), [
			'data' => \yii\helpers\ArrayHelper::map(\common\models\Tag::find()->all(), 'id', 'name'),
			'language' => 'ru',
			'options' => [
				'placeholder' => 'Выбрать tag',
				'multiple' => true, // чтобы позволял выбирать несколько значений
			],
			'pluginOptions' => [
				'allowClear' => true,
				'tags' => true,
				'maximumInputLength' => 10,
			],
		]);
	?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php //var_dump(\yii\helpers\ArrayHelper::map($model->getTags()->all(),'id','name')); //http://ipic.su/img/img7/fs/kiss_66kb.1553179257.png ?>
