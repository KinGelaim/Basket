using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Windows;
using System.Windows.Input;

namespace InfiniteMilitaryService_v1._0
{
    class ReadKeyboard
    {
        public static Episode Episode { get; set; }

        public static void Window_KeyUp(object sender, KeyEventArgs e)
        {
            if (Episode != null)
            {
                if (e.Key.ToString() == "Space")
                {
                    Episode.pauseSpeedText = 0;
                }
                if (e.Key.ToString() == "Escape")
                {
                    if (MessageBoxResult.Yes == MessageBox.Show("Ваш прогресс в эпизоде не будет сохранён, Вы уверены, что желаете выйти?", "Внимание", MessageBoxButton.YesNo, MessageBoxImage.Information))
                        Episode.game.EndGame();
                }
            }
        }
    }
}
