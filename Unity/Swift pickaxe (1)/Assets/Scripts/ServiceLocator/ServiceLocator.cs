using System;
using System.Collections.Generic;
using UnityEngine;

namespace SwiftPickaxe
{
    public static class ServiceLocator 
    {
        private static readonly Dictionary<Type, object> _service—ontainer =
            new Dictionary<Type, object>();

        public static void SetService<T>(T value) where T : class
        {
            var typeValue = typeof(T);
            if (!_service—ontainer.ContainsKey(typeValue))
            {
                _service—ontainer[typeValue] = value;
            }
        }

        public static T Resolve<T>()
        {
            var type = typeof(T);

            if (_service—ontainer.ContainsKey(type))
            {
                return (T)_service—ontainer[type];
            }

            return default;
        }

    }
}
