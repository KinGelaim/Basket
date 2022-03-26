using System.Collections.Generic;
using UnityEngine;


namespace TargemTest1
{
    public sealed class Construction : IExecute
    {
        #region Fields

        private static int _countConstruction = 0;
        private int ID = 0;

        private GameObject _centerUniverse;

        private GameObject _parentElements;
        private List<GameObject> _elements;

        private float _minSpeedMovement = 3.0f;
        private float _maxSpeedMovement = 15.0f;
        private float _speedMovement = 0.0f;
        private float _relaxDistance = 0.0f;

        private float _minSpeedRotation = 3.0f;
        private float _maxSpeedRotation = 15.0f;
        private float _speedRotation = 0.0f;

        private Vector3 _vector;
        private Quaternion _q;
        private float _angleRotation = 5;

        private bool _centerDirection = true;
        private Vector3 _targetPosition;
        private Vector3 _oldDirection;

        private float _minDistanceFromCenter = 15.0f;
        private float _maxDistanceFromCenter = 30.0f;

        private static bool _needScore = true;
        private ScoreController _scoreController;
        private int _addScoreCount = 1;

        #endregion


        #region Properties

        public Vector3 OldDirection => _oldDirection;

        #endregion


        #region LifeCycleClass

        public Construction(GameObject parent, GameObject centerUniverse, Reference reference, ScoreController scoreController)
        {
            _scoreController = scoreController;

            _centerUniverse = centerUniverse;
            _targetPosition = _centerUniverse.transform.position;

            _parentElements = new GameObject($"Construction{++_countConstruction}");
            ID = _countConstruction;
            _parentElements.transform.SetParent(parent.transform);
            _parentElements.transform.position = new Vector3(Random.Range(-20, 20), Random.Range(-20, 20));

            ElementsConsctructionFactory elementsFactory = new ElementsConsctructionFactory();
            _elements = elementsFactory.GetElements(reference, _parentElements);
            for (int i = 0; i < _elements.Count; i++)
            {
                ConstructionElement ce1 = _elements[i].GetComponent<ConstructionElement>();
                ce1.ID = ID;
                ce1.parent = this;
                ce1.OnColliderDelegate = Touch;
            }

            GeneratingNewParameters();
        }

        #endregion


        #region Methods

        private void GeneratingNewParameters()
        {
            _speedMovement = Random.Range(_minSpeedMovement, _maxSpeedMovement);
            _speedRotation = Random.Range(_minSpeedRotation, _maxSpeedRotation) * (Mathf.PI / 180);
            _vector = new Vector3(Random.Range(-_angleRotation, _angleRotation), Random.Range(-_angleRotation, _angleRotation), Random.Range(-_angleRotation, _angleRotation));
        }

        private void Touch(int ID, Vector3 oldDirection)
        {
            if (_centerDirection)
            {
                _centerDirection = false;
                var direction = Vector3.Reflect(_oldDirection, oldDirection);
                var distanceFromCenter = Random.Range(_minDistanceFromCenter, _maxDistanceFromCenter);
                _targetPosition = -direction * distanceFromCenter;

                GeneratingNewParameters();

                if (_needScore)
                {
                    _needScore = false;
                    _scoreController.OnScoreDellegate?.Invoke(_addScoreCount);
                }
            }
        }

        #endregion


        #region IExecute

        public void Execute()
        {
            _vector = _vector.normalized;
            _q = new Quaternion(Mathf.Sin(_speedRotation / 2) * _vector.x, Mathf.Sin(_speedRotation / 2) * _vector.y, Mathf.Sin(_speedRotation / 2) * _vector.z, Mathf.Cos(_speedRotation / 2));
            _parentElements.transform.rotation = _parentElements.transform.rotation * _q;

            var heading = _targetPosition - _parentElements.transform.position;
            if (heading.sqrMagnitude > _relaxDistance * _relaxDistance)
            {
                float step = _speedMovement * Time.deltaTime;
                _parentElements.transform.position = Vector3.MoveTowards(_parentElements.transform.position, _targetPosition, step);

                var distance = heading.magnitude;
                _oldDirection = heading / distance;
            }
            else
            {
                if (!_centerDirection)
                {
                    _needScore = true;
                    _centerDirection = true;
                    _targetPosition = _centerUniverse.transform.position;
                }
            }
        }

        #endregion
    }
}