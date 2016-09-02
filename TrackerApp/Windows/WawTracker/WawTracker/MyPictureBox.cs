using System;
using System.Collections.Generic;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace WawTracker
{
    class MyPictureBox :PictureBox
    {
        public string CountText;
        protected override void OnPaint(PaintEventArgs e)
        {
            // Call the OnPaint method of the base class.
            base.OnPaint(e);
            // Call methods of the System.Drawing.Graphics object.
            StringFormat format = new StringFormat();
            format.LineAlignment = StringAlignment.Center;
            format.Alignment = StringAlignment.Center;
            e.Graphics.DrawString(CountText, new Font("Arial", 20), new SolidBrush(Color.FromArgb(0, 139, 204)), ClientRectangle, format);
            
        } 
    }
}
