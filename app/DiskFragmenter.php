<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;

	class DiskFragmenter extends Helper
	{
		private array $map;
		private array $disk = [];

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$this->map = array_map("intval", str_split($raw));
		}

		private function decode(): void
		{
			$fileCounter = 0;

			for ($index = 0; $index < count($this->map); $index++)
			{
				$item = $this->map[$index];

				if ($index % 2 === 0)
				{
					$value = $fileCounter;
					$fileCounter++;
				}
				else
				{
					$value = null;
				}

				$this->disk = array_merge($this->disk, array_fill(0, $item, $value));
			}
		}

		private function fragment(): void
		{
			for ($index = 0; $index < count($this->disk); $index++)
			{
				$item = $this->disk[$index];

				$this->output($index . ": " . $item);

				if ($item === null)
				{
					$value = null;

					while ($value === null)
					{
						$value = array_pop($this->disk);
					}

					$this->disk[$index] = $value;
				}
			}
		}

		private function checksum(): int
		{
			$total = 0;

			for ($index = 0; $index < count($this->disk); $index++)
			{
				$item = $this->disk[$index];

				if ($item === null)
				{
					continue;
				}

				$total += $index * $item;
			}

			return $total;
		}

		public function draw(array $files): void
		{
			$disk = [];

			usort(
				$files,
				function ($a, $b): int
				{
					return $a->index - $b->index;
				}
			);

			foreach ($files as $file)
			{
				for ($index = $file->index; $index < $file->index + $file->size; $index++)
				{
					$max = $index;
					$disk[$index] = $file->name;
				}
			}

			$output = array_fill(0, $max, ".");
			$this->output(implode("", array_replace($output, $disk)));
		}

		private function sortSpaces(array $spaces): array
		{
			usort(
				$spaces,
				function (mixed $a, mixed $b): int
				{
					return $a->index - $b->index;
				}
			);

			return $spaces;
		}

		public function betterPart2()
		{
			$files = [];
			$spaces = [];

			$fileCounter = 0;
			$tmp = 0;

			for ($index = 0; $index < count($this->map); $index++)
			{
				$item = $this->map[$index];

				if ($index % 2 === 0)
				{
					if ($item > 0)
					{
						$files[$fileCounter] = (object)[
							"index" => $tmp,
							"size" => $item,
							"name" => $fileCounter
						];
						$tmp += $item;
					}
					$fileCounter++;
				}
				else
				{
					if ($item > 0)
					{
						$spaces[] = (object)[
							"index" => $tmp,
							"size" => $item
						];
						$tmp += $item;
					}
				}
			}

			$files = array_reverse($files);
			$spaces = $this->sortSpaces($spaces);

			foreach ($files as $file)
			{
				// find first space large enough
				for ($index = 0; $index < count($spaces); $index++)
				{
					if ($spaces[$index]->index > $file->index)
					{
						break;
					}

					if ($spaces[$index]->size >= $file->size)
					{
						$spaces[] = (object)[
							"index" => $file->index,
							"size" => $file->size
						];

						// change index
						$file->index = $spaces[$index]->index;
						// adjust space
						$spaces[$index]->size -= $file->size;
						$spaces[$index]->index += $file->size;

						// merge spaces?

						// resort spaces
						$spaces = $this->sortSpaces($spaces);
					}
				}
			}

			$checksum = 0;

			foreach ($files as $file)
			{
				for ($index = $file->index; $index < $file->index + $file->size; $index++)
				{
					$checksum += $index * $file->name;
				}
			}

			return $checksum;
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			$this->output(implode("", $this->map));

			$this->decode();
			$this->fragment();

			$result->part1 = $this->checksum();
			$result->part2 = $this->betterPart2();

			return $result;
		}
	}
?>
