using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace ARMNewViewReports
{
    public partial class FormSNS : Form
    {
        private DataBaseSNS dataBaseSNS;

        public FormSNS(DataBaseSNS dataBaseSNS)
        {
            InitializeComponent();
            this.dataBaseSNS = dataBaseSNS;
            RefreshList();
        }

        //Обновление списка
        private void RefreshList()
        {
            //Сохраняем индекс текущего элемента
            int selectedIndex = listBox1.SelectedIndex;
            
            //Обновляем список
            listBox1.Items.Clear();
            for (int i = 0; i < dataBaseSNS.Count; i++)
            {
                listBox1.Items.Add(dataBaseSNS[i].code + " | " + dataBaseSNS[i].name);
            }            

            //Выбираем только что измененный элемент ИЛИ последний
            if (listBox1.Items.Count > 0)
            {
                if (listBox1.Items.Count - 1 >= selectedIndex)
                    listBox1.SelectedIndex = selectedIndex;
                else
                    listBox1.SelectedIndex = listBox1.Items.Count - 1;
            }
            
            //Количество элеметов, которые видит пользователь для создания отступа
            int visibleItems = listBox1.ClientSize.Height / listBox1.ItemHeight;
            int padding = visibleItems / 2;
            if (listBox1.SelectedIndex - padding >= 0)
                listBox1.TopIndex = listBox1.SelectedIndex - padding;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            FormSNSEdit fEdit = new FormSNSEdit();
            fEdit.ShowDialog();
            if(fEdit.isExit)
            {
                if (!dataBaseSNS.Add(fEdit.code, fEdit.name))
                    MessageBox.Show("Элемент с таким кодом уже добавлен!");
                RefreshList();
            }
        }

        private void button2_Click(object sender, EventArgs e)
        {
            if (listBox1.SelectedIndex < 0)
            {
                MessageBox.Show("Выберите элемент для редактирования!");
            }
            else
            {
                string key = listBox1.Items[listBox1.SelectedIndex].ToString().Split('|')[0].Trim();
                FormSNSEdit fEdit = new FormSNSEdit(key, dataBaseSNS[listBox1.SelectedIndex].name);
                fEdit.ShowDialog();
                if (fEdit.isExit)
                {
                    dataBaseSNS.Update(fEdit.oldCode, fEdit.code, fEdit.name);
                    RefreshList();
                }
            }
        }

        private void button3_Click(object sender, EventArgs e)
        {
            if (listBox1.SelectedIndex < 0)
            {
                MessageBox.Show("Выберите элемент для удаления!");
            }
            else
            {
                string key = listBox1.Items[listBox1.SelectedIndex].ToString().Split('|')[0].Trim();
                if (MessageBox.Show("Вы уверены, что желаете удалить элемент?", "Подтверждение", MessageBoxButtons.YesNo, MessageBoxIcon.Question) == System.Windows.Forms.DialogResult.Yes)
                {
                    dataBaseSNS.Delete(key);
                    RefreshList();
                }
            }
        }

        //Двойное нажатие
        private void listBox1_DoubleClick(object sender, EventArgs e)
        {
            if (listBox1.SelectedIndex < 0)
            {
                MessageBox.Show("Выберите элемент для редактирования!");
            }
            else
            {
                string key = listBox1.Items[listBox1.SelectedIndex].ToString().Split('|')[0].Trim();
                FormSNSEdit fEdit = new FormSNSEdit(key, dataBaseSNS[listBox1.SelectedIndex].name);
                fEdit.ShowDialog();
                if (fEdit.isExit)
                {
                    dataBaseSNS.Update(fEdit.oldCode, fEdit.code, fEdit.name);
                    RefreshList();
                }
            }
        }

        private void listBox1_KeyUp(object sender, KeyEventArgs e)
        {
            if (e.KeyCode == Keys.Delete)
            {
                if (listBox1.SelectedIndex < 0)
                {
                    MessageBox.Show("Выберите элемент для удаления!");
                }
                else
                {
                    string key = listBox1.Items[listBox1.SelectedIndex].ToString().Split('|')[0].Trim();
                    if (MessageBox.Show("Вы уверены, что желаете удалить элемент?", "Подтверждение", MessageBoxButtons.YesNo, MessageBoxIcon.Question) == System.Windows.Forms.DialogResult.Yes)
                    {
                        dataBaseSNS.Delete(key);
                        RefreshList();
                    }
                }
            }
        }
    }
}