using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.Serialization;
using System.Text;
using System.Threading.Tasks;

namespace WawTracker.Model
{
    [DataContract]
    public class UserInfo : ResponseData
    {
        [DataMember]
        public string name;
        [DataMember]
        public string token;
        [DataMember]
        public List<Contract> contracts;
    }
}
