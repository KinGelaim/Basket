using System.Collections;
using UnityEngine;
using UnityEngine.SceneManagement;
using UnityEngine.UI;


namespace SwiftPickaxe
{
    public class SceneLoading : MonoBehaviour
    {
        #region Fields

        [Header("Index loading scene")]
        [SerializeField] private int _sceneID;

        [Header("Object loading")]
        [SerializeField] private Image _loadingImage;
        [SerializeField] private Text _progressText;

        #endregion


        #region Methods

        public void SetSceneID(int indexScene)
        {
            _sceneID = indexScene;
        }

        public void LoadScene()
        {
            StartCoroutine(AsyncLoad());
            Time.timeScale = 1;
        }

        private IEnumerator AsyncLoad()
        {
            AsyncOperation operation = SceneManager.LoadSceneAsync(_sceneID);
            while (!operation.isDone)
            {
                float progress = operation.progress / 0.9f;
                _loadingImage.fillAmount = operation.progress;
                _progressText.text = string.Format("{0:0}%", progress * 100);
                yield return null;
            }
        }

        #endregion
    }
}