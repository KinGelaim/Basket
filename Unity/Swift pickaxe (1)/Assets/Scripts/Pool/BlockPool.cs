using System;
using System.Collections.Generic;
using System.Linq;
using UnityEngine;
using Object = UnityEngine.Object;


namespace SwiftPickaxe
{
    internal sealed class BlockPool
    {
        private readonly Dictionary<string, HashSet<Block>> _blockPool;
        private readonly int _capacityPool;
        private Transform _rootPool;

        private DiamodStoun _diamodStoun;
        private Earth _earth;
        private Stoun _stoun;
        private Void _void;
        private BedRock _bedRock;
        private GoldStone _goldStone;
        private SapphireStone _sapphireStone;
        private Andesite _andesite;
        private Marble _marble;
        private EmeraldStone _emeraldStone;
        private Sand _sand;
        private Limestone _limestone;
        private Chalk _chalk;
        private RubyStone _rubyStone;


        public BlockPool(int capacityPool)
        {
            _blockPool = new Dictionary<string, HashSet<Block>>();
            _capacityPool = capacityPool;
            if (!_rootPool)
            {
                _rootPool = new GameObject(NameManager.POOL_BLOCK).transform;
            }
            _diamodStoun = Resources.Load<DiamodStoun>("Prefabs/DiamodStoun");
            _earth = Resources.Load<Earth>("Prefabs/Earth");
            _stoun = Resources.Load<Stoun>("Prefabs/Stoun");
            _void = Resources.Load<Void>("Prefabs/Void");
            _bedRock = Resources.Load<BedRock>("Prefabs/BedRock");
            _goldStone = Resources.Load<GoldStone>("Prefabs/GoldStone");
            _sapphireStone = Resources.Load<SapphireStone>("Prefabs/SapphireStone");
            _andesite = Resources.Load<Andesite>("Prefabs/Andesite");
            _marble = Resources.Load<Marble>("Prefabs/Marble");
            _emeraldStone = Resources.Load<EmeraldStone>("Prefabs/EmeraldStone");
            _sand = Resources.Load<Sand>("Prefabs/Sand");
            _limestone = Resources.Load<Limestone>("Prefabs/Limestone");
            _chalk = Resources.Load<Chalk>("Prefabs/Chalk");
            _rubyStone = Resources.Load<RubyStone>("Prefabs/RubyStone");
        }

        public Block GetBlock(string type)
        {
            Block result;
            switch (type)
            {
                case "DiamodStoun":
                    result = GetDiamodStoun(GetListBlocks(type));
                    break;
                case "Stoun":
                    result = GetStoun(GetListBlocks(type));
                    break;
                case "Earth":
                    result = GetEarth(GetListBlocks(type));
                    break;
                case "Void":
                    result = GetVoid(GetListBlocks(type));
                    break;
                case "BedRock":
                    result = GetBedRock(GetListBlocks(type));
                    break;
                case "GoldStone":
                    result = GetGoldStone(GetListBlocks(type));
                    break;
                case "SapphireStone":
                    result = GetSapphireStone(GetListBlocks(type));
                    break;
                case "Andesite":
                    result = GetAndesite(GetListBlocks(type));
                    break;
                case "Marble":
                    result = GetMarble(GetListBlocks(type));
                    break;
                case "EmeraldStone":
                    result = GetEmeraldStone(GetListBlocks(type));
                    break;
                case "Sand":
                    result = GetSand(GetListBlocks(type));
                    break;
                case "Limestone":
                    result = GetLimestone(GetListBlocks(type));
                    break;
                case "Chalk":
                    result = GetChalk(GetListBlocks(type));
                    break;
                case "RubyStone":
                    result = GetRubyStone(GetListBlocks(type));
                    break;
                default:
                    throw new ArgumentOutOfRangeException(nameof(type), type, "Не предусмотрен в программе");
            }

            return result;
        }

        private HashSet<Block> GetListBlocks(string type)
        {
            return _blockPool.ContainsKey(type) ? _blockPool[type] : _blockPool[type] = new HashSet<Block>();
        }

        private RubyStone GetRubyStone(HashSet<Block> blocks)
        {
            var block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            if (block == null)
            {
                var loadBlock = _rubyStone;
                for (var i = 0; i < _capacityPool; i++)
                {
                    var instantiate = Object.Instantiate(loadBlock);
                    ReturnToPool(instantiate.transform);
                    blocks.Add(instantiate);
                }

                GetRubyStone(blocks);
            }
            block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            return (RubyStone)block;
        }

        private Chalk GetChalk(HashSet<Block> blocks)
        {
            var block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            if (block == null)
            {
                var loadBlock = _chalk;
                for (var i = 0; i < _capacityPool; i++)
                {
                    var instantiate = Object.Instantiate(loadBlock);
                    ReturnToPool(instantiate.transform);
                    blocks.Add(instantiate);
                }

                GetChalk(blocks);
            }
            block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            return (Chalk)block;
        }

        private Limestone GetLimestone(HashSet<Block> blocks)
        {
            var block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            if (block == null)
            {
                var loadBlock = _limestone;
                for (var i = 0; i < _capacityPool; i++)
                {
                    var instantiate = Object.Instantiate(loadBlock);
                    ReturnToPool(instantiate.transform);
                    blocks.Add(instantiate);
                }

                GetLimestone(blocks);
            }
            block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            return (Limestone)block;
        }

        private Sand GetSand(HashSet<Block> blocks)
        {
            var block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            if (block == null)
            {
                var loadBlock = _sand;
                for (var i = 0; i < _capacityPool; i++)
                {
                    var instantiate = Object.Instantiate(loadBlock);
                    ReturnToPool(instantiate.transform);
                    blocks.Add(instantiate);
                }

                GetSand(blocks);
            }
            block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            return (Sand)block;
        }

        private EmeraldStone GetEmeraldStone(HashSet<Block> blocks)
        {
            var block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            if (block == null)
            {
                var loadBlock = _emeraldStone;
                for (var i = 0; i < _capacityPool; i++)
                {
                    var instantiate = Object.Instantiate(loadBlock);
                    ReturnToPool(instantiate.transform);
                    blocks.Add(instantiate);
                }

                GetEmeraldStone(blocks);
            }
            block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            return (EmeraldStone)block;
        }

        private Marble GetMarble(HashSet<Block> blocks)
        {
            var block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            if (block == null)
            {
                var loadBlock = _marble;
                for (var i = 0; i < _capacityPool; i++)
                {
                    var instantiate = Object.Instantiate(loadBlock);
                    ReturnToPool(instantiate.transform);
                    blocks.Add(instantiate);
                }

                GetMarble(blocks);
            }
            block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            return (Marble)block;
        }

        private Andesite GetAndesite(HashSet<Block> blocks)
        {
            var block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            if (block == null)
            {
                var loadBlock = _andesite;
                for (var i = 0; i < _capacityPool; i++)
                {
                    var instantiate = Object.Instantiate(loadBlock);
                    ReturnToPool(instantiate.transform);
                    blocks.Add(instantiate);
                }

                GetAndesite(blocks);
            }
            block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            return (Andesite)block;
        }

        private SapphireStone GetSapphireStone(HashSet<Block> blocks)
        {
            var block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            if (block == null)
            {
                var loadBlock = _sapphireStone;
                for (var i = 0; i < _capacityPool; i++)
                {
                    var instantiate = Object.Instantiate(loadBlock);
                    ReturnToPool(instantiate.transform);
                    blocks.Add(instantiate);
                }

                GetSapphireStone(blocks);
            }
            block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            return (SapphireStone)block;
        }

        private GoldStone GetGoldStone(HashSet<Block> blocks)
        {
            var block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            if (block == null)
            {
                var loadBlock = _goldStone;
                for (var i = 0; i < _capacityPool; i++)
                {
                    var instantiate = Object.Instantiate(loadBlock);
                    ReturnToPool(instantiate.transform);
                    blocks.Add(instantiate);
                }

                GetGoldStone(blocks);
            }
            block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            return (GoldStone)block;
        }

        private Void GetVoid(HashSet<Block> blocks)
        {
            var block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            if (block == null)
            {
                var loadBlock = _void;
                for (var i = 0; i < _capacityPool; i++)
                {
                    var instantiate = Object.Instantiate(loadBlock);
                    ReturnToPool(instantiate.transform);
                    blocks.Add(instantiate);
                }

                GetVoid(blocks);
            }
            block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            return (Void)block;
        }

        private DiamodStoun GetDiamodStoun(HashSet<Block> blocks)
        {
            var block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            if (block == null)
            {
                var loadBlock = _diamodStoun;
                for (var i = 0; i < _capacityPool; i++)
                {
                    var instantiate = Object.Instantiate(loadBlock);
                    ReturnToPool(instantiate.transform);
                    blocks.Add(instantiate);
                }

                GetDiamodStoun(blocks);
            }
            block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            return (DiamodStoun)block;
        }

        private BedRock GetBedRock(HashSet<Block> blocks)
        {
            var block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            if (block == null)
            {
                var loadBlock = _bedRock;
                for (var i = 0; i < _capacityPool; i++)
                {
                    var instantiate = Object.Instantiate(loadBlock);
                    ReturnToPool(instantiate.transform);
                    blocks.Add(instantiate);
                }

                GetBedRock(blocks);
            }
            block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            return (BedRock)block;
        }

        private Stoun GetStoun(HashSet<Block> blocks)
        {
            var block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            if (block == null)
            {
                var loadBlock = _stoun;
                for (var i = 0; i < _capacityPool; i++)
                {
                    var instantiate = Object.Instantiate(loadBlock);
                    ReturnToPool(instantiate.transform);
                    blocks.Add(instantiate);
                }

                GetStoun(blocks);
            }
            block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            return (Stoun)block;
        }

        private Earth GetEarth(HashSet<Block> blocks)
        {
            var block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            if (block == null)
            {
                var loadBlock = _earth;
                for (var i = 0; i < _capacityPool; i++)
                {
                    var instantiate = Object.Instantiate(loadBlock);
                    ReturnToPool(instantiate.transform);
                    blocks.Add(instantiate);
                }

                GetEarth(blocks);
            }
            block = blocks.FirstOrDefault(a => !a.gameObject.activeSelf);
            return (Earth)block;
        }

        private void ReturnToPool(Transform transform)
        {
            transform.localPosition = Vector3.zero;
            transform.localRotation = Quaternion.identity;
            transform.gameObject.SetActive(false);
            transform.SetParent(_rootPool);
        }

        public void RemovePool()
        {
            Object.Destroy(_rootPool.gameObject);
        }

    }
}
