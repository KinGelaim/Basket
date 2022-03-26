using System.Collections.Generic;
using UnityEngine;


namespace SwiftPickaxe
{
    public class PlayerController : Unit
    {

        #region Fields

        [Header("Settings")]
        public AudioClip AudioSound;

        [SerializeField] private int _health = 3;
        [SerializeField] private static int _damage = 1;

        [SerializeField] private float _damageSpeedTime = 0.5f;

        [SerializeField] private GameManager _gameManager;

        private CharacterState _state = CharacterState.Idle;

        private static Dictionary<TypeItem, int> _dictionaryItem = new Dictionary<TypeItem, int>();
        private Dictionary<TypeItem, int> _dictionaryItemLevel = new Dictionary<TypeItem, int>();

        private static TypeItem _typePickaxe = TypeItem.None;

        private int _countToUpGoldPickaxe = 5;
        private int _countToUpDiamondPickaxe = 8;
        private int _countToUpSapphirePickaxe = 11;
        private int _countToUpEmeraldPickaxe = 14;
        private int _countToUpRubyPickaxe = 17;

        private static bool _haveGoldPickaxe = false;
        private static bool _haveDiamondPickaxe = false;
        private static bool _haveSapphirePickaxe = false;
        private static bool _haveEmeraldPickaxe = false;
        private static bool _haveRubyPickaxe = false;

        private int _addDamageGoldPickaxe = 1;
        private int _addDamageDiamondPickaxe = 2;
        private int _addDamageSapphirePickaxe = 3;
        private int _addDamageEmeraldPickaxe = 4;
        private int _addDamageRubyPickaxe = 5;

        #endregion


        #region Properties

        public int Health => _health;

        public CharacterState State
        {
            get
            {
                return _state;
            }
            set
            {
                _state = value;
            }
        }

        public int Damage => _damage;

        public TypeItem TypePickaxe => _typePickaxe;

        public float DamageSpeedTime => _damageSpeedTime;

        public int Score
        {
            get
            {
                int score = 0;
                if (_dictionaryItem.ContainsKey(TypeItem.Gold))
                    score += _dictionaryItem[TypeItem.Gold];
                if (_dictionaryItem.ContainsKey(TypeItem.Diamond))
                    score += 2 * _dictionaryItem[TypeItem.Diamond];
                if (_dictionaryItem.ContainsKey(TypeItem.Sapphire))
                    score += 4 * _dictionaryItem[TypeItem.Sapphire];
                if (_dictionaryItem.ContainsKey(TypeItem.Emerald))
                    score += 8 * _dictionaryItem[TypeItem.Emerald];
                if (_dictionaryItem.ContainsKey(TypeItem.Ruby))
                    score += 16 * _dictionaryItem[TypeItem.Ruby];

                int depth = 0;
                int.TryParse(_gameManager.Depth, out depth);
                score += depth;

                return score;
            }
        }

        #endregion


        #region Indexers

        public int this[TypeItem typeItem]
        {
            get
            {
                if(_dictionaryItem.ContainsKey(typeItem))
                    return _dictionaryItem[typeItem];
                return 0;
            }
        }

        #endregion


        #region Methods

        public void AddHealth(int value)
        {
            _health += value;
        }

        public override void Hurt(int value)
        {
            _health -= value;
            if (_health <= 0)
                Die();
        }

        public override void Die()
        {
            _gameManager.Die(Score);
        }

        public void Win()
        {
            _gameManager.Win(Score);
        }

        public void NewItem(TypeItem typeItem)
        {
            if ((int)typeItem < 10)
            {
                AddItem(_dictionaryItem, typeItem);
                AddItem(_dictionaryItemLevel, typeItem);
            }
            else
            {
                if(typeItem == TypeItem.PotionFog)
                {
                    FindObjectOfType<FogOfWar>().StartToEndFog();
                }
            }
        }

        private void AddItem(Dictionary<TypeItem, int> dictionaryItem, TypeItem typeItem)
        {
            if (dictionaryItem.ContainsKey(typeItem))
            {
                dictionaryItem[typeItem] = dictionaryItem[typeItem] + 1;
                if (typeItem == TypeItem.Diamond && !_haveDiamondPickaxe)
                {
                    if (dictionaryItem[typeItem] >= _countToUpDiamondPickaxe)
                    {
                        _haveDiamondPickaxe = true;
                        gameObject.GetComponent<Animator>().runtimeAnimatorController = Resources.Load<RuntimeAnimatorController>("Animators/miner_diamond");
                        _typePickaxe = typeItem;
                        _damage += _addDamageDiamondPickaxe;
                    }
                }
                if (typeItem == TypeItem.Emerald && !_haveEmeraldPickaxe)
                {
                    if (dictionaryItem[typeItem] >= _countToUpEmeraldPickaxe)
                    {
                        _haveEmeraldPickaxe = true;
                        gameObject.GetComponent<Animator>().runtimeAnimatorController = Resources.Load<RuntimeAnimatorController>("Animators/miner_emerald");
                        _typePickaxe = typeItem;
                        _damage += _addDamageEmeraldPickaxe;
                    }
                }
                if (typeItem == TypeItem.Gold && !_haveGoldPickaxe)
                {
                    if (dictionaryItem[typeItem] >= _countToUpGoldPickaxe)
                    {
                        _haveGoldPickaxe = true;
                        gameObject.GetComponent<Animator>().runtimeAnimatorController = Resources.Load<RuntimeAnimatorController>("Animators/miner_gold");
                        _typePickaxe = typeItem;
                        _damage += _addDamageGoldPickaxe;
                    }
                }
                if (typeItem == TypeItem.Ruby && !_haveRubyPickaxe)
                {
                    if (dictionaryItem[typeItem] >= _countToUpRubyPickaxe)
                    {
                        _haveRubyPickaxe = true;
                        gameObject.GetComponent<Animator>().runtimeAnimatorController = Resources.Load<RuntimeAnimatorController>("Animators/miner_ruby");
                        _typePickaxe = typeItem;
                        _damage += _addDamageRubyPickaxe;
                    }
                }
                if (typeItem == TypeItem.Sapphire && !_haveSapphirePickaxe)
                {
                    if (dictionaryItem[typeItem] >= _countToUpSapphirePickaxe)
                    {
                        _haveSapphirePickaxe = true;
                        gameObject.GetComponent<Animator>().runtimeAnimatorController = Resources.Load<RuntimeAnimatorController>("Animators/miner_sapphire");
                        _typePickaxe = typeItem;
                        _damage += _addDamageSapphirePickaxe;
                    }
                }
            }
            else
                dictionaryItem.Add(typeItem, 1);
        }

        public void ClearDictionaryItem()
        {
            _dictionaryItem = new Dictionary<TypeItem, int>();
            _damage = 1;
            _typePickaxe = TypeItem.None;
            _haveGoldPickaxe = false;
            _haveDiamondPickaxe = false;
            _haveSapphirePickaxe = false;
            _haveEmeraldPickaxe = false;
            _haveRubyPickaxe = false;
    }

        public void BeginDictionaryItem()
        {
            foreach(TypeItem item in _dictionaryItemLevel.Keys)
            {
                _dictionaryItem[item] = _dictionaryItem[item] - _dictionaryItemLevel[item];
            }
        }

        #endregion

    }
}