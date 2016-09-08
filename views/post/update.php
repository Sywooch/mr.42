<?php
use yii\helpers\Html;

$this->title = $model->title . ' ∷ Edit Article';
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['index', 'id' => $model->id, 'title' => $model->title]];
$this->params['breadcrumbs'][] = 'Edit Article';
?>
<div class="article-update">
	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo $this->render('_formPost', [
		'model' => $model,
	]); ?>
</div>
