using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Data.SqlClient;
using System.Drawing;
using System.Drawing.Imaging;
using System.IO;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Interop;
using System.Data.Common;

namespace TestImageFromSQLServer
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            string connectionString = @"Data Source=(local)\SQLEXPRESS;Initial Catalog=t_dokum_100;Integrated Security=True;Pooling=False";
            List<Image> images = new List<Image>();
            using (SqlConnection connection = new SqlConnection(connectionString))
            {
                string sql = "SELECT TOP 1 * FROM resol";
                SqlCommand command = new SqlCommand(sql, connection);
                connection.Open();
                SqlDataReader reader = command.ExecuteReader();
                while (reader.Read())
                {
                    int id = reader.GetInt32(0);
                    int id_contract = reader.GetInt32(1);
                    byte[] data = (byte[])reader.GetValue(2);
                    int znak = reader.GetInt32(3);

                    Image image = new Image(id, id_contract, data, znak);
                    images.Add(image);
                }
            }

            //Сохраняем первый файл
            if (images.Count > 0)
            {
                int nameFile = 0;
                foreach (Image image in images)
                {
                    /*using (System.IO.FileStream fs = new System.IO.FileStream(@"D:\image\" + nameFile + ".bin", System.IO.FileMode.OpenOrCreate))
                    {
                        fs.Write(image.Data, 0, image.Data.Length);
                    }*/
                    using (System.IO.FileStream fs = new System.IO.FileStream(@"D:\image\" + image.ID + ".pdf", System.IO.FileMode.OpenOrCreate))
                    {
                        fs.Write(image.Data, 0, image.Data.Length);
                    }
                    nameFile++;
                    //MessageBox.Show(image.ID.ToString());
                }
            }

            MessageBox.Show("Готово!");
        }
    }
}
