<?php

//namespace common\models;
//namespace common\modules\blog\models;
namespace antonovgit\blog\models; // при создании расширения

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use common\components\behaviors\StatusBehavior;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
//use yii\helpers\FileHelper;
use yii\helpers\Url;
use common\models\User;
use common\models\ImageManager;

/**
 * This is the model class for table "blog".
 *
 * @property int $id
 * @property string $title
 * @property string $text
 * @property string $image
 * @property string $url
 * @property string $date_create
 * @property string $date_update
 * @property int $status_id
 * @property int $sort
 */
class Blog extends \yii\db\ActiveRecord
{
    const STATUS_LIST = ['off', 'on'];
	const IMAGES_SIZE = [
        ['50','50'],
        ['800',null],
    ];
	
	public $tags_array;
	public $file;
	
	
    public static function tableName()
    {
        return 'blog';
    }
	
	
	public function behaviors()
	{
		/* return [
			[
				'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'date_create',
				'updatedAtAttribute' => 'date_update',
				'value' => new Expression('NOW()'),
			],
		]; */
		return [
			'TimestampBehavior' => [
				'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'date_create',
				'updatedAtAttribute' => 'date_update',
				'value' => new Expression('NOW()'),
			],
			
			// Подключим к модели наше поведение
			// D:\OpenServer\domains\yii2.blondy\yii2\common\components\behaviors\StatusBehavior.php
			'StatusBehavior' => [
				'class' => StatusBehavior::className(),
				
				//настроим статус лист
				//'statusList' => ['off', 'on'],
				'statusList' => self::STATUS_LIST,
			],
		];
	}
	

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        /* return [
            [['title', 'url'], 'required'],
            [['text'], 'string'],
            [['status_id', 'sort'], 'integer'],
            [['title', 'url'], 'string', 'max' => 150],
        ]; */
		/* return [
            [['title', 'url'], 'required'],
            [['text'], 'string'],
            [['url'], 'unique'], // проверка на уникальность // ЧПУ "first" has already been taken.
            [['status_id', 'sort'], 'integer'],
            [['sort'], 'integer', 'max' => 99, 'min' => 1],
            [['title', 'url'], 'string', 'max' => 150],
            //[['tags_array'], 'safe'],
            [['tags_array', 'date_create', 'date_update'], 'safe'],
        ]; */
		
		return [
            [['title', 'url'], 'required'],
            [['text'], 'string'],
            [['url'], 'unique'], // проверка на уникальность // ЧПУ "first" has already been taken.
            [['status_id', 'sort'], 'integer'],
            [['sort'], 'integer', 'max' => 99, 'min' => 1],
            [['title', 'url'], 'string', 'max' => 150],
            [['image'], 'string', 'max' => 100],
            [['file'], 'image'],
            [['tags_array', 'date_create', 'date_update'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        /* return [
            'id' => 'ID',
            'title' => 'Title',
            'text' => 'Text',
            'url' => 'Url',
            'status_id' => 'Status ID',
            'sort' => 'Sort',
        ]; */
		return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'text' => 'Текст',
            'url' => 'ЧПУ',
            'status_id' => 'Статус',
            'sort' => 'Сортировка',
            'tags_array' => 'Теги',
            'image' => 'Картинка',
            'file' => 'Картинка',
            'tagsAsString' => 'Теги',
            'author.username' => 'Имя автора',
            'author.email' => 'Email автора',
            'date_update' => 'Дата обновления',
            'date_create' => 'Дата создания',
        ];
    }
	
	
	/* // Перенесу эти ф-ции в D:\OpenServer\domains\yii2.blondy\yii2\common\components\behaviors\StatusBehavior.php
	// Возвращает массив возможных значений
	public static function getStatusList()
    {
		return ['off', 'on']; // равнозначно <=> [0 => 'off', 1=> 'on']
	}
	// Возвращает текущий статус
	public function getStatusName() //т.к. мы сделали get(getStatusName) то результат выполнения данной ф-ции может быть доступен как атрибут
    {
		$list = self::getStatusList();
		return $list[$this->status_id];
	} */
	
	
	
	public function getAuthor()
    {
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}
	
	// Связь модели с картинками
	public function getImages()
    {
		/* return $this->hasMany(ImageManager::className(), ['item_id' => 'id']) // item_id равен айди текущей модели(айди блога)
			->andWhere(['class' => self::tableName()]); */
		return $this->hasMany(ImageManager::className(), ['item_id' => 'id'])->andWhere(['class'=>self::tableName()])->orderBy('sort');
	}
	public function getImagesLinks() // Yii2: удаление и сортировка фото. Видео 12.4
    {
        return ArrayHelper::getColumn($this->images, 'imageUrl');
    }
	public function getImagesLinksData() // Yii2: удаление и сортировка фото. Видео 12.4
    {
        return ArrayHelper::toArray($this->images, [ // выбираем из всего массива картинок
                ImageManager::className() => [
                    'caption' => 'name',
                    'key' => 'id',
                ]]
        );
    }
	
	// Теги
	public function getBlogTag()
    {
		return $this->hasMany(BlogTag::className(), ['blog_id' => 'id']); // blog_id равен айди текущей модели 
	}
	
	// Связывание посредством промежуточной таблицы: https://yiiframework.com.ua/ru/doc/guide/2/db-active-record/#junction-table
	// При объявлении подобных связей вы можете пользоваться методом via() или методом viaTable() для указания промежуточной таблицы. Разница между методами via() и viaTable() заключается в том, что первый метод указывает промежуточную таблицу с помощью названия связи, в то время как второй метод непосредственно указывает промежуточную таблицу.
	public function getTags() // связь, которая в свою очередь идет через другую связь
    {
		//return $this->hasMany(Tag::className(), ['blog_id' => 'id']); // blog_id равен айди текущей модели 
		return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->via('blogTag');
	}
	
	public function getTagsAsString()
    {
		$arr = \yii\helpers\ArrayHelper::map($this->tags, 'id', 'name');
		return implode(', ', $arr);
	}
	
	// Метод для вывода миниатюры D:\OpenServer\domains\yii2.blondy\yii2\backend\views\blog\view.php
	public function getSmallImage()
	{
	
		/* //$result_link = str_replace('admin.', '', Url::home(true) . 'uploads/images/' . $sub . '/'); //true что бы урл был абсолютным
			//var_dump($result_link); die; // http://yii2.blondy/yii2/backend/web/uploads/images/blog/
		$url = 'http://yii2.blondy/yii2/frontend/web/uploads/images/blog/';
		
		return $url.'50x50/' . $this->image; */
		
		if ($this->image) {
			$path = 'http://yii2.blondy/yii2/frontend/web/uploads/images/blog/50x50/' . $this->image;
		} else {
			//$path = str_replace('admin.', '', Url::home(true) .'uploads/images/nophoto (5).svg'
			//$path = 'http://yii2.blondy/yii2/frontend/web/uploads/images/no_image.jpg';
			//$path = 'http://yii2.blondy/yii2/frontend/web/uploads/images/no-image.png';
			//$path = 'http://yii2.blondy/yii2/frontend/web/uploads/images/no-photo.svg'; // svg -это вектор и она как ее не растягивай будет выглядеть красиво и т.к. мы не знаем какого размера она должна быть, мы везде будем использовать одну и туже картинку-заглушку
			$path = 'http://yii2.blondy/yii2/frontend/web/uploads/images/nophoto (5).svg';
		}
		
		return $path;
	}
	
	
	// В Yii есть несколько событий: когда создается объект, например, когда мы делаем объект Блог, вначае вызывается событие init(мы его можем переопределить). Потом у нас есть событие файнд, когда мы производим поиск внутри модели. Соответственно есть события бефор-файнд и афтер-файнд. Это события куда можна вставить какую то логику, которая будет предшествовать или наоборот, быть после того как была заполнена модель данными из базы данных. Так же есть бефор-сейв и афтер-сейв, сответственно мы можем туда тоже какую то логику внедрить
	// Нам нужно сразу после того как произошол запрос к базе даных(когда модель заполнилась данными)..нужно что бы в переменную $this->tags_array попали данные(засунулись) из модели
	public function afterFind()
    {
		parent::afterFind();
		$this->tags_array = $this->tags;
	}
	
	public function afterSave($insert, $changedAttributes)
    {
		parent::afterSave($insert, $changedAttributes);
		
		//$arr = $this->tags; // обращаемся к связи
		$arr = \yii\helpers\ArrayHelper::map($this->tags, 'id', 'id');
		foreach ($this->tags_array as $one) {
			// если пришли эти данные, то мы ничего не должны делать. В противном случае мы должны их сохранить.. ну и кроме этого нам нужно еще удалить те, которые были до этого
			if (!in_array($one, $arr)) { // если ключа в массиве нет, тогда мы должны его добавить
				$model = new BlogTag();
				$model->blog_id = $this->id;
				$model->tag_id = $one;
				$model->save();
				//Yii::$app->session->setFlash('success', 'добавлен тег ' .$one);
				//Yii::$app->session->setFlash('success', 'добавлен тег');
			}
			if (isset($arr[$one])) {
				unset($arr[$one]);
				//Yii::$app->session->setFlash('error', 'тег не добавился');
				//Yii::$app->session->setFlash('error', "тег {$one} не добавился");
			}
		}
		// Нам нужно удалить все связи // ! Нужно иметь в виду, что метож deleteAll() он не задействует стандартный ивент yii, который называется бефор-делит и афтер-делит. Соответственно им можно пользоваться только зная, что у нас в модели BlogTag не добавлена какая либо логика в эти ивенты
		BlogTag::deleteAll(['tag_id' => $arr, 'blog_id' => $this->id]);		
	}
	
	// Цепляемся за событие в Активрекордс.. эта ф-ция вызывается прямо перед сохранением модели
	public function beforeSave($insert)
	{
		/* 
		UploadedFile::getInstance(); // просто берет модель...получаем в единичнм варианте // $file->extension;
		UploadedFile::getInstances(); // получаем массив, даже если там одно изображение пришло //$file[0]->extension или в цикле обращаться
		UploadedFile::getInstanceByName(); // наличие ByName говорит нам что мы берем файл по названию
		UploadedFile::getInstancesByName(); 
		*/

		// В переменную записываем с помощью ф-ции UploadedFile, встроенную в yii, мы записываем сайм файл, т.е. объект
		if ($file = UploadedFile::getInstance($this, 'file')) { // существует ли такой файл
			// В \common\config\bootstrap.phpYii::setAlias('@images', dirname(dirname(__DIR__)) . '/backend/web/uploads/images');
			$dir = Yii::getAlias('@images').'/blog/'; // задаем директорию blog, куда будут загружаться фото
			// 'D:\OpenServer\domains\yii2.blondy\yii2/frontend/web/uploads/images/blog/'
			
			// когда загружаем запись без картинки, а потом делаем Update и загружаем картинку, тогда он ругается, потому что действительно не может удалить директорию. Я поставил еще одну проверку на директории и все заработало
			if (!is_dir($dir . $this->image)) {
				if (file_exists($dir . $this->image)) {
					unlink($dir . $this->image); // проверяем существует ли старый файл и удаляем его, чтобы потом загрузить новый
				}
				if (file_exists($dir . '50x50/' . $this->image)) {
					unlink($dir . '50x50/' . $this->image); // удаляем миниатюру
				}
				if (file_exists($dir . '800x/' . $this->image)) {
					unlink($dir . '800x/' . $this->image); // удаляем миниатюру
				}
			};
			
			//strtotime() Преобразует текстовое представление даты на английском языке в метку времени Unix
			$this->image = strtotime('now').'_'.Yii::$app->getSecurity()->generateRandomString(6) . '.' . $file->extension; //уникальное назв
			$file->saveAs($dir . $this->image); // сохраняем новый файл в указанную директорию
			// Загружаем картинку с помощью расширения yii2-imperavi-widget
			$imag = Yii::$app->image->load($dir . $this->image);
			// делаем с каринкой некие преобразования
			$imag->background('#fff', 0); // белый бекграунд
			//$imag->resize('50', '50', Image::INVERSE);
			$imag->resize('50', '50', Yii\image\drivers\Image::INVERSE); // ресайзим картинку.. INVERSE -по меньшей стороне. Т.е. если картинка будет 200х100, то в результате у нас получится картинка 100х50
			$imag->crop('50', '50'); // соответственно потом, когда мы ее обрежим у нас получится 50х50
			
			if(!file_exists($dir . '50x50/')){
				FileHelper::createDirectory($dir.'50x50/');
			}
			$imag->save($dir.'50x50/'.$this->image, 90); // сохраняем миниатюру в директорию 50x50  с качеством 90
			
			$imag = Yii::$app->image->load($dir.$this->image); // берем эту же картинку
			$imag->background('#fff', 0);
			//$imag->resize('800', null, Image::INVERSE);
			$imag->resize('800', null, Yii\image\drivers\Image::INVERSE); // 800 в шириру и автоматически будет выставлена высота
			
			if(!file_exists($dir.'800x/')){
				FileHelper::createDirectory($dir.'800x/'); // создаем директорию 800x
			}
			
			$imag->save($dir.'800x/'.$this->image, 90); // сохраняем миниатюру в директорию 800x с качеством 90
		}
		return parent::beforeSave($insert); // возвращаем parent::beforeSave, для того чтобы работал родительский метод beforeSave()
	}

	/* public function beforeDelete()
	{
		if (parent::beforeDelete()) {
			$dir = Yii::getAlias('@images').'/blog/';
			if(file_exists($dir.$this->image)){
				unlink($dir.$this->image);
			}
			foreach (self::IMAGES_SIZE as $size){
				$size_dir = $size[0].'x';
				if($size[1] !== null)
					$size_dir .= $size[1];
				if(file_exists($dir.$this->image)){
					unlink($dir.$size_dir.'/'.$this->image);
				}
			}
			BlogTag::deleteAll(['blog_id'=>$this->id]);
			return true;
		} else {
			return false;
		}
	} */
	
}
