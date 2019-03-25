<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\modules\blog\models\BlogSearch */
/* @var $searchModel antonovgit\blog\models\BlogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Blogs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-index">

    <!-- <h1><?//= Html::encode($this->title) ?></h1> -->
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Blog', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //а можно как-то размер поля фильтра для id задать вручную? а то . .. много места занимает
			// >я бы класс единственно сделал, так как еще не раз понадобится. Еще, как вариант, так как столбец с id как правило второй, то можно было через :nth-of-type
			[
                'attribute' => 'id',
                'contentOptions' => ['style' => 'width: 25px'],
			],
			
            'title',
            //'text:ntext',
            
			//'url:url',
			/* // Равнозначно записи:  'url:url',
			[
				'attribute' => 'url',
				'format' => 'url',
			], */
			[
				'attribute' => 'url',
				'format' => 'text',
				//'format' => 'raw', // если формат html
				//'format' => 'html', // если формат html
				
				'headerOptions' => [
					'class' => 'btn btn-default',
				],
				'contentOptions' => ['style' => 'color: red']
			],
            
			//////////////////////////////////////////////////////////////////
			//'status_id',
			//'status_id:boolean',  // on/off
			
			// [
				// 'attribute' => 'status_id',
				// 'filter' => ['off', 'on'], // равнозначно <=> [0 => 'off', 1=> 'on']
				// // ф-ция будет запускаться всегда, когда будет происходить рендер ячейки
				// 'value' => function($model) { // внутри ф-ции передается модель каждой конкретной записи
					// /* //return $model->status_id;
					// if ($model->status_id == 1) {
						// //$status = 'on';
						// $status = 'Включен';
					// } else {
						// //$status = 'off';
						// $status = 'Выключен';
					// }
					// return $status; */
					
					// //
					// $status = 'off';
					// if ($model->status_id == 1) {
						// //$status = 'on';
						// $status = 'Включен';
					// }
					// return $status;
				// }
			// ],
			/* [
				'attribute' => 'status_id',
				'filter' => \common\models\Blog::getStatusList(), // сюда вернется нужный нам массив
				// ф-ция будет запускаться всегда, когда будет происходить рендер ячейки
				'value' => function($model) { // внутри ф-ции передается модель каждой конкретной записи
					//var_dump($model->statusName); //string 'on' (length=2)
					return $model->statusName;  // ?не зрозумів чому statusName а не getStatusName()
					//return $model->getStatusName();
				}
			], */
			[
				'attribute' => 'status_id',
				//'filter' => \common\models\Blog::getStatusList(), // сюда вернется нужный нам массив
				/* 'filter' => function($model){
					//return $model->getStatusList();
					return $model->statusList;
				}, */
				// Вернемся к нашему варианту
				//'filter' => \common\models\Blog::STATUS_LIST,
				//'filter' => \common\modules\blog\models\Blog::STATUS_LIST,
				'filter' => \antonovgit\blog\models\Blog::STATUS_LIST,
				
				//т.к. мы сделали get(getStatusName) то результат выполнения данной ф-ции может быть доступен как атрибут
				'value' => 'statusName',
				
				'contentOptions' => ['style' => 'width: 80px'],
			],
			/* // можете подсказать как вместо on и off ,передать в фильтр содержимое ячейки из другой таблицы?
			[
				'attribute' => 'myattr',
				'filter' => 'что угодно, хоть данные откуда угодно, хоть html, хоть ничего'
			] */
			//////////////////////////////////////////////////////////////////
            
			'sort',
			
			'smallImage:image', // выведем картинку
			
			//'date_create',
			//'date_create',
			'date_update:datetime',
			'date_update:datetime',
			
			// вывод тегов
			[
				'attribute' => 'tags',
				'value' => 'tagsAsString', // результат работы ф-ции getTagsAsString()
			],

			
			// Кнопки view, update, delete
            //['class' => 'yii\grid\ActionColumn'],
			// Допустим мы хотим настроить // https://www.yiiframework.com/doc/api/2.0/yii-grid-actioncolumn
			[
				'class' => 'yii\grid\ActionColumn',
				
				// C:\OpenServer\domains\images.com\backend\modules\complaints\views\manage\index.php
				// Указываем формат отображения кнопок при помощи свойства template
				//'template' => '{view} {update} {delete}', // по умолчанию
				//'template' => '{view} {update}',
				//'template' => '{view} {update} {my_button}',
				//'template' => '{view}&nbsp;&nbsp;&nbsp;{approve}&nbsp;&nbsp;&nbsp;{delete}',
				'template' => '{view} {update} {delete} {check}',
				
				// Описание кнопки (approve ..ее отрисовка), для этого определяется ключ buttons..без этого блока галочки не будет
				'buttons' => [
					// фактически $url будет вести в контроллер BlogController на экшен update. $key это айди строки
					// где, $url - это URL, который будет повешен как ссылка на кнопку, $model - это объект модели для текущей строки и $key - это ключ для модели из провайдера данных.
					
					/* // тут кнопки, которые нам нужны..view, update, delete  стандартные, поэтому их определять не стоит
					'update' => function ($url, $model, $key) {
						//return $model->status === 'editable' ? Html::a('Update', $url) : '';
						return Html::a('My_button_update', $url);
					}, */
					
					//'my_button' => function ($url, $model, $key) {
					'check' => function ($url, $model, $key) {
						//return Html::a('My_button_My', $url);
						return Html::a('<i class="fa fa-check" aria-hidden="true"></i>', $url);
						
						// возвращаем ссылку с иконкой, и ссылка введет на экшен approve в текущем контролере с айди текущего поста
                        //return Html::a('<span class="glyphicon glyphicon-ok"></span>', ['approve', 'id' => $post->id]);
					},
				],
				// Кастомизируем нашу кнопку check. Кнопка check видна если тру, если фолс-не видна(там где стасус off)
				'visibleButtons' => [
					//'check' => function ($url, $model, $key) {	// !Не работало, потому что порядок обратный
					// https://github.com/yiisoft/yii2/blob/master/docs/guide-ru/output-data-widgets.md#datacolumn-
					'check' => function ($model, $key, $index) { // ОК http://ipic.su/img/img7/fs/kiss_86kb.1553041468.png
						return ($model->status_id == 0) ? false : true;
						//return ($model['status_id'] == 0) ? false : true; // !работает
						
						// Решение ошибки: C:\OpenServer\domains\yii2.webformyself2\modules\admin\views\category\index.php
						//return isset($data->category->name) ? $data->category['name'] : 'Самостоятельная категория'; // !работает
						//Попробуй заменить в условии (сразу после ретурн) вместо $data->category->name -- isset($data->category->name) . Мне кажется, некоторые версии ПХП не могут взять названии поле как булеан﻿
					},
				],

			],
			
			
        ],
    ]); ?>
	
	
    <?php Pjax::end(); ?>
</div>
