<?php
namespace app\models\calculator;
use DateTime;
use Yii;

class Office365 extends \yii\base\Model {
	public $sourcedate;
	public $sourcecount;
	public $targetdate;
	public $targetcount;
	public $action;

	public function rules() {
		return [
			[['sourcedate', 'sourcecount', 'targetcount', 'action'], 'required'],
			[['sourcedate', 'targetdate'], 'date', 'format' => 'php:Y-m-d'],
			[['sourcecount', 'targetcount'], 'double'],
		];
	}

	public function attributeLabels() {
		return [
			'sourcedate' => 'Current End Date',
			'sourcecount' => 'Current Amount of Licenses',
			'targetdate' => 'Date of Product Key Redemption',
			'targetcount' => 'Amount of Licenses You Are Activating',
		];
	}

	public function calcEndDate() {
		if ($this->validate()) {
			$this->targetdate = $this->targetdate ?? date('Y-m-d');
			$diff = (new DateTime($this->sourcedate))->diff(new DateTime($this->targetdate));

			$redeemDate = ($diff->invert === 0 && $diff->days <= 30) ? $this->sourcedate : $this->targetdate;
			if ($diff->invert === 0)
				$diff = (new DateTime($this->targetdate))->diff(new DateTime($this->targetdate));

			$upcomingYear = new DateTime($redeemDate);
			$upcomingYearDays = (new DateTime($redeemDate))->diff($upcomingYear->modify('1 year'));

			$targetCount = $this->action == 'renew' ? $this->targetcount : $this->sourcecount + $this->targetcount;
			$dateCalc = (($diff->days * $this->sourcecount) + ($upcomingYearDays->days * $this->targetcount)) / $targetCount;

			$newDate = new DateTime($redeemDate);
			$newDate->modify(ceil($dateCalc) . ' days');

			$today = new DateTime();
			if ($newDate > $today->modify('3 years')) {
				Yii::$app->getSession()->setFlash('office365-error', ['date' => $newDate, 'count' => $targetCount]);
				return false;
			}

			Yii::$app->getSession()->setFlash('office365-success', ['date' => $newDate, 'count' => $targetCount]);
			return true;
		}
		return false;
	}
}
