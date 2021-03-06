<?php
namespace app\models\user;
use DateTime;
use Yii;
use yii\bootstrap\Html;
use yii\db\ActiveRecord;

class Profile extends \dektrium\user\models\Profile {
	public function attributeLabels() {
		$labels = parent::attributeLabels();
		$labels['lastfm'] = 'Last.fm Username';
		$labels['birthday'] = 'Date of Birth';
		$labels['bio'] = 'Profile';
		return $labels;
	}

	public function rules() {
		$rules = parent::rules();
		$rules['required'] = ['birthday', 'required'];
		$rules['lastfm'] = ['lastfm', 'string', 'max' => 64];
		$rules['bioString'] = ['bio', 'string', 'max' => 4096];
		$rules[] = ['birthday', 'date', 'format' => 'php:Y-m-d', 'max' => date('Y-m-d', strtotime('-16 years')), 'min' => date('Y-m-d', strtotime('-110 years'))];
		return $rules;
	}

	public function beforeSave($insert) {
		if (!ActiveRecord::beforeSave($insert))
			return false;
		$this->bio = Yii::$app->formatter->cleanInput($this->bio, false);
		$this->bio = strtr($this->bio, ['&lt;' => '<', '&gt;' => '>', '&amp;' => '&']);
		$this->name = !empty($this->name) ? $this->name : null;
		return true;
	}

	public function show($user) {
		$replace = ['%age%' => (new DateTime())->diff(new DateTime($user->birthday))->y];
		$user->bio = Yii::$app->formatter->cleanInput(strtr($user->bio, $replace), 'gfm-comment');
		return empty($user->bio) ? false : Html::tag('div', $user->bio, ['class' => 'profile']);
	}
}
