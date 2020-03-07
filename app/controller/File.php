<?php
namespace App\Controller;

use Base\Controller;

class File extends Controller
{
    public function upload()
    {
        $user = new \App\Model\User();
        $user->setId(4);

        if (!empty($_FILES['file'])) {
            $file = new \App\Model\File([
                'user_id' => $user->getId(),
                'upload_at' => date('Y-m-d H:i:s'),
                'name' => $_FILES['file']['name'],
                'size' => $_FILES['file']['size']
            ]);
            $file->save();

            $user->updateUserPhoto($user->getId(), $file->getId());
        }

        $files = \App\Model\File::getFilesByUserId($user->getId());

        $this->view->render(
            'file/upload.phtml',
            [
                'title' => 'Загрузка файла',
                'files' => $files
            ]
        );
    }

    public function read()
    {
        $id = $_GET['file_id'];
        $file = (new \App\Model\File())->getById($id);
        $data = file_get_contents('upload/files/user/' . $file->name);
        header('Content-type: image/jpeg');
        echo $data;
    }
}