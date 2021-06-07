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
    public partial class Form20 : Form
    {
        int searchType = -1;
        List<ClassPlIspKontrol> plIspKontrolList = new List<ClassPlIspKontrol>();
        int resultId = -1;

        public Form20(List<ClassPlIspKontrol> plIspKontrolList)
        {
            InitializeComponent();
            this.plIspKontrolList = plIspKontrolList;
            ClassSearchStatic.exit = true;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            button1.Visible = false;
            searchType = 1;
            ClassSearchStatic.searchtype = "кодом элемента";
            textBox1.Visible = true;
            button4.Visible = true;
        }

        private void button4_Click(object sender, EventArgs e)
        {
            if (searchType == 1)
            {
                int pos = 0;
                foreach (ClassPlIspKontrol pl in plIspKontrolList)
                {
                    if (pl.codeElementPlIspKontrol == textBox1.Text)
                    {
                        resultId = pl.id;
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
