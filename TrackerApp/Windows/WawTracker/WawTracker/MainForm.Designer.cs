namespace WawTracker
{
    partial class MainForm
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
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(MainForm));
            this.LogoutButton = new System.Windows.Forms.Button();
            this.UsernameLabel = new System.Windows.Forms.Label();
            this.ContractsComboBox = new System.Windows.Forms.ComboBox();
            this.MemoTextBox = new System.Windows.Forms.TextBox();
            this.label2 = new System.Windows.Forms.Label();
            this.StartButton = new System.Windows.Forms.Button();
            this.ScreenPictureBox = new System.Windows.Forms.PictureBox();
            this.trayIcon = new System.Windows.Forms.NotifyIcon(this.components);
            this.trayMenu = new System.Windows.Forms.ContextMenuStrip(this.components);
            this.startItem = new System.Windows.Forms.ToolStripMenuItem();
            this.stopItem = new System.Windows.Forms.ToolStripMenuItem();
            this.logoutItem = new System.Windows.Forms.ToolStripMenuItem();
            this.toolStripSeparator1 = new System.Windows.Forms.ToolStripSeparator();
            this.exitItem = new System.Windows.Forms.ToolStripMenuItem();
            this.trackTimer = new System.Windows.Forms.Timer(this.components);
            this.uploadBackWorker = new System.ComponentModel.BackgroundWorker();
            ((System.ComponentModel.ISupportInitialize)(this.ScreenPictureBox)).BeginInit();
            this.trayMenu.SuspendLayout();
            this.SuspendLayout();
            // 
            // LogoutButton
            // 
            this.LogoutButton.Location = new System.Drawing.Point(182, 12);
            this.LogoutButton.Name = "LogoutButton";
            this.LogoutButton.Size = new System.Drawing.Size(90, 30);
            this.LogoutButton.TabIndex = 0;
            this.LogoutButton.Text = "Logout";
            this.LogoutButton.UseVisualStyleBackColor = true;
            this.LogoutButton.Click += new System.EventHandler(this.LogoutButton_Click);
            // 
            // UsernameLabel
            // 
            this.UsernameLabel.Font = new System.Drawing.Font("Microsoft Sans Serif", 11.25F, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, ((byte)(0)));
            this.UsernameLabel.Location = new System.Drawing.Point(27, 58);
            this.UsernameLabel.Name = "UsernameLabel";
            this.UsernameLabel.Size = new System.Drawing.Size(230, 21);
            this.UsernameLabel.TabIndex = 1;
            this.UsernameLabel.Text = "Username";
            this.UsernameLabel.TextAlign = System.Drawing.ContentAlignment.MiddleLeft;
            // 
            // ContractsComboBox
            // 
            this.ContractsComboBox.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList;
            this.ContractsComboBox.FormattingEnabled = true;
            this.ContractsComboBox.Location = new System.Drawing.Point(37, 92);
            this.ContractsComboBox.Name = "ContractsComboBox";
            this.ContractsComboBox.Size = new System.Drawing.Size(210, 21);
            this.ContractsComboBox.TabIndex = 2;
            // 
            // MemoTextBox
            // 
            this.MemoTextBox.Location = new System.Drawing.Point(37, 140);
            this.MemoTextBox.Multiline = true;
            this.MemoTextBox.Name = "MemoTextBox";
            this.MemoTextBox.Size = new System.Drawing.Size(210, 90);
            this.MemoTextBox.TabIndex = 3;
            // 
            // label2
            // 
            this.label2.AutoSize = true;
            this.label2.Location = new System.Drawing.Point(34, 124);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(36, 13);
            this.label2.TabIndex = 5;
            this.label2.Text = "Memo";
            // 
            // StartButton
            // 
            this.StartButton.Location = new System.Drawing.Point(97, 250);
            this.StartButton.Name = "StartButton";
            this.StartButton.Size = new System.Drawing.Size(90, 30);
            this.StartButton.TabIndex = 6;
            this.StartButton.Text = "Start";
            this.StartButton.UseVisualStyleBackColor = true;
            this.StartButton.Click += new System.EventHandler(this.StartButton_Click);
            // 
            // ScreenPictureBox
            // 
            this.ScreenPictureBox.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle;
            this.ScreenPictureBox.Location = new System.Drawing.Point(37, 316);
            this.ScreenPictureBox.Name = "ScreenPictureBox";
            this.ScreenPictureBox.Size = new System.Drawing.Size(210, 150);
            this.ScreenPictureBox.SizeMode = System.Windows.Forms.PictureBoxSizeMode.StretchImage;
            this.ScreenPictureBox.TabIndex = 7;
            this.ScreenPictureBox.TabStop = false;
            // 
            // trayIcon
            // 
            this.trayIcon.ContextMenuStrip = this.trayMenu;
            this.trayIcon.Icon = ((System.Drawing.Icon)(resources.GetObject("trayIcon.Icon")));
            this.trayIcon.Text = "WawJob";
            this.trayIcon.Visible = true;
            this.trayIcon.DoubleClick += new System.EventHandler(this.trayIcon_DoubleClick);
            // 
            // trayMenu
            // 
            this.trayMenu.Items.AddRange(new System.Windows.Forms.ToolStripItem[] {
            this.startItem,
            this.stopItem,
            this.logoutItem,
            this.toolStripSeparator1,
            this.exitItem});
            this.trayMenu.Name = "trayMenu";
            this.trayMenu.Size = new System.Drawing.Size(113, 98);
            // 
            // startItem
            // 
            this.startItem.Name = "startItem";
            this.startItem.Size = new System.Drawing.Size(112, 22);
            this.startItem.Text = "Start";
            this.startItem.Click += new System.EventHandler(this.startItem_Click);
            // 
            // stopItem
            // 
            this.stopItem.Name = "stopItem";
            this.stopItem.Size = new System.Drawing.Size(112, 22);
            this.stopItem.Text = "Stop";
            // 
            // logoutItem
            // 
            this.logoutItem.Name = "logoutItem";
            this.logoutItem.Size = new System.Drawing.Size(112, 22);
            this.logoutItem.Text = "Logout";
            // 
            // toolStripSeparator1
            // 
            this.toolStripSeparator1.Name = "toolStripSeparator1";
            this.toolStripSeparator1.Size = new System.Drawing.Size(109, 6);
            // 
            // exitItem
            // 
            this.exitItem.Name = "exitItem";
            this.exitItem.Size = new System.Drawing.Size(112, 22);
            this.exitItem.Text = "Exit";
            this.exitItem.Click += new System.EventHandler(this.exitItem_Click);
            // 
            // trackTimer
            // 
            this.trackTimer.Interval = 60000;
            this.trackTimer.Tick += new System.EventHandler(this.trackTimer_Tick);
            // 
            // uploadBackWorker
            // 
            this.uploadBackWorker.DoWork += new System.ComponentModel.DoWorkEventHandler(this.uploadBackWorker_DoWork);
            this.uploadBackWorker.RunWorkerCompleted += new System.ComponentModel.RunWorkerCompletedEventHandler(this.uploadBackWorker_RunWorkerCompleted);
            // 
            // MainForm
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(284, 521);
            this.Controls.Add(this.ScreenPictureBox);
            this.Controls.Add(this.StartButton);
            this.Controls.Add(this.label2);
            this.Controls.Add(this.MemoTextBox);
            this.Controls.Add(this.ContractsComboBox);
            this.Controls.Add(this.UsernameLabel);
            this.Controls.Add(this.LogoutButton);
            this.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedSingle;
            this.Icon = ((System.Drawing.Icon)(resources.GetObject("$this.Icon")));
            this.MaximizeBox = false;
            this.Name = "MainForm";
            this.Text = "WawJob";
            this.FormClosing += new System.Windows.Forms.FormClosingEventHandler(this.MainForm_FormClosing);
            this.Load += new System.EventHandler(this.MainForm_Load);
            ((System.ComponentModel.ISupportInitialize)(this.ScreenPictureBox)).EndInit();
            this.trayMenu.ResumeLayout(false);
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.Button LogoutButton;
        private System.Windows.Forms.Label UsernameLabel;
        private System.Windows.Forms.ComboBox ContractsComboBox;
        private System.Windows.Forms.TextBox MemoTextBox;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.Button StartButton;
        private System.Windows.Forms.PictureBox ScreenPictureBox;
        private System.Windows.Forms.NotifyIcon trayIcon;
        private System.Windows.Forms.ContextMenuStrip trayMenu;
        private System.Windows.Forms.ToolStripMenuItem startItem;
        private System.Windows.Forms.ToolStripMenuItem stopItem;
        private System.Windows.Forms.ToolStripMenuItem logoutItem;
        private System.Windows.Forms.ToolStripSeparator toolStripSeparator1;
        private System.Windows.Forms.ToolStripMenuItem exitItem;
        private System.Windows.Forms.Timer trackTimer;
        private System.ComponentModel.BackgroundWorker uploadBackWorker;
    }
}

