# author: Mihail Kotkov
# license: GPLv3
"""Module to calculate some math functions

factorial(n) -- function to calculate the production of 1*2*...*n for
non-negative numbers.

"""
import math


def factorial(n):
    """Function to calculate factorial

    >>> factorial(1)
    1
    >>> factorial(0)
    1
    >>> factorial(5)
    120
    >>> [factorial(n) for n in range(6)]
    [1, 1, 2, 6, 24, 120]
    >>> factorial(-1)
    Traceback (most recent call last):
        ...
    ValueError: n must be >= 0

    Factorial of floats are OK, but the float must be an exact integer:
    >>> factorial(30.1)
    Traceback (most recent call last):
        ...
    ValueError: n must be exact integer
    >>> factorial(5.0)
    120

    It must also not be ridiculously large:
    >>> factorial(1e100)
    Traceback (most recent call last):
        ...
    OverflowError: n too large

    """
    if not n >= 0:
        raise ValueError("n must be >= 0")
    if math.floor(n) != n:
        raise ValueError("n must be exact integer")
    if n+1 == n:
        raise OverflowError("n too large")
    result = 1
    factor = 2
    while factor <= n:
        result *= factor
        factor += 1
    return result


if __name__ == '__main__':
    import doctest
    doctest.testmod()
