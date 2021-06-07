using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.IO;
using System.Reflection;
using System.Runtime.InteropServices;
using Microsoft.Win32;

namespace MyMiniInstaller
{
    public partial class Form1 : Form
    {
        private string programmGUID = "2081f237-9479-4081-9831-3b574975dcf2";
        private string programmName = "MiniProgramm";

        private string directory = "";

        public Form1()
        {
            InitializeComponent();
        }
        
        //Выбрать директорию
        private void button1_Click(object sender, EventArgs e)
        {
            FolderBrowserDialog fbd = new FolderBrowserDialog();
            if(fbd.ShowDialog() == System.Windows.Forms.DialogResult.OK)
            {
                if (Directory.Exists(fbd.SelectedPath))
                {
                    directory = fbd.SelectedPath;
                }
            }
        }

        //Установить
        private void button2_Click(object sender, EventArgs e)
        {
            if (directory.Length > 0)
            {
                if (Directory.Exists(directory))
                {
                    try
                    {
                        byte[] array = Properties.Resources.MiniProgramm;
                        FileStream fs = new FileStream(directory + @"\MiniProgramm.exe", FileMode.Create);
                        fs.Write(array, 0, array.Length);
                        fs.Close();

                        array = Properties.Resources.MyMiniUnistaller;
                        fs = new FileStream(directory + @"\MyMiniUnistaller.exe", FileMode.Create);
                        fs.Write(array, 0, array.Length);
                        fs.Close();

                        //Сегодняшняя дата
                        DateTime DateNow = DateTime.Now;
                        string DateInstall = String.Format("{0:yyyyMMdd}", DateNow);

                        //Закидываем в реестр инфу
                        /*| Получаем GUID программы и передаем в переменную "IsSubKeyName" название будущего раздела реестра |*/
                        /*Assembly IsAssembly = Assembly.GetExecutingAssembly();
                        var IsAttribute = (GuidAttribute)IsAssembly.GetCustomAttributes(typeof(GuidAttribute), true)[0];
                        var IsGUID = IsAttribute.Value;
                        string IsSubKeyName = "{" + IsGUID + "}";*/

                        MessageBox.Show("Начинаем прописывать в реестре");

                        /*| Создаем по указанному пути раздел и создаем в нем нужные нам ключи |*/
                        /*RegistryKey IsRegKey = Registry.LocalMachine.CreateSubKey(@"Software\Microsoft\Windows\CurrentVersion\Uninstall" + '{' + programmGUID + '}');
                        IsRegKey.SetValue("DisplayName", programmName);
                        IsRegKey.SetValue("DisplayVersion", "1.0.0.0");
                        IsRegKey.SetValue("InstallDate", DateInstall);
                        IsRegKey.SetValue("InstallLocation", directory);
                        IsRegKey.SetValue("Publisher", "ComamyNoName");
                        IsRegKey.SetValue("UninstallString", directory + @"\MyMiniUnistaller.exe");*/

                        // Определяем ветку реестра, в которую будем вносить изменения
                        string registryLocation = @"Software\Microsoft\Windows\CurrentVersion\Uninstall";
                        // Открываем указанный подраздел в разделе реестра HKEY_LOCAL_MACHINE для записи
                        RegistryKey regKey = (Registry.LocalMachine).OpenSubKey(registryLocation, true);
                        // Создаём новый вложенный раздел с информацией по нашей программе
                        RegistryKey progKey = regKey.CreateSubKey(programmName);
                        // Отображаемое имя
                        progKey.SetValue("DisplayName", programmName, RegistryValueKind.String);
                        // Папка с файлами
                        progKey.SetValue("InstallLocation", directory, RegistryValueKind.ExpandString);
                        // Иконка
                        //progKey.SetValue("DisplayIcon", progIcon, RegistryValueKind.String);
                        // Строка удаления
                        progKey.SetValue("UninstallString", directory + @"\MyMiniUnistaller.exe", RegistryValueKind.ExpandString);
                        // Отображаемая версия
                        progKey.SetValue("DisplayVersion", "6.1.7600", RegistryValueKind.String);
                        // Издатель
                        progKey.SetValue("Publisher", "KinCorporation", RegistryValueKind.String);



                        MessageBox.Show("Прописали");
                    }
                    catch (Exception IsError)
                    {
                        MessageBox.Show(IsError.Message.ToString()); // Выводим сообщение
                    }
                    MessageBox.Show("Успешно установлено!");
                }
                else
                    MessageBox.Show("Не удалось найти директорию для установки!");
            }
            else
                MessageBox.Show("Выберите директорию для сохранения!");
        }
    }
}
