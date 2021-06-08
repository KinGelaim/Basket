using System;
using System.Collections.Generic;
using System.Windows.Forms;

namespace Scanner3D
{
    public partial class FormLogs : Form
    {
        private List<Point> pointList;

        public FormLogs(List<Point> pointList)
        {
            InitializeComponent();

            this.pointList = pointList;
            CreateNodes();
        }

        private void CreateNodes()
        {
            if (pointList.Count > 0)
            {
                int idPoint = 0;
                int layer = 1;
                float lastZ = pointList[0].z;
                treeView1.Nodes.Clear();

                List<TreeNode> treeChildrens = new List<TreeNode>();

                for (int i = 0; i < pointList.Count; i++)
                {
                    if (lastZ != pointList[i].z)
                    {
                        lastZ = pointList[i].z;
                        TreeNode newNode = new TreeNode("Слой " + layer, treeChildrens.ToArray());
                        layer++;
                        treeView1.Nodes.Add(newNode);
                        treeChildrens.Clear();
                    }
                    TreeNode newPointTree = new TreeNode("d: " + pointList[i].distance + " x: " + pointList[i].x + " y: " + pointList[i].y + " z: " + pointList[i].z);
                    newPointTree.Tag = idPoint;
                    idPoint++;
                    treeChildrens.Add(newPointTree);
                }
                TreeNode newEndNode = new TreeNode("Слой " + layer, treeChildrens.ToArray());
                treeView1.Nodes.Add(newEndNode);
                treeChildrens.Clear();
            }
        }

        private void treeView1_DoubleClick(object sender, System.EventArgs e)
        {
            if (treeView1.SelectedNode.Level == 1)
            {
                ClearPointsParameters();
                pointList[Convert.ToInt32(treeView1.SelectedNode.Tag)].colorR = 1.0f;
                pointList[Convert.ToInt32(treeView1.SelectedNode.Tag)].colorG = 0.1f;
                pointList[Convert.ToInt32(treeView1.SelectedNode.Tag)].colorB = 0.1f;
                pointList[Convert.ToInt32(treeView1.SelectedNode.Tag)].size = 10.0f;
            }
        }

        private void FormLogs_FormClosing(object sender, FormClosingEventArgs e)
        {
            ClearPointsParameters();
        }

        private void ClearPointsParameters()
        {
            for (int i = 0; i < pointList.Count; i++)
            {
                pointList[i].colorR = 1.0f;
                pointList[i].colorG = 1.0f;
                pointList[i].colorB = 1.0f;
                pointList[i].size = 1.0f;
            }
        }
    }
}
