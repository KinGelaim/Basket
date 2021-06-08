using System;
using System.Collections.Generic;
using System.Drawing;
using System.Drawing.Drawing2D;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace MyListBox
{
    public class FirstListBox : ListBox
    {
        const int cornerRadius = 5; //Радиус скругления
        int x, y, rectWidth, rectHeight;    //Переменные для отрисовки

        public FirstListBox()
        {
            DrawMode = DrawMode.OwnerDrawVariable;
        }

        protected override void OnDrawItem(DrawItemEventArgs e)
        {
            //e - элемент с которым продолжается работа
            //Если элемента нет или список пуст - выходим
            if (e.Index <= -1 || this.Items.Count == 0) return;

            string s = Items[e.Index].ToString();   //Текст элемента

            //Формат строки для рисования
            StringFormat sf = new StringFormat();
            sf.Alignment = StringAlignment.Near;

            Brush solidBrush = new SolidBrush(Color.FromArgb(45, 131, 218));
            Brush gradientBrush = new LinearGradientBrush(e.Bounds, Color.FromArgb(221, 30, 207), Color.FromArgb(65, 151, 238), LinearGradientMode.Vertical);

            //Отрисовываем не активный элемент
            if ((e.State & DrawItemState.Focus) == 0)
            {
                e.Graphics.FillRectangle(new SolidBrush(SystemColors.Window), e.Bounds.X, e.Bounds.Y, e.Bounds.Width, e.Bounds.Height + 1);
                e.Graphics.DrawString(s, Font, new SolidBrush(SystemColors.WindowText), new RectangleF(0, e.Bounds.Y, e.Bounds.Width, 40), sf);
            }
            else
            {
                GraphicsPath gfxPath = new GraphicsPath();
                x = e.Bounds.X;
                y = e.Bounds.Y;
                rectWidth = e.Bounds.Width - 2;
                rectHeight = e.Bounds.Height;

                #region Рисуем прямоугольник с закругленными углами
                gfxPath.AddLine(x + cornerRadius, y, x + rectWidth - (cornerRadius * 2), y);
                gfxPath.AddArc(x + rectWidth - (cornerRadius * 2), y, cornerRadius * 2, cornerRadius * 2, 270, 90);
                gfxPath.AddLine(x + rectWidth, y + cornerRadius, x + rectWidth, y + rectHeight - (cornerRadius * 2));
                gfxPath.AddArc(x + rectWidth - (cornerRadius * 2), y + rectHeight - (cornerRadius * 2), cornerRadius * 2, cornerRadius * 2, 0, 90);
                gfxPath.AddLine(x + rectWidth - (cornerRadius * 2), y + rectHeight, x + cornerRadius, y + rectHeight);
                gfxPath.AddArc(x, y + rectHeight - (cornerRadius * 2), cornerRadius * 2, cornerRadius * 2, 90, 90);
                gfxPath.AddLine(x, y + rectHeight - (cornerRadius * 2), x, y + cornerRadius);
                gfxPath.AddArc(x, y, cornerRadius * 2, cornerRadius * 2, 180, 90);
                gfxPath.CloseFigure();
                e.Graphics.DrawPath(new Pen(solidBrush, 1), gfxPath);

                //Закраска области
                e.Graphics.FillPath(gradientBrush, gfxPath);
                gfxPath.Dispose();
                #endregion

                e.Graphics.DrawString(s, Font, new SolidBrush(Color.White), new RectangleF(0, e.Bounds.Y, e.Bounds.Width, 40), sf);
            }
        }

        protected override void OnSizeChanged(EventArgs e)
        {
            Refresh();
            base.OnSizeChanged(e);
        }

        protected override void OnMeasureItem(MeasureItemEventArgs e)
        {
            if (e.Index > -1)
            {
                e.ItemHeight = 40;
                e.ItemWidth = Width;
            }
        }
    }
}
