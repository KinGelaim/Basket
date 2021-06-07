using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Media;
using System.Windows.Shapes;

namespace InfiniteMilitaryService_v1._0
{
    [Serializable]
    class Tree
    {
        public enum Episodes
        {
            epEnd = -1,
            ep1,
            ep2,
            ep3,
            ep4
        }

        private Episodes episode;
        private Tree[] childrens;
        private bool isEnabled = false;

        public Tree() { }

        public Tree CreateTree()
        {
            Tree treeEp1 = new Tree();
            treeEp1.episode = Episodes.ep1;

            treeEp1.childrens = new Tree[1] { new Tree() { episode = Episodes.ep2 } };

            treeEp1.childrens[0].childrens = new Tree[2] { new Tree() { episode = Episodes.ep3 }, new Tree() { episode = Episodes.ep4 } };

            return treeEp1;
        }

        public void ShowTree(Grid TreeGameContainer)
        {
            TreeGameContainer.ColumnDefinitions.Clear();
            ColumnDefinition col = new ColumnDefinition();
            col.Width = new GridLength(400);
            TreeGameContainer.ColumnDefinitions.Insert(0, col);

            List<Control> prControls = GetBtnTreeControl(TreeGameContainer);

            foreach (Control control in prControls)
            {
                TreeGameContainer.Children.Remove(control);
            }

            //Строим количество колонок
            CreateGrid(TreeGameContainer);

            //Начинаем заполнять
            CreateBtn(TreeGameContainer, 0, Convert.ToInt32(TreeGameContainer.ActualHeight / 2 / childrens.Length));
        }

        public List<Control> GetBtnTreeControl(Grid TreeGameContainer)
        {
            List<Control> prControls = new List<Control>();
            foreach (var control in TreeGameContainer.Children)
            {
                if (control is Control)
                {
                    Control prControl = (Control)control;
                    if (prControl != null)
                        if (prControl.Tag != null)
                            if (prControl.Tag.ToString() == "BtnTree")
                                prControls.Add(prControl);
                }
            }
            return prControls;
        }

        private void CreateGrid(Grid TreeGameContainer)
        {
            if (childrens != null)
            {
                ColumnDefinition col = new ColumnDefinition();
                col.Width = new GridLength(400);
                TreeGameContainer.ColumnDefinitions.Insert(0, col);

                foreach (Tree tree in childrens)
                {
                    tree.CreateGrid(TreeGameContainer);
                }
            }
        }

        private void CreateBtn(Grid TreeGameContainer, int kColumnDefinitions, int margin, Button parentBtn = null)
        {
            Button btn = new Button();
            btn.Width = 300;
            btn.Height = 200;
            btn.Content = episode.ToString();
            btn.VerticalAlignment = VerticalAlignment.Top;
            btn.HorizontalAlignment = HorizontalAlignment.Left;
            btn.Margin = new Thickness(TreeGameContainer.ColumnDefinitions[kColumnDefinitions].Width.Value / 2 - btn.Width / 2, margin - btn.Height / 2, 0, 0);
            btn.IsEnabled = isEnabled;
            btn.Tag = "BtnTree";
            Grid.SetColumn(btn, kColumnDefinitions);
            TreeGameContainer.Children.Add(btn);
            if (childrens != null)
            {
                ++kColumnDefinitions;
                margin = Convert.ToInt32(TreeGameContainer.ActualHeight / 2 / childrens.Length);
                foreach (Tree tree in childrens)
                {
                    tree.CreateBtn(TreeGameContainer, kColumnDefinitions, margin, btn);
                    margin += Convert.ToInt32(TreeGameContainer.ActualHeight / childrens.Length);
                }
            }
            //Соединяем линиями
            if (parentBtn != null)
            {
                Canvas cv = new Canvas();

                Line line = new Line();
                double marginX1 = 0;
                for (int i = 0; i < Grid.GetColumn(parentBtn); i++)
                    marginX1 += TreeGameContainer.ColumnDefinitions[i].Width.Value - parentBtn.Width;
                line.X1 = parentBtn.Margin.Left + (Grid.GetColumn(parentBtn) + 1) * parentBtn.Width + marginX1;
                line.Y1 = parentBtn.Margin.Top + btn.Height / 2;
                double marginX2 = 0;
                for (int i = 0; i < Grid.GetColumn(btn); i++)
                    marginX2 += TreeGameContainer.ColumnDefinitions[i].Width.Value - btn.Width;
                line.X2 = btn.Margin.Left + (Grid.GetColumn(btn) + 1) * btn.Width + marginX2 - btn.Width;
                line.Y2 = btn.Margin.Top + btn.Height / 2;
                line.Stroke = Brushes.Black;

                cv.Children.Add(line);

                TreeGameContainer.Children.Add(cv);
            }
        }

        public void CompleteEpisode(Episodes ep)
        {
            Tree prTree = SearchTreeByEp(ep);
            if(prTree != null)
                prTree.isEnabled = true;
        }

        private Tree SearchTreeByEp(Episodes ep)
        {
            if (episode == ep)
                return this;
            if (childrens != null)
                for (int i = 0; i < childrens.Length; i++)
                {
                    Tree prTree = childrens[i].SearchTreeByEp(ep);
                    if (prTree != null)
                        return prTree;
                }
            return null;
        }
    }
}
