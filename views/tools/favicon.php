<?php
use nezhelskoy\highlight\HighlightAsset;
use yii\bootstrap\{ActiveForm, Alert, Html};
use yii\helpers\{Inflector, Url};
use yii\web\View;

$this->title = 'Favicon Converter';
$this->params['breadcrumbs'][] = 'Tools';
$this->params['breadcrumbs'][] = $this->title;

HighlightAsset::register($this);
$this->registerJs(Yii::$app->formatter->jspack('inputFile.js'), View::POS_READY);
?>
<div class="row">
	<div class="col-md-offset-2 col-md-8"><?php
		echo Html::tag('h1', Html::encode($this->title));
		echo Html::tag('p', 'A favicon (short for \'favorites icon\'), are little icons associated with a particular website or webpage, shown next to the site\'s name in the URL bar or the page\'s title on the tab of all major browsers. Browse to the file\'s location on your computer to select the image and press the \'' . $model->getAttributeLabel('generate') . '\' button to generate a favicon for your site.');

		foreach ($model->dimensions as $dimension)
			$dimensions[] = $dimension.'x'.$dimension;

		if ($flash = Yii::$app->session->getFlash('favicon-error'))
			echo Alert::widget(['options' => ['class' => 'alert-danger'], 'body' => $flash]);

		if ($icon = Yii::$app->session->getFlash('favicon-success')) {
			Alert::begin(['options' => ['class' => 'alert-success']]);
			echo Html::img(Url::to('@web/assets/temp/favicon/'.$icon), ['alt' => 'favicon.ico', 'class' => 'inline-left pull-left', 'height' => 64, 'width' => 64]);
			echo '<p>Your icon has been generated successfully. Save it to your website and add the code below between the &lt;head&gt; tags of your html. This will allow all major browsers to show the icon when the website is accessed and/or bookmarked.<br>';
			echo 'Do not link the icon directly to this website. Your icon will soon be automatically deleted.</p>';
			echo '<br><pre><code>&lt;link rel="icon" href="/path/to/'.$icon.'" type="image/x-icon" sizes="'.implode(' ', $dimensions).'" /&gt;</code></pre>';
			Alert::end();
		}

		$form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'email', [
				'template' => '{label} (optional)<div class="input-group"><span class="input-group-addon"><span class="addon-email"></span></span>{input}</div>{hint} {error}',
			])
			->input('email')
			->hint('If you enter your email address here the favicon will be mailed to that address.') ?>

		<div class="input-group">
			<span class="input-group-addon">
				<?= Html::icon('picture') ?>
			</span>
			<input type="text" id="file" class="form-control" placeholder="Select an image" onclick="$('input[id=sourceFile]').click();" readonly>
			<span class="input-group-btn">
				<button type="button" class="btn btn-primary" onclick="$('input[id=sourceFile]').click();"><?= Html::icon('folder-open') ?></button>
			</span>
		</div>

		<?= $form->field($model, 'sourceImage')
			->fileInput(['accept' => 'image/*', 'class' => 'hidden', 'id' => 'sourceFile'])
			->hint('For the best result you should upload a square image. Your icon will be generated in ' . Inflector::sentence($dimensions) . ' pixels.')
			->label(false) ?>

		<div class="form-group">
			<?= Html::submitButton($model->getAttributeLabel('generate'), ['class' => 'btn btn-block btn-primary']) ?>
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>
