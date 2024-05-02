<?php
namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\Persistence\ManagerRegistry as PersistanceManagerRegistry;
use App\Form\ArticleType;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Entity\CategorySearch;
use App\Form\CategorySearchType;
use App\Entity\PriceSearch;
use App\Form\PriceSearchType;

class IndexController extends AbstractController
{

    #[Route("/",name:'article_list')]

 public function home(PersistanceManagerRegistry $doctrine, Request $request)
{
  $propertySearch = new PropertySearch();
  $form = $this->createForm(PropertySearchType::class,$propertySearch);
  $form->handleRequest($request);
  $articles= [];
if($form->isSubmitted() && $form->isValid()) {
  $Nom = $propertySearch->getNom();
if ($Nom!="")
$articles = $doctrine->getRepository(Article::class)->findBy(['Nom' => $Nom]);

else
//si si aucun nom n'est fourni on affiche tous les articles
$articles= $doctrine->getRepository(Article::class)->findAll();
}
return $this->render('articles/index.html.twig',[ 'form' =>$form->createView(), 'articles' => $articles]);
}



#[Route('/save', name: 'save-article')]
public function save(PersistanceManagerRegistry $doctrine){
   $entityManager = $doctrine->getManager();
   $article = new Article();
   $article->setNom('Article 3');
   $article->setPrix(2080);
  
   $entityManager->persist($article);
   $entityManager->flush();
   return new Response('Article enregisté avec id '.$article->getId());
}



#[Route('/new', name: 'new_article', methods:['GET','POST'])]
    public function new(PersistanceManagerRegistry $managerRegistry,Request $request)  {
      $article = new Article();
      $form = $this->createForm(ArticleType::class,$article);
      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid()) 
      { 
        $article = $form->getData();
        $entityManager =$managerRegistry->getManager();
        $entityManager->persist($article);
        $entityManager->flush();
        return $this->redirectToRoute('article_list');
    }
    return $this->render('articles/new.html.twig',['form' => $form->createView()]);
  }

 #[Route("/article/{id}", )]

public function show($id,PersistanceManagerRegistry $doctrine) {
    $article = $doctrine->getRepository(Article::class)
    ->find($id);
    return $this->render('articles/show.html.twig', array('article' => $article));
    }

    #[Route('/article/edit/{id}',name:"edit_article",methods:['GET','POST'])]
    public function edit(PersistanceManagerRegistry $managerRegistry,Request $request,$id)  {
      $article = new Article();
      $article=$managerRegistry->getRepository(Article::class)->find($id);
      $form = $this->createForm(ArticleType::class,$article);
      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid()) 
      { 
        $entityManager = $managerRegistry->getManager(); 
        $entityManager->flush(); 
        return $this->redirectToRoute('article_list');
    }
    return $this->render('articles/edit.html.twig', ['form' => $form->createView()]);
  }
 #[Route("/article/delete/{id}")]

public function delete(Request $request, $id,PersistanceManagerRegistry $doctrine) {
    $article = $doctrine->getRepository(Article::class)->find($id);
    $entityManager = $doctrine->getManager();
    $entityManager->remove($article);
    $entityManager->flush();
    $response = new Response();
    $response->send();
    return $this->redirectToRoute('article_list');
    }

#[Route("/category/newCat",name: 'new_category', methods:['GET','POST'])]
public function newCategory(Request $request,PersistanceManagerRegistry $doctrine) {
  $category = new Category();
  $form = $this->createForm(CategoryType::class,$category);
  $form->handleRequest($request);
  if($form->isSubmitted() && $form->isValid()) {
  $article = $form->getData();
  $entityManager = $doctrine->getManager();
  $entityManager->persist($category);
  $entityManager->flush();
  }
  return $this->render('articles/newCategory.html.twig',['form'=>
  $form->createView()]);
  }

#[Route('/art_cat/', name: 'article_par_cat', methods:['GET','POST'])]
public function articlesParCategorie(Request $request,PersistanceManagerRegistry $doctrine) {
  $categorySearch = new CategorySearch();
  $form = $this->createForm(CategorySearchType::class,$categorySearch);
  $form->handleRequest($request);
  $articles= [];
  if($form->isSubmitted() && $form->isValid()) {
  $category = $categorySearch->getCategory();
  if ($category!="")
  $articles= $category->getArticles();
  else
  $articles= $doctrine->getRepository(Article::class)->findAll();
  }
  return $this->render('articles/articlesParCategorie.html.twig',['form' => $form->createView(),'articles' => $articles]);
  }


#[Route('/art_prix/', name: 'article_par_prix', methods:['GET','POST'])]
public function articlesParPrix(Request $request,PersistanceManagerRegistry $doctrine)
{
$priceSearch = new PriceSearch();
$form = $this->createForm(PriceSearchType::class,$priceSearch);
$form->handleRequest($request);
$articles= [];
if($form->isSubmitted() && $form->isValid()) {
$minPrice = $priceSearch->getMinPrice();
$maxPrice = $priceSearch->getMaxPrice();
$articles= $doctrine->
getRepository(Article::class)->findByPriceRange($minPrice,$maxPrice);
}
return $this->render('articles/articlesParPrix.html.twig',[ 'form' =>$form->createView(), 'articles' => $articles]);
}
}