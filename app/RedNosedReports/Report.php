<?php

	namespace App\RedNosedReports;

	class Report
	{
		/** @var int[] */
		private array $levels;

		public function __construct(array $data)
		{
			$this->levels = array_map("intval", $data);
		}

		public function isSafe(?array $levels = null): bool
		{
			$direction = null;

			if ($levels === null)
			{
				$levels = $this->levels;
			}

			for ($index = 0; $index < count($levels) - 1; $index++)
			{
				if ($direction === null)
				{
					$direction = $this->direction($levels[$index], $levels[$index + 1]);

					if ($direction === "-")
					{
						return false;
					}
				}

				if ($this->diff($levels[$index], $levels[$index + 1]) > 3)
				{
					return false;
				}

				$currentDirection = $this->direction($levels[$index], $levels[$index + 1]);

				if ($currentDirection !== $direction)
				{
					return false;
				}
			}

			return true;
		}

		public function checkClose(): bool
		{
			for ($index = 0; $index < count($this->levels); $index++)
			{
				$levels = $this->levels;
				unset($levels[$index]);
				$levels = array_values($levels);

				if ($this->isSafe($levels))
				{
					return true;
				}
			}

			return false;
		}

		private function direction(int $a, int $b): string
		{
			if ($a === $b)
			{
				return "-";
			}

			return $a < $b ? "^" : "v";
		}

		private function diff(int $a, int $b): int
		{
			return ($a > $b) ? $a - $b : $b - $a;
		}

		public function output(): void
		{
			echo(implode(" ", $this->levels) . PHP_EOL);
		}
	}
?>
