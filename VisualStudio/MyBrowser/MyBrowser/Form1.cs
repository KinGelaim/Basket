using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using WIA;
using System.IO;

namespace MyBrowser
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
        }

        private void downloadToolStripMenuItem_Click(object sender, EventArgs e)
        {
            //Тут взаимодействие со сканом
            /*
             * Дейстововать будем так:
             * Подключаемся к сану, запускаем его и сканируем изображения
             * Либо сразу же сохраняем в какой-нибудь битмап или временный файл на компе (потом обязательно стираем его)
             * Затем грузим данный файл на сервер 
             * 
             */
            Console.WriteLine("Приступаем к загрузке изображения!");
            var deviceManager = new DeviceManager();
            DeviceInfo firstScannerAvailable = null;
            Console.WriteLine("Поиск сканера");
            for (int i = 1; i <= deviceManager.DeviceInfos.Count; i++)
            {
                if (deviceManager.DeviceInfos[i].Type != WiaDeviceType.ScannerDeviceType)
                    continue;
                Console.WriteLine(deviceManager.DeviceInfos[i].Properties["Name"].get_Value());
                Console.WriteLine(deviceManager.DeviceInfos[i].Properties["Description"].get_Value());
                Console.WriteLine(deviceManager.DeviceInfos[i].Properties["Port"].get_Value());
                firstScannerAvailable = deviceManager.DeviceInfos[i];
                break;
            }
            if (firstScannerAvailable != null)
            {
                Console.WriteLine("Сканер найден!");
                var device = firstScannerAvailable.Connect();
                var scannerItem = device.Items[1];
                var imageFile = (ImageFile)scannerItem.Transfer(FormatID.wiaFormatJPEG);
                var path = "scan.jpeg";
                if (File.Exists(path))
                    File.Delete(path);
                imageFile.SaveFile(path);
                Console.WriteLine("Изображение сохранено: " + path);
            }
            else
                Console.WriteLine("Сканер не найден!");
        }

        private void exitToolStripMenuItem_Click(object sender, EventArgs e)
        {
            Application.Exit();
        }
    }
}