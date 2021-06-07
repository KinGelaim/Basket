using System.Reflection;
using System.Resources;
using System.Runtime.CompilerServices;
using System.Runtime.InteropServices;
using System.Windows;

// Управление общими сведениями о сборке осуществляется с помощью 
// набора атрибутов. Измените значения этих атрибутов, чтобы изменить сведения,
// связанные со сборкой.
[assembly: AssemblyTitle("BallisticWind")]
[assembly: AssemblyDescription("Программа для определения среднего и баллистического ветра на ЭВМ по данным засечек шара-зонда с помощью РЛС.{n}   Данные о среднем ветре по слоям и баллистическом ветре можно получить используя результаты зондирования атмосферы на высоте с помощью РЛС, которая даёт дискретные данные о положении зонда с интервалом дискретности t, при этом система координат, в которой производится измерение, сферическая. Началом отчёта, т.е. началом координат является местоположение станции РЛС.{n}   Положение i-ой засечки шара определяется тремя координатами D, G, V")]
[assembly: AssemblyConfiguration("")]
[assembly: AssemblyCompany("НТИИМ")]
[assembly: AssemblyProduct("BallisticWind")]
[assembly: AssemblyCopyright("Copyright ©  2020")]
[assembly: AssemblyTrademark("")]
[assembly: AssemblyCulture("")]

// Параметр ComVisible со значением FALSE делает типы в сборке невидимыми 
// для COM-компонентов.  Если требуется обратиться к типу в этой сборке через 
// COM, задайте атрибуту ComVisible значение TRUE для этого типа.
[assembly: ComVisible(false)]

//Чтобы начать сборку локализованных приложений, задайте 
//<UICulture>CultureYouAreCodingWith</UICulture> в файле .csproj
//внутри <PropertyGroup>.  Например, если используется английский США
//в своих исходных файлах установите <UICulture> в en-US.  Затем отмените преобразование в комментарий
//атрибута NeutralResourceLanguage ниже.  Обновите "en-US" в
//строка внизу для обеспечения соответствия настройки UICulture в файле проекта.

//[assembly: NeutralResourcesLanguage("en-US", UltimateResourceFallbackLocation.Satellite)]


[assembly: ThemeInfo(
    ResourceDictionaryLocation.None, //где расположены словари ресурсов по конкретным тематикам
    //(используется, если ресурс не найден на странице 
    // или в словарях ресурсов приложения)
    ResourceDictionaryLocation.SourceAssembly //где расположен словарь универсальных ресурсов
    //(используется, если ресурс не найден на странице, 
    // в приложении или в каких-либо словарях ресурсов для конкретной темы)
)]


// Сведения о версии сборки состоят из следующих четырех значений:
//
//      Основной номер версии
//      Дополнительный номер версии 
//   Номер сборки
//      Редакция
//
// Можно задать все значения или принять номера сборки и редакции по умолчанию 
// используя "*", как показано ниже:
// [assembly: AssemblyVersion("1.0.*")]
[assembly: AssemblyVersion("1.0.3.7")]
[assembly: AssemblyFileVersion("1.0.3.7")]
