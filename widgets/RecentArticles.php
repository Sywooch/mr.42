<?php
namespace app\widgets;
use Yii;
use app\models\articles\Articles;
use yii\bootstrap\{Html, Widget};

class RecentArticles extends Widget {
	public function run() {
		$limit = is_int(Yii::$app->params['recentArticles']) ? Yii::$app->params['recentArticles'] : 5;
		$articles = Articles::find()
			->orderBy('created DESC')
			->limit($limit)
			->all();
		echo empty($articles) ? Html::tag('p', 'No articles to display.') : Html::tag('ul', $this->renderArticles($articles), ['class' => 'list-unstyled']);
	}

	public function renderArticles($articles) {
		foreach ($articles as $article) :
			$link = Html::a(Html::encode($article->title), ['articles/index', 'id' => $article->id, 'title' => $article->url]);
			$items[] = Html::tag('li', $link);
		endforeach;
		return implode($items);
	}
}
