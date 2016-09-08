<?php
use app\assets\ClipboardJsAsset;
use yii\helpers\Html;
use yii\web\View;

$this->title = 'Random Password Generator';
$this->params['breadcrumbs'][] = 'Tools';
$this->params['breadcrumbs'][] = $this->title;

ClipboardJsAsset::register($this);
$this->registerJs('function rndpass(a){chars="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";pass="";for(x=0;x<a;x++){i=Math.floor(Math.random()*chars.length);pass+=chars.charAt(i)}return pass}function get(){$("#password").attr("class","well well-sm alert-success text-center form-control").html(rndpass($("#length").val()));return false}', View::POS_HEAD);
$this->registerJs('$("#length").change(function(){get();}).change();', View::POS_READY);
?>
<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<?= Html::tag('h1', Html::encode($this->title)) ?>

		<p>This <?= Html::encode($this->title) ?> provides an easy way to create a random password. Password generation is done client-side (on your computer) using JavaScript. <strong>None</strong> of this information will be sent over the network.</p>

		<form name="passform">
			<div class="form-group field-passform-length">
				<label class="control-label" for="length">Password Length</label>
				<div class="input-group">
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-dashboard"></span>
					</span>
					<select id="length" class="form-control">
						<?php for ($x=6; $x<=64; $x++) { echo '<option value="'.$x.'"'; if ($x==12) { echo ' selected'; } echo '>'.$x.'</option>'; } ?>
					</select>
				</div>
			</div>

			<div class="form-group passform-password">
				<label class="control-label">Password</label>
				<div class="row">
					<div class="col-md-11">
						<div id="password">Javascript is disabled in your web browser. This tool does not work without Javascript.</div>
					</div>
					<div class="col-md-1 text-right">
						<button class="btn btn-sm btn-primary clipboard-js-init" data-clipboard-target="#password" data-toggle="tooltip" data-placement="top" title="Copy to Clipboard" type="button"><span class="glyphicon glyphicon-copy"></span></button>
					</div>
				</div>
			</div>

			<div class="form-group button">
				<button type="button" class="btn btn-block btn-primary" onclick="get()">Generate Password</button>
			</div>
		</form>
	</div>
</div>
