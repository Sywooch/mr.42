<?php
namespace app\models\user;
use Yii;
use app\models\Feed;
use yii\bootstrap\Html;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\httpclient\Client;

class RecentTracks extends ActiveRecord
{
	public static function tableName()
	{
		 return '{{%recenttracks}}';
	}

	public static function display($userid)
	{
		if (time()-self::lastSeen($userid) > 300) {
			$profile = Profile::find()->where(['user_id' => $userid])->one();
			self::updateUser(1, $profile);
		}
		self::updateAll(['seen' => time()], 'userid = '.$userid);

		$limit = (isset(Yii::$app->params['recentTracksCount']) && is_int(Yii::$app->params['recentTracksCount'])) ? Yii::$app->params['recentTracksCount'] : 25;
		$recentTracks = self::find()
			->where(['userid' => $userid])
			->orderBy('count DESC')
			->limit($limit)
			->all();

		foreach ($recentTracks as $track) {
			echo '<div class="clearfix track">';
				echo Html::tag('span', $track['artist'], ['class' => 'pull-left']);
				if ($track['time'] === 0)
					echo Html::icon('volume-up', ['title' => 'Currently playing']);
				echo Html::tag('span', $track['track'], ['class' => 'pull-right text-right']);
			echo '</div>';
		}

		echo (empty($recentTracks)) ?
			Html::tag('p', 'No items to display.') :
			Html::tag('div',
				Html::tag('span', Html::tag('strong', 'Total tracks played:'), ['class' => 'pull-left']) .
				Html::tag('span', Html::tag('strong', Yii::$app->formatter->asInteger($recentTracks[0]['count'])), ['class' => 'pull-right'])
			, ['class' => 'clearfix']);
	}

	public static function lastSeen($userid) {
		$lastSeen = self::find()
			->select(['seen' => 'max(seen)'])
			->where(['userid' => $userid])
			->one();
		return $lastSeen->seen;
	}

	public static function updateUser($lastSeen, $profile) {
		$client = new Client(['baseUrl' => 'https://ws.audioscrobbler.com/2.0/']);
		$limit = (isset(Yii::$app->params['recentTracksCount']) && is_int(Yii::$app->params['recentTracksCount'])) ? Yii::$app->params['recentTracksCount'] : 25;

		if (isset($profile->lastfm)) {
			$response = $client->createRequest()
				->addHeaders(['user-agent' => Yii::$app->name.' (+'.Url::to(['site/index'], true).')'])
				->setMethod('get')
				->setUrl('?method=user.getrecenttracks&user=' . $profile->lastfm . '&limit=' . $limit . '&api_key=' . Yii::$app->params['LastFMAPI'])
				->send();

			if (!$response->isOK)
				return false;

			$playcount = (int) $response->data['recenttracks']['@attributes']['total'];
			$count = 0;
			self::deleteAll(['userid' => $profile->user_id]);
			foreach($response->data['recenttracks']['track'] as $track) {
				$addTrack = new RecentTracks();
				$addTrack->userid = $profile->user_id;
				$addTrack->artist = (string) $track->artist;
				$addTrack->track = (string) $track->name;
				$addTrack->count = $playcount--;
				$addTrack->time = ((bool) $track['nowplaying']) ? 0 : (int) $track->date['uts'];
				$addTrack->seen = $lastSeen;
				$addTrack->save();

				$count++;
				if ($count === $limit)
					break;
			}
		}
	}
}
