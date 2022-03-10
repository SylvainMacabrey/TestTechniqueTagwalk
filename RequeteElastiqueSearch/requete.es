PUT /medias
{
  "mappings": {
    "properties": {
      "slug":    { "type": "keyword" },  
      "season":  { "type": "keyword" }, 
      "designer":  { "type": "keyword" }, 
      "tags":   { "type": "keyword" }     
    }
  }
}

// REQUETE 1
//  Proposer une requête permettant de rechercher les documents ayant la season “fall-winter-2022” à partir d’une “term-level query”
GET /medias/_doc/_search
{
    "query": {
        "term": {
            "season": { "value": "fall-winter-2022" }
        }
    }
}


// REQUETE 2
// Proposer une requête permettant de rechercher les documents n’ayant pas le tag “dress” à partir d’une “boolean query” 

GET /medias/_doc/_search
{
    "query": {
        "bool": {
            "must_not" : [
                {
                    "match": {
                       "tags": "dress"
                    }
                }
            ]
        }
    }
}