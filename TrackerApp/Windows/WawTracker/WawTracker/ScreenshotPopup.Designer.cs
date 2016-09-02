namespace WawTracker
{
    partial class ScreenshotPopup
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.components = new System.ComponentModel.Container();
            this.DiscardButton = new System.Windows.Forms.Button();
            this.shotTimer = new System.Windows.Forms.Timer(this.components);
            this.screenshotPictureBox = new WawTracker.MyPictureBox();
            this.panel1 = new System.Windows.Forms.Panel();
            ((System.ComponentModel.ISupportInitialize)(this.screenshotPictureBox)).BeginInit();
            this.SuspendLayout();
            // 
            // DiscardButton
            // 
            this.DiscardButton.Location = new System.Drawing.Point(25, 131);
            this.DiscardButton.Name = "DiscardButton";
            this.DiscardButton.Size = new System.Drawing.Size(120, 23);
            this.DiscardButton.TabIndex = 1;
            this.DiscardButton.Text = "Discard";
            this.DiscardButton.UseVisualStyleBackColor = true;
            this.DiscardButton.Click += new System.EventHandler(this.DiscardButton_Click);
            // 
            // shotTimer
            // 
            this.shotTimer.Interval = 1000;
            this.shotTimer.Tick += new System.EventHandler(this.shotTimer_Tick);
            // 
            // screenshotPictureBox
            // 
            this.screenshotPictureBox.BackColor = System.Drawing.SystemColors.Control;
            this.screenshotPictureBox.Location = new System.Drawing.Point(5, 5);
            this.screenshotPictureBox.Name = "screenshotPictureBox";
            this.screenshotPictureBox.Size = new System.Drawing.Size(160, 120);
            this.screenshotPictureBox.SizeMode = System.Windows.Forms.PictureBoxSizeMode.StretchImage;
            this.screenshotPictureBox.TabIndex = 2;
            this.screenshotPictureBox.TabStop = false;
            // 
            // panel1
            // 
            this.panel1.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle;
            this.panel1.Location = new System.Drawing.Point(0, 0);
            this.panel1.Margin = new System.Windows.Forms.Padding(0);
            this.panel1.Name = "panel1";
            this.panel1.Size = new System.Drawing.Size(170, 160);
            this.panel1.TabIndex = 3;
            // 
            // ScreenshotPopup
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(170, 160);
            this.Controls.Add(this.screenshotPictureBox);
            this.Controls.Add(this.DiscardButton);
            this.Controls.Add(this.panel1);
            this.FormBorderStyle = System.Windows.Forms.FormBorderStyle.None;
            this.Name = "ScreenshotPopup";
            this.Text = "ScreenshotPopup";
            this.TopMost = true;
            this.Load += new System.EventHandler(this.ScreenshotPopup_Load);
            ((System.ComponentModel.ISupportInitialize)(this.screenshotPictureBox)).EndInit();
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.Button DiscardButton;
        private System.Windows.Forms.Timer shotTimer;
        private MyPictureBox screenshotPictureBox;
        private System.Windows.Forms.Panel panel1;
    }
}