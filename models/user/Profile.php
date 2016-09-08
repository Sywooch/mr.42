<?php
namespace app\models\user;
use dektrium\user\models\Profile as BaseProfile;
use DateTime;
use app\models\General;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;

class Profile extends BaseProfile
{
	public function attributeLabels()
	{
		$labels = parent::attributeLabels();
		$labels['lastfm'] = 'Last.fm Username';
		$labels['birthday'] = 'Date of Birth';
		$labels['bio'] = 'Profile';
		return $labels;
	}

	public function rules()
	{
		$rules = parent::rules();
		$rules['required'] = ['birthday', 'required'];
		$rules['lastfm'] = ['lastfm', 'string', 'max' => 64];
		$rules['bioString'] = ['bio', 'string', 'max' => 4096];
		$rules[] = ['birthday', 'date', 'format' => 'php:Y-m-d', 'max' => date('Y-m-d', strtotime('-16 years')), 'min' => date('Y-m-d', strtotime('-110 years'))];
		return $rules;
	}

	public function beforeSave($insert)
	{
		$this->bio = General::cleanInput($this->bio, false);
		$this->bio = str_replace(['&lt;', '&gt;', '&amp;'], ['<', '>', '&'], $this->bio);
		$this->setAttribute('bio', $this->bio);
		return ActiveRecord::beforeSave($insert);
	}

	public static function show($user)
	{
		$name = empty($user->name) ? Html::encode($user->user->username) : Html::encode($user->name);
		$replace_array = array('%age%' => (new DateTime())->diff(new DateTime($user->birthday))->y);
		$imgUrl = Url::to('@assetsUrl/images/william-morris-letters/'.strtolower($name[0])).'.png';
		$imgTag = Html::img($imgUrl, ['alt' => $name, 'class' => 'inline-left pull-left']);
		$user->bio = General::cleanInput($imgTag . '**'.substr($name, 1).'** '.strtr($user->bio, $replace_array), 'gfm-comment', true);
		return (empty($user->bio)) ? false : Html::tag('div', $user->bio, ['class' => 'profile']);
	}
}