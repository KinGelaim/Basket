using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace DataBase_v2._0
{
    public class User
    {
        public string ID { get; set; }
        public string Surname { get; set; }
        public string Name { get; set; }
        public string Patronymic { get; set; }
        public string Phone { get; set; }
        public string Home { get; set; }

        public User()
        {

        }

        public User(string ID, string surname, string name, string patronymic, string phone, string home)
            : this(surname, name, patronymic, phone, home)
        {
            this.ID = ID;
        }

        public User(string surname, string name, string patronymic, string phone, string home)
        {
            this.Surname = surname;
            this.Name = name;
            this.Patronymic = patronymic;
            this.Phone = phone;
            this.Home = home;
        }
    }
}
