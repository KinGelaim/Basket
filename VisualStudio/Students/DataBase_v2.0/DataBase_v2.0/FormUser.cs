using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace DataBase_v2._0
{
    public partial class FormUser : Form
    {
        public User user;
        public bool isExit = false;

        public FormUser(User user = null)
        {
            InitializeComponent();

            this.user = user;
            this.isExit = false;

            if (user != null)
            {
                textBox1.Text = user.Surname;
                textBox2.Text = user.Name;
                textBox3.Text = user.Patronymic;
                textBox4.Text = user.Phone;
                textBox5.Text = user.Home;
                button1.Text = "Изменить";
            }
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (!string.IsNullOrWhiteSpace(textBox1.Text) && !string.IsNullOrWhiteSpace(textBox2.Text))
            {
                if(user == null)
                    user = new User();
                user.Surname = textBox1.Text;
                user.Name = textBox2.Text;
                user.Patronymic = textBox3.Text;
                user.Phone = textBox4.Text;
                user.Home = textBox5.Text;
                isExit = true;
                this.Close();
            }
        }
    }
}
