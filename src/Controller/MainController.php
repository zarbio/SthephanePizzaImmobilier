<?php

namespace App\Controller;

use App\Entity\Logement;
use App\Form\LogementType;
use App\Form\SearchType;
use App\Repository\LogementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function home(LogementRepository $logementRepository,Request $request,\Doctrine\Persistence\ManagerRegistry $managerRegistry,PaginatorInterface $paginator): Response
    {
        $repository = $managerRegistry->getRepository(Logement::class);
        $logements = new Logement();
        $logement = new Logement();
        $form = $this->createForm(SearchType::class);
        if (file_exists('filter.txt'))
        {
        $param = explode("\n",file_get_contents('filter.txt'));
            $form->get('superficie')->setData($param[0]);
            $form->get('nombrePieces')->setData($param[1]);
            $form->get('typeLogement')->setData($param[2]);
            $form->get('adresse')->setData($param[3]);
            if($param[4] == 1)
            {
                $form->get('piscine')->setData(true);
            }
            else{
                $form->get('piscine')->setData(false);
            }
            $form->get('exterieur')->setData($param[5]);
            if($param[6] == 1)
            {
                $form->get('garage')->setData(true);
            }else{
                $form->get('garage')->setData(false);
            }

            $form->get('typeVente')->setData($param[7]);
            $form->get('prix')->setData($param[8]);
            $form->get('prixMax')->setData($param[9]);

        }
        $form->handleRequest($request);
        if($form->isSubmitted() || file_exists('filter.txt') )
        {
            $logements = $repository->sortByCriterias($form->get('superficie')->getData(),$form->get('nombrePieces')->getData(),$form->get('typeLogement')->getData(),$form->get('adresse')->getData(),$form->get('piscine')->getData(),$form->get('exterieur')->getData(),$form->get('garage')->getData(),$form->get('typeVente')->getData(),$form->get('prix')->getData(),$form->get('prixMax')->getData());
            $pagination = $paginator->paginate($logements,$request->query->getInt('page',1),10);
            file_put_contents('filter.txt',$form->get('superficie')->getData()."\n".$form->get('nombrePieces')->getData()."\n".$form->get('typeLogement')->getData()."\n".$form->get('adresse')->getData()."\n".$form->get('piscine')->getData()."\n".$form->get('exterieur')->getData()."\n".$form->get('garage')->getData()."\n".$form->get('typeVente')->getData()."\n".$form->get('prix')->getData()."\n".$form->get('prixMax')->getData()."\n");
        }
        else{
            $logements = $logementRepository->findBy([],['dateParution'=>'DESC']);
            $pagination = $paginator->paginate($logements,$request->query->getInt('page',1),10);
            if(file_exists('filter.txt'))
            {
                unlink('filter.txt');
            }

        }
        return $this->renderForm('main/home.html.twig', ['form'=>$form,
        'pagination'=>$pagination
        ]);
    }
    /**
     * @Route("/CGU", name="main_CGU")
     */
    public function CGU(): Response
    {
        return $this->render('main/CGU.html.twig', [

        ]);
    }
    /**
     * @Route("/mentions", name="main_mentions")
     */
    public function mentions(): Response
    {
        return $this->render('main/mentions.html.twig', [

        ]);
    }
    /**
     * @Route("/qui", name="main_qui")
     */
    public function qui(): Response
    {
        return $this->render('main/qui.html.twig', [

        ]);
    }
    /**
     * @Route("/logement/{id}", name="main_ajoutModifier",defaults={"id" = ""})
     */
    public function ajoutModifier(SluggerInterface $slugger,Request $request,EntityManagerInterface $entityManager,LogementRepository $logementRepository,ValidatorInterface $validator): Response
    {
        $logement = new Logement();

        $form = $this->createForm(LogementType::class,$logement);
        if ($request->get("id") != null)
        {
            $logement = $entityManager->getRepository(Logement::class)->find($request->get("id"));
            if($logement != null)
            {
                $modifier = true;
                $form->get("superficie")->setData($logement->getSuperficie());
                $form->get("nombrePieces")->setData($logement->getNombrePieces());
                $form->get("typeLogement")->setData($logement->getTypeLogement());
                $form->get("adresse")->setData($logement->getAdresse());
                $form->get("exterieur")->setData($logement->getExterieur());
                $form->get("prix")->setData($logement->getPrix());
                $form->get("piscine")->setData($logement->isPiscine());
                $form->get("garage")->setData($logement->isGarage());
                if ($logement->getImage() != null)
                {
                    $form->get("image")->setData(new File($this->getParameter('upload_dir').'/'.$logement->getImage()));

                }
            }
            else{
                $modifier= false;
            }

        }
        else{
            $modifier= false;
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $modifier == false)
        {
            $logement = $form->getData();
            $img = $form->get('image')->getData();
            if($img)
            {
                $originalFileName = pathinfo($img->getClientOriginalName(),PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$img->guessExtension();
                try {
                    $img->move($this->getParameter('upload_dir'),$newFileName);
                }
                catch (FileException $exception){

                }
                $logement->setImage($newFileName);
            }
            $logement->setdateParution(new \DateTime());
            $entityManager->persist($logement);
            $entityManager->flush($logement);
            return $this->redirectToRoute('main_home');
        }
        elseif ($form->isSubmitted()  && $modifier == true )
        {

            $logementBis = $form->getData();
            $errors = $validator->validate($logementBis,null,['modifier']);
            if(count($errors)>0)
            {
                $errorsString =(string) $errors;
                return new Response($errorsString);
            }
            if ($logement->getAdresse() !== $form->get('adresse')->getData())
            {
                $error = "adresse non modifiable";
                return new Response($error);
            }
            $img = $form->get('image')->getData();
            if($img)
            {
                $originalFileName = pathinfo($img->getClientOriginalName(),PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$img->guessExtension();
                try {
                    $img->move($this->getParameter('upload_dir'),$newFileName);
                }
                catch (FileException $exception){

                }
                $logement->setImage($newFileName);
            }
            $logement->setSuperficie($logementBis->getSuperficie());
            $logement->setNombrePieces($logementBis->getNombrePieces());
            $logement->setTypeLogement($logementBis->getTypeLogement());
            $logement->setPiscine($logementBis->isPiscine());
            $logement->setExterieur($logementBis->getExterieur());
            $logement->setGarage($logementBis->isGarage());
            $logement->setTypeVente($logementBis->getTypeVente());
            $logement->setPrix($logementBis->getPrix());
            $logement->setdateModification(new \DateTime());
            $entityManager->flush();
            return $this->redirectToRoute('main_home');
        }
        return $this->renderForm('main/ajoutModifier.html.twig', ['form'=>$form

        ]);
    }
    /**
     * @Route("/details/{id}", name="main_details")
     */
    public function details(Logement $logement,LogementRepository $logementRepository,Request $request): Response
    {
        $logement = $logementRepository->find($request->get('id'));
        return $this->render('main/details.html.twig', ['logement'=>$logement

        ]);
    }
}
