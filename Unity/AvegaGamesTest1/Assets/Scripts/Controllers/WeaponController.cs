using System.Collections;
using UnityEngine;


namespace AvegaGamesTest1
{
    public sealed class WeaponController : IExecute
    {
        #region Fields

        private readonly TextController _textController;

        private Coroutine _routine = null;
        private FP_Input _playerInput = null;
        private AudioSource _audioSource = null;
        private WeaponData _weaponData = null;

        private GameObject _bullet;
        private GameObject _spawnBulletPosition = null;

        private float _shootRate = 0.0f;
        private float _reloadTime = 0.0f;
        private int _ammoCount = 0;
        private float _power = 0.0f;

        private int _ammo = 0;
        private float _delay = 0.0f;
        private bool _reloading = false;

        #endregion


        #region ClassLifeCycle

        public WeaponController(FP_Input playerInput, WeaponData weaponData, AudioSource audioSource, GameObject bullet, GameObject spawnBulletPosition, TextController textController)
        {
            _textController = textController;

            _playerInput = playerInput;

            _weaponData = weaponData;
            _shootRate = weaponData.ShootRate;
            _reloadTime = weaponData.ReloadTime;
            _ammo = _ammoCount = weaponData.AmmoCount;
            _power = weaponData.Power;
            SetAmmoUI();

            _audioSource = audioSource;

            _bullet = GameObject.Instantiate(bullet);
            _bullet.SetActive(false);
            _spawnBulletPosition = spawnBulletPosition;
        }

        #endregion


        #region Methods

        private void Shoot()
        {
            if (!_reloading)
            {
                if (_ammoCount > 0)
                {
                    CreateFire();
                    _ammoCount--;
                    SetAmmoUI();
                }
                else
                    _audioSource.PlayOneShot(_weaponData.EmptySound);

                _delay = Time.time + _shootRate;
            }
        }

        private void CreateFire()
        {
            _audioSource.PlayOneShot(_weaponData.FireSound);

            var bullet = GameObject.Instantiate(_bullet);
            bullet.SetActive(true);
            bullet.transform.position = new Vector3(_spawnBulletPosition.transform.position.x, _spawnBulletPosition.transform.position.y, _spawnBulletPosition.transform.position.z);
            bullet.GetComponent<Rigidbody>().AddForce(_spawnBulletPosition.transform.forward * _power, ForceMode.Impulse);
            GameObject.Destroy(bullet, 2.0f);
        }

        private IEnumerator Reload()
        {
            _reloading = true;
            _audioSource.PlayOneShot(_weaponData.ReloadingSound);
            yield return new WaitForSeconds(_reloadTime);
            _ammoCount = _ammo;
            SetAmmoUI();
            _audioSource.PlayOneShot(_weaponData.EndReloadingSound);
            _reloading = false;
        }

        private void SetAmmoUI()
        {
            _textController.SetAmmoText(_ammoCount);
        }

        public void SetColorBullet(BoxMaterial boxMaterial)
        {
            MaterialController.ChaneMaterialForGameObject(_bullet, boxMaterial);
        }

        #endregion


        #region IExecute

        public void Execute()
        {
            if (_playerInput.Shoot())
                if (Time.time > _delay && !_reloading)
                    Shoot();

            if (_playerInput.Reload())
                if (!_reloading && _ammoCount < _ammo)
                    _routine = Coroutines.StartRoutine(Reload());
        }

        #endregion
    }
}