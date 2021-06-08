using System;
using System.Media;


namespace MiniSnake_v5._0
{
    public class SoundManager
    {
        private static SoundManager _soundManager;

        private SoundPlayer _spApple;
        private SoundPlayer _spChoise;
        private SoundPlayer _spDead;
        private SoundPlayer _spSnake;

        private SoundManager()
        {
            string path = Environment.CurrentDirectory + "\\Sound\\apple.wav";
            _spApple = new SoundPlayer(path);

            path = Environment.CurrentDirectory + "\\Sound\\choise.wav";
            _spChoise = new SoundPlayer(path);

            path = Environment.CurrentDirectory + "\\Sound\\dead.wav";
            _spDead = new SoundPlayer(path);

            path = Environment.CurrentDirectory + "\\Sound\\snake.wav";
            _spSnake = new SoundPlayer(path);
        }

        public static SoundManager GetSoundManager()
        {
            if (_soundManager == null)
                _soundManager = new SoundManager();
            return _soundManager;
        }

        public void PlayApple()
        {
            if (_spApple != null)
            {
                try
                {
                    _spApple.Play();
                }
                catch { }
            }
        }

        public void PlayChoise()
        {
            if (_spChoise != null)
            {
                try
                {
                    _spChoise.Play();
                }
                catch { }
            }
        }

        public void PlayDead()
        {
            if (_spDead != null)
            {
                try
                {
                    _spDead.Play();
                }
                catch { }
            }
        }

        public void PlaySnake()
        {
            if (_spSnake != null)
            {
                try
                {
                    _spSnake.Play();
                }
                catch { }
            }
        }
    }
}