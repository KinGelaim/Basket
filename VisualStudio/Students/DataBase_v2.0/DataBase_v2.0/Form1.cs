using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Data.SqlClient;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.IO;


namespace DataBase_v2._0
{
    public partial class Form1 : Form
    {
        private List<User> usersList = new List<User>();

        SqlConnection sqlConnection;
        private string connectionString = "";
        SqlDataReader sqlReader = null;

        enum CommandQuery
        {
            add,
            update,
            delete
        }

        public Form1()
        {
            InitializeComponent();

            connectionString = "Data Source=(LocalDB)\\v11.0;AttachDbFilename=\""+ Directory.GetCurrentDirectory() + "\\BD\\Database.mdf\";Integrated Security=True";

            LoadUsers();
            ShowUser();
        }

        //Добавить
        private void button1_Click(object sender, EventArgs e)
        {
            FormUser newFormUser = new FormUser();
            newFormUser.ShowDialog();
            if (newFormUser.isExit)
                QueryInBD(newFormUser.user, CommandQuery.add);
        }

        //Редактировать
        private void button2_Click(object sender, EventArgs e)
        {
            int row = 0;
            try
            {
                row = dataGridView1.SelectedRows[0].Index;
            }
            catch
            {
                MessageBox.Show("Выделите строку!");
                return;
            }
            FormUser newFormUser = new FormUser(FindUser(dataGridView1[0, row].Value.ToString()));
            newFormUser.ShowDialog();
            if(newFormUser.isExit)
                QueryInBD(newFormUser.user, CommandQuery.update);
        }

        //Удалить
        private void button3_Click(object sender, EventArgs e)
        {
            int row = 0;
            try
            {
                row = dataGridView1.SelectedRows[0].Index;
            }
            catch
            {
                MessageBox.Show("Выделите строку!");
                return;
            }
            QueryInBD(FindUser(dataGridView1[0, row].Value.ToString()), CommandQuery.delete);
        }

        private User FindUser(string id)
        {
            if (usersList.Count > 0)
            {
                foreach (User user in usersList)
                    if (user.ID == id)
                        return user;
            }
            return null;
        }

        private void LoadUsers()
        {
            usersList.Clear();
            sqlConnection = new SqlConnection(connectionString);
            sqlReader = null;
            SqlCommand command = new SqlCommand("SELECT * FROM Users", sqlConnection);
            try
            {
                sqlConnection.Open();
                sqlReader = command.ExecuteReader();
                while (sqlReader.Read())
                {
                    User newUser = new User();
                    newUser.ID = Convert.ToString(sqlReader["id"]);
                    newUser.Surname = Convert.ToString(sqlReader["surname"]);
                    newUser.Name = Convert.ToString(sqlReader["name"]);
                    newUser.Patronymic = Convert.ToString(sqlReader["patronymic"]);
                    newUser.Phone = Convert.ToString(sqlReader["phone"]);
                    newUser.Home = Convert.ToString(sqlReader["home"]);
                    usersList.Add(newUser);
                }
                sqlReader.Close();
                sqlConnection.Close();
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message.ToString(), ex.Source.ToString(), MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
            finally
            {
                if (sqlReader != null)
                    sqlReader.Close();
                if (sqlConnection.State == ConnectionState.Open)
                    sqlConnection.Close();
            }
        }

        private void ShowUser()
        {
            if (usersList.Count > 0)
            {
                dataGridView1.Rows.Clear();
                foreach (User user in usersList)
                {
                    dataGridView1.Rows.Add();
                    dataGridView1[0, dataGridView1.Rows.Count - 1].Value = user.ID;
                    dataGridView1[1, dataGridView1.Rows.Count - 1].Value = user.Surname;
                    dataGridView1[2, dataGridView1.Rows.Count - 1].Value = user.Name;
                    dataGridView1[3, dataGridView1.Rows.Count - 1].Value = user.Patronymic;
                    dataGridView1[4, dataGridView1.Rows.Count - 1].Value = user.Phone;
                    dataGridView1[5, dataGridView1.Rows.Count - 1].Value = user.Home;
                }
            }
        }

        private void QueryInBD(User user, CommandQuery commandQuery)
        {
            if (user != null)
            {
                sqlConnection = new SqlConnection(connectionString);
                //Формирование запроса
                SqlCommand command = new SqlCommand();
                switch (commandQuery)
                {
                    case CommandQuery.add:
                        command = new SqlCommand("INSERT INTO Users (surname, name, patronymic, phone, home) VALUES (@surname, @name, @patronymic, @phone, @home)", sqlConnection);
                        command.Parameters.AddWithValue("surname", user.Surname);
                        command.Parameters.AddWithValue("name", user.Name);
                        command.Parameters.AddWithValue("patronymic", user.Patronymic);
                        command.Parameters.AddWithValue("phone", user.Phone);
                        command.Parameters.AddWithValue("home", user.Home);
                        break;
                    case CommandQuery.update:
                        command = new SqlCommand("UPDATE Users SET surname=@surname, name=@name, patronymic=@patronymic, phone=@phone, home=@home WHERE id=@id", sqlConnection);
                        command.Parameters.AddWithValue("id", user.ID);
                        command.Parameters.AddWithValue("surname", user.Surname);
                        command.Parameters.AddWithValue("name", user.Name);
                        command.Parameters.AddWithValue("patronymic", user.Patronymic);
                        command.Parameters.AddWithValue("phone", user.Phone);
                        command.Parameters.AddWithValue("home", user.Home);
                        break;
                    case CommandQuery.delete:
                        command = new SqlCommand("DELETE FROM Users WHERE id=@id", sqlConnection);
                        command.Parameters.AddWithValue("id", user.ID);
                        break;
                    default:
                        break;
                }

                //Само взаимодействие с БД
                try
                {
                    sqlConnection.Open();
                    command.ExecuteReader();
                    sqlConnection.Close();
                }
                catch (Exception ex)
                {
                    MessageBox.Show(ex.Message.ToString(), ex.Source.ToString(), MessageBoxButtons.OK, MessageBoxIcon.Error);
                }
                finally
                {
                    if (sqlConnection.State == ConnectionState.Open)
                        sqlConnection.Close();
                }
                LoadUsers();
                ShowUser();
            }
        }
    }
}
