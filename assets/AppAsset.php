<?php
namespace app\assets;
use Yii;
use yii\web\{AssetBundle, View};

class AppAsset extends AssetBundle {
	public $sourcePath = '@app/assets/src/css';

	public $css = [
		'site.scss',
	];

	public $js = [
	];

	public $depends = [
 		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];

	public $publishOptions = [
		'forceCopy' => YII_ENV_DEV,
	];

	public function init() {
		Yii::$app->view->registerJs(Yii::$app->formatter->jspack('scrolltop.js'), View::POS_READY);
		Yii::$app->view->registerJs(Yii::$app->formatter->jspack('targetBlank.js'), View::POS_READY);
		Yii::$app->view->registerJs(Yii::$app->formatter->jspack('tooltip.js'), View::POS_READY);
	}
}
