using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace ParentControlOfTheInternetOnALocalNetwork
{
    public class Computer
    {
        public enum States {
            offline,    //Отсутствует в сети
            online,     //В сети присутствует, но отсутствует ответ от проги
            access,     //Доступ разрешен
            block       //Доступ заблокирован
        }

        public string name;
        public string ip;
        //public int posX;
        //public int posY;
        public States state;

        public Computer()
        {
            state = States.offline;
        }
    }
}
