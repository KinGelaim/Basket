using System;
using System.IO;
using System.IO.Ports;
using System.Windows.Forms;

namespace Scanner3D
{
    public partial class FormConnection : Form
    {
        public string reciveData = "";
        public bool isExit = false;

        string[] ports;

        // Для отображения точек во время получения
        bool isReciveD = false;
        string reciveD = "";
        bool isReciveL = false;
        string reciveL = "";
        public Action<string, string> actionDrawPoint;

        public FormConnection(Action<string, string> actionDrawPoint)
        {
            InitializeComponent();

            ports = System.IO.Ports.SerialPort.GetPortNames();
            comboBox1.Items.AddRange(ports);

            this.actionDrawPoint = actionDrawPoint;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (Connection())
            {
                button1.Enabled = false;
                button1.Visible = false;
                button2.Enabled = true;
                button2.Visible = true;

                comboBox1.Enabled = false;
                textBox1.Enabled = false;
                textBox2.Enabled = false;
                textBox3.Enabled = false;
                radioButton1.Enabled = false;
                radioButton2.Enabled = false;
                radioButton3.Enabled = false;
                checkBox1.Enabled = false;
            }
        }

        private void button2_Click(object sender, EventArgs e)
        {
            Disconnection();

            button2.Enabled = false;
            button2.Visible = false;
            button1.Enabled = true;
            button1.Visible = true;

            comboBox1.Enabled = true;
            textBox1.Enabled = true;
            textBox2.Enabled = true;
            textBox3.Enabled = true;
            radioButton1.Enabled = true;
            radioButton2.Enabled = true;
            radioButton3.Enabled = true;
            checkBox1.Enabled = true;
        }

        private bool Connection()
        {
            if (comboBox1.SelectedIndex >= 0)
            {
                if (radioButton1.Checked || radioButton2.Checked || radioButton3.Checked)
                {
                    try
                    {
                        if(checkBox1.Checked)
                            ClearLog();
                        //serialPort1 = new System.IO.Ports.SerialPort();
                        serialPort1.PortName = ports[comboBox1.SelectedIndex];
                        serialPort1.BaudRate = Convert.ToInt32(textBox1.Text);
                        serialPort1.DataBits = 8;
                        serialPort1.Parity = Parity.None;
                        serialPort1.StopBits = StopBits.One;
                        serialPort1.ReadTimeout = 3000;
                        serialPort1.WriteTimeout = 3000;
                        serialPort1.Open();
                        return true;
                    }
                    catch (Exception ex)
                    {
                        MessageBox.Show("Ошибка при подключении!\n" + ex.Message, "Ошибка!", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
                else
                    MessageBox.Show("Выберите режим загрузки!");
            }
            else
                MessageBox.Show("Выберите порт!");
            return false;
        }

        private void Disconnection()
        {
            try
            {
                serialPort1.Close();
            }
            catch (Exception ex)
            {
                MessageBox.Show("Ошибка при отключении!\n" + ex.Message, "Ошибка!", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        private void serialPort1_DataReceived(object sender, System.IO.Ports.SerialDataReceivedEventArgs e)
        {
            if (radioButton1.Checked)
            {
                reciveData = serialPort1.ReadExisting();
                Disconnection();
                isExit = true;
                this.Close();
            }
            else if(radioButton2.Checked)
            {
                string response = serialPort1.ReadExisting().Trim();
                if (response.Length > 0)
                {
                    foreach (char k in response)
                    {
                        if (k == 'b')
                            reciveData = "[";
                        else if (k == 'e')
                        {
                            reciveData = reciveData.TrimEnd(',');
                            reciveData += "]";
                            reciveData = reciveData.Replace("\n", "").Replace("\r", "").Replace(" ", "");
                            Disconnection();
                            MessageBox.Show(reciveData);
                            isExit = true;
                            this.Close();
                        }
                        else if (k == 'f')
                        {

                        }
                        else if (k == 'r')
                        {
                            reciveData += ",";
                        }
                        else
                        {
                            if (!string.IsNullOrWhiteSpace(k.ToString()))
                                reciveData += k;
                        }
                    }
                    if (checkBox1.Checked)
                        SaveLog(response, reciveData);
                }
            }
            else if(radioButton3.Checked)
            {
                string response = serialPort1.ReadExisting().Trim();
                if (response.Length > 0)
                {
                    foreach(char k in response)
                    {
                        if (k == 'b')
                        {
                            reciveData = "[{";
                        }
                        else if(k == 'l')
                        {
                            if (reciveData[reciveData.Length - 1] == ';')
                            {
                                reciveData = reciveData.TrimEnd(';');
                                reciveData += "\"},{";
                            }
                            reciveData += "\"l\":";
                            isReciveL = true;
                            reciveL = string.Empty;
                        }
                        else if (k == 'r')
                        {
                            reciveData += ",\"d\":\"";
                            isReciveL = false;
                        }
                        else if (k == 'd')
                        {
                            isReciveD = true;
                            reciveD = string.Empty;
                        }
                        else if(k == 'c')
                        {
                            reciveData += ";";
                            isReciveD = false;
                            actionDrawPoint.Invoke(reciveL, reciveD);
                            reciveD = string.Empty;
                        }
                        else if (k == 'e')
                        {
                            reciveData = reciveData.TrimEnd(';');
                            reciveData += '"';
                            reciveData += "}]";
                            reciveData = reciveData.Replace(" ", "").Replace("\n", "");

                            Disconnection();

                            //SaveLog(reciveData);
                            //MessageBox.Show(reciveData);

                            isExit = true;
                            this.Close();
                        }
                        else
                        {
                            if (!string.IsNullOrWhiteSpace(k.ToString()) && k != '\n' && k != '\r')
                            {
                                reciveData += k;
                                if (isReciveD)
                                    reciveD += k;
                                if (isReciveL)
                                    reciveL += k;
                            }
                        }
                    }
                    if(checkBox1.Checked)
                        SaveLog(response, reciveData);
                }
            }
        }

        private void ClearLog()
        {
            using (StreamWriter sw = new StreamWriter("log.txt"))
            {
                sw.Write("");
            }
        }

        private void SaveLog(string response, string reciveData)
        {
            using (StreamWriter sw = new StreamWriter("log.txt", true))
            {
                sw.Write(response);
                sw.Write("\n");
                sw.Write(reciveData);
                sw.Write("\n");
            }
        }
    }
}
