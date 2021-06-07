using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Interface
{
    class TestClass : IWindow, IRestaurant
    {
        public string GetMenu()
        {
            return "Собственный метод GetMenu";
        }

        string IWindow.GetMenu()
        {
            return "GetMenu интерфейса IWindow";
        }

        string IRestaurant.GetMenu()
        {
            return "GetMenu интерфейса IRestaurant";
        }
    }
}
