<?php namespace Jinggo\Elasticsearch;

use \Illuminate\Support\Facades\Config;

class Elasticsearch {

    protected $config;
    
    protected $server;

    protected $index;

    public function __construct()
    {
        $this->server   = Config::get("elasticsearch::elastic.es_server");
        $this->index    = Config::get("elasticsearch::elastic.index");
    }

    public function test()
    {
        return $this->index;
    }
    
    public function call($path, $http = array()) {

        if (!$this->index) throw new Exception('$this->index needs a value');

        $http['header'] = 'Content-Type: text/html; charset=utf-8';

        return json_decode(file_get_contents($this->server . '/' . $this->index . '/' . $path, NULL, stream_context_create(array('http' => $http))), true);
    }
    
    //curl -X PUT http://localhost:9200/{INDEX}/
    public function create(){
        
        $this->call(NULL, array('method' => 'PUT'));

    }
    
    //curl -X DELETE http://localhost:9200/{INDEX}/
    public function drop($type = null){
        $this->call($type, array('method' => 'DELETE'));
    }
    
    //curl -X GET http://localhost:9200/{INDEX}/_status
    public function status(){
        return $this->call('_status');
    }
    
    //curl -X GET http://localhost:9200/{INDEX}/{TYPE}/_count -d {matchAll:{}}
    public function count($type){
        return $this->call($type . '/_count', array('method' => 'GET', 'content' => '{ matchAll:{} }'));
    }
    
    //curl -X PUT http://localhost:9200/{INDEX}/{TYPE}/_mapping -d ...
    public function map($type, $data){
        return $this->call($type . '/_mapping', array('method' => 'PUT', 'content' => $data));
    }
    
    //curl -X PUT http://localhost:9200/{INDEX}/{TYPE}/{ID} -d ...
    public function add($type, $id, $data){
        return $this->call($type . '/' . $id, array('method' => 'PUT', 'content' => $data));
    }
    
    //curl -X GET http://localhost:9200/{INDEX}/{TYPE}/_search?q= ...
    public function query($type, $q, $from = 0, $size = 10){
        $query = array('q' => $q, 'from' => $from, 'size' => $size);

        return $this->call($type . '/_search?' . http_build_query($query));
    }
    
    //curl -X POST http://localhost:9200/{INDEX}/{TYPE}/_search -d ...
    public function fuzzy($type, $field, $value, $min_similarity = 0.2,$from = 0,$size = 10){
        if (!$type) throw new Exception('Fuzzy: needs a type');
        if (!$field) throw new Exception('Fuzzy: needs a field');
        if (!$value) throw new Exception('Fuzzy: needs a value');
            
        $data = array(
            'query' => array(
                'fuzzy' => array(
                    $field => array(
                        'value' => $value,
                        'min_similarity' => $min_similarity
                    )
                )
            ),
            'from' => $from,
            'size' => $size
        );
        
        return $this->call($type . '/_search', array('method' => 'POST', 'content' => json_encode($data)));
    }
    
    public function wildcard($type, $field, $value)
    {
        if (!$type) throw new Exception('Fuzzy: needs a type');
        if (!$field) throw new Exception('Fuzzy: needs a field');
        if (!$value) throw new Exception('Fuzzy: needs a value');
            
        $data = array(
            'query' => array(
                'wildcard' => array(
                    $field => array(
                        'value' => '*'.$value.'*',
                    )
                )
            )
        );
        
        return $this->call($type . '/_search', array('method' => 'POST', 'content' => json_encode($data)));
    }

    public function search($type,$data)
    {
        if (!$type) throw new Exception('Search: needs a type');
        if (!$data) throw new Exception('Search: needs a data');

        return $this->call($type . '/_search', array('method' => 'POST', 'content' => json_encode($data)));
    }


}