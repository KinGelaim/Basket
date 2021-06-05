# -*- coding: utf-8 -*-

from random import randrange as rnd, choice
import tkinter as tk
import math
import time


def main():
    global root, canv, t1, screen1, gun1, bullet, balls
    
    root = tk.Tk()
    root.geometry('800x600')
    canv = tk.Canvas(root, bg='white')
    canv.pack(fill=tk.BOTH, expand=1)

    t1 = Target()
    screen1 = canv.create_text(400, 300, text='', font='28')
    gun1 = Gun()
    bullet = 0
    balls = []


class Ball:
    def __init__(self, x = 40, y = 450):
        self.x = x
        self.y = y
        self.r = rnd(10, 20)
        self.vx = 0
        self.vy = 0
        self.color = choice(['blue', 'green', 'red', 'brown'])
        self.id = canv.create_oval(
            self.x - self.r,
            self.y - self.r,
            self.x + self.r,
            self.y + self.r,
            fill = self.color
        )
        self.live = 170

    def set_coords(self):
        canv.coords(
            self.id,
            self.x - self.r,
            self.y - self.r,
            self.x + self.r,
            self.y + self.r
        )

    def move(self):
        self.x += self.vx
        self.y += self.vy
        self.live -= 1

    def hittest(self, obj):
        length = math.sqrt((obj.x - self.x)**2 + (obj.y - self.y) ** 2)
        summ_radius = self.r + obj.r
        if length <= summ_radius:
            return True
        return False


class Gun:
    def __init__(self):
        self.f2_power = 10
        self.f2_on = 0
        self.an = 1
        self.id = canv.create_line(20, 450, 50, 420, width = 7)

    def fire2_start(self, event):
        self.f2_on = 1

    def fire2_end(self, event):
        global balls, bullet
        bullet += 1
        new_ball = Ball()
        new_ball.r += 5
        self.an = math.atan((event.y - new_ball.y) / (event.x - new_ball.x))
        new_ball.vx = self.f2_power * math.cos(self.an)
        new_ball.vy = self.f2_power * math.sin(self.an)
        balls += [new_ball]
        self.f2_on = 0
        self.f2_power = 10

    def targetting(self, event = 0):
        if event:
            self.an = math.atan((event.y - 450) / (event.x - 20))
        if self.f2_on:
            canv.itemconfig(self.id, fill='orange')
        else:
            canv.itemconfig(self.id, fill='black')
        canv.coords(self.id, 20, 450,
                    20 + max(self.f2_power, 20) * math.cos(self.an),
                    450 + max(self.f2_power, 20) * math.sin(self.an)
                    )

    def power_up(self):
        if self.f2_on:
            if self.f2_power < 100:
                self.f2_power += 1
            canv.itemconfig(self.id, fill='orange')
        else:
            canv.itemconfig(self.id, fill='black')


class Target:
    def __init__(self):
        self.points = 0
        self.live = 1
        self.id = canv.create_oval(0,0,0,0)
        self.id_points = canv.create_text(30, 30, text = self.points, font = '28')
        self.new_target()

    def new_target(self):
        x = self.x = rnd(500, 780)
        y = self.y = rnd(550, 750)
        r = self.r = rnd(2, 50)
        color = self.color = 'red'
        canv.coords(self.id, x - r, y - r, x + r, y + r)
        canv.itemconfig(self.id, fill=color)

    def hit(self, points=1):
        canv.coords(self.id, -10, -10, -10, -10)
        self.points += points
        canv.itemconfig(self.id_points, text=self.points)


def new_game(event=''):
    global gun, t1, screen1, balls, bullet
    t1.new_target()
    bullet = 0
    balls = []
    canv.bind('<Button-1>', gun1.fire2_start)
    canv.bind('<ButtonRelease-1>', gun1.fire2_end)
    canv.bind('<Motion>', gun1.targetting)

    z = 0.03
    t1.live = 1
    while t1.live or balls:
        for b in balls:
            b.move()
            b.set_coords()
            if b.live <= 0:
                canv.delete(b.id)
                balls.remove(b)
            if b.hittest(t1) and t1.live:
                t1.live = 0
                t1.hit()
                canv.bind('<Button-1>', '')
                canv.bind('<ButtonRelease-1>', '')
                canv.itemconfig(screen1, text='Вы уничтожили цель за ' + str(bullet) + ' выстрелов')
        canv.update()
        time.sleep(0.03)
        gun1.targetting()
        gun1.power_up()
    print('end while')
    canv.itemconfig(screen1, text='')
    #canv.delete(gun)
    root.after(750, new_game)


if __name__ == '__main__':
    main()
    new_game()
    root.mainloop()
