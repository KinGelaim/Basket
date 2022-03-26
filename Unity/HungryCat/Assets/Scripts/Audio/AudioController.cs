using UnityEngine;


namespace HungryCat
{
    public class AudioController : MonoBehaviour
    {
        private AudioSource _audioSource;

        private void Start()
        {
            _audioSource = GetComponent<AudioSource>();
            _audioSource.volume = SoundManager.GetVolume();
        }
    }
}