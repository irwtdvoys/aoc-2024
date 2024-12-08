<?php

	namespace App\BridgeRepair;

	class Equation
	{
		public int $total;
		public array $values;

		public function __construct(string $data)
		{
			[$total, $values] = explode(": ", $data);

			$this->total = (int)$total;
			$this->values = array_map("intval", explode(" ", $values));
		}

		public function __toString(): string
		{
			return $this->total . ": " . implode(" ", $this->values);
		}

		public function evaluate(array $operators): bool
		{
			$search = function (int $acc, int $index) use ($operators, &$search): bool {
				if ($index === 0) {
					return $acc === $this->values[0];
				}

				$number = $this->values[$index];
				$validOps = array_filter($operators, function ($operator) use ($acc, $number): bool {
					return (
						!($operator === Operator::Add && $acc < $number) &&
						!($operator === Operator::Multiply && ($number === 0 || $acc % $number != 0)) &&
						!($operator === Operator::Concat && ($acc === $number || !str_ends_with((string)$acc, (string)$number)))
					);
				});

				foreach ($validOps as $validOp) {
					$result = match ($validOp) {
						Operator::Add => $acc - $number,
						Operator::Multiply => $acc / $number,
						Operator::Concat => substr((string)$acc, 0, -strlen((string)$number)),
					};

					if ($search((int)$result, $index - 1)) {
						return true;
					}
				}

				return false;
			};

			return $search($this->total, count($this->values) - 1);
		}
	}
?>
