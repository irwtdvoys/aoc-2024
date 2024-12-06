<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use AoC\Utils\Position2d;
	use App\GuardGallivant\Direction;
	use App\GuardGallivant\Guard;
	use Exception;

	class GuardGallivant extends Helper
	{
		/** @var string[][] */
		private array $map;

		/** @var Direction[][] */
		private array $visited;

		private array $order = [];

		private Guard $guard;

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
					$value = $line[$x];

					if ($value === "^")
					{
						$this->guard = new Guard($x, $y, Direction::North);
						$this->visit($this->guard->position, $this->guard->direction);
						$value = ".";
					}

					$this->map[$y][$x] = $value;
				}
			}
		}

		private function draw(bool $includeVisited = false): void
		{
			if (!$this->verbose)
			{
				return;
			}

			for ($y = 0; $y < count($this->map); $y++)
			{
				$output = "";
				$line = $this->map[$y];

				for ($x = 0; $x < count($line); $x++)
				{
					$position = new Position2d($x, $y);

					if ($x === $this->guard->position->x && $y === $this->guard->position->y)
					{
						$value = $this->guard->direction->value;
					}
					elseif ($includeVisited && isset($this->visited[(string)$position]))
					{
						$value = "X";
					}
					else
					{
						$value = $line[$x];
					}

					$output .= $value;
				}

				$this->output($output);
			}

			$this->output("");
		}

		private function forward(): void
		{
			$target = $this->guard->target();

			while ($this->map[$target->y][$target->x] !== "#")
			{
				// step
				$this->guard->move();

				if (isset($this->visited[(string)$this->guard->position]) && in_array($this->guard->direction, $this->visited[(string)$this->guard->position]))
				{
					throw new Exception("Loop Found", 1);
				}

				$this->visit($this->guard->position, $this->guard->direction);

				$target = $this->guard->target();

				if (!isset($this->map[$target->y][$target->x]))
				{
					throw new Exception("Left Map @ " . $target, 0);
				}
			}
		}

		private function visit(Position2d $position, Direction $direction): void
		{
			if (!isset($this->visited[(string)$position]))
			{
				$this->visited[(string)$position] = [];
				$this->order[] = [
					$position->x,
					$position->y,
					$direction
				];
			}

			$this->visited[(string)$position][] = $direction;
		}

		private function followRoute(): bool
		{
			try
			{
				while (true)
				{
					$this->forward();
					$this->guard->turn();
					$this->draw();
				}
			}
			catch (Exception $exception)
			{
				$this->output($exception->getMessage());

				return $exception->getCode() === 0;
			}
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$cache = (object)[
				"visited" => [],
				"position" => clone $this->guard->position,
				"direction" => $this->guard->direction,
				"map" => $this->map,
			];

			$this->draw();
			$this->followRoute();
			$this->draw(true);

			$result->part1 = count($this->visited);

			// Don't place an obstacle at start position
			array_shift($this->order);
			$order = $this->order;

			foreach ($order as $next)
			{
				// reset
				$this->visited = $cache->visited;
				$this->map = $cache->map;
				$this->guard = new Guard($cache->position->x, $cache->position->y, $cache->direction);
				$this->order = [];

				[$x, $y, $direction] = $next;

				// set new obstacle
				$this->map[$y][$x] = "#";

				$exits = $this->followRoute();

				if ($exits === false)
				{
					$result->part2++;
				}

				$cache->visited[(string)$cache->position][] = $cache->direction;
				$cache->position = new Position2d($x, $y);
				$cache->direction = $direction;
			}

			return $result;
		}
	}
?>
