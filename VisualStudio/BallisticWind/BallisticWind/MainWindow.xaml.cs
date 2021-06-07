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
using System.Drawing.Printing;
using System.Drawing;
using Microsoft.Win32;
using SautinSoft.Document;
using System.Data;
using System.Collections.ObjectModel;

namespace BallisticWind
{
    /// <summary>
    /// Логика взаимодействия для MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {
        //Константы
        const double PI = 3.14159265;

        //Начальные данные
        string date = "";       //Дата проведения
        string dateTime = "";   //Время проведения

        int deltaTime = 60;     //Время между слоями
        int beginTime = 0;      //Время первого слоя

        //Начальные параметры камы
        int dKama = 0;          //Дальность
        int gKama = 0;          //Азимут (Горизонтальный угол)
        int vKama = 0;          //Угол места

        int pg = 0;             //Поправки для Камы
        int pv = 0;

        //Для начальных параметров слоёв ветра
        ObservableCollection<Wind> winds = new ObservableCollection<Wind>();

        //Для результатов расчётов
        List<Result> results = new List<Result>();

        //Коллекция для вспомогательных расчётов
        List<SecondaryRashet> srList = new List<SecondaryRashet>();

        //Стандартные высоты для баллистических расчётов
        int[] BallisticHeight = new int[37];

        //Вспомогательная коллекция для фиксации уже рассчитанной баллистической высоты
        List<int> oldBallisticHeight = new List<int>();

        //Для печати
        string result = "";

        public MainWindow()
        {
            InitializeComponent();
        }

        private void Window_Loaded(object sender, RoutedEventArgs e)
        {
            //Загружаем внешний вид формы
            LoadFormSettings();

            //Для начальных параметров слоёв ветра
            winds = new ObservableCollection<Wind>();
            dgMain.ItemsSource = winds;

            //Для результатов
            List<Result> results = new List<Result>();
            dgResult.ItemsSource = results;

            //Выбираем первый элемент в комбо боксе
            cmbKama.SelectedIndex = 0;

            //Загружаем начальные параметры
            BeginSettings();

            //Заполняем массив баллистических высот
            FillBallisticHeight();

            //Устанавливаем позицию окна (в центре)
            //this.Top = System.Windows.Forms.SystemInformation.PrimaryMonitorSize.Height / 2 - this.Height / 2;
            //this.Left = System.Windows.Forms.SystemInformation.PrimaryMonitorSize.Width / 2 - this.Width / 2;
        }

        //Изменение формы из настроек памяти приложения
        private void LoadFormSettings()
        {
            correctionKamaCanvas.Visibility = System.Windows.Visibility.Hidden;
            correction5Canvas.Visibility = System.Windows.Visibility.Hidden;
            if(Properties.Settings.Default.correctionKama == "1")
            {
                correctionKamaCanvas.Visibility = System.Windows.Visibility.Visible;
            }
            if (Properties.Settings.Default.correctionKama == "2")
            {
                correction5Canvas.Visibility = System.Windows.Visibility.Visible;
            }
        }

        //Отображение начальных параметров
        private void BeginSettings()
        {
            BeginTime();
            ShowSettings();
        }

        private void ShowSettings()
        {
            ShowTime();

            txtDeltaTime.Text = deltaTime.ToString();

            ShowKama();

            txtBeginTime.Text = beginTime.ToString();

            dgMain.ItemsSource = winds;
        }

        //Выбор расположения Камы в выпадающем списке
        private void cmbKama_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            string prSelectedName = ((ListBoxItem)cmbKama.SelectedItem).Content.ToString();
            txtKamaD.IsReadOnly = true;
            txtKamaG.IsReadOnly = true;
            txtKamaV.IsReadOnly = true;
            pv = 0;
            pg = 0;
            if (prSelectedName == "КАМА В ЦЕНТРЕ")
            {
                dKama = 331;
                gKama = 0;
                vKama = 2745;
            }
            else if (prSelectedName == "КАМА НА ХОЛМЕ")
            {
                dKama = 566;
                gKama = 34455;
                vKama = 35627;
            }
            else
            {
                dKama = 0;
                gKama = 0;
                vKama = 0;

                txtKamaD.IsReadOnly = false;
                txtKamaG.IsReadOnly = false;
                txtKamaV.IsReadOnly = false;
            }
            ShowKama();
            Rashet();
        }


        private void btnRefreshTime_Click(object sender, RoutedEventArgs e)
        {
            BeginTime();
            ShowTime();
        }

        //Заполняем текстовые поля значениями камы
        private void ShowKama()
        {
            txtKamaD.Text = dKama.ToString();
            txtKamaG.Text = gKama.ToString();
            txtKamaV.Text = vKama.ToString();

            txtKamaPV.Text = pv.ToString();
            txtKamaPG.Text = pg.ToString();
        }

        //Получаем текстовые поля даты и время от компьютера
        private void BeginTime()
        {
            date = DateTime.Now.Date.ToShortDateString();
            dateTime = DateTime.Now.TimeOfDay.Hours.ToString() + "." + DateTime.Now.TimeOfDay.Minutes.ToString("00");
        }

        private void ShowTime()
        {
            txtDate.Text = date;
            txtTime.Text = dateTime;
        }

        //Перед редактированием ячеек
        private void dgMain_BeginningEdit(object sender, DataGridBeginningEditEventArgs e)
        {
            if (txtBeginTime.Text.Length == 0 || txtBeginTime.Text == "0")
            {
                MessageBox.Show("Введите начальное время!", "Ошибка!", MessageBoxButton.OK, MessageBoxImage.Error);
                e.Cancel = true;
                txtBeginTime.Focus();
            }
        }

        //При завершении редактирования ячейки
        private void dgMain_CellEditEnding(object sender, DataGridCellEditEndingEventArgs e)
        {
            Rashet();
            //MessageBox.Show("ds");
        }

        //Смена фокуса ячеек
        private void dgMain_CurrentCellChanged(object sender, EventArgs e)
        {
            Rashet();
        }

        private void Rashet()
        {
            srList.Clear();
            winds = (ObservableCollection<Wind>)dgMain.ItemsSource;
            results = new List<Result>();
            string errorText = "Параметр Y пошёл на спад! Возможно, лопнул шарик! Слои: ";
            bool isError = false;
            for (int i = 0; i < winds.Count; i++ )
            {
                winds[i].isError = false;
                if (i == 0)
                {
                    //Нулевой слой
                    srList.Add(FindSecondaryRashet(i));

                    //Первый слой
                    srList.Add(FindSecondaryRashet(i + 1));

                    //Проверяем, что шарик взлетает
                    if (srList[i + 1].y != 0 && srList[i + 1].y < srList[i].y)
                    {
                        errorText += Convert.ToString(i + 1) + ",";
                        isError = true;
                        winds[i].isError = true;
                    }

                    //Считаем результат
                    results.Add(FindRashet(i + 1));
                }
                else
                {
                    srList.Add(FindSecondaryRashet(i + 1));
                    if (srList[i + 1].y != 0 && srList[i + 1].y < srList[i].y)
                    {
                        errorText += Convert.ToString(i + 1) + ",";
                        isError = true;
                        winds[i].isError = true;
                    }
                    results.Add(FindRashet(i + 1));
                }
                winds[i].GE = Convert.ToString(Convert.ToInt32(winds[i].G) - 500);
            }
            if (isError)
                txtErrorY.Content = errorText.TrimEnd(',');
            else
                txtErrorY.Content = "";
            FindBallisticResult();
            dgResult.ItemsSource = results;
            dgMain.ItemsSource = winds;
        }

        //Расчёт вспомогательных переменных
        private SecondaryRashet FindSecondaryRashet(int n)
        {
            SecondaryRashet sr = new SecondaryRashet();
            if (n == 0)
            {
                //pv
                sr.p = Math.Truncate(Convert.ToDouble(txtKamaPV.Text) / 100);
                sr.p1 = (Convert.ToDouble(txtKamaPV.Text) - 100 * sr.p) / 60 + sr.p;
                //pg
                sr.p3 = Math.Truncate(Convert.ToDouble(txtKamaPG.Text) / 100);
                sr.p2 = (Convert.ToDouble(txtKamaPG.Text) - 100 * sr.p3) / 60 + sr.p3;
                //v
                sr.v1 = Math.Truncate(Convert.ToDouble(txtKamaV.Text) / 100);
                sr.vk = (Convert.ToDouble(txtKamaV.Text) - 100 * sr.v1) / 60 + sr.v1;
                //g
                sr.g1 = Math.Truncate(Convert.ToDouble(txtKamaG.Text) / 100);
                sr.gk = (Convert.ToDouble(txtKamaG.Text) - 100 * sr.g1) / 60 + sr.g1;
                //vp
                sr.vp = sr.p1 + sr.vk;
                //gp
                sr.gp = sr.gk - 180 + sr.p2;    //Не вычитаем, т.к. Кама уже направлена с учётом 5 градусов
                //X, Y, Z
                sr.x = Convert.ToDouble(txtKamaD.Text) * Math.Cos(sr.vp * PI / 180) * Math.Sin(sr.gp * PI / 180);
                sr.y = Convert.ToDouble(txtKamaD.Text) * Math.Sin(sr.vp * PI / 180);
                sr.z = Convert.ToDouble(txtKamaD.Text) * Math.Cos(sr.vp * PI / 180) * Math.Cos(sr.gp * PI / 180);
            }
            else
            {
                sr.p = Math.Truncate(Convert.ToDouble(txtKamaPV.Text) / 100);
                sr.p1 = (Convert.ToDouble(txtKamaPV.Text) - 100 * sr.p) / 60 + sr.p;
                sr.p3 = Math.Truncate(Convert.ToDouble(txtKamaPG.Text) / 100);
                sr.p2 = (Convert.ToDouble(txtKamaPG.Text) - 100 * sr.p3) / 60 + sr.p3;
                sr.v1 = Math.Truncate(Convert.ToDouble(winds[n-1].V) / 100);
                sr.vk = (Convert.ToDouble(winds[n - 1].V) - 100 * sr.v1) / 60 + sr.v1;
                sr.g1 = Math.Truncate(Convert.ToDouble(winds[n - 1].G) / 100);
                sr.gk = (Convert.ToDouble(winds[n - 1].G) - 100 * sr.g1) / 60 + sr.g1;
                sr.vp = sr.p1 + sr.vk;
                if (cbCorrection5.IsChecked == true)
                    sr.gp = sr.gk - 180 + sr.p2 - 5;
                else
                    sr.gp = sr.gk - 180 + sr.p2;
                sr.x = Convert.ToDouble(winds[n - 1].D) * Math.Cos(sr.vp * PI / 180) * Math.Sin(sr.gp * PI / 180);
                sr.y = Convert.ToDouble(winds[n - 1].D) * Math.Sin(sr.vp * PI / 180);
                sr.z = Convert.ToDouble(winds[n - 1].D) * Math.Cos(sr.vp * PI / 180) * Math.Cos(sr.gp * PI / 180);
                //kx, kz
                sr.kx = (sr.x - srList[n - 1].x) * (sr.x - srList[n - 1].x);
                sr.kz = (sr.z - srList[n - 1].z) * (sr.z - srList[n - 1].z);
                //xp
                sr.xp = (sr.z - srList[n - 1].z) / Math.Sqrt(sr.kx + sr.kz);
                sr.arc = Math.Acos(sr.xp);
                sr.xi = (sr.x - srList[n - 1].x);
            }
            return sr;
        }

        //Расчёт результата (НЕ БАЛЛИСТИКА)
        private Result FindRashet(int n)
        {
            Result result = new Result();
            result.N = n;
            result.H = srList[n].y;
            if (n == 1)
            {
                result.WCR = Math.Sqrt(srList[n].kx + srList[n].kz) / Convert.ToDouble(txtBeginTime.Text);
            }
            else
            {
                result.WCR = Math.Sqrt(srList[n].kx + srList[n].kz) / Convert.ToDouble(txtDeltaTime.Text);
            }
            if (srList[n].xi > 0)
            {
                result.NWCR = 6000 * (PI + srList[n].arc) / (2 * PI);
            }
            else
            {
                result.NWCR = 6000 * (PI - srList[n].arc) / (2 * PI);
            }
            return result;
        }

        //Расчёт баллистических характеристик ветра
        private void FindBallisticResult()
        {
            oldBallisticHeight.Clear();
            for (int u = 1; u < results.Count + 1; u++ )
            {
                bool proverka = true;
                double yMax = 0;
                for (int i = 1; i < BallisticHeight.Length; i++)
                {
                    if (results[u - 1].H < BallisticHeight[i])
                    {
                        if (!oldBallisticHeight.Contains(BallisticHeight[i - 1]))
                        {
                            yMax = results[u - 1].H;
                            results[u - 1].HCT = BallisticHeight[i - 1];
                            oldBallisticHeight.Add(BallisticHeight[i - 1]);
                        }
                        else
                            proverka = false;
                        break;
                    }
                }
                if (!proverka)
                    continue;

                double c1 = 0.0;
                double c2 = 0.0;
                double c3 = 0.0;
                double c4 = 0.0;

                double[] ves = new double[results.Count];
                double[] wx = new double[results.Count];
                double[] wz = new double[results.Count];
                double[] w1 = new double[results.Count];
                double[] w2 = new double[results.Count];

                for (int i = 0; i < u; i++)
                {
                    if (i == 0)
                    {
                        ves[i] = Math.Sqrt(1 - srList[0].y / yMax) - Math.Sqrt(1 - srList[i + 1].y / yMax);
                        wx[i] = (srList[i+1].x - srList[0].x) / Convert.ToDouble(txtBeginTime.Text);
                        wz[i] = (srList[i+1].z - srList[0].z) / Convert.ToDouble(txtBeginTime.Text);
                        c3 += ves[i] * (srList[i+1].x - srList[0].x);
                        c4 += ves[i] * (srList[i+1].z - srList[0].z);
                    }
                    else
                    {
                        ves[i] = Math.Sqrt(1 - srList[i].y / yMax) - Math.Sqrt(1 - srList[i+1].y / yMax);
                        wx[i] = (srList[i+1].x - srList[i].x) / Convert.ToDouble(txtDeltaTime.Text);
                        wz[i] = (srList[i+1].z - srList[i].z) / Convert.ToDouble(txtDeltaTime.Text);
                        c3 += ves[i] * (srList[i+1].x - srList[i].x);
                        c4 += ves[i] * (srList[i+1].z - srList[i].z);
                    }
                    w1[i] = ves[i] * wx[i];
                    w2[i] = ves[i] * wz[i];
                    c1 += w1[i];
                    c2 += w2[i];
                }
                results[u - 1].WB = Math.Sqrt(Math.Pow(c1, 2) + Math.Pow(c2, 2));
                if (c3 >= 0)
                    results[u - 1].NWB = PI + Math.Acos(c4 / Math.Sqrt((Math.Pow(c3, 2) + Math.Pow(c4, 2))));
                else
                    results[u - 1].NWB = PI - Math.Acos(c4 / Math.Sqrt((Math.Pow(c3, 2) + Math.Pow(c4, 2))));
                //Преобразуем в азимутальную координату
                results[u - 1].NWB = 6000 * results[u - 1].NWB / (2 * PI);
            }
        }

        //Сдвигаем по нажатию Enter
        private void dgMain_PreviewKeyDown(object sender, KeyEventArgs e)
        {
            if (e.Key != Key.D1 && e.Key != Key.D2 && e.Key != Key.D3 && e.Key != Key.D4 && e.Key != Key.D5 && e.Key != Key.D6 && e.Key != Key.D7 && e.Key != Key.D8 && e.Key != Key.D9 && e.Key != Key.D0 &&
                e.Key != Key.NumPad0 && e.Key != Key.NumPad1 && e.Key != Key.NumPad2 && e.Key != Key.NumPad3 && e.Key != Key.NumPad4 && e.Key != Key.NumPad5 && e.Key != Key.NumPad6 && e.Key != Key.NumPad7 && e.Key != Key.NumPad8 && e.Key != Key.NumPad9 &&
                e.Key != Key.Enter && e.Key != Key.Back && e.Key != Key.Delete)
                e.Handled = true;
            var uiElement = e.OriginalSource as UIElement;
            if (e.Key == Key.Enter && uiElement != null)
            {

                if (dgMain.CurrentColumn.DisplayIndex > 1)
                    dgMain.CurrentColumn = dgMain.Columns[0];
                else
                {
                    e.Handled = true;
                    uiElement.MoveFocus(new TraversalRequest(FocusNavigationDirection.Next));
                }
            }
        }

        //Заполнение баллистических высот
        private void FillBallisticHeight()
        {
            BallisticHeight[0] = 400;
            for (int h = 0; h < 25; h++)
            {
                if (BallisticHeight[h] < 2400)
                {
                    BallisticHeight[h + 1] = BallisticHeight[h] + 400;
                }
                else if (BallisticHeight[h] == 2400)
                {
                    BallisticHeight[h + 1] = BallisticHeight[h] + 600;
                }
                else if (BallisticHeight[h] < 6000)
                {
                    BallisticHeight[h + 1] = BallisticHeight[h] + 500;
                }
                else if (BallisticHeight[h] < 30000)
                {
                    BallisticHeight[h + 1] = BallisticHeight[h] + 1000;
                }
            }
        }

        // --------- ВЕРХНЕЕ МЕНЮ ---------
        //Загрузка начальных данных
        private void btnOpenFile_Click(object sender, RoutedEventArgs e)
        {
            OpenFileDialog ofd = new OpenFileDialog();
            ofd.FileName = "";
            ofd.DefaultExt = "txt";
            ofd.Filter = "Текст|*.txt";
            ofd.Title = "Открыть документ";
            ofd.Multiselect = false;
            if (ofd.ShowDialog() == true)
            {
                try
                {
                    string data = "";
                    using (StreamReader sr = new StreamReader(ofd.FileName))
                    {
                        data = sr.ReadToEnd();
                    }
                    winds = new ObservableCollection<Wind>();
                    string[] arrStr = data.Split('\n');
                    date = arrStr[0].Replace("\r","");
                    dateTime = arrStr[1].Replace("\r", "");
                    deltaTime = Convert.ToInt32(arrStr[2].Replace("\r", ""));
                    beginTime = Convert.ToInt32(arrStr[3].Replace("\r", ""));
                    dKama = Convert.ToInt32(arrStr[4].Replace("\r", ""));
                    gKama = Convert.ToInt32(arrStr[5].Replace("\r", ""));
                    vKama = Convert.ToInt32(arrStr[6].Replace("\r", ""));
                    pv = Convert.ToInt32(arrStr[7].Replace("\r", ""));
                    pg = Convert.ToInt32(arrStr[8].Replace("\r", ""));
                    for (int i = 9; i < arrStr.Length; i++)
                    {
                        string[] windData = arrStr[i].Split(' ');
                        Wind wind = new Wind();
                        wind.D = windData[0].Replace("\r", "");
                        wind.G = windData[1].Replace("\r", "");
                        wind.V = windData[2].Replace("\r", "");
                        winds.Add(wind);
                    }
                    ShowSettings();
                    Rashet();
                }
                catch (Exception ex)
                {
                    MessageBox.Show("Не удалось загрузить файл!\n" + ex.Message, "Ошибка!", MessageBoxButton.OK, MessageBoxImage.Information);
                    winds = new ObservableCollection<Wind>();
                    BeginSettings();
                }
            }
        }

        //Сохранение отчёта
        private void btnSaveFile_Click(object sender, RoutedEventArgs e)
        {
            SaveFileDialog sfd = new SaveFileDialog();
            sfd.FileName = "";
            sfd.DefaultExt = "DOC";
            sfd.Filter = "Документ|*.DOC";
            sfd.Title = "Сохранить отчёт";
            if (sfd.ShowDialog() == true)
            {
                Rashet();
                try
                {
                    string resultSaveFile = "     " + txtDate.Text + "   ВРЕМЯ  " + txtTime.Text + "\n";
                    resultSaveFile += "     " + ((ListBoxItem)cmbKama.SelectedItem).Content.ToString() + "\n";
                    resultSaveFile += "     ПОПРАВКИ pv,pg    " + txtKamaPV.Text + "    " + txtKamaPG.Text + "\n";
                    resultSaveFile += "     НАЧАЛЬНОЕ ВРЕМЯ  " + txtBeginTime.Text + "\n";
                    resultSaveFile += "\n";
                    resultSaveFile += "        d      g      v       N     H    WCR    NWCR   HCT   WB    NWB\n";
                    resultSaveFile += "\n";
                    for (int i = 0; i < results.Count; i++)
                    {
                        string beginG = winds[i].G;
                        if (cbCorrection5.IsChecked == true)
                            beginG = winds[i].GE;
                        resultSaveFile += winds[i].D.PadLeft(11) + beginG.PadLeft(7) + winds[i].V.PadLeft(7) + Convert.ToString(results[i].N).PadLeft(6) +
                            Math.Round(results[i].H).ToString("0").PadLeft(7) + Math.Round(results[i].WCR, 1).ToString("0.0").Replace(',', '.').PadLeft(6) +
                            Math.Round(results[i].NWCR).ToString("0").PadLeft(8) +
                            (results[i].HCT != 0 ? results[i].HCT.ToString().PadLeft(6) : "      ") +
                            (results[i].WB != 0 ? Math.Round(results[i].WB, 1).ToString("0.0").Replace(',', '.').PadLeft(6) : "      ") +
                            (results[i].NWB != 0 ? Math.Round(results[i].NWB).ToString("0").PadLeft(6) : "      ") + "\n";
                    }
                    resultSaveFile += "\n";
                    resultSaveFile += "          ОПЕРАТОР  ";

                    if (Properties.Settings.Default.encodingReport == "DOS")
                    {
                        using (StreamWriter sw = new StreamWriter(sfd.FileName, false, Encoding.GetEncoding(866)))  //Для ДОСА
                        //using (StreamWriter sw = new StreamWriter(sfd.FileName, false, Encoding.GetEncoding(1251))  //Для нормального документа текущей винды
                        {
                            sw.Write(resultSaveFile);
                        }
                    }
                    else if (Properties.Settings.Default.encodingReport == "Default")
                    {
                        using (StreamWriter sw = new StreamWriter(sfd.FileName, false, Encoding.Default))
                        {
                            sw.Write(resultSaveFile);
                        }
                    }
                    else if (Properties.Settings.Default.encodingReport == "DOC")
                    {
                        DocumentCore dc = new DocumentCore();

                        //Создание новой секции
                        SautinSoft.Document.Section s = new SautinSoft.Document.Section(dc);
                        dc.Sections.Add(s);

                        //Настройка полей секции
                        s.PageSetup.PageMargins = new PageMargins()
                        {
                            Top = LengthUnitConverter.Convert(20, LengthUnit.Millimeter, LengthUnit.Point),
                            Right = LengthUnitConverter.Convert(0.926, LengthUnit.Inch, LengthUnit.Point),
                            Bottom = LengthUnitConverter.Convert(20, LengthUnit.Millimeter, LengthUnit.Point),
                            Left = LengthUnitConverter.Convert(2.35, LengthUnit.Centimeter, LengthUnit.Point)
                        };

                        //Вставляем текст
                        dc.Content.End.Insert(resultSaveFile, new CharacterFormat() { FontName = "Courier New", Size = 10.5f });

                        //Перебираем поля для редактирования интервала
                        foreach (SautinSoft.Document.Block block in dc.Sections[0].Blocks)
                        {
                            (block as SautinSoft.Document.Paragraph).ParagraphFormat.SpaceAfter = 0;
                            (block as SautinSoft.Document.Paragraph).ParagraphFormat.LineSpacing = 1;
                        }

                        //dc.Content.End.Insert(resultSaveFile, new CharacterFormat() { FontName = "Courier New", Size = 10.5f });
                        dc.Save(sfd.FileName);
                        MessageBox.Show("Отчёт сохранён");
                    }
                    else
                    {
                        MessageBox.Show("Неправильно выбрана кодировка в настройках!");
                    }
                }
                catch (Exception ex)
                {
                    MessageBox.Show("Не удалось сохранить отчёт!\n" + ex.Message, "Ошибка!", MessageBoxButton.OK, MessageBoxImage.Information);
                }
            }
        }

        //Сохранение исходных данных
        private void btnSaveBeginFile_Click(object sender, RoutedEventArgs e)
        {
            SaveFileDialog sfd = new SaveFileDialog();
            sfd.FileName = "";
            sfd.DefaultExt = "txt";
            sfd.Filter = "Текст|*.txt";
            sfd.Title = "Сохранить исходные данные";
            if (sfd.ShowDialog() == true)
            {
                Rashet();
                try
                {
                    string saveBeginData = "";
                    saveBeginData += txtDate.Text + Environment.NewLine;
                    saveBeginData += txtTime.Text + Environment.NewLine;
                    saveBeginData += txtDeltaTime.Text + Environment.NewLine;
                    saveBeginData += txtBeginTime.Text + Environment.NewLine;
                    saveBeginData += txtKamaD.Text + Environment.NewLine;
                    saveBeginData += txtKamaG.Text + Environment.NewLine;
                    saveBeginData += txtKamaV.Text + Environment.NewLine;
                    saveBeginData += txtKamaPV.Text + Environment.NewLine;
                    saveBeginData += txtKamaPG.Text + Environment.NewLine;
                    for (int i = 0; i < winds.Count; i++)
                    {
                        if (i != winds.Count - 1)
                            saveBeginData += winds[i].D + " " + winds[i].G + " " + winds[i].V + Environment.NewLine;
                        else
                            saveBeginData += winds[i].D + " " + winds[i].G + " " + winds[i].V;
                    }
                    using (StreamWriter sw = new StreamWriter(sfd.FileName))
                    {
                        sw.Write(saveBeginData);
                    }
                    MessageBox.Show("Исходные данные сохранены");
                }
                catch (Exception ex)
                {
                    MessageBox.Show("Не удалось сохранить исходные данные!\n" + ex.Message, "Ошибка!", MessageBoxButton.OK, MessageBoxImage.Information);
                }
            }
        }

        //Печать документа
        private void btnPrintFile_Click(object sender, RoutedEventArgs e)
        {
            Rashet();
            //Формируем строку печати
            result = "";
            result += "     " + txtDate.Text + "   ВРЕМЯ  " + txtTime.Text + Environment.NewLine;
            result += "     " + ((ListBoxItem)cmbKama.SelectedItem).Content.ToString() + Environment.NewLine;
            result += "     ПОПРАВКИ pv,pg    " + txtKamaPV.Text + "    " + txtKamaPG.Text + Environment.NewLine;
            result += "     НАЧАЛЬНОЕ ВРЕМЯ  " + txtBeginTime.Text + Environment.NewLine;
            result += Environment.NewLine;
            result += "        d      g      v       N     H    WCR    NWCR   HCT   WB    NWB" + Environment.NewLine;
            result += Environment.NewLine;
            for (int i = 0; i < results.Count; i++)
            {
                string beginG = winds[i].G;
                if (cbCorrection5.IsChecked == true)
                    beginG = winds[i].GE;
                result += winds[i].D.PadLeft(11) + beginG.PadLeft(7) + winds[i].V.PadLeft(7) + Convert.ToString(results[i].N).PadLeft(6) +
                    Math.Round(results[i].H).ToString("0").PadLeft(7) + Math.Round(results[i].WCR, 1).ToString("0.0").Replace(',', '.').PadLeft(6) +
                    Math.Round(results[i].NWCR).ToString("0").PadLeft(8) +
                    (results[i].HCT != 0 ? results[i].HCT.ToString().PadLeft(6) : "      ") +
                    (results[i].WB != 0 ? Math.Round(results[i].WB, 1).ToString("0.0").Replace(',', '.').PadLeft(6) : "      ") +
                    (results[i].NWB != 0 ? Math.Round(results[i].NWB).ToString("0").PadLeft(6) : "      ") + Environment.NewLine;
            }
            result += Environment.NewLine;
            result += "          ОПЕРАТОР  ";

            //Печатаем эти строки
            PrintDocument printDocument = new PrintDocument();
            printDocument.PrintPage += PrintPageHandler;
            printDocument.Print();
            MessageBox.Show("Документ отправлен на печать");
        }

        private void PrintPageHandler(object sender, PrintPageEventArgs e)
        {
            e.Graphics.DrawString(result, new Font("Courier New", 10.5f), System.Drawing.Brushes.Black, 0, 0);
        }

        //Выход из приложения
        private void btnCloseApp_Click(object sender, RoutedEventArgs e)
        {
            this.Close();
        }

        //Моделирование
        private void btn3D_Click(object sender, RoutedEventArgs e)
        {
            if (srList.Count > 0)
            {
                ModelingWindow mw = new ModelingWindow(srList);
                mw.ShowDialog();
            }
            else
                MessageBox.Show("Сначала заполните поля!");
        }

        //Настройки
        private void btnSettings_Click(object sender, RoutedEventArgs e)
        {
            //Меню настроек (включение отображения поправок, включение поправок на 5 градусов в азимутальном угле, выбор кодировки конечного файла)
            SettingsWindow sw = new SettingsWindow();
            sw.Top = this.Top + this.Height / 2 - sw.Height / 2;
            sw.Left = this.Left + this.Width / 2 - sw.Width / 2;
            sw.ShowDialog();
            LoadFormSettings();
        }

        //О Программе
        private void btnAboutTheProgramm_Click(object sender, RoutedEventArgs e)
        {
            AboutBox ab = new AboutBox();
            ab.ShowDialog();
        }

        //Перемещение по элементам формы
        private void txtDate_KeyUp(object sender, KeyEventArgs e)
        {
            if (e.Key == Key.Enter)
            {
                date = txtDate.Text;
                txtTime.SelectionStart = 0;
                txtTime.SelectionLength = txtTime.Text.Length;
                txtTime.Focus();
            }
        }

        private void txtTime_KeyUp(object sender, KeyEventArgs e)
        {
            if (e.Key == Key.Enter)
            {
                dateTime = txtTime.Text;
                txtDeltaTime.SelectionStart = 0;
                txtDeltaTime.SelectionLength = txtDeltaTime.Text.Length;
                txtDeltaTime.Focus();
            }
        }

        private void txtDeltaTime_KeyUp(object sender, KeyEventArgs e)
        {
            if (e.Key == Key.Enter)
            {
                deltaTime = Convert.ToInt32(txtDeltaTime.Text);
                txtBeginTime.SelectionStart = 0;
                txtBeginTime.SelectionLength = txtBeginTime.Text.Length;
                txtBeginTime.Focus();
                Rashet();
            }
        }

        private void txtBeginTime_KeyUp(object sender, KeyEventArgs e)
        {
            if (e.Key == Key.Enter)
            {
                beginTime = Convert.ToInt32(txtBeginTime.Text);
                txtKamaD.SelectionStart = 0;
                txtKamaD.SelectionLength = txtKamaD.Text.Length;
                txtKamaD.Focus();
                Rashet();
            }
        }

        private void txtKamaD_KeyUp(object sender, KeyEventArgs e)
        {
            if (e.Key == Key.Enter)
            {
                dKama = Convert.ToInt32(txtKamaD.Text);
                txtKamaG.SelectionStart = 0;
                txtKamaG.SelectionLength = txtKamaG.Text.Length;
                txtKamaG.Focus();
                Rashet();
            }
        }

        private void txtKamaG_KeyUp(object sender, KeyEventArgs e)
        {
            if (e.Key == Key.Enter)
            {
                gKama = Convert.ToInt32(txtKamaG.Text);
                txtKamaV.SelectionStart = 0;
                txtKamaV.SelectionLength = txtKamaV.Text.Length;
                txtKamaV.Focus();
                Rashet();
            }
        }

        private void txtKamaV_KeyUp(object sender, KeyEventArgs e)
        {
            if (e.Key == Key.Enter)
            {
                vKama = Convert.ToInt32(txtKamaV.Text);
                txtKamaPV.SelectionStart = 0;
                txtKamaPV.SelectionLength = txtKamaPV.Text.Length;
                txtKamaPV.Focus();
                Rashet();
            }
        }

        private void txtKamaPV_KeyUp(object sender, KeyEventArgs e)
        {
            if (e.Key == Key.Enter)
            {
                pv = Convert.ToInt32(txtKamaPV.Text);
                txtKamaPG.SelectionStart = 0;
                txtKamaPG.SelectionLength = txtKamaPG.Text.Length;
                txtKamaPG.Focus();
                Rashet();
            }
        }

        private void txtKamaPG_KeyUp(object sender, KeyEventArgs e)
        {
            if (e.Key == Key.Enter)
            {
                pg = Convert.ToInt32(txtKamaPG.Text);
                Rashet();
            }
        }

        //Изменение чек бокса 5 градусов
        private void cbCorrection5_Checked(object sender, RoutedEventArgs e)
        {
            Rashet();
            dgMain.Columns[3].Visibility = System.Windows.Visibility.Visible;
        }

        private void cbCorrection5_Unchecked(object sender, RoutedEventArgs e)
        {
            Rashet();
            dgMain.Columns[3].Visibility = System.Windows.Visibility.Hidden;
        }

        private void dgMain_LoadingRow(object sender, DataGridRowEventArgs e)
        {
            if (e.Row.DataContext is Wind)
            {
                if (winds[e.Row.GetIndex()].isError)
                    e.Row.Background = new SolidColorBrush(Colors.OrangeRed);
                else
                    e.Row.Background = new SolidColorBrush(Colors.White);
            }
        }

        private void dgMain_RowEditEnding(object sender, DataGridRowEditEndingEventArgs e)
        {
            if (e.Row.DataContext is Wind)
            {
                if (winds[e.Row.GetIndex()].isError)
                    e.Row.Background = new SolidColorBrush(Colors.OrangeRed);
                else
                    e.Row.Background = new SolidColorBrush(Colors.White);
            }
        }
    }
}