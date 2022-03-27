using System;
using System.Collections;
using System.Linq;


namespace AvegaGamesTest1
{
    public sealed class ListExecutableObject : IEnumerator, IEnumerable
    {
        #region Fields

        private IExecute[] _executeObjects;

        private int _index = -1;

        #endregion


        #region Properties

        public int Count => _executeObjects.Length;

        public IExecute this[int index]
        {
            get => _executeObjects[index];
        }

        #endregion


        #region Methods

        public void AddExecuteObject(IExecute execute)
        {
            if (_executeObjects == null)
            {
                _executeObjects = new[] { execute };
                return;
            }
            Array.Resize(ref _executeObjects, Count + 1);
            _executeObjects[Count - 1] = execute;
        }

        public void Remove(IExecute executeObject)
        {
            _executeObjects = (from x in _executeObjects where x != executeObject select x).ToArray();
        }

        #endregion


        #region IEnumerator

        public object Current => _executeObjects.Length;

        public bool MoveNext()
        {
            if (_index == _executeObjects.Length - 1)
            {
                Reset();
                return false;
            }

            _index++;
            return true;
        }

        public void Reset() => _index = -1;

        #endregion


        #region IEnumerable

        public IEnumerator GetEnumerator()
        {
            return this;
        }

        #endregion
    }
}