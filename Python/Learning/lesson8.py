# -*- coding: utf-8 -*-

# SOLID
# ...
# Принцип единственной ответственности класса
# ...

# KISS
# keep it simple stupid (не усложняй)
# Example: FizzBuzz

import tkinter as tk
from random import randint

WIDTH = 300
HEIGHT = 200

class Ball:
    def __init__(self, dx, dy, color):
        self.r = randint(20, 50)
        self.x = randint(self.r, WIDTH - self.r)
        self.y = randint(self.r, HEIGHT - self.r)
        self.dx = dx
        self.dy = dy
        self.color = color
        self.ball_id = canvas.create_oval(self.x - self.r,
                                          self.y - self.r,
                                          self.x + self.r,
                                          self.y + self.r,
                                          fill = color)

    def move(self):
        self.x += self.dx
        self.y += self.dy
        if self.x + self.r > WIDTH or self.x - self.r <= 0:
            self.dx = -self.dx
        if self.y + self.r > HEIGHT or self.y - self.r <= 0:
            self.dy = -self.dy

    def show(self):
        canvas.move(self.ball_id, self.dx, self.dy)

    def check_collision(self):
        pass


def tick():
    for ball in balls:
        ball.move()
        ball.show()
    root.after(50, tick)

def main():
    global root, canvas, balls

    root = tk.Tk()
    root.geometry(str(WIDTH) + 'x' + str(HEIGHT))
    canvas = tk.Canvas(root)
    canvas.pack(anchor='nw', fill=tk.BOTH)

    balls = [Ball(randint(1,3), randint(1,3), 'Green') for i in range(5)]

    tick()
    root.mainloop()

if __name__ == '__main__':
    main()
