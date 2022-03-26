using System.Collections.Generic;
using UnityEngine;


namespace TargemTest1
{
    public class ElementsConsctructionFactory
    {
        private int _sizeUpAndDown = 2;
        private int _maxLengthProtrude = 1;

        public List<GameObject> GetElements(Reference reference, GameObject parentElements)
        {
            List<GameObject> elements = new List<GameObject>();

            for (int i = 0 - _sizeUpAndDown; i < _sizeUpAndDown; i++)
            {
                int protrudeY = Random.Range(-_maxLengthProtrude, _maxLengthProtrude);
                int protrudeZ = Random.Range(-_maxLengthProtrude, _maxLengthProtrude);
                GameObject element = GameObject.Instantiate(reference.ExampleCube, parentElements.transform);
                float elementX = element.transform.position.x + element.transform.localScale.x * i;
                float elementY = element.transform.position.y + protrudeY;
                float elementZ = element.transform.position.z + protrudeZ;
                element.transform.position = new Vector3(elementX, elementY, elementZ);
                elements.Add(element);
            }

            for (int i = 0 - _sizeUpAndDown; i < _sizeUpAndDown; i++)
            {
                int protrudeX = Random.Range(-_maxLengthProtrude, _maxLengthProtrude);
                int protrudeZ = Random.Range(-_maxLengthProtrude, _maxLengthProtrude);
                float elementX = parentElements.transform.position.x + protrudeX;
                float elementY = parentElements.transform.position.y + reference.ExampleCube.transform.localScale.y * i;
                float elementZ = parentElements.transform.position.z + protrudeZ;
                Vector3 position = new Vector3(elementX, elementY, elementZ);
                bool check = true;
                for (int j = 0; j < elements.Count; j++)
                    if (elements[j].transform.position == position)
                        check = false;
                if (check)
                {
                    GameObject element = GameObject.Instantiate(reference.ExampleCube, parentElements.transform);
                    element.transform.position = new Vector3(elementX, elementY, elementZ);
                    elements.Add(element);
                }
            }

            for (int i = 0 - _sizeUpAndDown; i < _sizeUpAndDown; i++)
            {
                int protrudeX = Random.Range(-_maxLengthProtrude, _maxLengthProtrude);
                int protrudeY = Random.Range(-_maxLengthProtrude, _maxLengthProtrude);
                float elementX = parentElements.transform.position.x + protrudeX;
                float elementY = parentElements.transform.position.y + protrudeY;
                float elementZ = parentElements.transform.position.z + parentElements.transform.localScale.z * i;
                Vector3 position = new Vector3(elementX, elementY, elementZ);
                bool check = true;
                for (int j = 0; j < elements.Count; j++)
                    if (elements[j].transform.position == position)
                        check = false;
                if (check)
                {
                    GameObject element = GameObject.Instantiate(reference.ExampleCube, parentElements.transform);
                    element.transform.position = new Vector3(elementX, elementY, elementZ);
                    elements.Add(element);
                }
            }

            return elements;
        }
    }
}