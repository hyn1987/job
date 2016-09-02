using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Security.Cryptography;
using System.Text;
using System.Threading.Tasks;

namespace WawTracker
{

    class JWTEncode
    {
        private const String key = "ePOIUmH$jABFUXd#3G~~dUviV!gNd15P";
        static public string encodeJWTFromJSON(string json)
        {
            StringBuilder sBuilder = new StringBuilder();

            String jwtHeader = "{\"typ\":\"JWT\", \"alg\": \"sha256\"}";

            String encodeString = Convert.ToBase64String(Encoding.UTF8.GetBytes(jwtHeader)) + 
                "." + Convert.ToBase64String(Encoding.UTF8.GetBytes(json));

            byte[] signature;

            using (HMACSHA256 hmac = new HMACSHA256(Encoding.ASCII.GetBytes(key)))
            {
                signature = hmac.ComputeHash(Encoding.UTF8.GetBytes(encodeString));
            }

            sBuilder.Append(encodeString);
            sBuilder.Append(".");
            for (int i = 0; i < signature.Length; i++)
            {
                sBuilder.Append(signature[i].ToString("x2"));
            }

            return sBuilder.ToString();
        }
    }
}
