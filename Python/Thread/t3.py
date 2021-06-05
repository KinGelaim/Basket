import threading
import Queue

class Worker(threading.Thread):

    def __init__(self, work_queue, word):
        super(Worker,self).__init__()
        self.work_queue = work_queue
        self.word = word

    def run(self):
        try:
            filename = self.work_queue.get()
            self.process(filename)
        finally:
            pass

    def process(self, filename):
        previous = ""
        current=True
        with open(filename, "rb") as fh:
            while current:
                current = fh.readline()
                if not current: break
                current = current.decode("utf8", "ignore")
                if self.word in current :
                    print("find {0}: {1}".format(self.word,filename))
                previous = current

word = 'import'
filelist = ['t1.py','t2.py','t3.py']
work_queue = Queue.Queue()
for filename in filelist:
    work_queue.put(filename)
for i in range(3):
    worker = Worker(work_queue, word)
    worker.start()
