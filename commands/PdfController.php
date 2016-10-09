<?php
namespace app\commands;
use Yii;
use app\models\Formatter;
use app\models\lyrics\{Lyrics1Artists, Lyrics2Albums, Lyrics3Tracks};
use app\models\post\Post;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Builds PDF files for cache, unless already cached and up-to-date.
 */
class PdfController extends Controller {
	public $defaultAction = 'all';

	/**
	 * Builds all PDF files.
	*/
	public function actionAll() {
		self::actionAlbums();
		self::actionArticles();
	}

	/**
	 * Builds all albums PDF files.
	*/
	public function actionAlbums() {
		foreach(Lyrics1Artists::albumsList() as $artist) :
			$artistName = $this->ansiFormat($artist->name, Console::FG_PURPLE);
			foreach($artist->albums as $album) :
				$albumYear = $this->ansiFormat($album->year, Console::FG_GREEN);
				$albumName = $this->ansiFormat($album->name, Console::FG_GREEN);
				$this->stdout("$artistName");
				for($x=0; $x<(3-floor(strlen($artist->name)/8)); $x++)
					$this->stdout("\t");
				$this->stdout("$albumYear\t\t$albumName");
				for($x=0; $x<(7-floor(strlen($album->name)/8)); $x++)
					$this->stdout("\t");

				$tracks = Lyrics3Tracks::tracksListFull($album->artist->url, $album->year, $album->url);
				$html = $this->renderPartial('@app/views/lyrics/albumPdf', ['tracks' => $tracks]);
				$fileName = Lyrics2Albums::buildPdf($tracks, $html);

				if (!$fileName) {
					$this->stdout($this->ansiFormat("ERROR!\n", Console::BOLD, Console::FG_RED));
					continue;
				}

				$this->stdout(Yii::$app->formatter->asShortSize(filesize($fileName), 2)."\t" . $this->ansiFormat("OK\n", Console::BOLD, Console::FG_GREEN));
			endforeach;
		endforeach;

		return Controller::EXIT_CODE_NORMAL;
	}

	/**
	 * Builds all articles PDF files.
	*/
	public function actionArticles() {
		$posts = Post::find()
			->orderBy('created')
			->all();

		foreach($posts as $post) :
			$id = $this->ansiFormat($post->id, Console::FG_PURPLE);
			$updated = $this->ansiFormat(Yii::$app->formatter->asDate($post->updated, 'medium'), Console::FG_GREEN);
			$title = $this->ansiFormat($post->title, Console::FG_GREEN);
			$this->stdout("$id\t\t\t$updated\t$title");
			for($x=0; $x<(7-floor(strlen($post->title)/8)); $x++)
				$this->stdout("\t");

			$post->content = Formatter::cleanInput($post->content, 'gfm', true);
			$html = $this->renderPartial('@app/views/post/pdf', ['model' => $post]);
			$fileName = Post::buildPdf($post, $html);

			if (!$fileName) {
				$this->stdout($this->ansiFormat("ERROR!\n", Console::BOLD, Console::FG_RED));
				continue;
			}

			$this->stdout(Yii::$app->formatter->asShortSize(filesize($fileName), 2)."\t" . $this->ansiFormat("OK\n", Console::BOLD, Console::FG_GREEN));
		endforeach;

		return Controller::EXIT_CODE_NORMAL;
	}
}
