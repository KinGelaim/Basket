using System;
using System.Collections.Generic;
using System.Linq;
using System.Reflection;
using System.Text;
using System.IO;
using System.Runtime.Serialization.Formatters.Binary;

namespace InfiniteMilitaryService_v1._0
{
    static class SaveAndLoad
    {
        private static string pathToDirectory = Environment.GetFolderPath(Environment.SpecialFolder.Personal);

        //Сохранение
        //Сохраняем в Мои Документы\Games\Название игры\save.gims
        public static bool Save(Tree tree)
        {
            int k = 0;
            while (k++ < 10)
            {
                try
                {
                    if (Directory.Exists(pathToDirectory + "\\Games"))
                    {
                        if (Directory.Exists(pathToDirectory + "\\Games\\" + AssemblyTitle))
                        {
                            BinaryFormatter binFormatter = new BinaryFormatter();
                            using (FileStream sw = new FileStream(pathToDirectory + "\\Games\\" + AssemblyTitle + "\\save.gims", FileMode.OpenOrCreate))
                            {
                                binFormatter.Serialize(sw, tree);
                            }
                            break;
                        }
                        else
                            Directory.CreateDirectory(pathToDirectory + "\\Games\\" + AssemblyTitle);
                    }
                    else
                        Directory.CreateDirectory(pathToDirectory + "\\Games");
                }
                catch
                {
                    
                }
            }
            return true;
        }

        public static Tree Load()
        {
            Tree tree = new Tree();
            try
            {
                if (File.Exists(pathToDirectory + "\\Games\\" + AssemblyTitle + "\\save.gims"))
                {
                    BinaryFormatter binFormatter = new BinaryFormatter();
                    using (FileStream sr = new FileStream(pathToDirectory + "\\Games\\" + AssemblyTitle + "\\save.gims", FileMode.Open))
                    {
                        object treeObject = binFormatter.Deserialize(sr);
                        tree = treeObject as Tree;
                    }
                    return tree;
                }
            }
            catch
            {

            }
            return null;
        }

        public static string AssemblyTitle
        {
            get
            {
                object[] attributes = Assembly.GetExecutingAssembly().GetCustomAttributes(typeof(AssemblyTitleAttribute), false);
                if (attributes.Length > 0)
                {
                    AssemblyTitleAttribute titleAttribute = (AssemblyTitleAttribute)attributes[0];
                    if (titleAttribute.Title != "")
                    {
                        return titleAttribute.Title;
                    }
                }
                return System.IO.Path.GetFileNameWithoutExtension(Assembly.GetExecutingAssembly().CodeBase);
            }
        }
    }
}
