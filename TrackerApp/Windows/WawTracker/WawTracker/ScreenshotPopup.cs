using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace WawTracker
{
    public partial class ScreenshotPopup : Form
    {
        int count = 3;
        private MainForm mainform;
        bool isDiscard = false;
        
        public ScreenshotPopup(MainForm form)
        {
            InitializeComponent();

            mainform = form;
            this.ShowInTaskbar = false;
        }

        private void ScreenshotPopup_Load(object sender, EventArgs e)
        {
            Rectangle workArea = Screen.PrimaryScreen.WorkingArea;
            this.Location = new Point(workArea.Right - this.Width, workArea.Bottom - this.Height);
        }

        private void shotTimer_Tick(object sender, EventArgs e)
        {
            if (isDiscard)
            {
                shotTimer.Stop();
                this.Hide();
                return;
            }

            count--;
            screenshotPictureBox.CountText = count.ToString();
            screenshotPictureBox.Refresh();

            if (count == 0)
            {
                shotTimer.Stop();
                mainform.SendTimelog();
                this.Hide();
            }
        }

        public void setScreenshot(Image screenshot)
        {
            screenshotPictureBox.Image = screenshot;
        }

        public void StartDiscount()
        {
            count = 3;
            isDiscard = false;
            screenshotPictureBox.CountText = count.ToString();
            shotTimer.Start();
        }

        private void DiscardButton_Click(object sender, EventArgs e)
        {
            isDiscard = true;
            screenshotPictureBox.CountText = "Discarded";
            screenshotPictureBox.Refresh();
        }
    }
}
