<?php

require_once _MODELS . "Client.php";
require_once _MODELS . "Company.php";
require_once _MODELS . "User.php";

class SearchModel extends Model 
{
    public function __construct()
    {
        parent::__construct();
    }

    ##
    # Custom functions
    ##

    public function search($keyword, $page = 1, $categorize = 1)
    {
        global $config;
        $results = array();
        $searchLimit = 25;
        $totalResults = 0;

        # page 1 by default
        if($page == 0)
            $page = 1;

        # categorize by default
        // if($categorize == 0)
        //     $categorize = 1;

        $limit = $searchLimit * ($page - 1) . ", " . $searchLimit;

        # define queries
        $queries = array(
            "company" => array("source" => "ceta", "query" => "SELECT companyID, name, tradecode AS profession, email, country AS location, address, website FROM company WHERE name LIKE '%" . $keyword . "%' AND fd_status = 'Available' OR fd_status = NULL LIMIT " . $limit),
            // "client" => array("source" => "ceta", "query" => "SELECT ce.*, co.name AS companyName FROM (SELECT c.contactID, c.name, c.vocation as job_title, c.country, c.email, c.telephone, c.address, e.companyID FROM contact AS c INNER JOIN employee AS e ON c.contactID = e.contactID WHERE c.name LIKE '%" . $keyword . "%' OR c.firstname LIKE '%" . $keyword . "%' OR c.middlename LIKE '%" . $keyword . "%' OR c.lastname LIKE '%" . $keyword . "%') AS ce INNER JOIN company AS co ON ce.companyID = co.companyID WHERE fd_status = 'Available' OR fd_status = NULL"),
            // "client" => array("source" => "ceta", "query" => "SELECT c.companyID, c.name, c.tradecode AS profession, c.email, c.country AS location, c.address, c.website, a.akatext AS alias FROM company AS c LEFT JOIN aka AS a ON c.companyID = a.companyid WHERE c.name LIKE '%" . $keyword . "%' AND c.fd_status = 'Available' OR fd_status = NULL OR a.akatext LIKE '%" . $keyword . "%'"),
            "client" => array("source" => "ceta", "query" => "SELECT c.contactID, c.name, c.firstname, c.middlename, c.lastname, c.vocation, c.email, c.telephone, c.address, comp.companyID, comp.name as company, comp.tradecode AS profession, comp.email, comp.country FROM employee AS e LEFT JOIN contact AS c ON e.contactID = c.contactID LEFT JOIN company AS comp ON e.companyID = comp.companyID LEFT JOIN aka AS a ON comp.companyID = a.companyid WHERE e.username LIKE '%" . $keyword . "%' OR c.name LIKE '%" . $keyword . "%' OR c.firstname LIKE '%" . $keyword . "%' OR c.middlename LIKE '%" . $keyword . "%' OR c.lastname LIKE '%" . $keyword . "%' OR c.lastname LIKE '%" . $keyword . "%' OR comp.name LIKE '%" . $keyword . "%' OR a.akatext LIKE '%" . $keyword . "%' LIMIT " . $limit),
            "users" => array("source" => "loop", "query" => "SELECT * FROM users WHERE username LIKE '%" . $keyword . "%' OR fullname LIKE '%" . $keyword . "%' or email LIKE '%" . $keyword . "%' LIMIT " . $limit)
        );

        $totalQueries = array(
            "company" => array("source" => "ceta", "query" => "SELECT count(*) AS total FROM company WHERE name LIKE '%" . $keyword . "%' AND fd_status = 'Available' OR fd_status = NULL"),
            "client" => array("source" => "ceta", "query" => "SELECT count(*) AS total FROM employee AS e LEFT JOIN contact AS c ON e.contactID = c.contactID LEFT JOIN company AS comp ON e.companyID = comp.companyID LEFT JOIN aka AS a ON comp.companyID = a.companyid WHERE e.username LIKE '%" . $keyword . "%' OR c.name LIKE '%" . $keyword . "%' OR c.firstname LIKE '%" . $keyword . "%' OR c.middlename LIKE '%" . $keyword . "%' OR c.lastname LIKE '%" . $keyword . "%' OR c.lastname LIKE '%" . $keyword . "%' OR comp.name LIKE '%" . $keyword . "%' OR a.akatext LIKE '%" . $keyword . "%'"),
            "users" => array("source" => "loop", "query" => "SELECT count(*) AS total FROM users WHERE username LIKE '%" . $keyword . "%' OR fullname LIKE '%" . $keyword . "%' or email LIKE '%" . $keyword . "%'")
        );

        # instantiate ceta
        $ceta = new Ceta();
        $ceta->cetaLocation($config['ceta']['location']);

        # execute queries 
        foreach($queries as $entity => $queryDetails)
        {
            switch($queryDetails['source'])
            {
                # call ceta
                case "ceta":                    
                    $result = $ceta->sqlRead($queryDetails['query']);
                    while($row = mysql_fetch_assoc($result))
                    {
                        if($categorize)
                            $results[$entity][] = $row;
                        else
                            $results[] = $row;
                    }
                    break;

                # call loop
                case "loop":
                    $result = $this->select(array(
                        "tableName" => "users",
                        "query" => $queryDetails['query']
                        )
                    );

                    foreach($result as $row)
                    {
                        if($categorize)
                            $results[$entity][] = (array) $row;
                        else
                            $results[] = $row;
                    }
                    break;

                default:
                    break;

            }
        }

        if($categorize)
        {
            # execute queries for totals
            foreach($totalQueries as $entity => $queryDetails)
            {
                switch($queryDetails['source'])
                {
                    # call ceta
                    case "ceta":                    
                        $result = $ceta->sqlRead($queryDetails['query']);
                        while($row = mysql_fetch_assoc($result))
                        {
                            $results['total'] += $row['total'];
                        }
                        break;

                    # call loop
                    case "loop":
                        $result = $this->select(array(
                            "tableName" => "users",
                            "query" => $queryDetails['query']
                            )
                        );

                        foreach($result as $row)
                        {
                            $results['total'] += $row->total;
                        }
                        break;

                    default:
                        break;
                }
            }
        }
    	return $results;
    }
}

?>