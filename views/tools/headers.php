<?php
use yii\bootstrap\Html;

$this->title = 'Browser Headers';
$this->params['breadcrumbs'][] = 'Tools';
$this->params['breadcrumbs'][] = $this->title;

echo Html::tag('h1', Html::encode($this->title));

echo '<div class="site-headers">';
$x=0;
$headers = apache_request_headers();
foreach ($headers as $header => $value) {
	$x++;
	if ($header != "Cookie") {
		echo '<div class="row">';
		echo Html::tag('div', Html::tag('strong', $header), ['class' => 'col-lg-2']);
		echo Html::tag('div', $value, ['class' => 'col-lg-10']);
		echo '</div>';
	}
}
echo '</div>';
