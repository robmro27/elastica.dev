<?php

namespace AppBundle\Controller;

use AppBundle\Model\CodeSearch;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

/**
 *
 * @author rmroz
 */
class CodeController extends Controller {
    
    
    /**
     * @Route("/codes/{page}/{pageCity}/{pageStreet}", name="code_list", defaults={"page" = 1, "pageCity" = 1, "pageStreet" = 1},options={"expose"=true})
     */
    public function listAction( Request $request, $page ,$pageCity, $pageStreet )
    {
        $codeSearch = new CodeSearch();
        $codeSearch->handleRequest($request);
        
        $codeSearchForm = $this->get('form.factory')
                ->createNamed(
                        'code_search_type',
                        \AppBundle\Form\Type\CodeSearchType::class,
                        $codeSearch,
                        [
                          'csrf_protection' => false,
                          'action' => $this->generateUrl('code_list')  
                        ]
                        );
        
        $codeSearchForm->handleRequest( $request );
        $codeSearch = $codeSearchForm->getData();
        $elasticaManager = $this->container->get('fos_elastica.manager');
        
        
        // search and group by cities
        $cityPagerData = [];
        $cityQuery = $elasticaManager->getRepository('AppBundle:Code')->getQueryForSearchGroupByCity($codeSearch);
        $cityResults = $this->get('fos_elastica.index.postal_codes.code')->search($cityQuery)->getAggregations();   
        if ( $cityResults ) {
            $cityPagerData = $cityResults['city']['buckets'];
        }  
        
        $cityAdapter = new ArrayAdapter($cityPagerData);
        $cityPager = new Pagerfanta($cityAdapter);
        $cityPager->setMaxPerPage($codeSearch->getPerPage());
        $cityPager->setCurrentPage($pageCity);
        
        
        // search and group by streets
        $streetPagerData = [];
        $streetQuery = $elasticaManager->getRepository('AppBundle:Code')->getQueryForSearchGroupByStreet($codeSearch);
        $streetResults = $this->get('fos_elastica.index.postal_codes.code')->search($streetQuery)->getAggregations();   
        if ( $streetResults ) {
            $streetPagerData = $streetResults['street']['buckets'];
        }  
        
        $streetAdapter = new ArrayAdapter($streetPagerData);
        $streetPager = new Pagerfanta($streetAdapter);
        $streetPager->setMaxPerPage($codeSearch->getPerPage());
        $streetPager->setCurrentPage($pageStreet);
        
        
        // simple search by two fields
        $results = $elasticaManager->getRepository('AppBundle:Code')->search($codeSearch);
        $adapter = new ArrayAdapter($results);
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage($codeSearch->getPerPage());
        $pager->setCurrentPage($page);
        
        
        
        
        return $this->render('AppBundle:Code:list.html.twig', [
            'cityBuckets' => $cityPager->getCurrentPageResults(),
            'cityPager' => $cityPager,
            'streetBuckets' => $streetPager->getCurrentPageResults(),
            'streetPager' => $streetPager,
            
            'results' => $pager->getCurrentPageResults(),
            'pager' => $pager,
            
            'form' => $codeSearchForm->createView()
        ]);
    }
    
    
    
    /**
     * 
     * @Route("/search", name="search",options={"expose"=true})
     */
    public function searchAction(Request $request) 
    {
        $codeSearch = new \AppBundle\Model\CodeSearch();
        $codeSearch->setCode($request->query->get('code'));
        
        $elasticaManager = $this->container->get('fos_elastica.manager');
        $results = $elasticaManager->getRepository('AppBundle:Code')->search($codeSearch);
        
        return $this->render('AppBundle:Code:search.html.twig', [
            'results' => $results
        ]);
        
    }
    
}
