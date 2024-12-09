<?php
	declare(strict_types=1);

	namespace App;

	use AoC\Helper;
	use AoC\Result;

	class DiskFragmenter extends Helper
	{
		public function __construct(int $day, bool $verbose = false, string $override = null)
		{
			parent::__construct($day, $verbose);

			$raw = parent::load($override);

			dump($raw);
		}

		public function run(): Result
		{
			$result = new Result(0, 0);

			return $result;
		}
	}
?>
