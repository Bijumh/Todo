<?php
	include_once 'dbConnect.php';
    
    $sql = (isset($_REQUEST["sql"])) ? $_REQUEST["sql"] : '';
	
    prepareGridData($sql);
	
    /*
     * [Generate xml to load the data in Grid]
     */
	function prepareGridData($sql){
        $posStart = 0;
        
        $link = connectDatabase();
		$result = $link->query($sql);
		
        ob_clean();
		header("Content-type:text/xml");
		print("<?xml version=\"1.0\"  encoding=\"UTF-8\"?>");
		print("<rows total_count='".$result->num_rows."' pos='".$posStart."'>");
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_row()) {
                print("<row id='".$posStart."'>");

                foreach ($row as $key => $value) {
                    print("<cell><![CDATA[");
                    print($value);
                    print("]]></cell>");
                }

                print("</row>");
                $posStart++;
            
            }
        } 
        print("</rows>");
	}
    
?>