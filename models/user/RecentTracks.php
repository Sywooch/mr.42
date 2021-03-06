<?php
namespace app\models\user;
use Yii;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\httpclient\Client;

class RecentTracks extends \yii\db\ActiveRecord {
	public $limit = 20;

	public static function tableName() {
		 return '{{%recenttracks}}';
	}

	public function display($userid) {
		if (time() - self::lastSeen($userid) > 300) {
			$profile = Profile::find()->where(['user_id' => $userid])->one();
			self::updateUser(time(), $profile);
		}
		self::updateAll(['seen' => time()], 'userid = '.$userid);

		$limit = is_int(Yii::$app->params['recentTracksCount']) ? Yii::$app->params['recentTracksCount'] : $this->limit;
		$tracks = self::find()
			->where(['userid' => $userid])
			->orderBy('count DESC')
			->limit($limit)
			->all();

		foreach ($tracks as $track) :
			echo '<div class="clearfix track">';
				echo Html::tag('span', $track['artist'], ['class' => 'pull-left']);
				if ($track['time'] === 0)
					echo Html::icon('volume-up', ['title' => 'Currently playing']);
				echo Html::tag('span', $track['track'], ['class' => 'pull-right text-right']);
			echo '</div>';
		endforeach;

		echo empty($tracks)
			? Html::tag('p', 'No items to display.')
			: Html::tag('div',
				Html::tag('span', Html::tag('b', 'Total tracks played:'), ['class' => 'pull-left']) .
				Html::tag('span', Html::tag('b', Yii::$app->formatter->asInteger($tracks[0]['count'])), ['class' => 'pull-right'])
			);
	}

	public function lastSeen($userid) {
		$lastSeen = self::find()
			->select(['seen' => 'max(seen)'])
			->where(['userid' => $userid])
			->one();
		return $lastSeen->seen;
	}

	public function updateUser($lastSeen, $profile) {
		$client = new Client(['baseUrl' => 'https://ws.audioscrobbler.com/2.0/']);
		$limit = is_int(Yii::$app->params['recentTracksCount']) ? Yii::$app->params['recentTracksCount'] : $this->limit;

		if (isset($profile->lastfm)) {
			$response = $client->createRequest()
				->addHeaders(['user-agent' => Yii::$app->name.' (+'.Url::to(['site/index'], true).')'])
				->setData([
					'method' => 'user.getrecenttracks',
					'user' => $profile->lastfm,
					'limit' => $limit,
					'api_key' => Yii::$app->params['LastFMAPI'],
				])
				->send();

			if (!$response->isOK)
				return false;

			$playcount = (int) $response->data['recenttracks']['@attributes']['total'];
			$count = 1;
			foreach($response->data['recenttracks']['track'] as $track) {
				$time = (bool) $track['nowplaying'] ? 0 : (int) $track->date['uts'];
				$addTrack = self::findOne(['userid' => $profile->user_id, 'time' => $time]) ?? new RecentTracks();
				$addTrack->userid = $profile->user_id;
				$addTrack->artist = (string) $track->artist;
				$addTrack->track = (string) $track->name;
				$addTrack->count = $playcount--;
				$addTrack->time = $time;
				$addTrack->seen = $lastSeen;
				$addTrack->save();

				if ($count++ === $limit)
					break;
			}

			$delete = self::find()->where(['userid' => $profile->user_id])->orderBy('count DESC')->limit(999)->offset($limit)->all();
			foreach ($delete as $item)
				$item->delete();
		}
	}
}
