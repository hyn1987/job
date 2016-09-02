using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.Serialization;
using System.Text;
using System.Threading.Tasks;

namespace WawTracker.Model
{
    [DataContract]
    public class ResponseData
    {
        [DataMember]
        public Object error;
//        [DataMember]
//        string data;
    }
}
