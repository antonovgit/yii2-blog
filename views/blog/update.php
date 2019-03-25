<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\blog\models\Blog */
/* @var $model antonovgit\blog\models\Blog */

$this->title = 'Update Blog: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Blogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="blog-update">

    <h1><?//= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
	
	<div class="well">
		<?php //foreach($model->blogTag as $one): ?>
			<?//=$one->tag->name ?>
		
		<?php /*foreach($model->tags as $one): ?>
			<?=$one->name ?><br>
		<?php endforeach;*/ ?>
	</div>

	<?php //var_dump($model->tags); // Объект http://ipic.su/img/img7/fs/kiss_92kb.1553178723.png ?>
	<?php //var_dump($model->getTags()->asArray()->all()); //Если хотим вывести как массив http://ipic.su/img/img7/fs/kiss_67kb.1553178991.png ?>
</div>
