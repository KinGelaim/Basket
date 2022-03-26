using System.Collections.Generic;
using UnityEngine;


namespace TargemTest1
{
    public sealed class ConstructionController : IExecute
    {
        #region Fields

        private List<Construction> _constructions;

        private int _countConstruction = 2;

        #endregion


        #region LifeCycleClass

        public ConstructionController(GameObject parent, GameObject centerUniverse, Reference reference, ScoreController scoreController)
        {
            _constructions = new List<Construction>();
            for (int i = 0; i < _countConstruction; i++)
                _constructions.Add(new Construction(parent, centerUniverse, reference, scoreController));
        }

        #endregion


        #region IExecute

        public void Execute()
        {
            for (int i = 0; i < _constructions.Count; i++)
            {
                var executeObject = _constructions[i];
                executeObject?.Execute();
            }
        }

        #endregion
    }
}