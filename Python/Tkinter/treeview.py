# -*- coding: utf-8 -*-

import tkinter as tk

def reloadTreeViewData(self):
    # get current size of main window
    curMainWindowHeight = self.parent.winfo_height()
    curMainWindowWidth = self.parent.winfo_width()

    #delete all
    for child in self.tree.get_children():
        self.dicData = {}
        self.tree.delete(child)

    # reload all
    count = 0
    for row in self.data:
        adding_string = []
        for element in self.indexlist:
            adding_string.append(str(row[element]))
        insert_ID = self.tree.insert('', 'end', text=count, values=adding_string)
        # save the data in it's original types in a dictionary
        self.dicData[insert_ID] = [row[x] for x in self.indexlist]
        count += 1

    # reset column width
    max_colum_widths = [0] * len(self.indexlist)
    for child in self.tree.get_children():
        rowData = self.dicData[child]
        for idx, rowIdx in enumerate(self.indexlist):
            item = rowData[idx]
            new_length = self.myFont.measure(str(item))
            if new_length > max_colum_widths[idx]:
                max_colum_widths[idx] = new_length
    for idx, item in enumerate(self.attributeList):
        if int(max_colum_widths[idx]) < 50:
            self.tree.column(item, width=50)
        else:
            self.tree.column(item, width=int(max_colum_widths[idx]))

    # restore the size of the mainWindow
    self.parent.update()
    self.parent.geometry("" + str(curMainWindowWidth) + "x" + str(curMainWindowHeight))

root = tk.Tk()



