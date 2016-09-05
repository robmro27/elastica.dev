<?php

namespace AppBundle\Entity\SearchRepository;

use FOS\ElasticaBundle\Repository;
use AppBundle\Model\CodeSearch;

/**
 *
 * @author rmroz
 */
class CodeRepository extends Repository {
    
    /**
     * Simple query
     * @param CodeSearch $codeSearch
     * @return type
     */
    public function search( CodeSearch $codeSearch )
    {
        $query = $this->getQueryForSearch($codeSearch);
        
        return $this->find( $query, 10 ); 
        
    }
    
    
    /**
     * Get best hits grouped by streets
     * @param CodeSearch $codeSearch
     * @return type
     */
    public function getQueryForSearchGroupByStreet( CodeSearch $codeSearch )
    {
        $query = null;
        if ( $codeSearch->getCode() != '' && $codeSearch != '' ) {
            
            $query = $this->getQueryForSearch($codeSearch);
            
            $streetAggregation = $this->getStreetAggregation();
            
            $query->addAggregation($streetAggregation);
            $query->setSize(0);
            
        } 
        return $query;
    }
    
    
    /**
     * Get best hits grouped by cities
     * @param CodeSearch $codeSearch
     * @return type
     */
    public function getQueryForSearchGroupByCity( CodeSearch $codeSearch )
    {
        $query = null;
        if ( $codeSearch->getCode() != '' && $codeSearch != '' ) {
            
            $query = $this->getQueryForSearch($codeSearch);
            
            $cityAggregation = $this->getCityAggregation();
            
            $query->addAggregation($cityAggregation);
            $query->setSize(0);
            
            /* simple query
            $query = new \Elastica\Query\Match();
            $query->setFieldQuery('code.cityName', $codeSearch->getCode());
            $query->setFieldFuzziness('code.cityName', 0.7);
            $query->setFieldMinimumShouldMatch('code.cityName', '100%');
            */
            
        } 
        return $query;
    }
    
    
    /**
     * 
     * @return \Elastica\Aggregation\Terms
     */
    private function getStreetAggregation()
    {
        // group by street 
        $streetAggregation = new \Elastica\Aggregation\Terms('street');
        $streetAggregation->setSize(5);
        $streetAggregation->setField('rawStreetName');
        
        // foreach street one best hit
        $hitsAggregation = new \Elastica\Aggregation\TopHits('street_hits');
        $hitsAggregation->setSize(1);

        $streetAggregation->addAggregation($hitsAggregation);
        
        return $streetAggregation;
    }
    
    
    /**
     * 
     * @return \Elastica\Aggregation\Terms
     */
    private function getCityAggregation()
    {
        // group by city 
        $cityAggregation = new \Elastica\Aggregation\Terms('city');
        $cityAggregation->setSize(5);
        $cityAggregation->setField('rawCityName');

        // foreach city one best hit
        $hitsAggregation = new \Elastica\Aggregation\TopHits('city_hits');
        $hitsAggregation->setSize(1);

        $cityAggregation->addAggregation($hitsAggregation);
        
        return $cityAggregation;
    }
    
    
    
    
    /**
     * Simple query 
     * @param CodeSearch $codeSearch
     * @return \Elastica\Query
     */
    private function getQueryForSearch( CodeSearch $codeSearch ) 
    {
        $query = null;
        if ( $codeSearch->getCode() ) {
            $query = new \Elastica\Query\MultiMatch();
            $query->setQuery($codeSearch->getCode());
            $query->setFuzziness(0.7);
            $query->setFields(['cityName^10','streetName']);
            $query = new \Elastica\Query($query);
        }
        return $query;
    }
    
    
    
}
