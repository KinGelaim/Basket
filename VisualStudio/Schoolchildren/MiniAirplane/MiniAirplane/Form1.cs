using System;
using System.Windows.Forms;

namespace MiniAirplane
{
    public partial class Form1 : Form
    {
        Game game;

        public Form1()
        {
            InitializeComponent();

            //Создаём объект для создания всей игры в целом
            game = new Game(this, pictureBox1, label1, 10, 50);

            //Для управления самолётом
            game.MoveAirpalaneX(20);    //Правее на 20
            game.MoveAirpalaneX(-30);   //Левее на 30
            game.MoveAirpalaneY(40);    //Ниже на 30
            game.MoveAirpalaneY(-50);   //Выше на 30

            //Для более плавной отрисовки
            this.DoubleBuffered = true;
        }

        //Старт
        private void button2_Click(object sender, EventArgs e)
        {
            game.StartGame();
        }

        //Стоп
        private void button3_Click(object sender, EventArgs e)
        {
            game.StopGame();
        }
    }
}
