using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace ARM
{
    public partial class Form5 : Form
    {
        int searchType = -1;
        List<ClassVidIsp> vidIspList = new List<ClassVidIsp>();
        int resultId = -1;
        public Form5(List<ClassVidIsp> vidIspList)
        {
            InitializeComponent();
            ClassSearchStatic.exit = true;
            this.vidIspList = vidIspList;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            button1.Visible = false;
            searchType = 1;
            ClassSearchStatic.searchtype = "кодом";
            textBox1.Visible = true;
            button4.Visible = true;
        }

        private void button4_Click(object sender, EventArgs e)
        {
            if (searchType == 1)
            {
                int pos = 0;
                foreach (ClassVidIsp el in vidIspList)
                {
                    if (el.code == textBox1.Text)
                    {
                        resultId = el.id;
                        ClassSearchStatic.position = pos;
                        break;
                    }
                    pos++;
                }
            }
            ClassSearchStatic.id = resultId;
            ClassSearchStatic.exit = false;
            this.Close();
        }
    }
}
