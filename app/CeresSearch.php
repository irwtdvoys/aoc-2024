<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;

	class CeresSearch extends Helper
	{
		/** @var string[][] */
		private array $data;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$this->data = array_map(
				function ($element)
				{
					return str_split($element);
				},
				explode(PHP_EOL, $raw)
			);
		}

		private function draw(): void
		{
			foreach ($this->data as $next)
			{
				$this->output(implode("", $next));
			}
		}

		private function value(int $x, int $y): string
		{
			return $this->data[$x][$y] ?? " ";
		}

		private function check(int $x, int $y): int
		{
			$count = 0;

			$strings = [
				$this->value($x, $y) . $this->value($x + 1, $y) . $this->value($x + 2, $y) . $this->value($x + 3, $y),              // v
				$this->value($x, $y) . $this->value($x - 1, $y) . $this->value($x - 2, $y) . $this->value($x - 3, $y),              // ^
				$this->value($x, $y) . $this->value($x, $y - 1) . $this->value($x, $y - 2) . $this->value($x, $y - 3),              // <
				$this->value($x, $y) . $this->value($x, $y + 1) . $this->value($x, $y + 2) . $this->value($x, $y + 3),              // >
				$this->value($x, $y) . $this->value($x + 1, $y + 1) . $this->value($x + 2, $y + 2) . $this->value($x + 3, $y + 3),  // v>
				$this->value($x, $y) . $this->value($x + 1, $y - 1) . $this->value($x + 2, $y - 2) . $this->value($x + 3, $y - 3),  // v<
				$this->value($x, $y) . $this->value($x - 1, $y - 1) . $this->value($x - 2, $y - 2) . $this->value($x - 3, $y - 3),  // ^<
				$this->value($x, $y) . $this->value($x - 1, $y + 1) . $this->value($x - 2, $y + 2) . $this->value($x - 3, $y + 3)   // ^>
			];

			foreach ($strings as $string)
			{
				if ($string === "XMAS")
				{
					$count++;
				}
			}

			return $count;
		}

		private function checkX(int $x, int $y): int
		{
			$count = 0;

			$strings = [
				$this->value($x - 1, $y - 1) . $this->value($x, $y) . $this->value($x + 1, $y + 1), // ^< - v>
				$this->value($x - 1, $y + 1) . $this->value($x, $y) . $this->value($x + 1, $y - 1), // ^> - v<
			];

			if (($strings[0] === "MAS" || $strings[0] === "SAM") && ($strings[1] === "MAS" || $strings[1] === "SAM"))
			{
				$count++;
			}

			return $count;
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			for ($x = 0; $x < count($this->data); $x++)
			{
				for ($y = 0; $y < count($this->data[$x]); $y++)
				{
					if ($this->data[$x][$y] === "X")
					{
						$result->part1 += $this->check($x, $y);
					}

					if ($this->data[$x][$y] === "A")
					{
						$result->part2 += $this->checkX($x, $y);
					}
				}
			}

			return $result;
		}
	}
?>
