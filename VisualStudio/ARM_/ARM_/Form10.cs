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
    public partial class Form10 : Form
    {
        int searchType = -1;
        List<ClassVKMespl> vkMesplList = new List<ClassVKMespl>();
        //List<ClassVKMespl> vkMesplSbList = new List<ClassVKMespl>();
        List<ClassVKMesplElements> vkMesplElementList = new List<ClassVKMesplElements>();
        //List<ClassVKMesplElements> vkMesplSbElementList = new List<ClassVKMesplElements>();
        bool k = true;
        int resultId = -1;
        public Form10(List<ClassVKMespl> vkMesplList, bool k)
        {
            InitializeComponent();
            ClassSearchStatic.exit = true;
            this.vkMesplList = vkMesplList;
            this.k = k;
        }
        public Form10(List<ClassVKMesplElements> vkMesplElementList, bool k)
        {
            InitializeComponent();
            ClassSearchStatic.exit = true;
            this.vkMesplElementList = vkMesplElementList;
            this.k = k;
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
                if(k)
                    foreach (ClassVKMespl el in vkMesplList)
                    {
                        if (el.codeElementVKMespl == textBox1.Text)
                        {
                            resultId = el.id;
                            ClassSearchStatic.position = pos;
                            break;
                        }
                        pos++;
                    }
                else
                    foreach (ClassVKMesplElements el in vkMesplElementList)
                    {
                        if (el.el.getCode() == textBox1.Text)
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
