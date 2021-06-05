# -*- coding: utf-8 -*-

import hashlib
import os
import shutil

class Resolution:
    id = None
    id_document = None
    path_resolution = None
    name_resolution = None

    @staticmethod
    def copy_resolution(settings, old_path_file):
        # Получаем MD5 файла
        md5 = Resolution.create_md5(old_path_file)

        # Создаём необходимые директории
        if(os.path.exists(settings.path_resolution + '/' + md5[0] + md5[1]) == False):
            os.mkdir(settings.path_resolution + '/' + md5[0] + md5[1])
            os.chmod(settings.path_resolution + '/' + md5[0] + md5[1], 777)
        if(os.path.exists(settings.path_resolution + '/' + md5[0] + md5[1] + '/' + md5[2] + md5[3]) == False):
            os.mkdir(settings.path_resolution + '/' + md5[0] + md5[1] + '/' + md5[2] + md5[3])
            os.chmod(settings.path_resolution + '/' + md5[0] + md5[1] + '/' + md5[2] + md5[3], 777)
        if(os.path.exists(settings.path_resolution + '/' + md5[0] + md5[1] + '/' + md5[2] + md5[3] + '/' + md5[4] + md5[5]) == False):
            os.mkdir(settings.path_resolution + '/' + md5[0] + md5[1] + '/' + md5[2] + md5[3] + '/' + md5[4] + md5[5])
            os.chmod(settings.path_resolution + '/' + md5[0] + md5[1] + '/' + md5[2] + md5[3] + '/' + md5[4] + md5[5], 777)
            
        # Формируем новый путь
        new_path = settings.path_resolution + '/' + md5[0] + md5[1] + '/' + md5[2] + md5[3] + '/' + md5[4] + md5[5] + '/' + os.path.basename(old_path_file)

        # Копируем файл с ЗАМЕНОЙ!
        shutil.copyfile(old_path_file, new_path)

        return new_path

    @staticmethod
    def create_md5(path_file):
        hash_md5 = hashlib.md5()
        with open(path_file, 'rb') as f:
            for byte_block in iter(lambda: f.read(4096),b""):
                hash_md5.update(byte_block)
        return hash_md5.hexdigest()

    @staticmethod
    def delete_resolution(path_file):
        if(os.path.exists(path_file)):
            os.remove(path_file)
    
