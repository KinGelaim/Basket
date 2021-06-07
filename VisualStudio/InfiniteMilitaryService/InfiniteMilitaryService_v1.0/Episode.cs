using System;
using System.Collections.Generic;
using System.Linq;
using System.Media;
using System.Text;
using System.Threading;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Media;
using System.Windows.Media.Imaging;

namespace InfiniteMilitaryService_v1._0
{
    class Episode
    {
        private Tree.Episodes chapter;                  //Текущая часть эпизода, будет увеличиваться при переходе или присваиваться к желаемому
        private Tree.Episodes choseChapter = 0;
        private List<Screen> screenList;
        private int currentScreen;                      //Текущий экран, будет увеличиваться при переходе

        private int beginPauseSpeedText = 50;
        public int pauseSpeedText = 50;

        //Где будем всё отображать
        private Label lbl;
        private Image img;
        private TextBox txt;
        private Button btnForward;
        private Button btnYes;
        private Button btnNo;

        //Для вызова конца игры
        public Game game { get; private set; }

        //Для отслеживания процесса игры
        Tree tree;

        //Поток отображения экрана
        public static Thread screenThread;

        public Episode(Game game, Tree.Episodes chapter, Tree tree)
        {
            this.game = game;
            this.chapter = chapter;
            this.tree = tree;
            BeginSettingsEpisode();
            tree.CompleteEpisode(chapter);
        }

        //Настройка эпизода
        private bool BeginSettingsEpisode()
        {
            choseChapter = 0;
            currentScreen = 0;
            screenList = new List<Screen>();
            switch (chapter)
            {
                case Tree.Episodes.ep1:
                    return LoadScreenForEpisode1();
                case Tree.Episodes.ep2:
                    return LoadScreenForEpisode2();
                case Tree.Episodes.ep3:
                    return LoadScreenForEpisode3();
                case Tree.Episodes.ep4:
                    return LoadScreenForEpisode4();
                case Tree.Episodes.epEnd:
                    return false;
            }
            return false;
        }

        //Стартуем эпизод (в качестве параметра тот грид, где будем отображать)
        public void StartEpisode(Label lbl, Image img, TextBox txt, Button btnForward, Button btnYes, Button btnNo)
        {
            this.lbl = lbl;
            this.img = img;
            this.txt = txt;
            this.btnForward = btnForward;
            this.btnYes = btnYes;
            this.btnNo = btnNo;
            ShowEpisode();
        }

        //Отображаем эпизод
        private void ShowEpisode()
        {
            screenThread = new Thread(() =>
            {
                if (screenList[currentScreen].pathForSound != "")
                {
                    SoundPlayer sp = new SoundPlayer();
                    sp.Stream = Application.GetResourceStream(new Uri(screenList[currentScreen].pathForSound, UriKind.Relative)).Stream;
                    sp.Play();
                }
                if (screenList[currentScreen].pathForImage != "")
                {
                    img.Dispatcher.Invoke(new Action(() => img.Source = new BitmapImage(new Uri(screenList[currentScreen].pathForImage, UriKind.Relative))));
                }
                else
                {
                    img.Dispatcher.Invoke(new Action(() => img.Source = null));
                }
                lbl.Dispatcher.Invoke(new Action(() => lbl.Content = screenList[currentScreen].centerText));
                string mainText = screenList[currentScreen].author + "\n" + screenList[currentScreen].mainText;
                if (pauseSpeedText == 0)
                {
                    txt.Dispatcher.Invoke(new Action(() => txt.Text = mainText));
                }
                else
                {
                    txt.Dispatcher.Invoke(new Action(() => txt.Text = ""));
                    foreach (char ch in mainText)
                    {
                        if (pauseSpeedText == 0)
                        {
                            txt.Dispatcher.Invoke(new Action(() => txt.Text = mainText));
                            break;
                        }
                        txt.Dispatcher.Invoke(new Action(() => txt.Text += ch));
                        Thread.Sleep(pauseSpeedText);
                    }
                }
                pauseSpeedText = beginPauseSpeedText;
                if(screenList[currentScreen].isChose)
                {
                    btnYes.Dispatcher.Invoke(new Action(() => btnYes.Visibility = System.Windows.Visibility.Visible));
                    btnNo.Dispatcher.Invoke(new Action(() => btnNo.Visibility = System.Windows.Visibility.Visible));
                }
                else
                    btnForward.Dispatcher.Invoke(new Action(() => btnForward.Visibility = System.Windows.Visibility.Visible));
            });
            screenThread.Start();
        }

        //Обработка кнопок
        public void btnMainForward_Click(object sender, RoutedEventArgs e)
        {
            NextScreenInEpisode();
        }

        public void btnMainYes_Click(object sender, RoutedEventArgs e)
        {
            AnswerYesInScreen();
        }

        public void btnMainNo_Click(object sender, RoutedEventArgs e)
        {
            AnswerNoInScreen();
        }

        //Переход к следующему экрану в эпизоде
        public void NextScreenInEpisode()
        {
            btnForward.Dispatcher.Invoke(new Action(() => btnForward.Visibility = System.Windows.Visibility.Hidden));
            btnYes.Dispatcher.Invoke(new Action(() => btnYes.Visibility = System.Windows.Visibility.Hidden));
            btnNo.Dispatcher.Invoke(new Action(() => btnNo.Visibility = System.Windows.Visibility.Hidden));
            if (currentScreen + 1 < screenList.Count())
            {
                currentScreen++;
                ShowEpisode();
            }
            else
                EndEpisode();
        }

        //Конец эпизода
        private void EndEpisode()
        {
            if (choseChapter == 0)
                this.chapter++;
            else
                this.chapter = choseChapter;
            if (BeginSettingsEpisode())
            {
                tree.CompleteEpisode(chapter);
                ShowEpisode();
            }
            else
                game.EndGame();
        }

        //Ответил да
        private void AnswerYesInScreen()
        {
            if (chapter == Tree.Episodes.ep2)
                choseChapter = Tree.Episodes.ep3;
            NextScreenInEpisode();
        }

        //Ответил нет
        private void AnswerNoInScreen()
        {
            if (chapter == Tree.Episodes.ep2)
                choseChapter = Tree.Episodes.ep4;
            NextScreenInEpisode();
        }

        //Загружаем все экраны для эпизода 1
        private bool LoadScreenForEpisode1()
        {
            try
            {
                screenList.Add(new Screen("ЭПИЗОД 1\nТяжелое утро"));
                screenList.Add(new Screen("08:00 6 июня 2020 года", "ГГ:", "Голова по швам. Звук будильника говорит о том, что это конец. Конец моей гражданской жизни. Конец свободе. Конец ярким и насыщенным моментам. Эх. Пора вставать. А может не ехать? Просто забить и потеряться? Надо было косить, бежать из страны. Ладно, посмотрим, что будет дальше. Встаю.", "", "Resources\\WindowsDing.wav"));
                screenList.Add(new Screen("", "Сопровождающий:", "Вот мы и на месте. 5 минут перекур!", "Resources\\1.1.jpg"));
                screenList.Add(new Screen("", "ГГ:", "На часах 14:27 того же дня. И вот, я уже на пороге армейской жизни, армейских будней. Что же меня ждёт дальше? Какова моя судьба? А может прикинуться психом и попробовать откосить? Чёрт, голова ещё до сих пор трещит.", "Resources\\1.1.jpg"));
                screenList.Add(new Screen("", "Сопровождающий:", "Заканчиваем перекур! Заходим!", "Resources\\1.1.jpg"));
                screenList.Add(new Screen("", "ГГ:", "И вот мы зашли на КПП. Началось оформление документов, проверка сумок на наличие запрещенных и горючих веществ, ну, и томительное ожидание. Кажется, время остановилось. Ещё и пену для бритья отобрали, суки. Закрадывается мысль, что я где-то нагрешил, свернул не туда, ведь я не должен здесь находиться. Время уже 16:13, но кажется, будто прошла вечность.", "Resources\\1.2.jpg"));
                screenList.Add(new Screen("", "Сопровождающий:", "Оставляем сумки и идем проходить ВВК.", "Resources\\1.2.jpg"));
                screenList.Add(new Screen("", "ГГ:", "И вот, из кабинета в кабинет, от врача к врачу: кому-то трусы снять, кому-то жопу показать. Боже, дикость какая. Но это только начало. Скоро ужин, но аппетита особо нет. Это не назвать первым армейским приемом пищи, ведь кушать будем то, что взяли с собой.", "Resources\\1.3.jpg"));
                screenList.Add(new Screen("Спустя 2 часа. Ужин"));
                screenList.Add(new Screen("Конец первого эпизода"));
            }
            catch
            {
                return false;
            }
            return true;
        }

        //Загружаем все экраны для эпизода 2
        private bool LoadScreenForEpisode2()
        {
            try
            {
                screenList.Add(new Screen("ЭПИЗОД 2"));
                screenList.Add(new Screen("", "ГГ:", "Надоело служить! Может послать всех нахер?", "", "", true));
                screenList.Add(new Screen("Конец второго эпизода"));
            }
            catch
            {
                return false;
            }
            return true;
        }

        //Загружаем все экраны для эпизода 3
        private bool LoadScreenForEpisode3()
        {
            try
            {
                screenList.Add(new Screen("WASTED", "", "Вы послали всех нахер и всю роту заставили пахать всю ночь. Утром, когда все легли спать вас убили тенисными мячами\nP.S. ачивка смерть по неосторожности"));
                choseChapter = Tree.Episodes.epEnd;
            }
            catch
            {
                return false;
            }
            return true;
        }

        //Загружаем все экраны для эпизода 4
        private bool LoadScreenForEpisode4()
        {
            try
            {
                screenList.Add(new Screen("ЭПИЗОД 4"));
                screenList.Add(new Screen("", "ГГ", "Будет тяжело, но я постараюсь никого не посылать!"));
                screenList.Add(new Screen("Конец четвёртого эпизода"));
            }
            catch
            {
                return false;
            }
            return true;
        }
    }
}
