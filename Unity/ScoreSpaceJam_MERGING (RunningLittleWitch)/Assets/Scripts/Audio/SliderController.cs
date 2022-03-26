using UnityEngine;
using UnityEngine.UI;


namespace ScoreSpaceJam_MERGING
{
    public class SliderController : MonoBehaviour
    {
        [SerializeField] private AudioSource _audioSource;
       
        private Slider _slider;

        private void Start()
        {
            _slider = GetComponent<Slider>();

            float volume = SoundManager.GetVolume();
            _slider.value = volume;
            _audioSource.volume = volume;
        }

        public void ValueChange()
        {
            SoundManager.SetVolume(_slider.value);
            _audioSource.volume = _slider.value;
        }
    }
}