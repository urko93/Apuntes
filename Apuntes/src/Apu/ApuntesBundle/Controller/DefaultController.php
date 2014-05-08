<?php

namespace Apu\ApuntesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Apu\ApuntesBundle\Entity\Apunte;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function nuevoApunteAction(Request $request)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
    		throw new AccessDeniedException();
    	}
    	
    	$apunte = new Apunte();
    	
    	$apunte->setUser($this->getUser());
    	
    	$form = $this->createFormBuilder($apunte)
    	->setAction($this->generateUrl('apu_apuntes_nuevo'))
    	->add('descripcion')
    	->add('nombre')
    	->add('file')
    	->getForm();
    
    	$form->handleRequest($request);
    	
    	if ($form->isSubmitted()) {    	
    		$em = $this->getDoctrine()->getManager();
		    $em->persist($apunte);
		    $em->flush();
		    
		    return $this->render('ApuApuntesBundle:Default:subidaOK.html.twig');
		    //return $this->redirect($this->generateUrl('homepage'));
    	}
    	
    	return $this->render('ApuApuntesBundle:Default:index.html.twig', array(
    			'form' => $form->createView(),
    	));
    }
    
    public function descargarApunteAction(Request $request, $id, $contra)
    {
    	$em = $this->getDoctrine()->getManager();
    	$apunte = $em->getRepository('ApuApuntesBundle:Apunte')->find($id);
    	
    	if ( ($apunte and $apunte->getContra()!=$contra) and false === $this->get('security.context')->isGranted('ROLE_USER')) {
    		throw new AccessDeniedException();
    	}

    	
    	if (!$apunte) throw $this->createNotFoundException('No product found for id '.$id);
 
    	$filename = $apunte->getAbsolutePath();
    	
		// Generate response
		$response = new Response();
		
		// Set headers
		$response->headers->set('Cache-Control', 'private');
		$response->headers->set('Content-type', mime_content_type($filename));
		$response->headers->set('Content-Disposition', 'attachment; filename="' . basename($filename) . '"');
		$response->headers->set('Content-length', filesize($filename));
		
		// Send headers before outputting anything
		$response->sendHeaders();
		
		$response->setContent(readfile($filename));
		
		return $response;
    }
    
    public function verTodosApunteAction(Request $request)
    {   	 
    	$em = $this->getDoctrine()->getManager();
    	$repository = $this->getDoctrine()->getRepository('ApuApuntesBundle:Apunte');
    	$apuntes = $repository->findAll();
    	 
    	if (!$apuntes) ;//throw $this->createNotFoundException('No product found for id ');
    
    	return $this->render('ApuApuntesBundle:Default:producto.html.twig',array('apuntes' => $apuntes));
    	
    }
    
    
    public function borrarApunteAction(Request $request, $id)
    {
    	if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
    		throw new AccessDeniedException();
    	}
    	 
    	$em = $this->getDoctrine()->getManager();
    	$apunte = $em->getRepository('ApuApuntesBundle:Apunte')->find($id);
    	
    	if (!$apunte){
    		return $this->redirect($this->generateUrl('homepage'));
    	}
    
    	if ((true == $this->get('security.context')->isGranted('ROLE_ADMIN')) or ($apunte->getUser()->getId() == $this->get('security.context')->getToken()->getUser()->getId())) {
    		$em->remove($apunte);
    		$em->flush();
    		 
    		return $this->verTodosApunteAction($request);
    	} else {
    		throw new AccessDeniedException();
    	}
    }
}


