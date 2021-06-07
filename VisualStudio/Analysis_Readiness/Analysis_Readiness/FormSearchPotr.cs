using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Analysis_Readiness
{
    public partial class FormSearchPotr : Form
    {

        public bool is_exit = false;

        private List<ClassPotr> allPotrList;

        public ClassPotr searchPotr;

        private List<ClassPotr> searchPotrList;

        public FormSearchPotr()
        {
            InitializeComponent();
        }

        public FormSearchPotr(List<ClassPotr> allPotrList)
        {
            InitializeComponent();
            this.allPotrList = allPotrList;
        }

        //Кнопка поиска
        private void button1_Click(object sender, EventArgs e)
        {
            searchPotrList = new List<ClassPotr>();
            foreach (ClassPotr potr in allPotrList)
                if (potr.code_potr == textBox1.Text && potr.count_potr == Convert.ToInt32(textBox2.Text))
                    searchPotrList.Add(potr);
            foreach (ClassPotr potr in allPotrList)
                if (potr.code_potr == textBox1.Text && potr.count_potr != Convert.ToInt32(textBox2.Text))
                    searchPotrList.Add(potr);
            foreach (ClassPotr potr in searchPotrList)
                    listBox1.Items.Add("Код: " + potr.code_potr + " вид: " + potr.vid_potr + " кол-во: " + potr.count_potr);
        }

        //Выбор комплектующего
        private void listBox1_DoubleClick(object sender, EventArgs e)
        {
            is_exit = true;
            searchPotr = searchPotrList[listBox1.SelectedIndex];
            this.Close();
        }
    }
}
