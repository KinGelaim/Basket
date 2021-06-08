using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;


namespace LearningArrayAndClasses
{
    public sealed class MyList<T>
    {
        private T[] arr = null;

        public MyList() { }

        public void Add(T item)
        {
            if (arr == null)
            {
                arr = new T[1] { item };
                return;
            }

            T[] prArr = new T[arr.Length + 1];
            for(int i = 0; i < arr.Length; i++)
            {
                prArr[i] = arr[i];
            }
            prArr[prArr.Length - 1] = item;
            arr = prArr;
        }
    }
}
