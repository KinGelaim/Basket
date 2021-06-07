using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Project7
{
    public partial class Form1 : Form
    {
        //Имя файла
        string fn = "";
        public Form1()
        {
            InitializeComponent();

            textBox1.Text = "";

            this.Text = "Новый документ";

            //Настройки для компонента openDialog
            openFileDialog1.DefaultExt = "txt";
            openFileDialog1.Filter = "Текст|*.txt";
            openFileDialog1.Title = "Открыть документ";
            openFileDialog1.Multiselect = false;

            //Настройки для компонента saveDialog
            saveFileDialog1.DefaultExt = "txt";
            saveFileDialog1.Filter = "Текст|*.txt";
            saveFileDialog1.Title = "Сохранить документ";
        }

        //Открыть
        private void openToolStripMenuItem_Click(object sender, EventArgs e)
        {
            openFileDialog1.FileName = "";
            if (openFileDialog1.ShowDialog() == DialogResult.OK)
            {
                fn = openFileDialog1.FileName;

                this.Text = fn;
                try
                {
                    System.IO.StreamReader sr = new System.IO.StreamReader(fn);
                    textBox1.Text = sr.ReadToEnd();
                    textBox1.SelectionStart = textBox1.TextLength;
                    sr.Close();
                }
                catch (Exception ex)
                {
                    MessageBox.Show(ex.Message, "Ошибка!", MessageBoxButtons.OK, MessageBoxIcon.Error);
                }
            }
        }

        //Сохранить
        private void saveToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (fn == "")
            {
                if (saveFileDialog1.ShowDialog() == DialogResult.OK)
                {
                    fn = saveFileDialog1.FileName;
                    this.Text = fn;
                }

                if (fn != "")
                {
                    try
                    {
                        System.IO.StreamWriter sw = new System.IO.StreamWriter(fn);
                        sw.Write(textBox1.Text);
                        sw.Close();
                    }
                    catch (Exception ex)
                    {
                        MessageBox.Show(ex.Message, "Ошибка!", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
            }
            else
            {
                if (MessageBox.Show("Перезаписать?", "Сохранение", MessageBoxButtons.YesNo, MessageBoxIcon.Information) == DialogResult.Yes)
                {
                    try
                    {
                        System.IO.StreamWriter sw = new System.IO.StreamWriter(fn);
                        sw.Write(textBox1.Text);
                        sw.Close();
                    }
                    catch (Exception ex)
                    {
                        MessageBox.Show(ex.Message, "Ошибка!", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
                else
                {
                    if (saveFileDialog1.ShowDialog() == DialogResult.OK)
                    {
                        fn = saveFileDialog1.FileName;
                        this.Text = fn;

                        if (fn != "")
                        {
                            try
                            {
                                System.IO.StreamWriter sw = new System.IO.StreamWriter(fn);
                                sw.Write(textBox1.Text);
                                sw.Close();
                            }
                            catch (Exception ex)
                            {
                                MessageBox.Show(ex.Message, "Ошибка!", MessageBoxButtons.OK, MessageBoxIcon.Error);
                            }
                        }
                    }
                }
            }
        }

        //Печать
        private void printToolStripMenuItem_Click(object sender, EventArgs e)
        {
            //printDialog1.
            //printDialog1.ShowDialog();
            printDocument1.Print();
        }

        //Выход
        private void exitToolStripMenuItem_Click(object sender, EventArgs e)
        {
            //Первый вариант - закрывает текущую форму, но т.к. данная форма и есть все наше приложение, то закрывается сразу же все
            //this.Close();
            //Второй вариант - закрывает именно приложение (даже, если открыта не одна форма)
            Application.Exit();
        }

        //Настройки
        private void settingsToolStripMenuItem_Click(object sender, EventArgs e)
        {
            fontDialog1.Font = textBox1.Font;
            if (fontDialog1.ShowDialog() == DialogResult.OK)
            {
                textBox1.Font = fontDialog1.Font;
            }
        }

        private void printDocument1_PrintPage(object sender, System.Drawing.Printing.PrintPageEventArgs e)
        {
            e.Graphics.DrawString(textBox1.Text, textBox1.Font, Brushes.Black, 10, 10, new StringFormat());
        }
    }
}
