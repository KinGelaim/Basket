using System.Collections;
using UnityEngine;


namespace AvegaGamesTest1
{
    public sealed class Coroutines : MonoBehaviour
    {
        private static Coroutines _instance;

        private Coroutines() { }

        private static Coroutines GetInstance
        {
            get
            {
                if (_instance == null)
                {
                    var go = new GameObject("[COROUTINE MANAGER]");
                    _instance = go.AddComponent<Coroutines>();
                    DontDestroyOnLoad(go);
                }

                return _instance;
            }
        }

        public static Coroutine StartRoutine(IEnumerator enumerator)
        {
            return GetInstance.StartCoroutine(enumerator);
        }

        public static void StopRoutine(Coroutine routine)
        {
            if (routine != null)
                GetInstance.StopCoroutine(routine);
        }
    }
}