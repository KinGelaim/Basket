using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.IO;
using System.IO.Ports;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace SerialPort
{
    public partial class Form1 : Form
    {
        string[] ports;

        public Form1()
        {
            InitializeComponent();
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            ports = System.IO.Ports.SerialPort.GetPortNames();
            comboBox1.Items.AddRange(ports);

            textBox1.Text = serialPort1.BaudRate.ToString();
            textBox2.Text = serialPort1.DataBits.ToString();

            comboBox2.SelectedIndex = 0;

            button1.Enabled = true;
            button1.Visible = true;

            button2.Enabled = false;
            button2.Visible = false;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            try
            {
                //serialPort1 = new System.IO.Ports.SerialPort();
                serialPort1.PortName = ports[comboBox1.SelectedIndex];
                serialPort1.BaudRate = Convert.ToInt32(textBox1.Text);
                serialPort1.DataBits = Convert.ToInt32(textBox2.Text);
                serialPort1.Parity = Parity.None;
                if (comboBox1.SelectedIndex == 0)
                    serialPort1.StopBits = StopBits.One;
                else if(comboBox1.SelectedIndex == 1)
                    serialPort1.StopBits = StopBits.Two;
                else if (comboBox1.SelectedIndex == 2)
                    serialPort1.StopBits = StopBits.OnePointFive;
                else
                    serialPort1.StopBits = StopBits.None;
                serialPort1.ReadTimeout = 3000;
                serialPort1.WriteTimeout = 3000;
                serialPort1.Open();

                button1.Enabled = false;
                button1.Visible = false;

                button2.Enabled = true;
                button2.Visible = true;
            }
            catch (Exception ex)
            {
                MessageBox.Show("Ошибка при подключении!\n" + ex.Message, "Ошибка!", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        private void button2_Click(object sender, EventArgs e)
        {
            serialPort1.Close();

            button1.Enabled = true;
            button1.Visible = true;

            button2.Enabled = false;
            button2.Visible = false;
        }

        private void serialPort1_DataReceived(object sender, SerialDataReceivedEventArgs e)
        {
            richTextBox1.Text += serialPort1.ReadExisting();
        }
    }
}
