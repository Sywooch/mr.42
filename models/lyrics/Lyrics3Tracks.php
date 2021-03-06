<?php
namespace app\models\lyrics;
use Yii;

class Lyrics3Tracks extends \yii\db\ActiveRecord {
	public static function tableName() {
		return '{{%lyrics_3_tracks}}';
	}

	public function afterFind() {
		parent::afterFind();
		$this->track = sprintf('%02d', $this->track);
	}

	protected function baseList($artist, $year, $name, $view = null) {
		return self::find()
			->orderBy('track')
			->joinWith('artist')
			->with('album')
			->where(['or', Lyrics1Artists::tableName().'.name=:artist', Lyrics1Artists::tableName().'.url=:artist'])
			->andWhere(Lyrics2Albums::tableName().'.year=:year')
			->andWhere(['or', Lyrics2Albums::tableName().'.name=:album', Lyrics2Albums::tableName().'.url=:album'])
			->addParams([':artist' => $artist, ':year' => $year, ':album' => $name]);
	}

	public function tracksList($artist, $year, $name) {
		return self::baseList($artist, $year, $name)
			->all();
	}

	public function tracksListFull($artist, $year, $name) {
		return self::baseList($artist, $year, $name)
			->with('lyrics')
			->all();
	}

	public function lastUpdate($artist, $year, $name, $data = null, $max = null) {
		$data = $data ?? self::tracksList($artist, $year, $name);
		foreach ($data as $item)
			$max = max($max, $item->album->updated);
		return $max;
	}

	public function getArtist() {
		return $this->hasOne(Lyrics1Artists::className(), ['id' => 'parent'])
			->via('album');
	}

	public function getAlbum() {
		return $this->hasOne(Lyrics2Albums::className(), ['id' => 'parent']);
	}

	public function getLyrics() {
		return $this->hasOne(Lyrics4Lyrics::className(), ['id' => 'lyricid']);
	}
}
