using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.Serialization;
using System.Text;
using System.Threading.Tasks;

namespace WawTracker.Model
{
    [DataContract]
    public class Contract
    {
        [DataMember]
        public int id;
        [DataMember]
        public string title;
        [DataMember]
        public string buyer;

        public string displayTitle
        {
            get
            {
                return buyer + " - " + title;
            }
        }

        public int valueId
        {
            get
            {
                return id;
            }
        }
    }
}
