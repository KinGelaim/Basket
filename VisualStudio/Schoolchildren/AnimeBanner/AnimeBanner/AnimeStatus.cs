using System;
using System.Collections.Generic;
using System.Linq;
using System.Reflection;
using System.Resources;
using System.Text;
using System.Windows.Forms;

namespace AnimeBanner
{
    class AnimeStatus
    {
        public static int countBanner = 0;

        static int widthMonitor = SystemInformation.PrimaryMonitorSize.Width;
        static int heightMonitor = SystemInformation.PrimaryMonitorSize.Height;
        static Random rand = new Random();

        public static Random randImage = new Random();

        public static void CallAnimeBanner(Form1 form)
        {
            try
            {
                countBanner++;
                FormAnime fa = new FormAnime();
                fa.StartPosition = System.Windows.Forms.FormStartPosition.Manual;
                fa.Top = rand.Next(10, heightMonitor - fa.Height - 10);
                fa.Left = rand.Next(10, widthMonitor - fa.Width - 10);
                form.Invoke(new Action(() => { fa.ShowDialog(form); }));
            }
            catch { }
        }
    }
}