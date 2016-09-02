using System;
using System.Collections.Generic;
using System.Drawing;
using System.IO;
using System.Linq;
using System.Net;
using System.Runtime.Serialization;
using System.Runtime.Serialization.Json;
using System.Text;
using System.Threading.Tasks;
using WawTracker.Model;

namespace WawTracker
{
    class WebAPI
    {
        [DataContract]
        internal class UserLogin
        {
            [DataMember]
            internal string username;

            [DataMember]
            internal string password;
        }

        public static WebRequest loginRequest(string username, string password) {
            UserLogin login = new UserLogin();
            login.username = username;
            login.password = password;

            MemoryStream stream = new MemoryStream();
            DataContractJsonSerializer ser = new DataContractJsonSerializer(typeof(UserLogin));
            ser.WriteObject(stream, login);
            stream.Position = 0;
            StreamReader sr = new StreamReader(stream);
            string jsonParam = sr.ReadToEnd();
            string jwt = JWTEncode.encodeJWTFromJSON(jsonParam);
            Console.WriteLine(jwt);

            WebRequest request = WebRequest.Create("http://www.wawjob.com/api/v1/login");
            // Set the Method property of the request to POST.
            request.Method = "POST";
            request.Headers.Add("JWT", jwt);
            // Set the ContentType property of the WebRequest.
            request.ContentType = @"application/x-www-form-urlencoded";
            // Set the ContentLength property of the WebRequest.
            request.ContentLength = 0;
            // Get the request stream. 

            return request;
        }

        public static WebRequest validRequest(string token)
        {
            string jsonParam = "{\"token\": \"" + token + "\"}";
            string jwt = JWTEncode.encodeJWTFromJSON(jsonParam);
            Console.WriteLine(jwt);

            WebRequest request = WebRequest.Create("http://www.wawjob.com/api/v1/valid");
            // Set the Method property of the request to POST.
            request.Method = "GET";
            request.Headers.Add("JWT", jwt);
            // Set the ContentType property of the WebRequest.
            request.ContentType = @"application/x-www-form-urlencoded";
            // Set the ContentLength property of the WebRequest.
            request.ContentLength = 0;
            // Get the request stream. 

            return request;
        }

        public static WebRequest syncRequest(string token)
        {
            string jsonParam = "{\"token\": \"" + token + "\"}";
            string jwt = JWTEncode.encodeJWTFromJSON(jsonParam);
            Console.WriteLine(jwt);

            WebRequest request = WebRequest.Create("http://www.wawjob.com/api/v1/sync");
            // Set the Method property of the request to POST.
            request.Method = "GET";
            request.Headers.Add("JWT", jwt);
            // Set the ContentType property of the WebRequest.
            request.ContentType = @"application/x-www-form-urlencoded";
            // Set the ContentLength property of the WebRequest.
            request.ContentLength = 0;
            // Get the request stream. 

            return request;
        }

        public static WebRequest timelogRequest(Timelog timelog, Image screenshot, UInt64 timestamp)
        {
            /*
            MemoryStream stream = new MemoryStream();
            DataContractJsonSerializer ser = new DataContractJsonSerializer(typeof(Timelog));
            ser.WriteObject(stream, timelog);
            stream.Position = 0;
            StreamReader sr = new StreamReader(stream);
            string jsonParam = sr.ReadToEnd();
            string jwt = JWTEncode.encodeJWTFromJSON(jsonParam);
            Console.WriteLine(jwt);
             */
            StringBuilder jsonBuilder = new StringBuilder();
            jsonBuilder.Append("{ \"token\" : \"" + timelog.token + "\", \"logs\": { ");
            jsonBuilder.Append("\"" + timelog.logs.First().Key + "\" : { ");
            jsonBuilder.Append("\"contract\" : \"" + timelog.logs.First().Value.contract + "\", ");
            jsonBuilder.Append("\"comment\" : \"" + timelog.logs.First().Value.comment + "\", ");
            jsonBuilder.Append("\"active_window\" : \"" + timelog.logs.First().Value.active_window + "\", ");
            jsonBuilder.Append("\"activities\" : { ");

            int count = 1;
            foreach (KeyValuePair<string, Activity> kvp in timelog.logs.First().Value.activities)
            {
                jsonBuilder.Append("\"" + kvp.Key + "\": { \"k\" : " + kvp.Value.k +", \"m\" : " + kvp.Value.m + "}");
                if (timelog.logs.First().Value.activities.Count > count)
                {
                    jsonBuilder.Append(", ");
                }
                count++;
            }
            jsonBuilder.Append("} } } }");

            string jsonParam = jsonBuilder.ToString();
            string jwt = JWTEncode.encodeJWTFromJSON(jsonParam);
            Console.WriteLine(jwt);

            string boundary = "-----WawJobTracker";
            MemoryStream body = new MemoryStream();
            byte[] imageData = Encoding.UTF8.GetBytes(String.Format("--{0}\r\nContent-Disposition: form-data; name=\"{1}\"; filename=\"{2}\"\r\nContent-Type: image/jpeg\r\n\r\n", boundary, "screenshot_"+timestamp.ToString(), "screenshot.jpg"));
            body.Write(imageData, 0, imageData.Length);
            screenshot.Save(body, System.Drawing.Imaging.ImageFormat.Jpeg);
            byte[] endData = Encoding.UTF8.GetBytes(String.Format("\r\n--{0}--\r\n", boundary));
            body.Write(endData, 0, endData.Length);


            WebRequest request = WebRequest.Create("http://www.wawjob.com/api/v1/timelog");
            // Set the Method property of the request to POST.
            request.Method = "POST";
            request.Headers.Add("JWT", jwt);
            // Set the ContentType property of the WebRequest.
            request.ContentType = @"multipart/form-data; boundary=-----WawJobTracker";
            // Set the ContentLength property of the WebRequest.
            request.ContentLength = body.Length;
            // Get the request stream. 
            Stream dataStream = request.GetRequestStream();
            // Write the data to the request stream.
            dataStream.Write(body.GetBuffer(), 0, (int)body.Length);
            // Close the Stream object.
            dataStream.Close();

            return request;
        }

        public static WebRequest logoutRequest(string token)
        {
            string jsonParam = "{\"token\": \"" + token + "\"}";
            string jwt = JWTEncode.encodeJWTFromJSON(jsonParam);
            Console.WriteLine(jwt);

            WebRequest request = WebRequest.Create("http://www.wawjob.com/api/v1/logout");
            // Set the Method property of the request to POST.
            request.Method = "GET";
            request.Headers.Add("JWT", jwt);
            // Set the ContentType property of the WebRequest.
            request.ContentType = @"application/x-www-form-urlencoded";
            // Set the ContentLength property of the WebRequest.
            request.ContentLength = 0;
            // Get the request stream. 

            return request;
        }
    }
}
