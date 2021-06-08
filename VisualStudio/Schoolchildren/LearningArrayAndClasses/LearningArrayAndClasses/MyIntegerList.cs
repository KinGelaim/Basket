using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace LearningArrayAndClasses
{
    public sealed class MyIntegerList
    {
        private int[] arr = null;

        public MyIntegerList() { }

        public void Add(int item)
        {
            if (arr == null)
            {
                arr = new int[1] { item };
                return;
            }

            int[] prArr = new int[arr.Length + 1];
            for(int i = 0; i < arr.Length; i++)
            {
                prArr[i] = arr[i];
            }
            prArr[prArr.Length - 1] = item;
            arr = prArr;
        }
    }
}
