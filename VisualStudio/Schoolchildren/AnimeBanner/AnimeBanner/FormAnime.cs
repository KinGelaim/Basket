using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Reflection;
using System.Text;
using System.Windows.Forms;

namespace AnimeBanner
{
    public partial class FormAnime : Form
    {
        public FormAnime()
        {
            InitializeComponent();
        }

        private void FormAnime_Load(object sender, EventArgs e)
        {
            pictureBox1.Image = (Image)Properties.Resources.ResourceManager.GetObject("_" + AnimeStatus.randImage.Next(1, 453));
        }
    }
}
