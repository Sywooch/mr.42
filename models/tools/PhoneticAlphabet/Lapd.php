<?php
namespace app\models\tools\PhoneticAlphabet;

class Lapd extends \app\models\tools\PhoneticAlphabet
{
	public function name() {
		return 'LAPD radio alphabet';
	}

	public function sortOrder() {
		return '3';
	}

	public function alphabetArray() {
		return ['a' => 'Adam',
				'b' => 'Boy',
				'c' => 'Charles',
				'd' => 'David',
				'e' => 'Edward',
				'f' => 'Frank',
				'g' => 'George',
				'h' => 'Henry',
				'i' => 'Ida',
				'j' => 'John',
				'k' => 'King',
				'l' => 'Lincoln',
				'm' => 'Mary',
				'n' => 'Nora',
				'o' => 'Ocean',
				'p' => 'Paul',
				'q' => 'Queen',
				'r' => 'Robert',
				's' => 'Sam',
				't' => 'Tom',
				'u' => 'Union',
				'v' => 'Victor',
				'w' => 'William',
				'x' => 'X-ray',
				'y' => 'Young',
				'z' => 'Zebra',
		];
	}

	public function numericArray() {
		return ['0' => 'Zero',
				'1' => 'One',
				'2' => 'Two',
				'3' => 'Three',
				'4' => 'Four',
				'5' => 'Five',
				'6' => 'Six',
				'7' => 'Seven',
				'8' => 'Eight',
				'9' => 'Niner',
		];
	}
}
