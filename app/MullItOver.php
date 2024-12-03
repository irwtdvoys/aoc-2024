<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;

	class MullItOver extends Helper
	{
		private string $data;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$this->data = parent::load($override);
		}

		public function run(): Result
		{
			$result = new Result(0, 0);
			$enabled = true;

			preg_match_all("/mul\((?'a'[0-9]{1,3}),(?'b'[0-9]{1,3})\)|don't\(\)|do\(\)/", $this->data, $matches);

			for ($index = 0; $index < count($matches[0]); $index++)
			{
				if (str_starts_with($matches[0][$index], "mul"))
				{
					$value = $matches['a'][$index] * $matches['b'][$index];

					$result->part1 += $value;

					if ($enabled)
					{
						$result->part2 += $value;
					}
				}
				else
				{
					$enabled = $matches[0][$index] === "do()";
				}
			}

			return $result;
		}
	}
?>
