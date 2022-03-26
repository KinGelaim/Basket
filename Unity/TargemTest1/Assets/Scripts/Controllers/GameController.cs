using System;
using UnityEngine;
using UnityEngine.UI;


namespace TargemTest1
{
    public sealed class GameController : MonoBehaviour
    {
        #region Fields

        [SerializeField]
        private Text _textTime;
        [SerializeField]
        private Text _textScore;
        [SerializeField]
        private Button _btnClear;

        private ListExecutableObject _listExecutableObject;

        private Reference _reference;

        #endregion


        #region UnityMethods

        private void Awake()
        {
            if (_textTime == null)
                _textTime = GameObject.Find("Canvas/TextTimeAmount").GetComponent<Text>();
            if (_textScore == null)
                _textScore = GameObject.Find("Canvas/TextScoreAmount").GetComponent<Text>();
            if (_btnClear == null)
                _btnClear = GameObject.Find("Canvas/ButtonClear").GetComponent<Button>();

            _reference = new Reference();
            _listExecutableObject = new ListExecutableObject();

            GameObject gameObjects = new GameObject("GameObjects");
            GameObject centerUniverse = CenterUniverseFactory.CreateCenter(_reference.CenterUniverse, gameObjects);

            ScoreController scoreController = new ScoreController(_textScore);

            ConstructionController constructionController = new ConstructionController(gameObjects, centerUniverse, _reference, scoreController);
            _listExecutableObject.AddExecuteObject(constructionController);

            CameraController cameraController = new CameraController(_reference.MainCamera, centerUniverse);

            TimeController timeController = new TimeController(_textTime);
            _listExecutableObject.AddExecuteObject(timeController);

            _btnClear.onClick.AddListener(scoreController.ClearScore);
            _btnClear.onClick.AddListener(timeController.ClearTime);
        }

        private void Update()
        {
            for (int i = 0; i < _listExecutableObject.Count; i++)
            {
                var executeObject = _listExecutableObject[i];

                if (executeObject == null)
                {
                    throw new Exception("NULL â ExecuteLIst");
                }

                executeObject.Execute();
            }
        }

        #endregion
    }
}