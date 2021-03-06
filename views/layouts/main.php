<?php
use app\assets\{AppAsset, ImagesAsset};
use app\models\MenuItems;
use yii\bootstrap\{Html, Nav, NavBar};
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
ImagesAsset::register($this);

$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
<?= Html::tag('title', Html::encode($this->title)) ?>
<?= $this->registerMetaTag(['charset' => Yii::$app->charset]) ?>
<?= $this->registerMetaTag(['name' => 'author', 'content' => Yii::$app->name]) ?>
<?= $this->registerMetaTag(['name' => 'description', 'content' => Html::encode(Yii::$app->params['description'])]) ?>
<?= $this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1']) ?>
<?= $this->registerLinkTag(['rel' => 'canonical', 'href' => Url::current([], true)]) ?>
<?= $this->registerLinkTag(['rel' => 'alternate', 'href' => Url::to(['/feed/rss'], true), 'type' => 'application/rss+xml', 'title' => Yii::$app->name]) ?>
<?= $this->registerLinkTag(['rel' => 'icon', 'sizes' => '16x16 32x32 48x48 64x64', 'type' => 'image/x-icon', 'href' => Yii::$app->assetManager->getBundle('app\assets\ImagesAsset')->baseUrl.'/favicon.ico']) ?>
<?= Html::csrfMetaTags() ?>
<?= $this->head() ?></head>
<body>

<?php $this->beginBody() ?>
<div class="wrap">
	<?php NavBar::begin([
		'brandLabel' => Yii::$app->name,
		'brandUrl' => Yii::$app->homeUrl,
		'options' => [
			'class' => 'navbar-default navbar-fixed-top',
		],
	]);

	if (Yii::$app->controller->id !== 'site' || Yii::$app->controller->action->id !== 'offline') {
		echo Nav::widget([
			'encodeLabels' => false,
			'items' => MenuItems::menuArray(),
			'options' => ['class' => 'navbar-nav navbar-right'],
		]);
	}

	NavBar::end();
	?>

	<div class="container">
		<?= Breadcrumbs::widget([
			'homeLink' => ['label' => Yii::$app->name, 'url' => Yii::$app->homeUrl],
			'links' => $this->params['breadcrumbs'] ?? [],
		]) ?>
		<?= $content ?>
	</div>
</div>

<footer>
	<div class="container">
		<p class="pull-left">&copy; 2014-<?= date('Y') ?> <?= Yii::$app->name ?></p>
		<p class="pull-right"><?php
			if (Yii::$app->controller->id !== 'site' || Yii::$app->controller->action->id !== 'offline') {
				echo Html::a('Contact', ['/site/contact'], ['class' => 'label label-primary']) . ' ';
				echo Html::a('Changelog', ['/site/changelog'], ['class' => 'label label-primary visible-md-inline visible-lg-inline']) . ' ';
				echo Html::a('RSS', ['/feed/rss'], ['class' => 'label label-warning visible-md-inline visible-lg-inline', 'target' => '_blank']);
			}
		?></p>
	</div>
	<?php echo Html::a(Html::tag('span', '&nbsp;&nbsp;^&nbsp;&nbsp;', ['title' => 'Scroll to top', 'data-toggle' => 'tooltip', 'data-placement' => 'top']), false, ['id' => 'btn-scrolltop']); ?>
</footer>
<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
