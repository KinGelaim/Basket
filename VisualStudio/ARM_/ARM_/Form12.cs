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
    public partial class Form12 : Form
    {
        int searchType = -1;
        List<ClassVIspMespl> vIspListMespl = new List<ClassVIspMespl>();
        List<ClassPlIspMespl> plIspListMespl = new List<ClassPlIspMespl>();
        int resultId = -1;
        bool vOrPlIspMespl = true;

        public Form12(List<ClassVIspMespl> vIspListMespl)
        {
            InitializeComponent();
            ClassSearchStatic.exit = true;
            this.vIspListMespl = vIspListMespl;
            vOrPlIspMespl = true;
            this.Text = "Поиск объема испытаний";
        }

        public Form12(List<ClassPlIspMespl> plIspListMespl)
        {
            InitializeComponent();
            ClassSearchStatic.exit = true;
            this.plIspListMespl = plIspListMespl;
            vOrPlIspMespl = false;
            this.Text = "Поиск плана испытаний";
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
                if (vOrPlIspMespl)
                {
                    foreach (ClassVIspMespl el in vIspListMespl)
                    {
                        if (el.codeElementVIspMespl == textBox1.Text)
                        {
                            resultId = el.id;
                            ClassSearchStatic.position = pos;
                            break;
                        }
                        pos++;
                    }
                }
                else
                {
                    foreach (ClassPlIspMespl el in plIspListMespl)
                    {
                        if (el.codeElementPlIspMespl == textBox1.Text)
                        {
                            resultId = el.id;
                            ClassSearchStatic.position = pos;
                            break;
                        }
                        pos++;
                    }
                }
            }
            ClassSearchStatic.id = resultId;
            ClassSearchStatic.exit = false;
            this.Close();
        }
    }
}
