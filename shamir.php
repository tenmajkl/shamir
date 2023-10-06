<?php

/**
 * This is actually unnesessary but it is cool init?
 */
function buildPolynom(array $coeficients): callable
{
    $f = fn($x) => 0;
    foreach ($coeficients as $index => $coeficient) {
        $f = fn($x) => $f($x) + $coeficient * ($x**$index);
    }

    return $f;
}

function encrypt(int $input, int $n, int $k): array
{
    if ($k > $n) {
        throw new Exception('unable to erncrypt, k must be smaller than n');
    }

    $random = $k - 1;

    $coeficients = [$input, ...array_map(fn($_) => rand(1, 999), array_fill(0, $random, 0))];

    $f = buildPolynom($coeficients);

    $result = [];

    for ($i = 1; $i <= $n; $i++) {
        $result[] = [$i, $f($i)];
    }

    return $result;
}

function decrypt(array $inputs): int 
{
    $xs = array_map(fn($x) => $x[0], $inputs);
    $result = 0;
    foreach ($inputs as $j => [$_, $y]) {
        $mult = 1;
        foreach ($inputs as $m => $_) {
            if ($m !== $j) {
                $mult *= $xs[$m] / ($xs[$m] - $xs[$j]); 
            }
        }
        $result += $y * $mult;
    }

    return $result;
}



$a = encrypt(1234, 6, 3);
print_r(decrypt([$a[3], $a[2]]));
