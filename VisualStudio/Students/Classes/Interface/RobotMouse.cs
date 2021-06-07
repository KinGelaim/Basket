using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Interface
{
    class RobotMouse : IMouse, IRobot
    {
        public RobotMouse() { }

        public RobotMouse(string name, int countWheel)
        {
            
        }

        public string returnName()
        {
            throw new NotImplementedException();
        }

        public void Move()
        {
            throw new NotImplementedException();
        }
    }
}
