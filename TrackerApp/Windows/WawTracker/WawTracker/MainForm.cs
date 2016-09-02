using gma.System.Windows;
using SnapShot;
using System;
using System.Collections;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.IO;
using System.Linq;
using System.Net;
using System.Runtime.Serialization.Json;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using WawTracker.Model;

namespace WawTracker
{
    public partial class MainForm : Form
    {
        delegate void SuccessSyncDelegate(UInt64 time);
        delegate void FailureSyncDelegate(ResponseData error);

        public static bool isLogin = false;
        public static UserInfo userInfo = null;

        public UInt64 timestamp;
        public UInt64 timeoffset;
        public UInt64 nextsnaptime = 0;
        
        private NotifyIcon notifyIcon;

        private int currentContract;
        private bool isTracking = false;

        UserActivityHook actionHook;
        int mouseClickCount = 0;
        int keyPressCount = 0;

        Dictionary<string, Activity> activities;

        Random randObj;

        ScreenshotPopup popup;

        Timelog timelog;
        Image screenshot;
        UInt64 synctime;

        public MainForm()
        {
            InitializeComponent();

            notifyIcon = new NotifyIcon();

            randObj = new Random();

            popup = new ScreenshotPopup(this);
        }

        private void MainForm_Load(object sender, EventArgs e)
        {
            Rectangle workArea = Screen.PrimaryScreen.WorkingArea;
            this.Location = new Point(workArea.Right - this.Width, workArea.Bottom - this.Height);

            actionHook = new UserActivityHook();
            actionHook.OnMouseActivity += new MouseEventHandler(MouseClickedHook);
            actionHook.KeyPress += new KeyPressEventHandler(KeyPressedHook);

            loginAction();
        }

        private void loginAction()
        {
            LoginForm loginForm = new LoginForm();

            if (loginForm.ShowDialog(this) == DialogResult.OK)
            {
                this.Show();
                updateUserInfoComponents();
                updateActionsStatus();
            }
            else
            {
                Application.Exit();
            }
        }

        private void updateActionsStatus()
        {
            if (MainForm.isLogin == true)
            {
                if (isTracking)
                {
                    startItem.Enabled = false;
                    stopItem.Enabled = true;
                    StartButton.Text = "Stop";
                    ContractsComboBox.Enabled = false;
                }
                else
                {
                    startItem.Enabled = true;
                    stopItem.Enabled = false;
                    StartButton.Text = "Start";
                    if (userInfo.contracts.Count > 0)
                        ContractsComboBox.Enabled = true;
                    else
                        ContractsComboBox.Enabled = false;
                }
                logoutItem.Enabled = true;
            }
            else
            {
                startItem.Enabled = false;
                stopItem.Enabled = false;
                logoutItem.Enabled = false;
            }
        }

        private void updateUserInfoComponents()
        {
            if ((MainForm.userInfo != null) && (MainForm.isLogin == true))
            {
                UsernameLabel.Text = userInfo.name;
                MemoTextBox.Text = "";

                if (userInfo.contracts.Count > 0)
                {
                    ContractsComboBox.Enabled = true;
                    ContractsComboBox.DataSource = userInfo.contracts;
                    ContractsComboBox.DisplayMember = "displayTitle";
                    ContractsComboBox.ValueMember = "valueId";
                }
                else
                {
                    ContractsComboBox.Enabled = false;
                    ContractsComboBox.DataSource = userInfo.contracts;
                    ContractsComboBox.DisplayMember = "displayTitle";
                    ContractsComboBox.ValueMember = "valueId";
                }
            }
        }

        private void MainForm_FormClosing(object sender, FormClosingEventArgs e)
        {
            if (e.CloseReason == CloseReason.UserClosing)
            {
                e.Cancel = true;
                this.WindowState = FormWindowState.Minimized;
                this.ShowInTaskbar = false;
            }
        }

        private void startItem_Click(object sender, EventArgs e)
        {

        }

        private void exitItem_Click(object sender, EventArgs e)
        {
            Application.Exit();
        }

        private void LogoutButton_Click(object sender, EventArgs e)
        {
            if (isLogin)
            {
                logoutAction();
            }
        }

        private void logoutAction()
        {
            try
            {
                // Create a new webrequest to the mentioned URL.   
                WebRequest request = WebAPI.logoutRequest(MainForm.userInfo.token);

                // Create a new instance of the RequestState.
                RequestState myRequestState = new RequestState();
                // The 'WebRequest' object is associated to the 'RequestState' object.
                myRequestState.request = request;
                // Start the Asynchronous call for response.

                IAsyncResult asyncResult = (IAsyncResult)request.BeginGetResponse(new AsyncCallback(handleLogoutResponse), myRequestState);
            }
            catch (WebException ex)
            {
                Console.WriteLine("WebException raised!");
                Console.WriteLine("\n{0}", ex.Message);
                Console.WriteLine("\n{0}", ex.Status);
            }
            catch (Exception ex)
            {
                Console.WriteLine("Exception raised!");
                Console.WriteLine("Source : " + ex.Source);
                Console.WriteLine("Message : " + ex.Message);
            }
            finally
            {
                isLogin = false;
                if (isTracking) {
                    stopTrackAction();
                }

                this.Hide();

                Properties.Settings.Default.token = "";
                Properties.Settings.Default.remember = false;
                Properties.Settings.Default.Save();

                loginAction();
            }
        }

        private void handleLogoutResponse(IAsyncResult asynchronousResult)
        {
            try
            {
                // Set the State of request to asynchronous.
                RequestState myRequestState = (RequestState)asynchronousResult.AsyncState;
                WebRequest myWebRequest = myRequestState.request;
                // End the Asynchronous response.
                myRequestState.response = myWebRequest.EndGetResponse(asynchronousResult);
                // Read the response into a 'Stream' object.
                Stream responseStream = myRequestState.response.GetResponseStream();
                myRequestState.responseStream = responseStream;
                // Begin the reading of the contents of the HTML page and print it to the console.
                Console.WriteLine("Response for Logout Request");

                StreamReader readStream = new StreamReader(responseStream);
                string respData = readStream.ReadToEnd();
                Console.WriteLine(respData);
                readStream.Close();

//                DataContractJsonSerializer ser = new DataContractJsonSerializer(typeof(UserInfo));
//                UserInfo info = (UserInfo)ser.ReadObject(responseStream);
//                responseStream.Close();
            }
            catch (WebException e)
            {
                Console.WriteLine("WebException raised!");
                Console.WriteLine("\n{0}", e.Message);
                Console.WriteLine("\n{0}", e.Status);
            }
            catch (Exception e)
            {
                Console.WriteLine("Exception raised!");
                Console.WriteLine("Source : " + e.Source);
                Console.WriteLine("Message : " + e.Message);
            }
        }

        private void StartButton_Click(object sender, EventArgs e)
        {
            if (isTracking)
            {
                isTracking = false;
                stopTrackAction();
            }
            else
            {
                isTracking = true; 
                startTrackAction();
            }
            updateActionsStatus();
        }

        private void startTrackAction()
        {
            if (!ContractsComboBox.Enabled)
            {
                isTracking = false;
                return;
            }
                
            currentContract = (int)ContractsComboBox.SelectedValue;
            try
            {
                // Create a new webrequest to the mentioned URL.   
                WebRequest request = WebAPI.syncRequest(MainForm.userInfo.token);

                // Create a new instance of the RequestState.
                RequestState myRequestState = new RequestState();
                // The 'WebRequest' object is associated to the 'RequestState' object.
                myRequestState.request = request;
                // Start the Asynchronous call for response.

                IAsyncResult asyncResult = (IAsyncResult)request.BeginGetResponse(new AsyncCallback(handleSyncResponse), myRequestState);
            }
            catch (Exception ex)
            {
                Console.WriteLine("Exception raised!");
                Console.WriteLine("Source : " + ex.Source);
                Console.WriteLine("Message : " + ex.Message);

                stopTrackAction();
            }
        }

        private void handleSyncResponse(IAsyncResult asynchronousResult)
        {
            try
            {
                // Set the State of request to asynchronous.
                RequestState myRequestState = (RequestState)asynchronousResult.AsyncState;
                WebRequest myWebRequest = myRequestState.request;
                // End the Asynchronous response.
                myRequestState.response = myWebRequest.EndGetResponse(asynchronousResult);
                // Read the response into a 'Stream' object.
                Stream responseStream = myRequestState.response.GetResponseStream();
                myRequestState.responseStream = responseStream;
                // Begin the reading of the contents of the HTML page and print it to the console.
                Console.WriteLine("Response for Sync Request");

                //StreamReader readStream = new StreamReader(responseStream);
                //string respData = readStream.ReadToEnd();
                //Console.WriteLine(respData);
                //readStream.Close();

                DataContractJsonSerializer ser = new DataContractJsonSerializer(typeof(SyncResponse));
                SyncResponse syncResp = (SyncResponse)ser.ReadObject(responseStream);
                responseStream.Close();

                if (syncResp.error == null)
                {
                    SuccessSyncDelegate d = new SuccessSyncDelegate(SuccessSync);
                    this.Invoke(d, syncResp.time);
                }
                else
                {
                    FailureSyncDelegate d = new FailureSyncDelegate(FailureSync);
                    this.Invoke(d, syncResp);
                }
            }
            catch (WebException e)
            {
                Console.WriteLine("WebException raised!");
                Console.WriteLine("\n{0}", e.Message);
                Console.WriteLine("\n{0}", e.Status);

                SyncResponse syncResp = new SyncResponse();
                syncResp.error = e.Message;
                FailureSyncDelegate d = new FailureSyncDelegate(FailureSync);
                this.Invoke(d, syncResp);
            }
            catch (Exception e)
            {
                Console.WriteLine("Exception raised!");
                Console.WriteLine("Source : " + e.Source);
                Console.WriteLine("Message : " + e.Message);
            }
        }

        public void SuccessSync(UInt64 time)
        {
            timestamp = time;
            timeoffset = 0;
            nextsnaptime = 0;

            mouseClickCount = 0;
            keyPressCount = 0;
            activities = new Dictionary<string, Activity>();

            actionHook.Start();
            
            trackTimer.Interval = 3000;
            trackTimer.Start();
        }

        public void FailureSync(ResponseData error)
        {
            MessageBox.Show((String)error.error);

            stopTrackAction();
        }

        private void stopTrackAction()
        {
            trackTimer.Stop();
            actionHook.Stop();
            isTracking = false;
            updateActionsStatus();
        }

        private void trackTimer_Tick(object sender, EventArgs e)
        {
            trackTimer.Interval = 60000;
            takeSnapshot();
        }

        private void takeSnapshot()
        {
            timeoffset += 60; // Add 60 seconds to offset

            DateTime datetime = new DateTime(1970,1,1);
            datetime = datetime.AddSeconds(timestamp + timeoffset);

            string time_key = datetime.ToString("HH:mm");
            Activity act = new Activity();
            act.k = keyPressCount; act.m = mouseClickCount;
            
            activities.Add(time_key, act);
            keyPressCount = 0; mouseClickCount = 0;

            if (timeoffset >= nextsnaptime)
            {
                synctime = timestamp + timeoffset;

                // Calculate Next time
                int mins = datetime.Minute;
                int offset = (((mins / 10) + 1) * 10 + randObj.Next(0, 9)) - mins;
                nextsnaptime = timeoffset + (UInt64)(offset * 60);

                screenshot = GetScreenShot();
                //Image thumbnail = screenshot.GetThumbnailImage(160, 120, null, new IntPtr());
                ScreenPictureBox.Image = screenshot;

                Snapshot snapshot = new Snapshot();
                snapshot.contract = currentContract;
                snapshot.comment = MemoTextBox.Text;
                snapshot.active_window = "ActiveWin";
                snapshot.activities = activities;
                activities = new Dictionary<string, Activity>();

                Dictionary<string, Snapshot> logs = new Dictionary<string,Snapshot>();
                logs.Add(synctime.ToString(), snapshot);
                timelog = new Timelog();
                timelog.token = MainForm.userInfo.token;
                timelog.logs = logs;

                //trayIcon.ShowBalloonTip(5, "Screenshot", "Just take screenshot",ToolTipIcon.Info);
                popup.setScreenshot(screenshot);
                popup.Show();
                popup.StartDiscount();
            }
        }

        public void SendTimelog()
        {
            uploadBackWorker.RunWorkerAsync();
            /*
            try
            {
                // Create a new webrequest to the mentioned URL.   
                WebRequest request = WebAPI.timelogRequest(timelog, screenshot, synctime);
                // Create a new instance of the RequestState.
                RequestState myRequestState = new RequestState();
                // The 'WebRequest' object is associated to the 'RequestState' object.
                myRequestState.request = request;
                // Start the Asynchronous call for response.
                IAsyncResult asyncResult = (IAsyncResult)request.BeginGetResponse(new AsyncCallback(handleTimelogResponse), myRequestState);
            }
            catch (Exception ex)
            {
                Console.WriteLine("Exception raised!");
                Console.WriteLine("Source : " + ex.Source);
                Console.WriteLine("Message : " + ex.Message);
            }*/
        }

        private void handleTimelogResponse(IAsyncResult asynchronousResult)
        {
            try
            {
                // Set the State of request to asynchronous.
                RequestState myRequestState = (RequestState)asynchronousResult.AsyncState;
                WebRequest myWebRequest = myRequestState.request;
                // End the Asynchronous response.
                myRequestState.response = myWebRequest.EndGetResponse(asynchronousResult);
                // Read the response into a 'Stream' object.
                Stream responseStream = myRequestState.response.GetResponseStream();
                myRequestState.responseStream = responseStream;
                // Begin the reading of the contents of the HTML page and print it to the console.
                Console.WriteLine("Response for Timelog Request");

                StreamReader readStream = new StreamReader(responseStream);
                string respData = readStream.ReadToEnd();
                Console.WriteLine(respData);
                readStream.Close();
            }
            catch (WebException e)
            {
                Console.WriteLine("WebException raised!");
                Console.WriteLine("\n{0}", e.Message);
                Console.WriteLine("\n{0}", e.Status);
            }
            catch (Exception e)
            {
                Console.WriteLine("Exception raised!");
                Console.WriteLine("Source : " + e.Source);
                Console.WriteLine("Message : " + e.Message);
            }
        }

        private Image GetScreenShot()
        {
            IntPtr windowHandle = Win32API.GetDesktopWindow();
            RECT desktopRect;
            Win32API.GetWindowRect(windowHandle, out desktopRect);

            Image myImage = new Bitmap(desktopRect.Width, desktopRect.Height);
            Graphics g = Graphics.FromImage(myImage);
            IntPtr destDeviceContext = g.GetHdc();
            IntPtr srcDeviceContext = Win32API.GetWindowDC(windowHandle);
            Win32API.BitBlt(destDeviceContext, 0, 0, desktopRect.Width, desktopRect.Height, srcDeviceContext, 0, 0, Win32API.SRCCOPY);
            Win32API.ReleaseDC(windowHandle, srcDeviceContext);
            g.ReleaseHdc(destDeviceContext);
            return myImage;
        }

        public void MouseClickedHook(object sender, MouseEventArgs e)
        {
            if (e.Clicks > 0)
            {
                mouseClickCount++;
                Console.WriteLine("MouseButton 	- " + e.Button.ToString());
            }
        }

        public void KeyPressedHook(object sender, KeyPressEventArgs e)
        {
            keyPressCount++;
            Console.WriteLine("KeyPress 	- " + e.KeyChar);
        }

        private void trayIcon_DoubleClick(object sender, EventArgs e)
        {
            this.WindowState = FormWindowState.Normal;
            this.ShowInTaskbar = true;
            this.Activate();
        }

        private void uploadBackWorker_DoWork(object sender, DoWorkEventArgs e)
        {
            try
            {
                // Create a new webrequest to the mentioned URL.   
                WebRequest request = WebAPI.timelogRequest(timelog, screenshot, synctime);
                HttpWebResponse response = (HttpWebResponse)request.GetResponse();
                // Display the status.
                Console.WriteLine("Response for Timelog Request");
                Console.WriteLine(response.StatusDescription);
                Stream responseStream = response.GetResponseStream();
                StreamReader reader = new StreamReader(responseStream);
                string respData = reader.ReadToEnd();
                Console.WriteLine(respData);
                reader.Close();
                responseStream.Close();
                response.Close();
            }
            catch (Exception ex)
            {
                Console.WriteLine("Exception raised!");
                Console.WriteLine("Source : " + ex.Source);
                Console.WriteLine("Message : " + ex.Message);
            }
        }

        private void uploadBackWorker_RunWorkerCompleted(object sender, RunWorkerCompletedEventArgs e)
        {

        }
    }
}
