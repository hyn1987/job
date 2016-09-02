using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.IO;
using System.Linq;
using System.Net;
using System.Runtime.Serialization.Json;
using System.Text;
using System.Threading;
using System.Threading.Tasks;
using System.Windows.Forms;
using WawTracker.Model;

namespace WawTracker
{
    public partial class LoginForm : Form
    {
        delegate void SuccessLoginDelegate(UserInfo userInfo, bool valid);
        delegate void FailureLoginDelegate(ResponseData error);
        delegate void FinishLoginRequestDelegate();

        public LoginForm()
        {
            InitializeComponent();

            UsernameTextBox.Text = Properties.Settings.Default.username;
        }

        private void LoginButton_Click(object sender, EventArgs e)
        {
            try
            {
                // Create a new webrequest to the mentioned URL.   
                WebRequest request = WebAPI.loginRequest(UsernameTextBox.Text, PasswordTextBox.Text);

                // Create a new instance of the RequestState.
                RequestState myRequestState = new RequestState();
                // The 'WebRequest' object is associated to the 'RequestState' object.
                myRequestState.request = request;
                // Start the Asynchronous call for response.

                IAsyncResult asyncResult = (IAsyncResult)request.BeginGetResponse(new AsyncCallback(handleLoginResponse), myRequestState);

                UsernameTextBox.Enabled = false;
                PasswordTextBox.Enabled = false;
                LoginButton.Enabled = false;
                RememberCheckBox.Enabled = false;
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
        }

        public void SuccessLogin(UserInfo info, bool valid = false)
        {
            MainForm.isLogin = true;
            MainForm.userInfo = info;

            if (valid) {

            } else {
                Properties.Settings.Default.token = info.token;
                Properties.Settings.Default.username = UsernameTextBox.Text;
                Properties.Settings.Default.remember = RememberCheckBox.Checked;
                Properties.Settings.Default.Save();
            }

            this.DialogResult = DialogResult.OK;
            this.Close();
        }

        public void FailureLogin(ResponseData error)
        {
            MainForm.isLogin = false;
            
            if (error.error.GetType() == typeof(string))
            {
                MessageBox.Show((string)error.error);
            }

            UsernameTextBox.Enabled = true;
            PasswordTextBox.Enabled = true;
            LoginButton.Enabled = true;
            RememberCheckBox.Enabled = true;
            PasswordTextBox.Text = "";
        }

        public void handleLoginResponse(IAsyncResult asynchronousResult)
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
                Console.WriteLine("Response for Login Request");

                //StreamReader readStream = new StreamReader(responseStream);
                //string respData = readStream.ReadToEnd();
                //Console.WriteLine(respData);
                //readStream.Close();

                DataContractJsonSerializer ser = new DataContractJsonSerializer(typeof(UserInfo));
                UserInfo info = (UserInfo)ser.ReadObject(responseStream);
                responseStream.Close();

                if (info.error == null)
                {
                    SuccessLoginDelegate d = new SuccessLoginDelegate(SuccessLogin);
                    this.Invoke(d, info, false);
                }
                else
                {
                    FailureLoginDelegate d = new FailureLoginDelegate(FailureLogin);
                    this.Invoke(d, info);
                }
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

        private void LoginForm_Load(object sender, EventArgs e)
        {
            Rectangle workArea = Screen.PrimaryScreen.WorkingArea;
            this.Location = new Point(workArea.Right - this.Width, workArea.Bottom - this.Height);
        }

        private void LoginForm_Shown(object sender, EventArgs e)
        {
            if (Properties.Settings.Default.remember)
            {
                UsernameTextBox.Text = Properties.Settings.Default.username;
                PasswordTextBox.Text = "";
                RememberCheckBox.Checked = true;

                try
                {
                    // Create a new webrequest to the mentioned URL.   
                    WebRequest request = WebAPI.validRequest(Properties.Settings.Default.token);

                    // Create a new instance of the RequestState.
                    RequestState myRequestState = new RequestState();
                    // The 'WebRequest' object is associated to the 'RequestState' object.
                    myRequestState.request = request;
                    // Start the Asynchronous call for response.

                    IAsyncResult asyncResult = (IAsyncResult)request.BeginGetResponse(new AsyncCallback(handleValidResponse), myRequestState);

                    UsernameTextBox.Enabled = false;
                    PasswordTextBox.Enabled = false;
                    LoginButton.Enabled = false;
                    RememberCheckBox.Enabled = false;
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
            }
        }

        public void handleValidResponse(IAsyncResult asynchronousResult)
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
                Console.WriteLine("Response for Valid Request");

                //StreamReader readStream = new StreamReader(responseStream);
                //string respData = readStream.ReadToEnd();
                //Console.WriteLine(respData);
                //readStream.Close();

                DataContractJsonSerializer ser = new DataContractJsonSerializer(typeof(UserInfo));
                UserInfo info = (UserInfo)ser.ReadObject(responseStream);
                responseStream.Close();

                if (info.error == null)
                {
                    info.token = Properties.Settings.Default.token;
                    SuccessLoginDelegate d = new SuccessLoginDelegate(SuccessLogin);
                    this.Invoke(d, info, true);
                }
                else
                {
                    FailureLoginDelegate d = new FailureLoginDelegate(FailureLogin);
                    this.Invoke(d, info);
                }
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

    }
}
