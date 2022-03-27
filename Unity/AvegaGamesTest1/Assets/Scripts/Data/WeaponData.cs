using UnityEngine;


namespace AvegaGamesTest1
{
    [CreateAssetMenu(fileName = "WeaponSettings", menuName = "Data/Weapon/WeaponSettings")]
    public sealed class WeaponData : ScriptableObject
    {
        #region Fields

        [SerializeField]
        private float _shootRate = 0.15F;
        [SerializeField]
        private float _reloadTime = 1.0F;
        [SerializeField, Range(1, 30)]
        private int _ammoCount = 15;
        [SerializeField]
        private float _power = 70.0f;
        [SerializeField]
        private AudioClip _fireSound;
        [SerializeField]
        private AudioClip _emptySound;
        [SerializeField]
        private AudioClip _reloadingSound;
        [SerializeField]
        private AudioClip _endReloadingSound;

        #endregion


        #region Properties

        public float ShootRate => _shootRate;
        public float ReloadTime => _reloadTime;
        public int AmmoCount => _ammoCount;
        public float Power => _power;
        public AudioClip FireSound => _fireSound;
        public AudioClip EmptySound => _emptySound;
        public AudioClip ReloadingSound => _reloadingSound;
        public AudioClip EndReloadingSound => _endReloadingSound;

        #endregion
    }
}