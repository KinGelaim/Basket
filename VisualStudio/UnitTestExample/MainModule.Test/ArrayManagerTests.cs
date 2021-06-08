using System;
using Microsoft.VisualStudio.TestTools.UnitTesting;


namespace MainModule.Test
{
    [TestClass]
    public class ArrayManagerTests
    {
        //Методы называть: Какой метод тестируем_Что туда передаём_Что ожидаем
        #region FindMax

        [TestMethod]
        public void FindMax_PositiveElements_Test()
        {
            int[] arr = { 3, 8, 11, 50, 4 };
            
            int expected = 50;

            int actual = ArrayManager.FindMax(arr);

            Assert.AreEqual(expected, actual);
        }

        [TestMethod]
        public void FindMax_NegativeElements_Test()
        {
            int[] arr = { -3, -8, -11, -50, -4 };

            int expected = -3;

            int actual = ArrayManager.FindMax(arr);

            Assert.AreEqual(expected, actual);
        }

        [TestMethod]
        [ExpectedException(typeof(NullReferenceException))]
        public void FindMax_NullArray_ExpectedException()
        {
            int[] arr = null;

            int actual = ArrayManager.FindMax(arr);
        }

        [TestMethod]
        [ExpectedException(typeof(Exception))]
        public void FindMax_EmptyArray_ExpectedException()
        {
            int[] arr = { };

            int actual = ArrayManager.FindMax(arr);
        }

        #endregion

        #region FindAverage

        [TestMethod]
        public void FindAverage_Test()
        {
            int[] arr = { 2, 5, 3 };

            double expected = 3.33;

            double actual = ArrayManager.FindAverage(arr);

            //Assert.AreEqual(expected, actual);  //Будет провальный т.к. 3.333333333335
            Assert.AreEqual(expected, actual, 0.01);
        }

        #endregion
    }
}
