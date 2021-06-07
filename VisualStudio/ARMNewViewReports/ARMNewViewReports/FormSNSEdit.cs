using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace ARMNewViewReports
{
    public partial class FormSNSEdit : Form
    {
        public bool isExit = false;
        public string oldCode = "";
        public string code = "";
        public string name = "";
        public FormSNSEdit(string code = "", string name = "")
        {
            InitializeComponent();

            oldCode = code;

            textBox1.Text = code;
            textBox2.Text = name;
            if (code != "")
            {
                textBox1.ReadOnly = false;
                this.Text = "Редактирование С=>НС";
            }
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (!string.IsNullOrWhiteSpace(textBox1.Text) && !string.IsNullOrWhiteSpace(textBox2.Text))
            {
                isExit = true;
                code = textBox1.Text;
                name = textBox2.Text;
                this.Close();
            }
        }
    }
}
