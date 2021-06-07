using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Windows;
using System.Windows.Controls;

namespace InfiniteMilitaryService_v1._0
{
    class Game
    {
        //Ссылка на главное окно
        private MainWindow mw;

        //Отслеживание текущего эпизода
        private Tree.Episodes currentEpisode;

        //Выносим эпизод для динамики обработчика событий (зачем только?)
        private Episode episode;

        //Выносим древо, чтобы следить за процессом прохождения
        Tree tree;

        public Game(MainWindow mw)
        {
            this.mw = mw;

            mw.MainMenuContinueGame.Click += new System.Windows.RoutedEventHandler(MainMenuContinueGame_Click);
            mw.MainMenuNewGame.Click += new System.Windows.RoutedEventHandler(MainMenuStartGame_Click);
            mw.MainMenuExitGame.Click += new System.Windows.RoutedEventHandler(MainMenuExitGame_Click);
            mw.BtnTreeGameBack.Click += new RoutedEventHandler(TreeGameBtnBack_Click);

            //Загружаем, если есть сейв и заменяем пустое древо на то, что загрузили
            tree = new Tree();
            tree = SaveAndLoad.Load();
            if (tree == null)
            {
                tree = new Tree();
                tree = tree.CreateTree();
            }
        }

        //Главное меню
        public void ShowMainMenu()
        {
            mw.MainMenu.Visibility = System.Windows.Visibility.Visible;
            mw.TreeGame.Visibility = Visibility.Hidden;
            mw.MainContainer.Visibility = Visibility.Hidden;
        }

        //Продолжение игры
        private void MainMenuContinueGame_Click(object sender, RoutedEventArgs e)
        {
            ShowGameTree();
        }

        //Загрузка игры
        private void TreeLoadGame_Click(object sender, RoutedEventArgs e)
        {
              if (sender is Button)
                LoadGame(((Button)sender).Content.ToString());
        }

        //Старт новой игры
        private void MainMenuStartGame_Click(object sender, RoutedEventArgs e)
        {
            StartGame();
        }

        //Нажатие на клавишу выход в главном окне
        private void MainMenuExitGame_Click(object sender, RoutedEventArgs e)
        {
            mw.CloseWindow();
        }

        //Игровая логика
        private void LoadGame(string btnTag)
        {
            try
            {
                Tree.Episodes ep = (Tree.Episodes)Enum.Parse(typeof(Tree.Episodes), btnTag);
                this.currentEpisode = ep;
                StartEpisode();
            }
            catch { }
        }
        
        //Старт новой игры
        private void StartGame()
        {
            currentEpisode = Tree.Episodes.ep1;
            StartEpisode();
        }

        //Старт эпизода
        private void StartEpisode()
        {
            //Подгрузка информации для эпизода
            episode = new Episode(this, currentEpisode, tree);

            //Отображение экрана игры
            mw.MainMenu.Visibility = Visibility.Hidden;
            mw.TreeGame.Visibility = Visibility.Hidden;
            mw.MainContainer.Visibility = Visibility.Visible;

            //Настройки кнопки для следующего эпизода
            mw.btnMainForward.Click += new RoutedEventHandler(episode.btnMainForward_Click);
            mw.btnMainYes.Click += new RoutedEventHandler(episode.btnMainYes_Click);
            mw.btnMainNo.Click += new RoutedEventHandler(episode.btnMainNo_Click);

            //Подписка эпизода в клавиши
            ReadKeyboard.Episode = episode;

            //Старт эпизода
            episode.StartEpisode(mw.lblCenterScreen, mw.imgMainImage, mw.txtMainText, mw.btnMainForward, mw.btnMainYes, mw.btnMainNo);
        }

        //Конец игры
        public void EndGame()
        {
            mw.btnMainForward.Click -= new RoutedEventHandler(episode.btnMainForward_Click);
            mw.btnMainYes.Click -= new RoutedEventHandler(episode.btnMainYes_Click);
            mw.btnMainNo.Click -= new RoutedEventHandler(episode.btnMainNo_Click);
            ReadKeyboard.Episode = null;
            ShowMainMenu();
            SaveAndLoad.Save(tree);
        }

        //Меню с построение древа
        private void ShowGameTree()
        {
            mw.MainMenu.Visibility = Visibility.Hidden;
            mw.TreeGame.Visibility = Visibility.Visible;

            //Отображаем древо
            tree.ShowTree(mw.TreeGameContainer);

            //Навешиваем на кнопки событие
            List<Control> treeControls = tree.GetBtnTreeControl(mw.TreeGameContainer);
            foreach (Control control in treeControls)
            {
                if (control is Button)
                {
                    ((Button)control).Click += new RoutedEventHandler(TreeLoadGame_Click);
                }
            }
        }

        //Возврат из меню продолжения
        private void TreeGameBtnBack_Click(object sender, RoutedEventArgs e)
        {
            ShowMainMenu();
        }
    }
}
