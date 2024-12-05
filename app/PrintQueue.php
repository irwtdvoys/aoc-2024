<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use App\PrintQueue\Node;
	use Exception;

	class PrintQueue extends Helper
	{
		/** @var int[][] */
		private array $rules;
		/** @var int[][] */
		private array $updates;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			[$rules, $updates] = explode(PHP_EOL . PHP_EOL, $raw);

			$this->rules = array_map(
				function ($rule)
				{
					return array_map("intval", explode("|", $rule));
				},
				explode(PHP_EOL, $rules)
			);

			$this->updates = array_map(
				function ($update)
				{
					return array_map("intval", explode(",", $update));
				},
				explode(PHP_EOL, $updates)
			);
		}

		private function score(array $update): int
		{
			return $update[floor(count($update) / 2)];
		}

		private function check(array $update): bool
		{
			for ($index = 0; $index < count($update); $index++)
			{
				$page = $update[$index];
				$this->output("Check [" . $index . "]: " . $page);

				$rules = $this->filterRules($this->rules, $page);

				foreach ($rules as $rule)
				{
					$this->output(implode("|", $rule));

					if ($rule[0] === $page)
					{
						$this->output("Check following");

						$foundIndex = array_search($rule[1], $update);

						if ($foundIndex !== false && $foundIndex < $index)
						{
							$this->output("Invalid: " . $rule[1] . " should be after " . $page);
							return false;
						}
					}
					else
					{
						$this->output("Check preceeding");

						$foundIndex = array_search($rule[0], $update);

						if ($foundIndex !== false && $foundIndex > $index)
						{
							$this->output("Invalid: " . $rule[0] . "($foundIndex) should be before " . $page . "($index)");
							return false;
						}
					}
				}
			}

			return true;
		}

		private function relevantRules(array $list): array
		{
			return array_filter(
				$this->rules,
				function ($rule) use ($list)
				{
					return count(array_intersect($rule, $list)) === 2;
				}
			);
		}

		private function filterRules(array $rules, int $value, string $type = "both"): array
		{
			return array_values(
				array_filter(
					$rules,
					function ($rule) use ($value, $type)
					{
						return match ($type)
						{
							"first" => $rule[0] === $value,
							"last" => $rule[1] === $value,
							default => in_array($value, $rule),
						};
					}
				)
			);
		}

		private function calculateTerminator(array $rules, array $list, int $type): int
		{
			$possible = array_diff(
				$list,
				array_values(
					array_unique(
						array_map(
							function ($rule) use ($type)
							{
								return $rule[$type];
							},
							$rules
						)
					)
				)
			);

			if (count($possible) > 1)
			{
				throw new Exception("Two possible terminators found (" . $type . ")");
			}

			return array_pop($possible);
		}

		private function walk(Node $node, $target, $length): array
		{
			$stack[] = [$node, [$node->value]];

			while (!empty($stack))
			{
				[$current, $currentPath] = array_pop($stack);

				$this->output(implode("->", $currentPath), true, true);

				// check result
				if ($current->value === $target && $length === count($currentPath))
				{
					return $currentPath;
				}
				else
				{
					if (!$current->isEmpty())
					{
						// add children
						foreach ($current->children as $next)
						{
							$stack[] = [$next, [...$currentPath, $next->value]];
						}
					}
				}
			}
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			foreach ($this->updates as $update)
			{
				$this->output("Update: " . implode(",", $update));

				$correct = $this->check($update);

				if ($correct === true)
				{
					$this->output("Score: " . $this->score($update));
					$result->part1 += $this->score($update);
				}
				else
				{
					$tree = [];

					foreach ($update as $page)
					{
						$tree[$page] = new Node($page);
					}

					$rules = $this->relevantRules($update);

					foreach ($update as $page)
					{
						$filtered = $this->filterRules($rules, $page, "first");

						foreach ($filtered as $next)
						{
							$tree[$page]->children[] = $tree[$next[1]];
						}
					}

					$target = count($update);

					// workout possible first / last
					$start = $this->calculateTerminator($rules, $update, 1);
					$end = $this->calculateTerminator($rules, $update, 0);

					$corrected = $this->walk($tree[$start], $end, $target);

					$this->output("Corrected: " . implode(",", $corrected));
					$this->output("Score: " . $this->score($corrected));

					$result->part2 += $this->score($corrected);
				}
			}

			return $result;
		}
	}
?>
