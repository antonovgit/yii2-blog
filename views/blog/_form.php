<?php
// https://github.com/vova07/yii2-imperavi-widget#like-an-activeform-widget

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
//use kartik\select2\Select2;
//use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\modules\blog\models\Blog */
/* @var $model antonovgit\blog\models\Blog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blog-form">

    <?php //$form = ActiveForm::begin(); ?>
    <?php $form = ActiveForm::begin([
		'options' => ['enctype' => 'multipart/form-data'],
	]); ?>

	
	<div class="row">
	<?php
		/* //use kartik\file\FileInput;
		// Usage with ActiveForm and model / Использование с ActiveForm и моделью
		echo $form->field($model, 'file')->widget(\kartik\file\FileInput::classname(), [
			'options' => ['accept' => 'image/*'],
		]); */
		
		// Настроим http://plugins.krajee.com/file-input
		// Чтобы была одна сплошная кнопка http://ipic.su/img/img7/fs/kiss_106kb.1553380341.png
		// http://ipic.su/img/img7/fs/kiss_118kb.1553380293.png
		//echo $form->field($model, 'file')->widget(\kartik\file\FileInput::classname(), [
		echo $form->field($model, 'file', ['options' => ['class' => 'col-xs-6']])->widget(\kartik\file\FileInput::classname(), [
			'options' => ['accept' => 'image/*'],
			
			'pluginOptions' => [
				'showCaption' => false,
				'showRemove' => false,
				'showUpload' => false,
				'browseClass' => 'btn btn-primary btn-block',
				'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
				//'browseLabel' =>  'Select Photo'
				'browseLabel' =>  'Выбрать изображение'
			],
			
		]);
		
	?>

	<?= $form->field($model, 'title', ['options' => ['class' => 'col-xs-6']])->textInput(['maxlength' => true]) ?>
	
    <?//= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'url', ['options' => ['class' => 'col-xs-6']])->textInput(['maxlength' => true]) ?>

    <?//= $form->field($model, 'status_id')->textInput() ?>
    <?//= $form->field($model, 'status_id')->dropDownList(['off', 'on']) ?>
    <?//= $form->field($model, 'status_id')->dropDownList( \common\models\Blog::getStatusList() ) ?>
    <?//= $form->field($model, 'status_id')->dropDownList( \common\models\Blog::STATUS_LIST ) ?>
    <?//= $form->field($model, 'status_id', ['options' => ['class' => 'col-xs-6']])->dropDownList( \common\models\Blog::STATUS_LIST ) ?>
    <?= $form->field($model, 'status_id', ['options' => ['class' => 'col-xs-6']])->dropDownList( \common\modules\blog\models\Blog::STATUS_LIST ) ?>

    <?//= $form->field($model, 'sort')->textInput() ?>
    <?= $form->field($model, 'sort', ['options' => ['class' => 'col-xs-6']])->textInput() ?>
	
	
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
		//echo $form->field($model, 'tags_array')->widget(\kartik\select2\Select2::classname(), [
		echo $form->field($model, 'tags_array', ['options' => ['class' => 'col-xs-6']])->widget(\kartik\select2\Select2::classname(), [
			//'data' => \yii\helpers\ArrayHelper::map(\common\models\Tag::find()->all(), 'id', 'name'),
			//'data' => \yii\helpers\ArrayHelper::map(\common\modules\blog\models\Tag::find()->all(), 'id', 'name'),
			'data' => \yii\helpers\ArrayHelper::map(\antonovgit\blog\models\Tag::find()->all(), 'id', 'name'),
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
	</div>
	
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
	
	<?php //var_dump($model->imagesLinksData) // https://a.radikal.ru/a00/1903/82/0b261153186e.png ?>
	
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
	
	
	<?php
		// Загрузка нескольких фото(например для галереи товара)
		//echo $form->field($model, 'file')->widget(\kartik\file\FileInput::classname(), [
		echo \kartik\file\FileInput::widget([
			//'name' => 'attachment[]',
			'name' => 'ImageManager[attachment]', //imagemanager это название модели, attachment это название ее атрибута, поэтому в модели это просто атрибут, а в других местах это модель-атрибут﻿
			
			//'options' => ['accept' => 'image/*'],
			'options'=>[
				'multiple'=>true,
			],
			'pluginOptions' => [
				//'deleteUrl' => Url::toRoute(['/blog/delete-image']),
				'deleteUrl' => \yii\helpers\Url::toRoute(['/blog/delete-gallery']),
				'initialPreview' => $model->imagesLinks, // здесь приходит массив с картинками, с их урлами
				'initialPreviewAsData' => true,
				'overwriteInitial' => false,
				'initialPreviewConfig' => $model->imagesLinksData,			
				'uploadUrl' => \yii\helpers\Url::to(['/site/save-gallery']),
				'uploadExtraData' => [
					//'album_id' => 20,
					//'cat_id' => 'Nature',
					/* 'class' => $model->formName(),
					'item_id' => $model->id, // айди нашего блога */
					'ImageManager[class]' => $model->formName(),
					'ImageManager[item_id]' => $model->id, // айди нашего блога
				],
				'maxFileCount' => 10,
			],
			'pluginEvents' => [
				// Сортировка
				'filesorted' => new \yii\web\JsExpression('function(event, params){
					$.post("'.\yii\helpers\Url::toRoute(["/blog/sort-gallery", "id" => $model->id]).'", {sort: params});
				}')
			],
			
		]);
	?>


</div>

<?php //var_dump(\yii\helpers\ArrayHelper::map($model->getTags()->all(),'id','name')); //http://ipic.su/img/img7/fs/kiss_66kb.1553179257.png ?>
