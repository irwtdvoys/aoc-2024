<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;
	use App\BridgeRepair\Equation;
	use App\BridgeRepair\Operator;

	class BridgeRepair extends Helper
	{
		private array $equations;

		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			$this->equations = array_map(
				function ($element)
				{
					return new Equation($element);
				},
				explode(PHP_EOL, $raw)
			);
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			foreach ($this->equations as $equation) {
				if ($equation->evaluate([Operator::Add, Operator::Multiply])) {
					$result->part1 += $equation->total;
				}


				if ($equation->evaluate([Operator::Add, Operator::Multiply, Operator::Concat])) {
					$result->part2 += $equation->total;
				}
			}


			return $result;
		}
	}
?>
