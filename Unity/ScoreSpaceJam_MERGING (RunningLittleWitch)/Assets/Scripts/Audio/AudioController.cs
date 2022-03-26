using UnityEngine;


namespace ScoreSpaceJam_MERGING
{
    public class AudioController : MonoBehaviour
    {
        [SerializeField] private float _maxVolume = 1.0f;

        private AudioSource _audioSource;

        private void Start()
        {
            _audioSource = GetComponent<AudioSource>();
            float getVolume = SoundManager.GetVolume();
            float convertVolume = Mathf.Lerp(0, _maxVolume, getVolume);
            _audioSource.volume = convertVolume;
        }
    }
}