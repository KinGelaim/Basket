using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Net;                       //Общая сетевая библиотека
using System.Net.NetworkInformation;    //Позволяет оперировать портами и IP
using System.Net.Sockets;               //Подключение через UDP || TCP/IP

using System.Threading;

using Newtonsoft.Json;
using System.IO;

namespace ParentControlOfTheInternetOnALocalNetwork
{
    class Project
    {
        public string name;
        public Computer mainComputer;
        public List<Computer> computers;

        public Project(string name = "")
        {
            this.name = name;
            mainComputer = new Computer();
            computers = new List<Computer>();
        }

        #region СЕТЬ

        //Проверить все компьютеры на их статусы
        public void CheckAllComputers()
        {
            List<Computer> breakList = new List<Computer>();
            foreach (Computer comp in computers)
            {
                //Проверяем на ответ от нашей клиенской проги и формируем лист тех, кто не ответил
                Computer.States checkState = CheckClientComputer(comp.ip, Computer.States.offline);
                if (checkState != Computer.States.offline)
                {
                    comp.state = checkState;
                }
                else
                    breakList.Add(comp);
            }
            //Проверяем тех, кто не ответил
            foreach (Computer comp in breakList)
            {
                if (PingComputer(comp.ip))
                    comp.state = Computer.States.online;
                else
                    comp.state = Computer.States.offline;
            }
        }

        //Проверка на присутствие в сети
        private bool PingComputer(string IP)
        {
            try
            {
                Ping ping = new Ping();
                PingReply status = ping.Send(IP, 1000);
                if (status.Status == IPStatus.Success)
                    return true;
            }
            catch
            {

            }
            return false;
        }

        //Проверить на отклик от клиенской проги
        private Computer.States CheckClientComputer(string IP, Computer.States state)
        {
            string strState = "0";
            if (state == Computer.States.block)
                strState = "1";
            if (state == Computer.States.access)
                strState = "2";
            try
            {
                Console.WriteLine("Начинаем подключение");
                //Сокет для клиентов
                Socket clientSocket = new Socket(AddressFamily.InterNetwork, SocketType.Stream, ProtocolType.Tcp);

                //Подключаемся
                clientSocket.Connect(IP, 904);
                Console.WriteLine("Подключились");

                //Отправляем
                string message = "com " + strState + " end";
                byte[] buffer = Encoding.ASCII.GetBytes(message);
                clientSocket.Send(buffer);
                Console.WriteLine("Отправили: " + message);
                Thread.Sleep(100);

                //Получаем ответ
                byte[] buffer2 = new byte[1024];
                clientSocket.Receive(buffer2);
                Console.WriteLine("Получили ответ");

                //Отключаемся
                clientSocket.Close();
                Console.WriteLine("Закрыли соединение");

                //Обрабатываем ответ
                string answer = Encoding.ASCII.GetString(buffer2);
                answer = answer.Remove('\n').Trim();
                Console.WriteLine("Обрабатываем ответ: " + answer);
                string[] arrAnswer = answer.Split(' ');
                if (arrAnswer.Length > 1)
                {
                    if (arrAnswer[0] == "otv")
                    {
                        //Возвращаем состояние компьютера
                        if (arrAnswer[1] == "block")
                            return Computer.States.block;
                        else if (arrAnswer[1] == "access")
                            return Computer.States.access;
                    }
                }
            }
            catch
            {
                Console.WriteLine("Какая-то ошибка в подключении");
            }
            return Computer.States.offline;
        }

        //Отправка данных на компьютер (включить, отключить доступ в интернет)
        public bool SendOnComputer(string IP, Computer.States state)
        {
            foreach (Computer comp in computers)
            {
                if (comp.ip == IP)
                {
                    //Проверяем возможность отправки
                    if (comp.state == Computer.States.offline || comp.state == Computer.States.online)
                        return false;

                    //Отправляем запрос в нашу прогу и в зависимости от результата запроса меняем положение state
                    comp.state = CheckClientComputer(comp.ip, state);
                }
            }
            return true;
        }

        //Отправка данных на все компьютеры
        public bool SendOnAllComputer(Computer.States state)
        {
            foreach (Computer comp in computers)
            {
                comp.state = CheckClientComputer(comp.ip, state);
            }
            return true;
        }

        #endregion

        #region РАБОТА С ПРОЕКТАМИ

        public bool Save(string path)
        {
            try
            {
                //Сериализация проекта и сохранение в файл
                File.WriteAllText(Properties.Settings.Default.directoryPathProject + "name.parentproject", JsonConvert.SerializeObject(this));

                return true;
            }
            catch
            {

            }
            return false;
        }

        public Project Load(string path)
        {
            try
            {
                Project project = new Project();
                string loadText = File.ReadAllText(path);
                project = JsonConvert.DeserializeObject<Project>(loadText);
                return project;
            }
            catch
            {

            }
            return null;
        }

        #endregion
    }
}
