# -*- coding: utf-8 -*-

from frame_new_direction import *

class UpdateDirection(NewDirection):
    direction = None
    def __init__(self, root, data, update_document_window, document, id_direction):
        super().__init__(root, data, update_document_window, document)
        self.get_direction_info(id_direction)
        self.init_update_direction()
        self.load_info()

    def init_update_direction(self):
        self.title("Обновить направление")
        self.btn_ok.destroy()
        self.btn_ok_update = tk.Button(self, text='Изменить')
        self.btn_ok_update.bind('<Button-1>', lambda event: self.update_direction())
        #self.btn_ok_update.place(x=170, y=300)
        self.btn_ok_update.grid(row=8, column=0, pady=10)

    def get_direction_info(self, id_direction):
        for direction in self.document.directions:
            if(direction.id == int(id_direction)):
                self.direction = direction
                break

    def load_info(self):
        if(self.direction.date_shipment != None):
            self.entry_date_shipment.insert(0, self.direction.date_shipment)
        if(self.direction.executor_shipment != None):
            k = -1
            for x in self.parent_data.executors:
                k+=1
                if(x.id == self.direction.executor_shipment):
                    break
            if(k >= 0):
                self.combo.current(k)
        if(self.direction.date_execute != None):
            self.entry_date_execute.insert(0, self.direction.date_execute)
        if(self.direction.instruction != None):
            self.txt_instruction.insert(tk.INSERT, self.direction.instruction)
        if(self.direction.executor_return != None):
            k = -1
            for x in self.parent_data.executors:
                k+=1
                if(x.id == self.direction.executor_return):
                    break
            if(k >= 0):
                self.combo_return.current(k)
        if(self.direction.date_return != None):
            self.entry_date_return.insert(0, self.direction.date_return)
        if(self.direction.result_instruction != None):
            self.txt_instruction_result.insert(tk.INSERT, self.direction.result_instruction)

    def update_direction(self):
        if(self.entry_date_shipment.get() != None and self.entry_date_shipment.get() != '' and self.combo.get() != None and self.combo.get() != '' and self.entry_date_execute.get() != None and self.entry_date_execute.get() != ''):
            check_date = True
            try:
                datetime.strptime(self.entry_date_execute.get(),'%d.%m.%Y')
                split_date = self.entry_date_execute.get().split('.')
                k = date(year=int(split_date[2]), month=int(split_date[1]), day=int(split_date[0]))
            except:
                check_date = False
            if(check_date == True):
                direction = self.direction
                direction.date_shipment = self.entry_date_shipment.get()
                direction.executor_shipment = None
                for executor in self.parent_data.executors:
                    if(executor.surname == self.combo.get()):
                        direction.executor_shipment = executor.id
                        break
                direction.name_executor_shipment = self.combo.get()
                direction.date_execute = self.entry_date_execute.get()
                direction.instruction = self.txt_instruction.get('1.0', tk.END)
                direction.date_return = self.entry_date_return.get()
                direction.executor_return = None
                for executor in self.parent_data.executors:
                    if(executor.surname == self.combo_return.get()):
                        direction.executor_return = executor.id
                        break
                direction.name_executor_return = self.combo_return.get()
                direction.result_instruction = self.txt_instruction_result.get('1.0', tk.END)
                self.parent_data.db.update_direction(direction)

                self.update_document_window.refresh_document_info()
                self.destroy()                
            else:
                messagebox.showinfo('Внимание!', 'Дата исполнения должна являться датой формата: день.месяц.год!')
        else:
            messagebox.showinfo('Внимание!', 'Заполните поле даты внесения, исполнителя и дата исполнения!')
        
