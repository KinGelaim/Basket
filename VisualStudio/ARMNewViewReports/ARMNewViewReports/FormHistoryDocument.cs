using System.Collections.Generic;
using System.IO;
using System.Windows.Forms;
using System.Linq;


namespace ARMNewViewReports
{
    public partial class FormHistoryDocument : Form
    {
        List<string> files = new List<string>();
        string[] fullPath;
        string[] result;

        public FormHistoryDocument()
        {
            InitializeComponent();

            string[] files = Directory.GetFiles(Directory.GetCurrentDirectory() + "/reports");
            string[] sortedFiles = files.OrderByDescending(f => File.GetCreationTime(f)).ToArray<string>();
            fullPath = sortedFiles.Take(27).ToArray();
            result = new string[fullPath.Length];
            for (int i = 0; i < fullPath.Length; i++)
            {
                string[] prArr = fullPath[i].Split('\\');
                result[i] = prArr[prArr.Length - 1];
            }

            listBox1.Items.Clear();
            listBox1.Items.AddRange(result);
        }

        private void listBox1_MouseDoubleClick(object sender, MouseEventArgs e)
        {
            if(listBox1.SelectedIndex >= 0)
            {
                if(File.Exists(fullPath[listBox1.SelectedIndex]))
                {
                    System.Diagnostics.Process.Start(fullPath[listBox1.SelectedIndex]);
                }
            }
        }
    }
}
