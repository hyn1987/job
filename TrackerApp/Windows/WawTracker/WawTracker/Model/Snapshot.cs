using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.Serialization;
using System.Text;
using System.Threading.Tasks;

namespace WawTracker.Model
{
    [DataContract]
    public class Snapshot
    {
        [DataMember]
        public int contract;
        [DataMember]
        public string comment;
        [DataMember]
        public string active_window;
        [DataMember]
        public Dictionary<string, Activity> activities;
    }
}
