<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;

	class HistorianHysteria extends Helper
	{
		/** @var int[][] */
		private array $lists;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);
			$lines = explode(PHP_EOL, $raw);

			foreach ($lines as $line)
			{
				[$left, $right] = (explode("   ", $line));

				$this->lists[0][] = $left;
				$this->lists[1][] = $right;
			}

			sort($this->lists[0]);
			sort($this->lists[1]);
		}

		private function distance(int $index): int
		{
			$left = $this->lists[0][$index];
			$right = $this->lists[1][$index];

			return ($left > $right) ? $left - $right : $right - $left;
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$counts = array_count_values($this->lists[1]);

			for ($index = 0; $index < count($this->lists[0]); $index++)
			{
				$result->part1 += $this->distance($index);

				$value = $this->lists[0][$index];

				if (isset($counts[$value]))
				{
					$result->part2 += $value * $counts[$value];
				}
			}


			return $result;
		}
	}
?>
