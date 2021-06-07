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
    public partial class Form7 : Form
    {
        int searchType = -1;
        List<ClassMC> mcList = new List<ClassMC>();
        int resultId = -1;
        public Form7(List<ClassMC> mcList)
        {
            InitializeComponent();
            ClassSearchStatic.exit = true;
            this.mcList = mcList;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            button1.Visible = false;
            button2.Visible = false;
            searchType = 1;
            ClassSearchStatic.searchtype = "3-х значным кодом";
            textBox1.Visible = true;
            button4.Visible = true;
        }

        private void button2_Click(object sender, EventArgs e)
        {
            button1.Visible = false;
            button2.Visible = false;
            searchType = 2;
            ClassSearchStatic.searchtype = "5-ти значным кодом";
            textBox1.Visible = true;
            button4.Visible = true;
        }

        private void button4_Click(object sender, EventArgs e)
        {
            if (searchType == 1)
            {
                int pos = 0;
                foreach (ClassMC el in mcList)
                {
                    if (el.codeMC3 == textBox1.Text)
                    {
                        resultId = el.id;
                        ClassSearchStatic.position = pos;
                        break;
                    }
                    pos++;
                }
            }
            if (searchType == 2)
            {
                int pos = 0;
                foreach (ClassMC el in mcList)
                {
                    if (el.codeMC5 == textBox1.Text)
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
