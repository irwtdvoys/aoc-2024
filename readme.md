# Advent of Code 2024

https://adventofcode.com/2024/

| Day | Name                  |        Part 1 |          Part 2 | Time      | Memory      |
|----:|:----------------------|--------------:|----------------:|:----------|:------------|
|   1 | Historian Hysteria    |       2904518 |        18650129 | 2.2614ms  | 1.2862 MiB  |
|   2 | Red Nosed Reports     |           257 |             328 | 3.6076ms  | 1.4555 MiB  |
|   3 | Mull It Over          |     166630675 |        93465710 | 648.22μs  | 1.2483 MiB  |
|   4 | Ceres Search          |          2517 |            1960 | 21.8478ms | 2.8809 MiB  |
|   5 | Print Queue           |          4281 |            5466 | 21.8502s  | 1.6292 MiB  |
|   6 | Guard Gallivant       |          4789 |            1304 | 4.7323s   | 6.4104 MiB  |
|   7 | Bridge Repair         | 3312271365652 | 509463489296712 | 30.1085ms | 3.0521 MiB  |
|   8 | Resonant Collinearity |           285 |             944 | 34.6012ms | 1.2077 MiB  |
|   9 | Disk Fragmenter       | 6398252054886 |   6415666220005 | 61.1378s  | 14.6985 MiB |
|  10 | Hoof It               |           548 |            1252 | 9.545ms   | 1.2013 MiB  |

## Notes

##### Day 01

Nice and simple, didn't bother to implement negative numbers for the difference as the data was all positive.

##### Day 02

Reused difference code from day 1, caught the double number start issue before it happened ;)

##### Day 03

REGEX!

##### Day 04

Just ran a quick search for the start and middle of the x characters, just had to be careful to avoid overflowing the edge.

##### Day 05

Tried a very naive bruteforce before attempting a tree based on the rules. In the end used simpler trees after filtering for relevant rules and then running a DFS.

##### Day 06

Kept track of all directions taken through a space in a hash map to detect loops. Ran for each visited space from part 1 and counted loops detected. Further optimised to not recalculate the start of the path each time.

##### Day 07

Tough one. Struggled with issues on non-test data so rewrote working in reverse.

##### Day 08

Nice and straight forward. Part two just needed a loop.

##### Day 09

Struggled with part 2 today. Tried a Doubly linked List which worked on the test data but was very slow. Changed tactic to a list of files and spaces. It works but still takes almost a minute...  

##### Day 10

Recursive Depth First Search. Returned a list of end points reached with each path. Counted unique for part 1 and all of them for part 2.  
