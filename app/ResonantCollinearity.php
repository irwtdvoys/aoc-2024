<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use AoC\Utils\Dimensions2d;
	use AoC\Utils\Position2d;

	class ResonantCollinearity extends Helper
	{
		/** @var Position2d[][] */
		private array $antennas;
		private Dimensions2d $dimensions;
		/** @var int[] */
		private array $antinodes = [];

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
					if ($line[$x] !== ".")
					{
						$this->antennas[$line[$x]][] = new Position2d($x, $y);
					}
				}
			}

			$this->dimensions = new Dimensions2d(strlen($lines[0]), count($lines));
		}

		private function draw(): void
		{
			for ($y = $this->dimensions->y->min; $y < $this->dimensions->y->max + 1; $y++)
			{
				$output = "";

				for ($x = $this->dimensions->x->min; $x < $this->dimensions->x->max + 1; $x++)
				{
					$value = ".";

					if (isset($this->antinodes[$x . "," . $y]))
					{
						$value = "#";
					}

					foreach ($this->antennas as $frequency => $antennas)
					{
						foreach ($antennas as $antenna)
						{
							if ($antenna->x === $x && $antenna->y === $y)
							{
								$value = $frequency;
								break 2;
							}
						}
					}

					$output .= $value;
				}

				$this->output($output);
			}

			$this->output("");
		}

		private function markAntinode(Position2d $position): void
		{
			if (!isset($this->antinodes[(string)$position]))
			{
				$this->antinodes[(string)$position] = 0;
			}

			$this->antinodes[(string)$position]++;
		}

		private function calculateAntinodes(bool $includeResonants = false): void
		{
			foreach ($this->antennas as $antennas)
			{
				foreach ($antennas as $source)
				{

					foreach ($antennas as $destination)
					{
						if ($source === $destination)
						{
							continue;
						}

						$this->output($source . " -> " . $destination);

						if ($includeResonants)
						{
							$this->markAntinode($source);
							$this->markAntinode($destination);
						}

						$vector = new Position2d($destination->x - $source->x, $destination->y - $source->y);
						$this->output($vector);

						$target = new Position2d($destination->x, $destination->y);

						while (true)
						{
							$target->x += $vector->x;
							$target->y += $vector->y;

							$this->output($target);

							if ($this->dimensions->x->contains($target->x) && $this->dimensions->y->contains($target->y))
							{
								$this->output($target . " is in range");
								$this->markAntinode($target);
							}
							else
							{
								break;
							}

							if ($includeResonants === false)
							{
								break;
							}
						}
					}
				}
			}
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$this->draw();

			$this->calculateAntinodes();
			$this->draw();
			$result->part1 = count($this->antinodes);

			$this->calculateAntinodes(true);
			$this->draw();

			$result->part2 = count($this->antinodes);

			return $result;
		}
	}
?>
