<?php

namespace Login\LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Login\LoginBundle\Entity\Users;
use Login\LoginBundle\Modals\Login;
use Login\LoginBundle\Modals\Document;
//use Symfony\Component\HttpFoundation\File\UploadedFile;

class DefaultController extends Controller {

    public function loginAction(Request $request) {
        $session = $request->getSession();

        if ($request->getMethod() == 'POST') {
            $session->clear();
            // get the form values
            $username = $request->get('username');
            $password = md5($request->get('password'));
            $remember = $request->get('remember');

            // acces the database
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('LoginLoginBundle:Users');

            $user = $repository->findOneBy(array('username' => $username, 'password' => $password));

            // if user is submited
            if ($user) {
                // remember
                if ($remember === 'remember_me') {
                    $login = new Login();
                    $login->setUsername($username);
                    $login->setPassword($password);
                    $session->set('login', $login);
                }

                return $this->render('LoginLoginBundle:Default:welcome.html.twig', array('name' => $user->getUsername()));
            }
            return $this->render('LoginLoginBundle:Default:login.html.twig', array('error' => 'Invalid credentials!', 'user' => $username));
        } else {
            if ($session->has('login')) {
                // access the database
                $em = $this->getDoctrine()->getManager();
                $repository = $em->getRepository('LoginLoginBundle:Users');

                $login = $session->get('login');
                $username = $login->getUsername();
                $password = $login->getPassword();
                $user = $repository->findOneBy(array('username' => $username, 'password' => $password));

                if ($user) {
                    return $this->render('LoginLoginBundle:Default:welcome.html.twig', array('name' => $user->getUsername()));
                }
            }
        }
        return $this->render('LoginLoginBundle:Default:login.html.twig');
    }

    public function signupAction(Request $request) {
        if ($request->getMethod() == 'POST') {
            $username = $request->get('username');
            $email = $request->get('email');
            $password = md5($request->get('password'));
            $repeat_password = md5($request->get('repeat_password'));

            // set the values to be entered in database
            $user = new Users();
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setPassword($password);

            if (($password === $repeat_password) && ($password !== null)) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                return $this->render('LoginLoginBundle:Default:welcome.html.twig', array('name' => $user->getUsername()));
            } else {
                return $this->render('LoginLoginBundle:Default:signup.html.twig', array('error' => 'Invalid credentials!', 'user' => $username, 'email' => $email));
            }
        }
        return $this->render('LoginLoginBundle:Default:signup.html.twig');
    }

    public function logoutAction(Request $request) {
        $session = $request->getSession();
        $session->clear();
        return $this->render('LoginLoginBundle:Default:login.html.twig');
    }

    public function welcomeAction(Request $request) {
//        if ($request->getMethod() == 'POST') {
//            $image = $request->files->get('img');
//
//            $status = 'success';
//            $uploadedUrl = '';
//            $message = '';
//            if (($image instanceof UploadedFile) && ($image->getError() == '0')) {
//                if (($image->getSize() < 2000000)) {
//                    $originalname = $image->getClientOriginalName();
//                    $nameArray = explode('.', $originalname);
//                    $fileType = $nameArray[sizeof($nameArray) - 1];
//                    $validFileType = array('jpg', 'jpeg', 'bmp', 'png');
//
//                    if (in_array(strtolower($fileType), $validFileType)) {
//                        // start uploadeding file
                        $document = new Document();
                        $form = $this->container->get('form.factory')->create(new DocumentType(), $document);
                        $request = $this->container->get('request');

                        if ($request->getMethod() == 'POST') {
                            if ($form->isValid()) {
                                $document->processFile();
                            }
                        }
//                    } else {
//                        $status = 'failed';
//                        $message = 'Invalit File Type!';
//                    }
//                } else {
//                    $status = 'failed';
//                    $message = 'Size excededs the limit!';
//                }
//            } else {
//                $status = 'failed';
//                $message = 'File Error!';
//            }
//            return $this->render('LoginLoginBundle:Default:welcome.html.twig', array('status' => $status, 'message' => $message, 'uploadedURL' => $uploadedUrl));
//        } else {
//            return $this->render('LoginLoginBundle:Default:welcome.html.twig');
//        }
    }

}
