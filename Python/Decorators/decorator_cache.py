print("Hello")

def decorator_cache(func):
    def oncall(*args):
        if args[0] in oncall.dic.keys():
            return oncall.dic[args[0]]
        else:
            k = func(*args)
            oncall.dic[args[0]] = k
            return k
    oncall.dic = {}
    return oncall
        
    
@decorator_cache
def func(x):
    print("Я долго считаю")
    return x*x + 1

print(func(10))
print(func(5))
print(func(10))
