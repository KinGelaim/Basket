using System;
using System.Security.AccessControl;
using System.Windows.Forms;

namespace MiniGuard
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
        }

        // Изменить атрибуты
        private void button1_Click(object sender, EventArgs e)
        {
            // Скрытый (файл скрыт, можно отобразить через настройки директорий, можно настроить в админке)
            System.IO.File.SetAttributes(@"D:\test\test1.txt", System.IO.FileAttributes.Hidden);
            // Только для чтения (нельзя изменять)
            System.IO.File.SetAttributes(@"D:\test\test2.txt", System.IO.FileAttributes.ReadOnly);
            // Системный (может удалить только админ, изменять - хз)
            System.IO.File.SetAttributes(@"D:\test\test3.txt", System.IO.FileAttributes.System);
            MessageBox.Show("Файлы успешно обновлены!");

            // Можно накидывать на директорию
            System.IO.File.SetAttributes(@"D:\test", System.IO.FileAttributes.Hidden);
            MessageBox.Show("Директория успешно обновлена!");
        }

        // Права доступа
        private void button2_Click(object sender, EventArgs e)
        {
            /*FileSystemAccessRule myRule = new FileSystemAccessRule(new System.Security.Principal.NTAccount(@"BUILTIN\Пользователи"), FileSystemRights.Read, AccessControlType.Deny);
            DirectorySecurity dirSec = new DirectorySecurity();
            dirSec.AddAccessRule(myRule);
            System.IO.Directory.SetAccessControl(@"D:\test2", dirSec);*/

            /*PermissionSet myPerms = new PermissionSet(PermissionState.None);
            myPerms.AddPermission(new FileIOPermission(FileIOPermissionAccess.NoAccess, @"D:\test2"));
            myPerms.Demand();*/

            // Если директория открыта в данный момент, то в ней можно продолжать работу
            // Но зайти нельзя
            // Через проводник даже админ не имеет доступа, а вот через терминал - изи
            try
            {
                string folderPath = @"D:\test3";
                string userName = Environment.UserName;
                DirectorySecurity ds = System.IO.Directory.GetAccessControl(folderPath);
                FileSystemAccessRule fsa = new FileSystemAccessRule(userName, FileSystemRights.FullControl, AccessControlType.Deny);
                ds.AddAccessRule(fsa);
                System.IO.Directory.SetAccessControl(folderPath, ds);
                MessageBox.Show("Заблокирован!");
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message);  
            }

            MessageBox.Show("Права изменены!");
        }

        // Разблокировка
        private void button3_Click(object sender, EventArgs e)
        {
            /*FileSystemAccessRule myRule = new FileSystemAccessRule(new System.Security.Principal.NTAccount(@"BUILTIN\Пользователи"), FileSystemRights.Read, AccessControlType.Allow);
            DirectorySecurity dirSec = new DirectorySecurity();
            dirSec.AddAccessRule(myRule);
            System.IO.Directory.SetAccessControl(@"D:\test2", dirSec);*/

            try
            {
                string folderPath = @"D:\test3";
                string userName = Environment.UserName;
                DirectorySecurity ds = System.IO.Directory.GetAccessControl(folderPath);
                FileSystemAccessRule fsa = new FileSystemAccessRule(userName, FileSystemRights.FullControl, AccessControlType.Deny);   
                ds.RemoveAccessRule(fsa);
                System.IO.Directory.SetAccessControl(folderPath, ds);
                MessageBox.Show("Разблокирован!");
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message);
            } 

            MessageBox.Show("Права изменены!");
        }


        #region Проводник

        // Закрыть проводники (или заблочить права на проводники)
        private void button4_Click(object sender, EventArgs e)
        {
            try
            {
                foreach (System.Diagnostics.Process pr in System.Diagnostics.Process.GetProcesses())
                {
                    if(pr.ProcessName == "explorer")    // Проверять на всякие тотал командеры и т.д. и НЕ ОФАТЬ главный проводник!!!!!!!!
                    {
                        pr.Kill();
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message);
            }
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            TreeNode nodes = GetStructureDir(@"D:\test3");
            treeView1.Nodes.Add(nodes);
        }

        // Получение структуры
        private TreeNode GetStructureDir(string path)
        {
            // Создаём родительский узел
            TreeNode parentNode = new TreeNode(path);

            // Получаем список директорий
            string[] directories = System.IO.Directory.GetDirectories(path);
            for (int i = 0; i < directories.Length; i++)
            {
                // Рекурсионно получаем все директории и файлы
                parentNode.Nodes.Add(GetStructureDir(directories[i]));
            }

            // Получаем список файлов
            string[] files = System.IO.Directory.GetFiles(path);
            for (int i = 0; i < files.Length; i++)
            {
                parentNode.Nodes.Add(System.IO.Path.GetFileName(files[i]));
            }

            return parentNode;
        }

        #endregion
    }
}