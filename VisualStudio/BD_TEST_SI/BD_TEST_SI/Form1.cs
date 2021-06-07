using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Data.SqlClient;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace BD_TEST_SI
{
    public partial class Form1 : Form
    {
        SqlConnection sqlConnection;
        List <String> nameFirms = new List<String>();
        List <int> positionUpdate = new List<int>();
        int posUpdate = 0;

        public Form1()
        {
            InitializeComponent();
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            string connectionString = @"Data Source=(LocalDB)\v11.0;AttachDbFilename=C:\Users\ad\documents\visual studio 2013\Projects\BD_TEST_SI\BD_TEST_SI\Products.mdf;Integrated Security=True";
            sqlConnection = new SqlConnection(connectionString);
            sqlConnection.Open();
            SqlDataReader sqlReader = null;
            SqlCommand command = new SqlCommand("SELECT Products.id,Products.name,Products.price,Firms.name FROM [Products],[Firms] WHERE Products.firma = Firms.Id", sqlConnection);
            SqlCommand command2 = new SqlCommand("SELECT name FROM [Firms]", sqlConnection);
            try
            {
                sqlReader = command.ExecuteReader();
                while (sqlReader.Read()) {
                    listBox1.Items.Add(Convert.ToString(sqlReader["Id"]) + " " + Convert.ToString(sqlReader["Name"]) + " " + Convert.ToString(sqlReader["Price"]) + " " + Convert.ToString(sqlReader["Name"]) + " " + Convert.ToString(sqlReader[3]));
                }
                sqlReader.Close();
                sqlReader = command2.ExecuteReader();
                while (sqlReader.Read())
                {
                    comboBox2.Items.Add(Convert.ToString(sqlReader["name"]));
                    nameFirms.Add(Convert.ToString(sqlReader["name"]));
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message.ToString(), ex.Source.ToString(), MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
            finally {
                if (sqlReader != null)
                    sqlReader.Close();
            }
        }

        private void exitToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (sqlConnection != null && sqlConnection.State != ConnectionState.Closed)
                sqlConnection.Close();
            Application.Exit();
        }

        private void Form1_FormClosing(object sender, FormClosingEventArgs e)
        {
            if (sqlConnection != null && sqlConnection.State != ConnectionState.Closed)
                sqlConnection.Close();
        }

        private void button6_Click(object sender, EventArgs e)
        {
            if (label5.Visible)
                label5.Visible = false;
            if (!string.IsNullOrEmpty(textBox1.Text) && !string.IsNullOrWhiteSpace(textBox1.Text) && !string.IsNullOrEmpty(textBox2.Text) && !string.IsNullOrWhiteSpace(textBox2.Text) && !string.IsNullOrEmpty(comboBox2.Text) && !string.IsNullOrWhiteSpace(comboBox2.Text))
            {
                SqlCommand command = new SqlCommand("INSERT [Products] (name,price,firma) VALUES (@name,@price,@firma)", sqlConnection);
                int prFirmaId = 1;
                for (int i = 0; i < nameFirms.Count; i++)
                    if (nameFirms[i] == comboBox2.Text)
                        prFirmaId = i + 1;
                command.Parameters.AddWithValue("name", textBox1.Text);
                command.Parameters.AddWithValue("price", textBox2.Text);
                command.Parameters.AddWithValue("firma", prFirmaId);
                command.ExecuteNonQuery();
                label5.Visible = true;
                label5.Text = "Данные успешно добавлены!";
                textBox1.Text = "";
                textBox2.Text = "";
            }
            else {
                label5.Visible = true;
                label5.Text = "Поля 'Название', 'Цена' и 'Фирма' должны быть заполнены!";
            }
        }

        private void button7_Click(object sender, EventArgs e)
        {
            listBox1.Items.Clear();
            SqlDataReader sqlReader = null;
            SqlCommand command = new SqlCommand("SELECT Products.id,Products.name,Products.price,Firms.name FROM [Products],[Firms] WHERE Products.firma = Firms.Id", sqlConnection);
            try
            {
                sqlReader = command.ExecuteReader();
                while (sqlReader.Read())
                {
                    listBox1.Items.Add(Convert.ToString(sqlReader["Id"]) + " " + Convert.ToString(sqlReader["Name"]) + " " + Convert.ToString(sqlReader["Price"]) + " " + Convert.ToString(sqlReader[3]));
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message.ToString(), ex.Source.ToString(), MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
            finally
            {
                if (sqlReader != null)
                    sqlReader.Close();
            }
        }

        private void comboBox3_SelectedIndexChanged(object sender, EventArgs e)
        {
            SqlDataReader sqlReader = null;
            SqlCommand command = new SqlCommand("SELECT Products.name,Products.price,Firms.name FROM [Products],[Firms] WHERE Products.id = @id AND Products.firma = Firms.Id", sqlConnection);
            command.Parameters.AddWithValue("id", comboBox3.Text);
            for (int i = 0; i < positionUpdate.Count; i++)
                if (positionUpdate[i] == Convert.ToInt32(comboBox3.Text))
                    posUpdate = i;
            try
            {
                sqlReader = command.ExecuteReader();
                while (sqlReader.Read())
                {
                    textBox3.Text = comboBox3.Text;
                    textBox4.Text = Convert.ToString(sqlReader["name"]);
                    textBox5.Text = Convert.ToString(sqlReader["price"]);
                    textBox6.Text = Convert.ToString(sqlReader[2]);
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message.ToString(), ex.Source.ToString(), MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
            finally
            {
                if (sqlReader != null)
                    sqlReader.Close();
            }
        }

        private void button1_Click(object sender, EventArgs e)
        {
            comboBox3.Items.Clear();
            SqlDataReader sqlReader = null;
            SqlCommand command = new SqlCommand("SELECT Products.id FROM [Products]", sqlConnection);
            try
            {
                sqlReader = command.ExecuteReader();
                while (sqlReader.Read())
                {
                    comboBox3.Items.Add(Convert.ToString(sqlReader["Id"]));
                    positionUpdate.Add(Convert.ToInt32(sqlReader["Id"]));
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message.ToString(), ex.Source.ToString(), MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
            finally
            {
                if (sqlReader != null)
                    sqlReader.Close();
            }
        }

        private void button4_Click(object sender, EventArgs e)
        {
            if (!string.IsNullOrEmpty(textBox4.Text) && !string.IsNullOrWhiteSpace(textBox4.Text) && !string.IsNullOrEmpty(textBox5.Text) && !string.IsNullOrWhiteSpace(textBox5.Text))
            {
                SqlCommand command = new SqlCommand("UPDATE [Products] SET [name]=@name, [price]=@price WHERE [id]=@id", sqlConnection);
                command.Parameters.AddWithValue("name",textBox4.Text);
                command.Parameters.AddWithValue("price", textBox5.Text);
                command.Parameters.AddWithValue("id", comboBox3.Text);
                command.ExecuteNonQuery();
            }
            
        }

        private void button5_Click(object sender, EventArgs e)
        {
            SqlCommand command = new SqlCommand("DELETE FROM [Products] WHERE [id]=@id", sqlConnection);
            command.Parameters.AddWithValue("id", comboBox3.Text);
            command.ExecuteNonQuery();
        }

        private void button2_Click(object sender, EventArgs e)
        {
            if (posUpdate - 1 >= 0)
            {
                posUpdate--;
                comboBox3.SelectedItem = Convert.ToString(positionUpdate[posUpdate]);
                for (int i = 0; i < positionUpdate.Count; i++)
                    if (positionUpdate[i] == Convert.ToInt32(comboBox3.Text))
                        posUpdate = i;
            }
        }

        private void button3_Click(object sender, EventArgs e)
        {
            if(posUpdate+1 < positionUpdate.Count)
            {
                posUpdate++;
                comboBox3.SelectedItem = Convert.ToString(positionUpdate[posUpdate]);
                for (int i = 0; i < positionUpdate.Count; i++)
                    if (positionUpdate[i] == Convert.ToInt32(comboBox3.Text))
                        posUpdate = i;
            }
        }
    }
}
