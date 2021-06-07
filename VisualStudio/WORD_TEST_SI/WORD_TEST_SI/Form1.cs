using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.IO;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using Word = Microsoft.Office.Interop.Word;

namespace WORD_TEST_SI
{
    public partial class Form1 : Form
    {
        private readonly string templateFileName = @"C:\Users\ad\Documents\Отчеты C#\ШАБЛОН СПРАВКА-ОБОСНОВАНИЯ.docx";
        private DateTime dateNowFileName;
        public Form1()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            string countEkz = textBox1.Text;
            string uchNumber = textBox2.Text;
            string month = textBox4.Text;
            string year = textBox3.Text;
            Word.Application oWord = new Word.Application();
            oWord.Visible = true;
            try
            {
                //Создание нового документа
                /*Object template = Type.Missing;
                Object newTemplate = false;
                Object documentType = Word.WdNewDocumentType.wdNewBlankDocument;
                Object visible = true;
                oWord.Documents.Add(ref template, ref newTemplate, ref documentType, ref visible);
                Word.Document oDoc = (Word.Document)oWord.Documents.get_Item(1);
                //Поля документа
                oDoc.PageSetup.LeftMargin = oWord.CentimetersToPoints((float)1.0);
                oDoc.PageSetup.TopMargin = oWord.CentimetersToPoints((float)1.0);
                oDoc.PageSetup.RightMargin = oWord.CentimetersToPoints((float)1.0);
                oDoc.PageSetup.BottomMargin = oWord.CentimetersToPoints((float)1.0);
                //Альбомная ориентация
                oDoc.PageSetup.Orientation = Word.WdOrientation.wdOrientLandscape;*/
                //Открытие документа
                Word.Document wordDocument = oWord.Documents.Open(templateFileName);
                //Поиск и замена текста
                functionReplaceInText(wordDocument, "{numberEkz}", countEkz);
                functionReplaceInText(wordDocument, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, "{month}", month);
                functionReplaceInText(wordDocument, "{year}", year);
                //Добавление в файл таблицы
                wordDocument.Paragraphs.Add();
                Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                wordDocument.Tables.Add(range, 1, 5, autoFitBehavior);
                Word.Table oTable = wordDocument.Tables[1];
                string[] nameHeaderTable = {"Код комплектующего элемента","Ед. измерения","Потребность на месяц","Наличие на складе","Испытуемый элемент","Код испытуемого элемента","Завод изготовитель","Объем испытаний"};
                for (int i = 0; i < oTable.Columns.Count; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                }
                oTable.Cell(1, 5).Split(2, 1);
                for (int i = 2; i < oTable.Rows.Count + 1; i++)
                {
                    oTable.Cell(i, 5).Split(1, 3);
                }
                for (int i = 5; i < 8; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(2, i).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                }
                int countKompEl = 4;
                int countIspEl = 2;
                for (int i = 0; i < countKompEl; i++)
                {
                    oTable.Rows.Add();
                    for (int j = 0; j < 4; j++)
                    {
                        Word.Range wordCellRange = oTable.Cell(i + 3, j + 1).Range;
                        wordCellRange.Text = "sd";
                    }
                    for (int k = 1; k < countIspEl; k++)
                    {
                        for (int j = 5; j < 8; j++)
                        {
                            Word.Range wordCellRange = oTable.Cell(i + 3, j).Range;
                            wordCellRange.Text = "5";
                        }
                        oTable.Cell(i + 3, 5).Split(2, 1);
                        oTable.Cell(i + 3, 6).Split(2, 1);
                        oTable.Cell(i + 3, 7).Split(2, 1);
                        i++;
                        countKompEl++;
                    }
                    for (int j = 5; j < 8; j++)
                    {
                        Word.Range wordCellRange = oTable.Cell(i + 3, j).Range;
                        wordCellRange.Text = "4";
                    }
                }
                //Сохранение
                dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                wordDocument.SaveAs(@"C:\Users\ad\Documents\Отчеты C#\" + strSaveName + ".docx");
                wordDocument.Close();
            }
            catch
            {
                MessageBox.Show("Ошибка!");
            }
            finally
            {
                oWord.Quit();
                this.Close();
            }
        }

        private void functionReplaceInText(Word.Document wordDocument, string findText, string replaceText)
        {
            var range = wordDocument.Content;
            range.Find.ClearFormatting();
            range.Find.Execute(FindText: findText, ReplaceWith: replaceText);
        }
    }
}
