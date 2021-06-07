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
    public partial class Form3 : Form
    {
        int searchType = -1;
        List<ClassFactorys> factorysList = new List<ClassFactorys>();
        int resultId = -1;
        public Form3(List<ClassFactorys> factorysList)
        {
            InitializeComponent();
            ClassSearchStatic.exit = true;
            this.factorysList = factorysList;
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
                foreach (ClassFactorys el in factorysList)
                {
                    if (el.getCode() == textBox1.Text)
                    {
                        resultId = el.getId();
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
