<?php
namespace app\models\tools\PhoneticAlphabet;

class DutchNl extends \app\models\tools\PhoneticAlphabet
{
	public function name() {
		return 'Dutch (The Netherlands)';
	}

	public function sortOrder() {
		return self::name();
	}

	public function alphabetArray() {
		return ['a' => 'Anton',
				'b' => 'Bernhard',
				'c' => 'Cornelis',
				'd' => 'Dirk',
				'e' => 'Eduard',
				'f' => 'Ferdinand',
				'g' => 'Gerard',
				'h' => 'Hendrik',
				'i' => 'Isaak',
				'j' => 'Johannes',
				'k' => 'Karel',
				'l' => 'Lodewijk',
				'm' => 'Marie',
				'n' => 'Nico',
				'o' => 'Otto',
				'p' => 'Pieter',
				'q' => 'Quotiënt',
				'r' => 'Rudolf',
				's' => 'Simon',
				't' => 'Tinus',
				'u' => 'Utrecht',
				'v' => 'Victor',
				'w' => 'Willem',
				'x' => 'Xantippe',
				'y' => 'Ypsilon',
				'z' => 'Zacharias',
		];
	}

	public function numericArray() {
		return ['0' => 'Nul',
				'1' => 'Een',
				'2' => 'Twee',
				'3' => 'Drie',
				'4' => 'Vier',
				'5' => 'Vijf',
				'6' => 'Zes',
				'7' => 'Zeven',
				'8' => 'Acht',
				'9' => 'Negen',
		];
	}
}
