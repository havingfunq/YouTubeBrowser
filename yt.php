
<?php
    # json string from youtube containing related items of a given youtube video
    # parameterize key and nextPage
    # you must white list this ip at the google apis console: curl -s checkip.dyndns.org | sed -e 's/.*Current IP Address: //' -e 's/<.*$//'
    

        $ret = array();
        $videoKey = $_GET["videoKey"];
        $videoKey = substr($videoKey,0,11);
        
        $nextPageToken = $_GET["nextPageToken"];
        $dataHandle = '';
        $data = '';
        
        if(is_null($videoKey)) {
            trigger_error("Video Key Needed", E_USER_ERROR);
        }
        
        $dataHandle = file("https://www.googleapis.com/youtube/v3/search?part=snippet&relatedToVideoId=$videoKey&type=video&key=#Insert YouTube API key here#&maxResults=25");
        
        foreach($dataHandle as $line) {
            $data = $data . "$line";
        }
    
        $obj = json_decode($data);
        $lengthOfResults = $obj->{"pageInfo"}->{"resultsPerPage"};
        
        for($i = 0; $i < $lengthOfResults; $i++) {
            $key = $obj->{"items"}[$i]->{"id"}->{"videoId"}; #->{1}->{"id"}->{"videoId"} . "\n";
            
            $title = get_title($key);

            $response = $response  ."\"$key\"" . ":" . "\"$title\"".",";
        }
        
        $response = rtrim ( $response, "," );

        // echo "{ \"related\" => ";
        // echo "[".$response."]}";
        echo '{'.$response.'}';
        
        function get_title($key){
            $dataHandle = file("https://www.googleapis.com/youtube/v3/videos?id=$key&key=AIzaSyDjs8UVVf9xvl5hPDBcolcZn9IcLbgOHbw&max&fields=items(snippet(title))&part=snippet");

            foreach($dataHandle as $line) {
                $data = $data . "$line";
            }
    
            $obj = json_decode($data);
            
            return str_replace(array("\"", "'", ":", "{", "}", "[", "]", ","), "", $obj->{"items"}[0]->{"snippet"}->{"title"});
        }
        
?>

