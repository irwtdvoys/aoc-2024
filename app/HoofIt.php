<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use AoC\Utils\Position2d;

	class HoofIt extends Helper
	{
		/** @var int[][] */
		private array $map;
		/** @var Position2d[] */
		private array $trailheads = [];

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);
			$lines = explode(PHP_EOL, $raw);

			for ($y = 0; $y < count($lines); $y++)
			{
				$line = str_split($lines[$y]);

				for ($x = 0; $x < count($line); $x++)
				{
					$value = (int)$line[$x];

					if ($value === 0)
					{
						$this->trailheads[] = new Position2d($x, $y);
					}

					$this->map[$y][$x] = $value;
				}
			}
		}

		public function draw(): void
		{
			if (!$this->verbose)
			{
				return;
			}

			for ($y = 0; $y < count($this->map); $y++)
			{
				$output = "";

				for ($x = 0; $x < count($this->map[$y]); $x++)
				{
					$output .= $this->map[$y][$x];
				}

				$this->output($output);
			}

			$this->output("");
		}

		private function process(Position2d $trailhead): array
		{
			$result = [];
			$value = $this->map[$trailhead->y][$trailhead->x];
			$this->output($trailhead . " - " . $value);

			if ($value === 9)
			{
				return [(string)$trailhead];
			}

			$points = array_values(
				array_filter([
					isset($this->map[$trailhead->y - 1][$trailhead->x]) ? new Position2d($trailhead->x, $trailhead->y - 1) : null,
					isset($this->map[$trailhead->y][$trailhead->x + 1]) ? new Position2d($trailhead->x + 1, $trailhead->y) : null,
					isset($this->map[$trailhead->y + 1][$trailhead->x]) ? new Position2d($trailhead->x, $trailhead->y + 1) : null,
					isset($this->map[$trailhead->y][$trailhead->x - 1]) ? new Position2d($trailhead->x - 1, $trailhead->y) : null
				])
			);

			foreach ($points as $point)
			{
				if ($this->map[$point->y][$point->x] === $value + 1)
				{
					$result = array_merge($result, $this->process($point));
				}
			}

			return $result;
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$this->draw();

			foreach ($this->trailheads as $trailhead)
			{
				$this->output("Processing: " . $trailhead);

				$points = $this->process($trailhead);

				$result->part1 += count(array_unique($points));
				$result->part2 += count($points);
			}

			return $result;
		}
	}
?>
