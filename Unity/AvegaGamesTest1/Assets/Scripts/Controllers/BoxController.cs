using System;
using System.Collections.Generic;
using UnityEngine;


namespace AvegaGamesTest1
{
    public sealed class BoxController : IExecute
    {
        #region Fields

        private readonly PlayerController _playerController;
        private readonly WeaponController _weaponController;

        private GameObject _box = null;
        private List<Box> boxes = new List<Box>();

        private AudioSource _audioSourc;
        private AudioClip _audioClip;

        #endregion


        public BoxController(GameObject box, PlayerController playerController, WeaponController weaponController, AudioSource audioSource, AudioClip audioClip)
        {
            _box = box;
            _playerController = playerController;
            _weaponController = weaponController;

            _audioSourc = audioSource;
            _audioClip = audioClip;
        }


        #region Methods

        public void CreateBox(Vector3 enemyPosition)
        {
            var values = Enum.GetValues(typeof(BoxMaterial));
            var randomBoxMaterial = (BoxMaterial)values.GetValue(new System.Random().Next(values.Length));

            var box = new Box(_box, randomBoxMaterial, enemyPosition);
            boxes.Add(box);
        }

        #endregion


        #region IExecute

        public void Execute()
        {
            foreach (var box in boxes.ToArray())
            {
                box.Action();

                if (box.IsDie)
                {
                    _playerController.ChangeCountBoxes(box.BoxMaterial);
                    _weaponController.SetColorBullet(box.BoxMaterial);
                    boxes.Remove(box);
                    _audioSourc.PlayOneShot(_audioClip);
                }
            }
        }

        #endregion
    }
}