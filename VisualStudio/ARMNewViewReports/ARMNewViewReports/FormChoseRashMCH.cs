using System;
using System.Windows.Forms;

namespace ARMNewViewReports
{
    public partial class FormChoseRashMCH : Form
    {
        public bool isExit = false;
        public int typeMCH = 0;
        public string year = "";
        public string uchNumber = "";
        public string count = "";

        public FormChoseRashMCH()
        {
            InitializeComponent();

            isExit = false;
            typeMCH = 0;

            year = DateTime.Now.Year.ToString();
            uchNumber = "";
            count = "1";

            numericUpDown2.Value = Convert.ToInt32(year);
        }

        private void button1_Click(object sender, EventArgs e)
        {
            year = Convert.ToString(numericUpDown2.Value);
            uchNumber = textBox1.Text;
            count = Convert.ToString(numericUpDown1.Value);
            typeMCH = 0;
            isExit = true;
            this.Close();
        }

        private void button2_Click(object sender, EventArgs e)
        {
            year = Convert.ToString(numericUpDown2.Value);
            uchNumber = textBox1.Text;
            count = Convert.ToString(numericUpDown1.Value);
            typeMCH = 1;
            isExit = true;
            this.Close();
        }

        private void button3_Click(object sender, EventArgs e)
        {
            year = Convert.ToString(numericUpDown2.Value);
            uchNumber = textBox1.Text;
            count = Convert.ToString(numericUpDown1.Value);
            typeMCH = 2;
            isExit = true;
            this.Close();
        }

        private void button4_Click(object sender, EventArgs e)
        {
            year = Convert.ToString(numericUpDown2.Value);
            uchNumber = textBox1.Text;
            count = Convert.ToString(numericUpDown1.Value);
            typeMCH = 3;
            isExit = true;
            this.Close();
        }
    }
}
