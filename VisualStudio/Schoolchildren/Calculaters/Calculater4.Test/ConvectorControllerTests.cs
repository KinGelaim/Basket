using System;
using Microsoft.VisualStudio.TestTools.UnitTesting;
using System.Collections.Generic;


namespace Calculater4.Test
{
    [TestClass]
    public class ConvectorControllerTests
    {
        private Dictionary<string, int> testData = new Dictionary<string, int>
        {
            {"I",1},
            {"II",2},
            {"III",3},
            {"IV",4},
            {"V",5},
            {"VI",6},
            {"VII",7},
            {"VIII",8},
            {"IX",9},
            {"X",10},
            {"XI",11},
            {"XII",12},
            {"XIII",13},
            {"XIV",14},
            {"XV",15},
            {"XVI",16},
            {"XVII",17},
            {"XVIII",18},
            {"XIX",19},
            {"XX",20},
            {"XLI",41},
            {"LXIX",69},
            {"LXXIII",73},
            {"LXXXIV",84},
            {"CCIX",209},
            {"CCCLXVIII",368},
            {"DCLII",652},
            {"DCCXXXIX",739},
            {"DCCCXXXVII",837},
            {"DCCCXCVIII",898},
            {"CMXVIII",918},
            {"CMXXIX",929},
            {"CMLXXI",971},
            {"CMXCIX",999},
            {"M",1000}
        };

        [TestMethod]
        public void ArabToRomanTests()
        {
            foreach (KeyValuePair<string, int> keyValue in testData)
            {
                int a = keyValue.Value;
                string r = ConvectorController.ArabToRoman(a);
                Assert.AreEqual(keyValue.Key, r);
            }
        }

        [TestMethod]
        public void RomanToArabTests()
        {
            foreach (KeyValuePair<string, int> keyValue in testData)
            {
                string r = keyValue.Key;
                int a = ConvectorController.RomanToArab(r);
                Assert.AreEqual(keyValue.Value, a);
            }
        }

        [TestMethod]
        public void IntegrationTests()
        {
            for (int i = 1; i <= 10000; i++)
            {
                string r = ConvectorController.ArabToRoman(i);
                int a = ConvectorController.RomanToArab(r);
                Assert.AreEqual(i, a);
            }
        }
    }
}
