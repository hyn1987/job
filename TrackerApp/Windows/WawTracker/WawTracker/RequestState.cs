using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Net;
using System.Text;
using System.Threading.Tasks;

namespace WawTracker
{
    class RequestState
    {
        // This class stores the state of the request. 
        const int BUFFER_SIZE = 1024;
        public StringBuilder requestData;
        public byte[] bufferRead;
        public WebRequest request;
        public WebResponse response;
        public Stream responseStream;
        public RequestState()
        {
            bufferRead = new byte[BUFFER_SIZE];
            requestData = new StringBuilder("");
            request = null;
            responseStream = null;
        }
    }
}
