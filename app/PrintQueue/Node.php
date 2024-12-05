<?php
	namespace App\PrintQueue;

	class Node
	{
		public int $value;
		/** @var \App\Node[] */
		public array $children = [];

		public function __construct($value)
		{
			$this->value = $value;
		}

		public function isEmpty(): bool
		{
			return count($this->children) === 0;
		}
	}
?>
