using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.Xml.Linq;
using System.IO;

namespace Exams2
{
    public partial class Form1 : Form
    {
        RadioButton[] radioButtons;
        Label[] labels;

        XDocument xDoc;
        DirectoryInfo di;

        IEnumerable<XElement> xel;

        string filePath;
        string fileName;

        int[] test;
        int cv = 0;

        int mode = 0;

        int right;   //Верный ответ
        int otvet;   //Выбранный ответ
        int nr;      //Всего правильных ответов
        int allQ;    //Всего вопросов

        //Имя файла надо указать в поле Command Line
        //Arguments на вкладке Debug, для доступа к которой надо
        //в меню Project выбрать команду Properties
        public Form1(string[] args)
        {
            InitializeComponent();

            radioButtons = new RadioButton[4];
            labels = new Label[4];
            for (int i = 0; i < 4; i++)
            {
                radioButtons[i] = new RadioButton();
                radioButtons[i].Location = new Point(25, 20 + i * 16);
                radioButtons[i].Name = "radioButton" + i.ToString();
                radioButtons[i].Size = new Size(14, 13);
                radioButtons[i].Visible = false;
                radioButtons[i].Click += new EventHandler(this.radioButton1_Click);
                radioButtons[i].Parent = this;

                labels[i] = new Label();
                labels[i].AutoSize = true;
                labels[i].Font = new Font("Microsoft Sans Serif", 9.75f, System.Drawing.FontStyle.Regular, GraphicsUnit.Point, (byte)204);
                labels[i].Location = new Point(45, 20 + i * 16);
                labels[i].MaximumSize = new Size(400, 0);
                labels[i].Name = "label" + i.ToString();
                labels[i].Size = new Size(45, 16);
                labels[i].Visible = false;
                labels[i].Parent = this;
            }

            radioButton5.Checked = true;

            if (args.Length > 0)
            {
                if (args[0].IndexOf(":") == 1)
                {
                    //Указано только имя файла
                    filePath = Application.StartupPath + "\\";
                    fileName = args[0];
                }
                else
                {
                    //Указан путь к файлу
                    filePath = args[0].Substring(0, args[0].LastIndexOf("\\") + 1);
                    fileName = args[0].Substring(args[0].LastIndexOf("\\") + 1);
                }
            }
            else
            {
                filePath = Application.StartupPath + "\\";
                fileName = "testik.xml";
            }

            try
            {
                xDoc = XDocument.Load(filePath + fileName);
                xel = xDoc.Elements();

                label10.Text = xel.Elements("info").ElementAt(0).Value;

                allQ = xel.Elements("queries").Elements().Count();

                test = new int[allQ];

                Boolean[] b;

                b = new Boolean[allQ];
                for (int i = 0; i < allQ; i++)
                {
                    b[i] = false;
                }

                Random rand = new Random();
                int r;
                for (int i = 0; i < allQ; i++)
                {
                    do
                        r = rand.Next(allQ);
                    while (b[r] == true);

                    test[i] = r;
                    b[r] = true;
                }

                mode = 0;
                cv = 0;
            }
            catch (Exception e)
            {
                mode = 2; 
                return;
            }
        }

        private void button1_Click(object sender, EventArgs e)
        {
            switch (mode)
            {
                case 0:
                    qw(test[cv]);
                    cv++;
                    mode = 1;
                    break;
                case 1:
                    if (otvet == right)
                        nr++;
                    if (cv < allQ)
                    {
                        qw(test[cv]);
                        cv++;
                    }
                    else
                    {
                        for (int j = 0; j < 4; j++)
                        {
                            labels[j].Visible = false;
                            radioButtons[j].Visible = false;
                        }
                        pictureBox1.Visible = false;
                        ShowResult();
                        mode = 2;
                    }
                    break;
                case 2:
                    this.Close();
                    break;
                default:
                    break;
            }
        }

        private void qw(int i)
        {
            int j;
            for (j = 0; j < 4; j++)
            {
                labels[j].Visible = false;
                radioButtons[j].Visible = false;
            }
            radioButton5.Checked = true;
            label10.Text = xel.Elements("queries").Elements().ElementAt(i).Element("q").Value;

            right = Convert.ToInt32(xel.Elements("queries").Elements().ElementAt(i).Element("q").Attribute("right").Value);

            string src = xel.Elements("queries").Elements().ElementAt(i).Element("q").Attribute("src").Value;
            
            if (src.Length != 0)
            {
                try
                {
                    pictureBox1.Image = new Bitmap(filePath + src);
                    pictureBox1.Visible = true;

                    radioButtons[0].Top = pictureBox1.Bottom + 16;
                    labels[0].Top = radioButtons[0].Top - 3;
                }
                catch (Exception e)
                {
                    if (pictureBox1.Visible)
                        pictureBox1.Visible = false;
                    radioButtons[0].Top = label10.Bottom + 10;
                    labels[0].Top = radioButtons[0].Top - 3;
                }
            }
            else
            {
                pictureBox1.Visible = false;
                radioButtons[0].Top = label10.Bottom + 10;
                labels[0].Top = radioButtons[0].Top - 3;
            }

            j = 0;
            foreach (XElement a in xel.Elements("queries").Elements().ElementAt(i).Element("as").Elements())
            {
                labels[j].Text = a.Value;
                labels[j].Visible = true;
                radioButtons[j].Visible = true;
                if (j != 0)
                {
                    radioButtons[j].Top = labels[j - 1].Bottom + 10;
                    labels[j].Top = radioButtons[j].Top - 3;
                }
                j++;
            }
            button1.Enabled = false;
        }

        private void radioButton1_Click(object sender, EventArgs e)
        {
            if ((RadioButton)sender == radioButtons[0]) otvet = 1;
            if ((RadioButton)sender == radioButtons[1]) otvet = 2;
            if ((RadioButton)sender == radioButtons[2]) otvet = 3;
            if ((RadioButton)sender == radioButtons[3]) otvet = 4;
            button1.Enabled = true;
        }

        private void ShowResult()
        {
            int k;
            int i;
            int p = 0;

            k = xel.Elements("levels").Elements().Count();

            for (i = 0; i < k - 1; i++)
            {
                p = Convert.ToInt32(xel.Elements("levels").Elements().ElementAt(i).Attribute("p").Value);
                if (nr >= p)
                    break;
            }
            label10.Text = "Всего вопросов: " + allQ.ToString() + "\n" +
                "Правильных ответов: " + nr.ToString() + "\n" +
                "Оценка: " + xel.Elements("levels").Elements().ElementAt(i).Value;
        }
    }
}
