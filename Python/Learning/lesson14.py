# -*- coding: utf-8 -*-

# Тестирование (Quality Control)
# - проверка соответствия между реальным поведением программы
#   и её ожиданием поведением
#   на конечном наборе тестов (test case),
#   выбранном определенном образом

# Аспекты качества:
# 1) Функциональность
# 2) Надёжность (Exception)
# 3) Практичность
# 4) Эффективность
# 5) Сопровождаемость
# 6) Переносимость

# Масштабы тестирования:
# 1) Модульное тестирование (unit testing)
# 2) Интеграционное тестирование
# 3) Системное тестирование

# Вариант 1
def sort_algorithm(a):
    for i in range(len(a)):
        for j in range(len(a)):
            if a[i] > a[j]:
                tmp = a[j]
                a[j] = a[i]
                a[i] = tmp

def test_sort():
    print('Test #1')
    print('testcase #1: ', end='')
    A = [4, 2, 5, 1, 3]
    A_sorted = [1, 2, 3, 4, 5]
    sort_algorithm(A)
    passed = A == A_sorted
    print('Ok' if passed else 'Fail')

    print('testcase #2: ', end='')
    A = []
    A_sorted = []
    sort_algorithm(A)
    passed = A == A_sorted
    print('Ok' if passed else 'Fail')

    print('testcase #3: ', end='')
    A = [1, 2, 3, 4, 5]
    A_sorted = [1, 2, 3, 4, 5]
    sort_algorithm(A)
    passed = A == A_sorted
    print('Ok' if passed else 'Fail')

test_sort()

# Вариант 2
from random import shuffle


def test_sort_2():
    print('Test #2')
    print('Test sorting algorithm:')
    passed = True

    passed &= test_sort_works_in_simple_cases()
    passed &= test_sort_algorithm_stable()
    passed &= test_sort_algorithm_is_universal()
    passed &= test_sort_algorithm_scalability(1000)

    print('Summary:', 'Ok' if passed else 'Fail')


def test_sort_works_in_simple_cases():
    print('- sort algorithm works in simple cases:', end=' ')
    passed = True

    for A1 in ([1], [], [1, 2], [1, 2, 3, 4, 5],
               [4, 2, 5, 1, 3], [5, 4, 4, 5, 5],
               list(range(20)), list(range(20, 1, -1))):
        A2 = sorted(list(A1))
        sort_algorithm(A1)
        passed &= all(x == y for x, y in zip(A1, A2))

    print('Ok' if passed else 'Fail')
    return passed


def test_sort_algorithm_stable():
    print('- sort algorithm is stable:', end=' ')
    passed = True

    for A1 in ([[100] for i in range(5)],
               [[1, 2], [1, 2], [2, 2], [2, 2], [2, 3], [2, 3]],
               [[5, 2] for i in range(30)] + [[10, 5] for i in range(30)]):
        shuffle(A1)
        A2 = sorted(list(A1))
        sort_algorithm(A1)
        # Не переставил ли мой алгоритм два одинаковых элемента местами
        passed &= all(x is y for x, y in zip(A1, A2))

    print('Ok' if passed else 'Fail')
    return passed


def test_sort_algorithm_is_universal():
    print('- sort algorithm is universal:', end=' ')
    passed = True

    # testing types: str, float, list
    for A1 in (list('abcdef'),
               [float(i)**0.5 for i in range(10)],
               [[1, 2], [2, 3], [3, 4], [3, 4, 5], [6, 7]]):
        shuffle(A1)
        A2 = sorted(list(A1))
        sort_algorithm(A1)
        passed &= all(x == y for x, y in zip(A1, A2))

    print('Ok' if passed else 'Fail')
    return passed


def test_sort_algorithm_scalability(max_scale=100):
    print('- sort algorithm on scale={0}:'.format(max_scale), end=' ')
    passed = True

    for A1 in (list(range(max_scale)),
               list(range(max_scale//2, max_scale)) + list(range(max_scale//2)),
               list(range(max_scale, 0, -1))):
        shuffle(A1)
        A2 = sorted(list(A1))
        sort_algorithm(A1)
        passed &= all(x == y for x, y in zip(A1, A2))
    
    print('Ok' if passed else 'Fail')
    return passed


test_sort_2()


# Вариант 3
import unittest


def is_not_in_descending_order(a):
    for i in range(len(a)-1):
        if a[i] > a[i+1]:
            return False
    return True


class TestSort(unittest.TestCase):
    def test_simple_cases(self):
        cases = ([1], [], [1, 2], [1, 2, 3, 4, 5],
                 [4, 2, 5, 1, 3], [5, 4, 4, 5, 5],
                 list(range(10)), list(range(10, 0, -1)))
        for b in cases:
            a = list(b)
            sort_algorithm(a)
            self.assertCountEqual(a, b)
            self.assertTrue(is_not_in_descending_order(a))

    def test_simple_cases_sub(self):
        cases = ([1], [], [1, 2], [1, 2, 3, 4, 5],
                 [4, 2, 5, 1, 3], [5, 4, 4, 5, 5],
                 list(range(10)), list(range(10, 0, -1)))
        for b in cases:
            with self.subTest(case=b):
                a = list(b)
                sort_algorithm(a)
                self.assertCountEqual(a, b)
                self.assertTrue(is_not_in_descending_order(a))


print('Test #3')
unittest.main()
