<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\blog\models\Blog */
/* @var $model antonovgit\blog\models\Blog */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Blogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="blog-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'text:ntext',
            'url:url',
            'status_id',
            'sort',
			
			//
            'author.username',
			'author.email',
            'tagsAsString', // результат работы ф-ции getTagsAsString()
            
			//'image', // выведем название картинки
            //'smallImage', // http://yii2.blondy/yii2/frontend/web/uploads/images/blog/50x50/1553364260_PXZEz3.jpg
            'smallImage:image', // выведем картинку
        ],
    ]) ?>
	
	<?php
		// Выведем галерею картинок
		$fotorama = \metalguardian\fotorama\Fotorama::begin(
			[
				'options' => [
					'loop' => true,
					'hash' => true,
					'ratio' => 800/600,
				],
				'spinner' => [
					'lines' => 20,
				],
				'tagName' => 'span',
				'useHtmlData' => false,
				'htmlOptions' => [
					'class' => 'custom-class',
					'id' => 'custom-id',
				],
			]
		);
		
		//foreach ($model->getImages()->all() as $one) { // или 
		foreach ($model->images as $one) {
			//echo Html::img('/uploads/images/blog/' .$one->name, ['alt' => $one->alt]);
			echo Html::img($one->imageUrl, ['alt' => $one->alt]);
		}
		
		$fotorama->end();
    ?>
	<!-- <img src="http://s.fotorama.io/1.jpg">    
	<img src="http://s.fotorama.io/2.jpg">
	<img src="http://s.fotorama.io/3.jpg">
	<img src="http://s.fotorama.io/4.jpg">
	<img src="http://s.fotorama.io/5.jpg"> -->
	<?php //\metalguardian\fotorama\Fotorama::end(); ?>

</div>
