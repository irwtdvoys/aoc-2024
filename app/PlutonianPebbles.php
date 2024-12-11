<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use AoC\Utils\Position2d;

	class PlutonianPebbles extends Helper
	{
		/** @var int[] */
		private array $pebbles;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$pebbles = array_map("intval", explode(" ", $raw));

			foreach ($pebbles as $pebble)
			{
				$this->pebbles[$pebble] = !isset($this->pebbles[$pebble]) ? 1 : $this->pebbles[$pebble] + 1;
			}
		}

		private function blink()
		{
			$new = [];

			foreach ($this->pebbles as $key => $count)
			{
				$keyString = (string)$key;

				if ($key === 0)
				{
					$new[1] = !isset($new[1]) ? $count : $new[1] + $count;
				}
				elseif (strlen($keyString) % 2 === 0)
				{
					[$left, $right] = array_map("intval", str_split($keyString, strlen($keyString) / 2));

					$new[$left] = !isset($new[$left]) ? $count : $new[$left] + $count;
					$new[$right] = !isset($new[$right]) ? $count : $new[$right] + $count;
				}
				else
				{
					$new[2024 * $key] = $count;
				}
			}

			$this->pebbles = $new;
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$count = 1;

			while ($count <= 25)
			{
				$this->blink();
				$count++;
			}

			$result->part1 = array_sum($this->pebbles);

			while ($count <= 75)
			{
				$this->blink();
				$count++;
			}

			$result->part2 = array_sum($this->pebbles);

			return $result;
		}
	}
?>
