using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.Threading;

namespace SearchImage
{
    public partial class Form1 : Form
    {
        private Bitmap raccoonBitmap;
        private Bitmap catBitmap;
        private Bitmap dogBitmap;
        private Bitmap frogBitmap;
        private Bitmap rabbitBitmap;
        private Bitmap beeBitmap;
        private Bitmap birdBitmap;
        private Bitmap owlBitmap;
        private Bitmap borderBitmap;

        List<AnimalImage> animalList = new List<AnimalImage>();
        List<AnimalImage> allAnimalList = new List<AnimalImage>();

        Graphics g;
        Bitmap bitmap;

        private AnimalImage firstAnimal = null; //Текущее выбранное животное (первое)
        private AnimalImage secondAnimal = null; //Текущее выбранное животное (второе)

        //Для синхронизации потоков
        object locker = new object();

        public Form1()
        {
            InitializeComponent();

            bitmap = new Bitmap(pictureBox1.Width, pictureBox1.Height);
            g = Graphics.FromImage(bitmap);

            if (LoadImage())
            {
                StartGame();
            }
        }

        //Загрузка изображений
        private bool LoadImage()
        {
            try
            {
                raccoonBitmap = new Bitmap(Properties.Resources.raccoon);
                catBitmap = new Bitmap(Properties.Resources.cat);
                dogBitmap = new Bitmap(Properties.Resources.dog);
                frogBitmap = new Bitmap(Properties.Resources.frog);
                rabbitBitmap = new Bitmap(Properties.Resources.rabbit);
                beeBitmap = new Bitmap(Properties.Resources.bee);
                birdBitmap = new Bitmap(Properties.Resources.bird);
                owlBitmap = new Bitmap(Properties.Resources.owl);
                borderBitmap = new Bitmap(Properties.Resources.border);
                return true;
            }
            catch
            {
                return false;
            }
        }

        //Старт игры
        private void StartGame()
        {
            firstAnimal = null;
            secondAnimal = null;
            locker = new object();
            CreateAnimalList();
            ShowImage();
        }

        private void CreateAnimalList()
        {
            Random rand = new Random();

            animalList.Clear();
            allAnimalList.Clear();

            allAnimalList.Add(new AnimalImage(raccoonBitmap, AnimalImage.TypeAnimal.raccoon));
            allAnimalList.Add(new AnimalImage(raccoonBitmap, AnimalImage.TypeAnimal.raccoon));
            allAnimalList.Add(new AnimalImage(catBitmap, AnimalImage.TypeAnimal.cat));
            allAnimalList.Add(new AnimalImage(catBitmap, AnimalImage.TypeAnimal.cat));
            allAnimalList.Add(new AnimalImage(dogBitmap, AnimalImage.TypeAnimal.dog));
            allAnimalList.Add(new AnimalImage(dogBitmap, AnimalImage.TypeAnimal.dog));
            allAnimalList.Add(new AnimalImage(frogBitmap, AnimalImage.TypeAnimal.frog));
            allAnimalList.Add(new AnimalImage(frogBitmap, AnimalImage.TypeAnimal.frog));
            allAnimalList.Add(new AnimalImage(rabbitBitmap, AnimalImage.TypeAnimal.rabbit));
            allAnimalList.Add(new AnimalImage(rabbitBitmap, AnimalImage.TypeAnimal.rabbit));
            allAnimalList.Add(new AnimalImage(beeBitmap, AnimalImage.TypeAnimal.bee));
            allAnimalList.Add(new AnimalImage(beeBitmap, AnimalImage.TypeAnimal.bee));
            allAnimalList.Add(new AnimalImage(birdBitmap, AnimalImage.TypeAnimal.bird));
            allAnimalList.Add(new AnimalImage(birdBitmap, AnimalImage.TypeAnimal.bird));
            allAnimalList.Add(new AnimalImage(owlBitmap, AnimalImage.TypeAnimal.owl));
            allAnimalList.Add(new AnimalImage(owlBitmap, AnimalImage.TypeAnimal.owl));

            int posX = 0;
            int posY = 0;
            for (int i = 0; i < 4; i++)
            {
                for (int j = 0; j < 4; j++)
                {
                    int pos = rand.Next(0, allAnimalList.Count - 1);
                    AnimalImage newAnimal = new AnimalImage(posX, posY, new Bitmap(allAnimalList[pos].bitmap, pictureBox1.Width /4, pictureBox1.Height / 4), allAnimalList[pos].typeAnimal);
                    animalList.Add(newAnimal);
                    allAnimalList.RemoveAt(pos);
                    posX += pictureBox1.Width / 4;
                }
                posX = 0;
                posY += pictureBox1.Height / 4;
            }
        }

        //Отображаем рисунки
        private void ShowImage()
        {
            lock (locker)
            {
                if (animalList.Count > 0)
                {
                    foreach (AnimalImage animal in animalList)
                    {
                        if (!animal.isHide)
                            g.DrawImage(animal.bitmap, animal.posX, animal.posY);
                        else
                            g.DrawImage(borderBitmap, animal.posX, animal.posY);
                    }
                    pictureBox1.Image = bitmap;
                }
            }
        }

        //Нажимаем мышкой по изображению
        private void pictureBox1_MouseClick(object sender, MouseEventArgs e)
        {
            if (animalList.Count > 0)
            {
                foreach (AnimalImage animal in animalList)
                {
                    if (e.X > animal.posX && e.X < animal.posX + animal.bitmap.Width
                        && e.Y > animal.posY && e.Y < animal.posY + animal.bitmap.Height)
                    {
                        if (animal.isHide)
                        {
                            animal.isHide = false;
                            if (firstAnimal == null)
                                firstAnimal = animal;
                            else
                                secondAnimal = animal;
                        }
                    }
                }
            }
            ShowImage();
            //MessageBox.Show("ds");
            new Thread(() => Check()).Start();
        }

        //Функция для проверки на совпадение между двумя изображениями
        private void Check()
        {
            Thread.Sleep(500);
            if (secondAnimal != null)
            {
                if (firstAnimal.typeAnimal != secondAnimal.typeAnimal)
                {
                    firstAnimal.isHide = true;
                    secondAnimal.isHide = true;
                }
                firstAnimal = null;
                secondAnimal = null;
            }
            ShowImage();
        }

        private void newGameToolStripMenuItem_Click(object sender, EventArgs e)
        {
            StartGame();
        }
    }
}
