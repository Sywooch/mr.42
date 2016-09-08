<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\helpers\Html;
use yii\jui\DatePicker;

$this->title = 'Microsoft® Office 365® End Date';
$this->params['breadcrumbs'][] = 'Calculator';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<?= Html::tag('h1', Html::encode($this->title)) ?>

		<p>This calculator calculates the new end date of a Microsoft® Office 365® Open SKU.</p>

		<?php
		if ($flash = Yii::$app->session->getFlash('office365-error')) {
			$lic = ($flash['count'] == 1) ? 'license' : 'licenses';
			$txt = '<p><strong>This action is not allowed.</strong> Subscriptions have a maximum end date of 3 years into the future.</p>';
			$txt .= '<p>Theoratically the subscription with <strong>' . $flash['count'] . ' user ' . $lic . '</strong> would approximately expire on <strong>' . $flash['date']->format('D, d M Y') . '</strong>.</p>';
			echo Alert::widget(['options' => ['class' => 'alert-danger'], 'body' => $txt]);
		} elseif ($flash = Yii::$app->session->getFlash('office365-success')) {
			$lic = ($flash['count'] == 1) ? 'license' : 'licenses';
			$txt = '<p>The subscription with <strong>' . $flash['count'] . ' user ' . $lic . '</strong> will approximately expire on <strong>' . $flash['date']->format('D, d M Y') . '</strong>.</p>';
			echo Alert::widget(['options' => ['class' => 'alert-success'], 'body' => $txt]);
		}

		$form = ActiveForm::begin();
		$tab = 0;
		foreach (['source', 'target'] as $field) {
			$tab++;
			echo '<div class="row">';
			echo $form->field($model, $field.'date', [
				'options' => ['class' => 'col-xs-6'],
				'template' => '{label}<div class="input-group"><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>{input}</div>{error}',
			])->widget(DatePicker::classname(), [
				'clientOptions' => [
					'changeMonth' => true,
					'changeYear' => true,
					'firstDay' => 1,
					'yearRange' => '-2Y:+3Y',
				],
				'dateFormat' => 'yyyy-MM-dd',
				'language' => 'en-GB',
				'options' => ['class' => 'form-control', 'tabindex' => $tab],
			]);

			$tab++;
			echo $form->field($model, $field.'count', [
				'options' => ['class' => 'col-xs-6'],
				'template' => '{label}<div class="input-group"><span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>{input}</div>{error}',
			])
			->textInput(['class' => 'form-control', 'tabindex' => $tab]);
			echo '</div>';
		}

		echo $form->field($model, 'action')->dropDownList([
			'renew' => 'I am renewing these licenses',
			'add' => 'I am adding these licenses',
		]);
		?>

		<div class="form-group text-right">
			<?= Html::resetButton('Reset', ['class' => 'btn btn-default', 'tabindex' => 6]) ?>
			<?= Html::submitButton('Calculate', ['class' => 'btn btn-primary', 'tabindex' => 5]) ?>
		</div>

		<?php ActiveForm::end(); ?>

	</div>
</div>