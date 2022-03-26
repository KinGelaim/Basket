using UnityEngine;
using UnityEngine.UI;


namespace SwiftPickaxe
{
    public class Block : MonoBehaviour
    {
        [SerializeField] private float _hpBlock = 10f;
        private float _standartHP;
        private PlayerController _player;
        private float _distance;
        private AudioSource _audioSource;
        private GameObject _canvas;
        private Slider _hpBar;
        private float _scaleBar;

        private bool isMouseDown = false;
        private float time = 0;
        [SerializeField] private GameObject _item;
        [SerializeField] private int _chance = 100;
        private PickupItem _pickupItem;

        private Transform _rotPool;

        protected private  void Awake()
        {
            _player = FindObjectOfType<PlayerController>();

            _audioSource = this.gameObject.transform.GetComponent<AudioSource>();
            _canvas = this.transform.Find("Canvas").gameObject;
            _hpBar = _canvas.transform.Find("HPBar").gameObject.GetComponent<Slider>();

            _scaleBar = _hpBar.value / _hpBlock;

            _canvas.SetActive(false);
            this.transform.GetComponent<SpriteRenderer>().maskInteraction = SpriteMaskInteraction.VisibleInsideMask;

            _standartHP = _hpBlock;

            if (_item)
                _pickupItem = _item.GetComponent<PickupItem>();
        }

        public void RestarBlock()
        {
            _hpBlock = _standartHP;
            if (_canvas != null)
            {
                _canvas.SetActive(false);
                _hpBar.value = _hpBlock * _scaleBar;
            }
            isMouseDown = false;
            time = 0;
        }

        protected private void Update()
        {
            if (isMouseDown)
            {
                time += Time.deltaTime;
                if (time >= _player.DamageSpeedTime)
                {
                    GetDamage();
                    time = 0;
                }
            }
        }

        private void GetDamage()
        {
            _distance = Vector2.Distance(this.gameObject.transform.position, _player.gameObject.transform.position);
            if (_distance < 1.5f)
            {
                _audioSource.Play();
                _hpBlock -= _player.Damage;
                _player.State = CharacterState.Dig;
                if (_hpBlock <= 0)
                {
                    DestroyBlock();
                }
                _hpBar.value = _hpBlock * _scaleBar;
            }
        }

        protected private void OnMouseEnter()
        {
            _canvas.SetActive(true);
        }

        protected void OnMouseExit()
        {
            _canvas.SetActive(false);
            isMouseDown = false;
            time = 0;
        }

        public void DestroyBlock()  
        {
            var VOID = Resources.Load<GameObject>("Prefabs/Void");
            Instantiate(VOID, this.transform.position, this.transform.rotation);
            if (_item)
            {
                if ((int)_pickupItem.TypeItem < 10)
                {
                    if (_pickupItem.TypeItem == TypeItem.Gold || _pickupItem.TypeItem == TypeItem.Diamond && (int)_player.TypePickaxe >= 1 ||
                        _pickupItem.TypeItem == TypeItem.Sapphire && (int)_player.TypePickaxe >= 2 ||
                        _pickupItem.TypeItem == TypeItem.Emerald && (int)_player.TypePickaxe >= 3 ||
                        _pickupItem.TypeItem == TypeItem.Ruby && (int)_player.TypePickaxe >= 4)
                        Instantiate(_item, this.transform.position, this.transform.rotation);
                }
                else
                {
                    if (_chance >= 100)
                        Instantiate(_item, this.transform.position, this.transform.rotation);
                    else
                    {
                        if (Random.Range(1, 100) < _chance)
                            Instantiate(_item, this.transform.position, this.transform.rotation);
                    }
                }
            }
            _player.State = CharacterState.Idle;
            ReturnToPool();
        }

        public void OnPointerEnter()
        {
            if (_canvas)
            {
                _canvas.SetActive(true);
                GetDamage();
                isMouseDown = true;
            }
        }

        public void OnPointerExit()
        {
            if (_canvas)
            {
                _canvas.SetActive(false);
                _player.State = CharacterState.Idle;
                time = 0;
            }
            isMouseDown = false;
        }

        protected void OnMouseDown()  
        {
            GetDamage();
            isMouseDown = true;
        }

        protected private void OnMouseUp()  
        {
            isMouseDown = false;
            _player.State = CharacterState.Idle;
            time = 0;
        }

        protected void ReturnToPool()
        {
            transform.localPosition = Vector3.zero;
            transform.localRotation = Quaternion.identity;
            gameObject.SetActive(false);
            transform.SetParent(RotPool);

            if (!RotPool)
            {
                Destroy(gameObject);
            }
        }

        public Transform RotPool
        {
            get
            {
                if (_rotPool == null)
                {
                    var find = GameObject.Find(NameManager.POOL_BLOCK);
                    _rotPool = find == null ? null : find.transform;
                }

                return _rotPool;
            }
        }
    }
}
