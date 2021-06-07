using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using MySql.Data.MySqlClient;
using System.Windows;

namespace Save_Image_IN_BD
{
    public static class ClassBD
    {
        static MySqlConnection sqlConnection;
        static string connectionString = "datasource=localhost;port=3306;username=root;password=;database=test;CharSet=utf8;";
        static MySqlDataReader sqlReader = null;

        public static List<ClassImage> imageList = new List<ClassImage>();

        public static void LoadBD()
        {
            imageList.Clear();
            sqlConnection = new MySqlConnection(connectionString);
            MySqlCommand command = new MySqlCommand("SELECT * FROM table_for_save_image", sqlConnection);
            command.CommandTimeout = 60;
            sqlReader = null;
            try
            {
                sqlConnection.Open();
                sqlReader = command.ExecuteReader();
                while (sqlReader.Read())
                {
                    imageList.Add(new ClassImage(Convert.ToInt32(sqlReader["id"]), Convert.ToString(sqlReader["name_image"]), sqlReader["image"] != DBNull.Value ? (byte[])sqlReader["image"] : null));
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message.ToString(), ex.Source.ToString());
            }
            finally
            {
                sqlConnection.Close();
            }
        }

        public static void SaveBD(ClassImage image)
        {
            sqlConnection = new MySqlConnection(connectionString);
            MySqlCommand command = new MySqlCommand("INSERT INTO table_for_save_image (name_image, image) VALUES (@name_image, @image)", sqlConnection);
            command.Parameters.AddWithValue("name_image", image.nameImage);
            command.Parameters.AddWithValue("image", image.byteImage);
            command.CommandTimeout = 60;
            try
            {
                sqlConnection.Open();
                sqlReader = command.ExecuteReader();
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message.ToString(), ex.Source.ToString());
            }
            finally
            {
                sqlConnection.Close();
            }
        }

        public static void EditBD(ClassImage image)
        {
            if (image.id != null)
            {
                sqlConnection = new MySqlConnection(connectionString);
                MySqlCommand command = new MySqlCommand("UPDATE table_for_save_image SET name_image=@name_image, image=@image WHERE id=@id", sqlConnection);
                command.Parameters.AddWithValue("id", image.id);
                command.Parameters.AddWithValue("name_image", image.nameImage);
                command.Parameters.AddWithValue("image", image.byteImage);
                command.CommandTimeout = 60;
                try
                {
                    sqlConnection.Open();
                    sqlReader = command.ExecuteReader();
                }
                catch (Exception ex)
                {
                    MessageBox.Show(ex.Message.ToString(), ex.Source.ToString());
                }
                finally
                {
                    sqlConnection.Close();
                }
            }
        }

        public static void DeleteBD(int id)
        {
            sqlConnection = new MySqlConnection(connectionString);
            MySqlCommand command = new MySqlCommand("DELETE FROM table_for_save_image WHERE id=@id", sqlConnection);
            command.Parameters.AddWithValue("id", id);
            command.CommandTimeout = 60;
            try
            {
                sqlConnection.Open();
                sqlReader = command.ExecuteReader();
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message.ToString(), ex.Source.ToString());
            }
            finally
            {
                sqlConnection.Close();
            }
        }
    }
}
