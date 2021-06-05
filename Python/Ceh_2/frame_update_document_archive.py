# -*- coding: utf-8 -*-

from frame_update_document import *

class UpdateDocumentArchive(UpdateDocument):
    parent_root = None
    parent_data = None
    document = None
    def __init__(self, root, data, id_document):
        super().__init__(root, data, id_document)

    # Переопределяем метод, чтобы искать в архиве, а не во всех документах
    def get_document_info(self, id_document):
        for document in self.parent_data.archive_documents:
            if(document.id == int(id_document)):
                self.document = document
                break
        self.parent_data.db.get_resolutions(self.document)
        self.parent_data.db.get_directions(self.document)

    def update_document(self):
        self.parent_data.db.update_document(self.document.id, self.txt_name_document.get("1.0", tk.END), self.entry_date.get(), self.chk_state.get())
        self.parent_data.archive_window.refresh_all_archives()
        if(self.is_change_archive):
            self.parent_data.all_documents.refresh_all_documents()
        self.destroy()
