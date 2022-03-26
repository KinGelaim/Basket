using UnityEngine;


namespace HungryCat
{
    public static class SoundManager
    {
        public static float GetVolume()
        {
            if (PlayerPrefs.HasKey("SoundVolume"))
                return PlayerPrefs.GetFloat("SoundVolume");
            return 1.0f;
        }

        public static void SetVolume(float volume)
        {
            PlayerPrefs.SetFloat("SoundVolume", volume);
            PlayerPrefs.Save();
        }

        public static void DeleteVolume()
        {
            PlayerPrefs.DeleteKey("SoundVolume");
        }
    }
}