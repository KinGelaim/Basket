using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace FirstClass_v2._0
{
    public partial class Form1 : Form
    {
        //Логин и пароль
        private string login = "User";
        private string password = "123";

        //Коллекция для хранения информации о всех животных
        private List<Animal> animalsList = new List<Animal>();
        //Массив, который хранит все типы животных
        private string[] typeAnimalsList;

        public Form1()
        {
            InitializeComponent();

            panel1.Visible = true;
            panel2.Visible = false;

            typeAnimalsList = new String[] { "Кот", "Собака" };
            comboBox1.Items.Add("Все");
            comboBox1.Items.AddRange(typeAnimalsList);
            comboBox1.SelectedIndex = 0;
        }

        //---------------------------МЕНЮ АВТОРИЗАЦИИ---------------------------
        //Авторизация
        private void button1_Click(object sender, EventArgs e)
        {
            if (textBox1.Text == login && textBox2.Text == password)
                enterOrOut(false);
            else
                MessageBox.Show("Неверный логин или пароль!", "Ошибка!", MessageBoxButtons.OK, MessageBoxIcon.Error);
            textBox2.Text = "";
        }

        private void enterOrOut(bool logout = false)
        {
            if (!logout)
            {
                panel1.Visible = false;
                panel2.Visible = true;
                showAllAnimals();
            }
            else
            {
                panel1.Visible = true;
                panel2.Visible = false;
            }
        }

        //---------------------------ОСНОВНОЕ МЕНЮ---------------------------
        //Добавить
        private void button2_Click(object sender, EventArgs e)
        {
            FormAddNewAnimal newForm = new FormAddNewAnimal(this, typeAnimalsList);
            newForm.Show();
        }

        public void AddNewAnimal(Animal animal)
        {
            animalsList.Add(animal);
            showAllAnimals();
        }

        //Информация
        private void button3_Click(object sender, EventArgs e)
        {
            callInfo(searchAnimal());
        }

        private void callInfo(Animal animal)
        {
            if(animal != null)
                MessageBox.Show(animal.PrintAllInfo(), "Информация", MessageBoxButtons.OK, MessageBoxIcon.Information);
        }

        //Удалить
        private void button4_Click(object sender, EventArgs e)
        {
            deleteAnimal(searchAnimal());
        }

        private void deleteAnimal(Animal animal)
        {
            if (animal != null)
            {
                animalsList.Remove(animal);
                showAllAnimals();
            }
        }

        //Выйти
        private void button5_Click(object sender, EventArgs e)
        {
            enterOrOut(true);
        }

        //Отображение информации о животных в списке животных
        private void showAllAnimals()
        {
            if (animalsList.Count > 0)
            {
                listBox1.Items.Clear();
                foreach (Animal animal in animalsList)
                {
                    if (comboBox1.Items[comboBox1.SelectedIndex].ToString().Trim() == "Все")
                        listBox1.Items.Add(animal.PrintInfo());
                    else
                        if(comboBox1.Items[comboBox1.SelectedIndex].ToString().Trim() == animal.type)
                            listBox1.Items.Add(animal.PrintInfo());
                }
            }
        }

        //Смена типа животного
        private void comboBox1_SelectedIndexChanged(object sender, EventArgs e)
        {
            showAllAnimals();
        }

        //Двойной клик по списку
        private void listBox1_DoubleClick(object sender, EventArgs e)
        {
            callInfo(searchAnimal());
        }

        //Находим какое животное сейчас выделено
        private Animal searchAnimal()
        {
            try
            {
                //Если выбраны все животные, то не паримся!
                if (comboBox1.Items[comboBox1.SelectedIndex].ToString().Trim() == "Все")
                    return animalsList[listBox1.SelectedIndex];
                //А вот тут надо заморочиться слегка
                List<Animal> prAnimal = new List<Animal>();
                foreach (Animal animal in animalsList)
                {
                    if (comboBox1.Items[comboBox1.SelectedIndex].ToString().Trim() == animal.type)
                        prAnimal.Add(animal);
                }
                return prAnimal[listBox1.SelectedIndex];
            }
            catch
            {
                return null;
            }
        }
    }
}
