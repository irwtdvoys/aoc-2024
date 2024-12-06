<?php
	namespace App\GuardGallivant;

	enum Direction: string
	{
		case North = "^";
		case East = ">";
		case South = "v";
		case West = "<";
	}
?>
