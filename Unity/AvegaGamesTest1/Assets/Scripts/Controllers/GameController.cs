using System;
using UnityEngine;
using UnityEngine.SceneManagement;
using UnityEngine.UI;


namespace AvegaGamesTest1
{
    [RequireComponent(typeof(WeaponData))]
    public sealed class GameController : MonoBehaviour
    {
        #region Fields

        [Header("UI")]
        [SerializeField]
        private Text _textAmmo;
        [SerializeField]
        private Text _textHP;
        [SerializeField]
        private Text _textRed;
        [SerializeField]
        private Text _textYellow;
        [SerializeField]
        private Text _textGreen;

        [Header("Weapon Settings")]
        [SerializeField]
        private WeaponData _weaponData;

        [Header("Box")]
        [SerializeField]
        private AudioClip _takeBoxSound;


        private ListExecutableObject _listExecutableObject;

        private Reference _reference;

        #endregion


        #region UnityMethods

        private void Awake()
        {
            _reference = new Reference();
            _listExecutableObject = new ListExecutableObject();

            var gameObjects = new GameObject("GameObjects");
            var mfpsController = PlayerFactory.CreatePlayer(_reference.Player, gameObjects);

            if (_textAmmo == null)
                _textAmmo = GameObject.Find("Canvas/TextAmmo").GetComponent<Text>();
            if (_textHP == null)
                _textHP = GameObject.Find("Canvas/TextHP").GetComponent<Text>();
            if (_textRed == null)
                _textRed = GameObject.Find("Canvas/TextRed").GetComponent<Text>();
            if (_textYellow == null)
                _textYellow = GameObject.Find("Canvas/TextYellow").GetComponent<Text>();
            if (_textGreen == null)
                _textGreen = GameObject.Find("Canvas/TextGreen").GetComponent<Text>();

            var textController = new TextController(_textAmmo, _textHP, _textRed, _textYellow, _textGreen);

            var audioSource = mfpsController.GetComponentInChildren<AudioSource>();

            var spawnBulletPosition = GameObject.Find("WeaponSpawn");
            spawnBulletPosition.transform.LookAt(GameObject.Find("Canvas/Crosshair").transform);
            var weaponController = new WeaponController(mfpsController.GetComponentInChildren<FP_Input>(), _weaponData, audioSource, _reference.Bullet, spawnBulletPosition, textController);
            _listExecutableObject.AddExecuteObject(weaponController);

            var player = GameObject.Find("Player");
            PlayerController playerController = new PlayerController(textController);
            playerController.DiePlayerAction += EndGame;

            var boxController = new BoxController(_reference.Box, playerController, weaponController, audioSource, _takeBoxSound);
            _listExecutableObject.AddExecuteObject(boxController);

            var enemyController = new EnemyController(_reference.Enemy, _reference.MainCamera, player, playerController, boxController);
            _listExecutableObject.AddExecuteObject(enemyController);
        }

        private void Update()
        {
            for (int i = 0; i < _listExecutableObject.Count; i++)
            {
                var executeObject = _listExecutableObject[i];

                if (executeObject == null)
                {
                    // TODO: only log - not throw Exception
                    throw new Exception("NULL â ExecuteLIst");
                }

                executeObject.Execute();
            }
        }

        #endregion


        #region Methods

        private void EndGame()
        {
            SceneManager.LoadScene(SceneManager.GetActiveScene().name);
        }

        #endregion
    }
}