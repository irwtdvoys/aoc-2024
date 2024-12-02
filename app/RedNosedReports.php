<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use App\RedNosedReports\Report;

	class RedNosedReports extends Helper
	{
		/** @var int[][] */
		private array $reports;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$this->reports = array_map(
				function ($report)
				{
					return new Report(explode(" ", $report));
				},
				explode(PHP_EOL, $raw)
			);
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			foreach ($this->reports as $report)
			{
				if ($this->verbose)
				{
					$report->output();
				}

				if ($report->isSafe())
				{
					$result->part1++;
					$result->part2++;
				}
				else
				{
					if ($report->checkClose())
					{
						$result->part2++;
					}
				}
			}

			return $result;
		}
	}
?>
