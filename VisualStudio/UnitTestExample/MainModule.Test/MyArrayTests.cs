using System;
using Microsoft.VisualStudio.TestTools.UnitTesting;

namespace MainModule.Test
{
    [TestClass]
    public class MyArrayTests
    {
        MyArray arr;

        [TestInitialize]
        public void Setup()
        {
            int[] a = { 4, 8, 3, -1 };
            arr = new MyArray(a);
        }

        [TestCleanup]
        public void Clean()
        {

        }


        [TestMethod]
        public void FindMaxTest()
        {
            //Assert.Fail();
            int expected = 8;

            int actual = arr.FindMax();

            Assert.AreEqual(expected, actual);
        }
    }
}
