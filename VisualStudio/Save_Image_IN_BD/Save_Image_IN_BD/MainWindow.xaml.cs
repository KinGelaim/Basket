using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Navigation;
using System.Windows.Shapes;
using System.IO;
using Microsoft.Win32;
using System.Drawing;

namespace Save_Image_IN_BD
{
    /// <summary>
    /// Логика взаимодействия для MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {
        public MainWindow()
        {
            InitializeComponent();

            ClassBD.LoadBD();
            showAllBD();
        }

        //Нажатие кнопки для загрузки нового изображения
        private void btnLoadImage_Click(object sender, RoutedEventArgs e)
        {
            OpenFileDialog opf = new OpenFileDialog();
            opf.Title = "Загрузка изображения";
            opf.Filter = "Image File|*.bmp; *.jpg; *.jpeg; *.gif; *.png;";
            if (opf.ShowDialog() == true)
            {
                if (File.Exists(opf.FileName))
                {
                    Bitmap bitmap = new Bitmap(opf.FileName);
                    mainImage.Source = BitmapToImageSource(bitmap);
                }
            }
        }

        private BitmapImage BitmapToImageSource(Bitmap bitmap)
        {
            using (MemoryStream ms = new MemoryStream())
            {
                bitmap.Save(ms, System.Drawing.Imaging.ImageFormat.Png);
                ms.Position = 0;
                BitmapImage bitImage = new BitmapImage();
                bitImage.BeginInit();
                bitImage.StreamSource = ms;
                bitImage.CacheOption = BitmapCacheOption.OnLoad;
                bitImage.EndInit();
                return bitImage;
            }
        }

        private Bitmap BitmapImage2Bitmap(BitmapImage bitmapImage)
        {
            using (MemoryStream ms = new MemoryStream())
            {
                BitmapEncoder enc = new BmpBitmapEncoder();
                enc.Frames.Add(BitmapFrame.Create(bitmapImage));
                enc.Save(ms);
                Bitmap bitmap = new Bitmap(ms);
                return new Bitmap(bitmap);
            }
        }

        private byte[] Bitmap2ByteArray(Bitmap bitmap)
        {
            byte[] dataImage;
            using (MemoryStream ms = new MemoryStream())
            {
                bitmap.Save(ms, System.Drawing.Imaging.ImageFormat.Png);
                dataImage = ms.ToArray();
            }
            return dataImage;
        }

        private Bitmap ByteArray2Bitmap(Byte[] dataImage)
        {
            Bitmap bitmap;
            using (MemoryStream ms = new MemoryStream(dataImage, 0, dataImage.Length))
            {
                ms.Write(dataImage, 0, dataImage.Length);
                bitmap = new Bitmap(ms);
            }
            return bitmap;
        }

        private Bitmap Bitmap2BadBitmap(Bitmap bitmapOld)
        {
            byte[] byteData = Bitmap2ByteArray(bitmapOld);
            Bitmap bitmapNew = new Bitmap(bitmapOld);
            while (byteData.Length > 63488)
            {
                bitmapNew = new Bitmap(bitmapNew, Convert.ToInt32(bitmapNew.Width / 1.5), Convert.ToInt32(bitmapNew.Height / 1.5));
                byteData = Bitmap2ByteArray(bitmapNew);
            }
            return bitmapNew;
        }

        //Добавить новое изображение в БД
        private void btnAdd_Click(object sender, RoutedEventArgs e)
        {
            //Извлекаем битмап из сорса контейнера Image
            BitmapImage bitmapImage = mainImage.Source as BitmapImage;
            Bitmap bitmap = BitmapImage2Bitmap(bitmapImage);

            //Ухудшаем изображение
            bitmap = Bitmap2BadBitmap(bitmap);

            //Конвертируем изображение в массив байтов
            byte[] dataImage = Bitmap2ByteArray(bitmap);

            //Создаем объект
            ClassImage newImage = new ClassImage(txtName.Text, dataImage);
            ClassBD.SaveBD(newImage);

            //Обновляем все
            ClassBD.LoadBD();
            showAllBD();
        }

        //Редактировать изображение в БД
        private void btnEdit_Click(object sender, RoutedEventArgs e)
        {
            if (mainDataGrid.SelectedItem != null)
            {
                BitmapImage bitmapImage = mainImage.Source as BitmapImage;
                Bitmap bitmap = BitmapImage2Bitmap(bitmapImage);

                bitmap = Bitmap2BadBitmap(bitmap);

                byte[] dataImage = Bitmap2ByteArray(bitmap);

                ClassImage oldImage = new ClassImage(ClassBD.imageList[mainDataGrid.SelectedIndex].id, txtName.Text, dataImage);
                ClassBD.EditBD(oldImage);

                //Обновляем все
                ClassBD.LoadBD();
                showAllBD();
            }
        }

        //Удалить изображение из БД
        private void btnDelete_Click(object sender, RoutedEventArgs e)
        {
            if (mainDataGrid.SelectedItem != null)
            {
                ClassBD.DeleteBD(ClassBD.imageList[mainDataGrid.SelectedIndex].id);

                //Обновляем все
                ClassBD.LoadBD();
                showAllBD();
            }
        }

        //Загрузка изображения в контейнер IMAGE
        private void mainDataGrid_MouseDoubleClick(object sender, MouseButtonEventArgs e)
        {
            if (mainDataGrid.SelectedItem != null)
            {
                if (ClassBD.imageList[mainDataGrid.SelectedIndex].byteImage != null)
                {
                    try
                    {
                        Bitmap bitmap = ByteArray2Bitmap(ClassBD.imageList[mainDataGrid.SelectedIndex].byteImage);
                        mainImage.Source = BitmapToImageSource(bitmap);
                    }
                    catch (Exception ex)
                    {
                        MessageBox.Show(ex.Message);
                    }
                }
            }
        }

        //Отображение базы данных в датагриде
        private void showAllBD()
        {
            mainDataGrid.Items.Clear();
            if (ClassBD.imageList.Count > 0)
            {
                foreach (ClassImage image in ClassBD.imageList)
                    mainDataGrid.Items.Add(image);
                txtName.Text = "Image " + (ClassBD.imageList.Count + 1);
            }
        }
    }
}
