using UnityEngine;


namespace ScoreSpaceJam_MERGING
{
    public class LittleWitchController : MonoBehaviour
    {
        [SerializeField] GameObject _catImage;
        [SerializeField] GameObject _hamsterImage;

        [SerializeField] AudioClip[] _clipsCat;
        [SerializeField] AudioClip[] _clipsHamster;

        private AudioSource _audioSource;

        private KeyboardController _keyboardController;
        private PlayerController _playerController;

        private bool _isCat = false;
        private bool _isHamster = false;


        private void Start()
        {
            _audioSource = GetComponent<AudioSource>();

            _keyboardController = GetComponent<KeyboardController>();
            _playerController = GetComponent<PlayerController>();
        }

        private void Update()
        {
            if (Input.GetKeyDown(KeyCode.Alpha1))
            {
                _playerController.State = CharacterState.Swap;

                _isHamster = false;
                _hamsterImage.SetActive(false);
                _keyboardController.HamsterDown();

                _isCat = !_isCat;
                if (_isCat)
                {
                    _keyboardController.CatUp();
                    _catImage.SetActive(true);

                    _audioSource.Stop();
                    _audioSource.PlayOneShot(_clipsCat[Random.Range(0, _clipsCat.Length)]);
                }
                else
                {
                    _keyboardController.CatDown();
                    _catImage.SetActive(false);
                }
            }
            if (Input.GetKeyDown(KeyCode.Alpha2))
            {
                _playerController.State = CharacterState.Swap;

                _isCat = false;
                _catImage.SetActive(false);
                _keyboardController.CatDown();

                _isHamster = !_isHamster;
                if (_isHamster)
                {
                    _keyboardController.HamsterUp();
                    _hamsterImage.SetActive(true);

                    _audioSource.Stop();
                    _audioSource.PlayOneShot(_clipsHamster[Random.Range(0, _clipsHamster.Length)]);
                }
                else
                {
                    _keyboardController.HamsterDown();
                    _hamsterImage.SetActive(false);
                }
            }
        }
    }
}
