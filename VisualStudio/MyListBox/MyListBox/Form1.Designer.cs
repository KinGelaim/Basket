namespace MyListBox
{
    partial class Form1
    {
        /// <summary>
        /// Требуется переменная конструктора.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Освободить все используемые ресурсы.
        /// </summary>
        /// <param name="disposing">истинно, если управляемый ресурс должен быть удален; иначе ложно.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Код, автоматически созданный конструктором форм Windows

        /// <summary>
        /// Обязательный метод для поддержки конструктора - не изменяйте
        /// содержимое данного метода при помощи редактора кода.
        /// </summary>
        private void InitializeComponent()
        {
            this.firstListBox1 = new MyListBox.FirstListBox();
            this.SuspendLayout();
            // 
            // firstListBox1
            // 
            this.firstListBox1.Anchor = ((System.Windows.Forms.AnchorStyles)((((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom) 
            | System.Windows.Forms.AnchorStyles.Left) 
            | System.Windows.Forms.AnchorStyles.Right)));
            this.firstListBox1.DrawMode = System.Windows.Forms.DrawMode.OwnerDrawVariable;
            this.firstListBox1.FormattingEnabled = true;
            this.firstListBox1.Items.AddRange(new object[] {
            "1142314234 1234172835678123 ghrgwjkegjh23gkj41g2jk3 g4h12gk3 4j12gh3k j412g3uj4 1" +
                "2jkg4j12",
            "2",
            "3",
            "4",
            "5",
            "6",
            "7",
            "8",
            "9"});
            this.firstListBox1.Location = new System.Drawing.Point(12, 12);
            this.firstListBox1.Name = "firstListBox1";
            this.firstListBox1.Size = new System.Drawing.Size(260, 233);
            this.firstListBox1.TabIndex = 0;
            // 
            // Form1
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(284, 262);
            this.Controls.Add(this.firstListBox1);
            this.Name = "Form1";
            this.Text = "Form1";
            this.ResumeLayout(false);

        }

        #endregion

        private FirstListBox firstListBox1;

    }
}

