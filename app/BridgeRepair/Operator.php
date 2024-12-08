<?php

	namespace App\BridgeRepair;

	enum Operator: string
	{
		case Add = "+";
		case Multiply = "*";
		case Concat = "||";
	}
?>
