namespace Calculater5
{
    public static class Extension
    {
        public static T[] Add<T>(this T[] arr, T k)
        {
            T[] newArr = new T[arr.Length + 1];
            for (int i = 0; i < arr.Length; i++)
            {
                newArr[i] = arr[i];
            }
            newArr[newArr.Length - 1] = k;
            return newArr;
        }
    }
}
