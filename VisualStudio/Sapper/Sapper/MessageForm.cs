using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Sapper
{
    public partial class MessageForm : Form
    {
        public MessageForm(bool isRules)
        {
            InitializeComponent();
            if (isRules)
            {
                this.Text = "Правила игры";
                label1.Text = "В игре нужно нажимать на пустые блоки и искать мины";
                label2.Text = "Число указанное в блоке отображает количество";
                label3.Text = "мин в соседних блоках";
                label4.Text = "ПКМ можно указать расположение мины";
                label5.Text = "Успехов, боец!";
            }
            else
            {
                this.Text = "О программе";
                label1.Text = "Прога разработана компанией KinCorporative";
                label2.Text = "Все авторские права защищены авторскими правами";
                label3.Text = "(С) KinCorporative, 2018";
                label4.Text = "";
                label5.Text = "";
            }
        }

        private void button1_Click(object sender, EventArgs e)
        {
            this.Close();
        }
    }
}
