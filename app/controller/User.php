<?php
namespace App\Controller;

use Base\Controller;
use Base\Model;

class User extends Controller
{
    public function login()
    {
        $this->view->render('user/login.phtml', ['title' => 'Форма авторизация']);
    }

    public function register()
    {
        if ($_POST['name']) {
            $user = new \App\Model\User();
            $user->setName($_POST['name'])
                ->setBirthDate('1999-04-01')
                ->setPassword(sha1('.,sdf.' , $_POST['password']))
                ->setPhotoId(0);
            $user->save();
            $this->redirect('/user/login?success=1');
        } else {
            $this->view->render('user/register.phtml', ['title' => 'Форма регистрации']);
        }
    }

    public function userlist()
    {
        $limit = $_GET['limit'] ?? 10;
        $offset = $_GET['offset'] ?? 0;
        /** @var \App\Model\User[] $users */
        $users = \App\Model\User::getList((int) $limit, (int) $offset);

        $this->view->render(
            'user/list.phtml',
            ['users' => $users]
        );
    }
}