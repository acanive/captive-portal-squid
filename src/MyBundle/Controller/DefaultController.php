<?php

namespace MyBundle\Controller;

use MyBundle\Entity\Avatar;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Ldap\LdapClient;

use MyBundle\Utils\ldap;
use MyBundle\Utils\conexion;
use MyBundle\Utils\memcache;
use MyBundle\Utils\data;

class DefaultController extends Controller
{
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        $error_show = 'Credenciales Inválidas';

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('default/login.html.twig',array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'error_show'    => $error_show,
        ));
    }
    public function perfilAction(Request $request)
    {
        $avatar = new Avatar();
        $ldap = new ldap();
        $Data = new Data();
        $conexion = new conexion();
        $memcache = new memcache();
        $usuario = $this->getUser();
        $data = $ldap->userInfo($usuario);
        $array_kuotas = $Data->getData();
        $kuota = "";

        /*Get assigned kuota*/
        $directoryPath = $this->container->getParameter('kernel.root_dir') . '/../web/Files/squish.conf';
        $kuota_assigned = $Data->readFile($directoryPath, $usuario);

        /*Get kuota*/
        for ($i = 6; $i <= count($array_kuotas); $i++) {
            $user_and_kuota = explode('-', $array_kuotas[$i]);
            if (($user_and_kuota[0] == $usuario)) {
                $foo = substr(preg_replace('/\s+/', '', $user_and_kuota[1]),-2);
                if ($foo == 'Gb') {
                    $kuota = intval(preg_replace('/[^0-9]+/', '', $user_and_kuota[1]), 10);
                }
                elseif ($foo == 'mb') {
                    $arr = preg_split('/(?<=[0-9])(?=[a-z]+)/i',$user_and_kuota[1]);
                    $kuota = $arr[0].'-Mb';
                }
            }
        }
        $percent_internet = $kuota / $kuota_assigned * 100;

        /*Email database*/
        if ($conexion->conexion_db($data['user_usuario']) != 0) {
            $result = $conexion->conexion_db($data['user_usuario']);
        }
         else {
             $result =0;
         }

         /*Insert avatar*/
        $formAvatar = $this->createForm('MyBundle\Form\AvatarType', $avatar);
        $formAvatar->handleRequest($request);
        if ($formAvatar->isSubmitted() && $formAvatar->isValid()){
            $em = $this->getDoctrine()->getManager();
            $user_avatar = $em->getRepository('MyBundle:Avatar')->findOneByUser($usuario);
            $avatar->setUser($data['user_usuario']);
            if ($user_avatar == null){
                $em->persist($avatar);
                $em->flush();
                $avatar->upload($usuario.'-'.md5(microtime().rand()));
                $em->flush();
            }
            else {
                $avatar->removeUpload($user_avatar->getImage());
                $em->remove($user_avatar);
                $em->flush();
                $avatar->upload($usuario.'-'.md5(microtime().rand()));
                $em->persist($avatar);
                $em->flush();
            }
            return $this->redirectToRoute('perfil');
        }

        /*Change password*/
        $form = $this->createForm('MyBundle\Form\PasswordType');
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            $oldPassword = $form["oldPassword"]->getData();
            $newPassword = $form["password"]->getData();
            $error_password = $ldap->changePass($usuario, $oldPassword, $newPassword);
            if (!$error_password)
            {
                $this->get('session')->getFlashBag()->add(
                    'wrongPass',
                    'Su contraseña no coincide.'

                );
            }
            else {
                $this->get('session')->getFlashBag()->add(
                    'correctPass',
                    'Usted a cambiado su contraseña.'
                );
            }
            return $this->redirectToRoute('perfil');
        }
        $em = $this->getDoctrine()->getManager();
        $avata = $em->getRepository('MyBundle:Avatar')->findByUser($data['user_usuario']);

        return $this->render('default/profile.html.twig', array(
            'form' => $form->createView(),
            'formAvatar' => $formAvatar->createView(),
            'data' => $data,
            'avatar' => $avata,
            'result' => $result,
            'kuota' => $kuota,
            'kuota_assigned' => $kuota_assigned,
            'percent_internet' => $percent_internet
        ));
    }

    public function avatarDeleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $avatar = $em->getRepository('MyBundle:Avatar')->findOneById($id);
        if (!$avatar) {
            throw $this->createNotFoundException('Unable to find Aplicaciones entity.');
        }
        $avatar->removeUpload($avatar->getImage());
        $em->remove($avatar);
        $em->flush();

        return $this->redirectToRoute('perfil');
    }

}
