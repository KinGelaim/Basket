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
    public partial class Form2 : Form
    {
        int searchType = -1;
        int resultId = -1;
        List<ClassElements> elementsList = new List<ClassElements>();
        //Поиск элемента в справочнике
        public Form2(List<ClassElements> elementsList)
        {
            InitializeComponent();
            ClassSearchStatic.exit = true;
            this.elementsList = elementsList;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            button1.Visible = false;
            button2.Visible = false;
            button3.Visible = false;
            searchType = 1;
            ClassSearchStatic.searchtype = "кодом";
            textBox1.Visible = true;
            button4.Visible = true;
        }

        private void button2_Click(object sender, EventArgs e)
        {
            button1.Visible = false;
            button2.Visible = false;
            button3.Visible = false;
            searchType = 2;
            ClassSearchStatic.searchtype = "чертежом";
            textBox1.Visible = true;
            button4.Visible = true;
        }

        private void button3_Click(object sender, EventArgs e)
        {
            button1.Visible = false;
            button2.Visible = false;
            button3.Visible = false;
            searchType = 3;
            ClassSearchStatic.searchtype = "индексом";
            textBox1.Visible = true;
            button4.Visible = true;
        }

        private void button4_Click(object sender, EventArgs e)
        {
            if (searchType == 1)
            {
                int pos = 0;
                foreach (ClassElements el in elementsList)
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
            else if(searchType == 2)
            {
                int pos = 0;
                foreach (ClassElements el in elementsList)
                {
                    if (el.getPicture() == textBox1.Text)
                    {
                        resultId = el.getId();
                        ClassSearchStatic.position = pos;
                        break;
                    }
                    pos++;
                }
            }
            else if (searchType == 3)
            {
                int pos = 0;
                foreach (ClassElements el in elementsList)
                {
                    if (el.getIndex() == textBox1.Text)
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
