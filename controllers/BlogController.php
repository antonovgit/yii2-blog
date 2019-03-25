<?php

//namespace backend\controllers;
//namespace common\modules\blog\controllers; // при создании модуля
namespace antonovgit\blog\controllers; // при создании расширения

use Yii;
/* use common\models\Blog;
use common\models\BlogSearch; */
/* use common\modules\blog\models\Blog;
use common\modules\blog\models\BlogSearch; */
use antonovgit\blog\models\Blog;
use antonovgit\blog\models\BlogSearch;
use common\models\ImageManager;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\MethodNotAllowedHttpException;


/**
 * BlogController implements the CRUD actions for Blog model.
 */
class BlogController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
					//'delete-image' => ['POST'],
                    //'sort-image' => ['POST'],
					'delete-gallery' => ['POST'], // Допускается только ПОСТ метод
                    'sort-gallery' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Blog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BlogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	/* // Создадим 30 фейковых постов
	public function actionFff() // http://yii2.blondy/yii2/backend/web/blog/fff
    {
        for ($i = 0; $i < 30; $i++) {
			$model = new Blog();
			$model->title = 'Заголовок №' .$i;
			$model->sort = 50;
			$model->status_id = 1;
			$model->url = 'url_' .$i;
			$model->save();
		}
		return '123';
    } */

    /**
     * Displays a single Blog model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Blog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Blog();
        $model->sort = 50;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Blog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /* public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    } */
	public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
    }

    /**
     * Deletes an existing Blog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
	
	
	//public function actionDeleteImage()
	public function actionDeleteGallery()
    {
        // Ищем запись у которой айди будет равен key=8 и потом пытаемся его удалить
		if (($model = ImageManager::findOne(Yii::$app->request->post('key'))) and $model->delete()) {
            return true; // если все хорошо, то возвращаем тру
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    //public function actionSortImage($id)
    public function actionSortGallery($id) // айди блога
    {
        if (Yii::$app->request->isAjax) { // если это аджакс запрос
			// получаем в переменную, все что есть в 'sort' https://d.radikal.ru/d18/1903/02/326ed5ec0f4d.png
            $post = Yii::$app->request->post('sort');
            if ($post['oldIndex'] > $post['newIndex']) { //чтобы понять передвинулась вверх или вниз наша картинка
                //значит картинка переместилась вверх..нам нужно выбрать все картинки, у которых сортировка будет больше или равна newIndex и будет меньше чем у oldIndex, т.е. выбираем только те картинки, которые попали в этот диапазон
				$param = ['and',['>=','sort',$post['newIndex']],['<','sort',$post['oldIndex']]];
                $counter = 1; // картинки с промежутка увеличиваем на 1
            } else { // в случае если движение идет в обратную сторону
                $param = ['and',['<=','sort',$post['newIndex']],['>','sort',$post['oldIndex']]];
                $counter = -1; // картинки с промежутка уменьшаем на 1
            }
			// updateAllCounters занимается тем, что увеличивает/уменьшает на 1 ..Почему не через выборку, перебор? Потому что через updateAllCounters это гораздо быстрее, меньше нагружает сервер, мы не трогаем все остальные картинки, которые у нас не учавствуют в данном случае, у которых ничего не изменилось
            ImageManager::updateAllCounters(['sort' => $counter], [
               // Условие для выборки..класс блог(работаем только с картинками, которые относятся к блогу), айди блога и параметры
			   'and', ['class'=>'blog','item_id' => $id], $param
               ]);
			// Когда мы удаляем картинку, там надо тоже поменять для всех картинок соответственно sort, они должны сдвинуться
            ImageManager::updateAll(['sort' => $post['newIndex']], [
                'id' => $post['stack'][$post['newIndex']]['key']
            ]);
            return true; //чтобы виджет понял, что сортировка удалась
        }
        throw new MethodNotAllowedHttpException(); // если не аджакс..для того чтобы без аджакса перейти на этот урл нельзы было
    }
	

    /**
     * Finds the Blog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Blog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    /* protected function findModel($id)
    {
        if (($model = Blog::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    } */
	protected function findModel($id)
    {
        // Blog::findOne($id) <=> Blog::find()->andWhere(['id' => $id])->one() // идентичные записи
		
		if (($model = Blog::find()->with('tags')->andWhere(['id' => $id])->one() ) !== null) { //
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
