using System;
using Microsoft.VisualStudio.TestTools.UnitTesting;

namespace MainModule.Test
{
    [TestClass()]
    public class StringUtilTests
    {
        public TestContext TestContext { get; set; }

        [DataSource("Microsoft.VisualStudio.TestTools.DataSource.XML",
            "data.xml",
            "info",
            DataAccessMethod.Sequential)]
        [TestMethod()]
        public void GetSumNumberTest()
        {
            string text = TestContext.DataRow["data"].ToString();
            int expected = int.Parse(TestContext.DataRow["expected"].ToString());

            int actual = StringUtil.GetSumNumber(text);

            Assert.AreEqual(expected, actual);
        }
    }
}
