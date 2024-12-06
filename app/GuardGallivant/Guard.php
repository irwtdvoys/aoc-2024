<?php
	namespace App\GuardGallivant;

	use AoC\Utils\Position2d;

	class Guard
	{
		public Position2d $position;
		public Direction $direction;

		public function __construct(int $x, int $y, Direction $direction)
		{
			$this->position = new Position2d($x, $y);
			$this->direction = $direction;
		}

		public function turn(): void
		{
			switch ($this->direction)
			{
				case Direction::North:
					$this->direction = Direction::East;
					break;
				case Direction::East:
					$this->direction = Direction::South;
					break;
				case Direction::South:
					$this->direction = Direction::West;
					break;
				case Direction::West:
					$this->direction = Direction::North;
					break;
			}
		}

		public function move(): void
		{
			switch ($this->direction)
			{
				case Direction::North:
					$this->position->y--;
					break;
				case Direction::East:
					$this->position->x++;
					break;
				case Direction::South:
					$this->position->y++;
					break;
				case Direction::West:
					$this->position->x--;
					break;
			}
		}

		public function target(): Position2d
		{
			$target = new Position2d($this->position->x, $this->position->y);

			switch ($this->direction)
			{
				case Direction::North:
					$target->y--;
					break;
				case Direction::East:
					$target->x++;
					break;
				case Direction::South:
					$target->y++;
					break;
				case Direction::West:
					$target->x--;
					break;
			}

			return $target;
		}
	}
?>
